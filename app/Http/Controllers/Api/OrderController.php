<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event_ticket;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\CoreApi;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = User::where("id", Auth::id())->with("level")->first();

        $limit = $request->limit ? $request->limit : 10;

        $sort = $request->sort;
        $dir = $request->dir;

        $invoice = $request->search;
        $payment_status = $request->payment_status;
        $date = $request->date;

        $orders = Order::with(["event_ticket" => function($q) {
            $q->with(["event" => function($q) {
                $q->with("category")->select(DB::raw("id, category_id, slug, title, start_time, end_time, location, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/notfound.jpg')) AS picture"));
            }, "event_ticket_type"]);
        }]);

        if ($invoice) {
            $orders->where("invoice", "LIKE", "%$invoice%");
        }

        if ($user["level"]["name"] == "Customer") {
            $orders->where("user_id", Auth::id());
        }

        if ($payment_status) {
            $orders->where("payment_status", $payment_status);
        }

        if ($date) {
            $explode_date = explode(" to ", $date);
            if (count($explode_date) == 1) {
                $orders->whereDate("created_at", $explode_date[0]);
            }else {
                $orders->whereBetween("created_at", [$explode_date[0], $explode_date[1]]);
            }
        }

        if ($sort && $dir) {
            $orders->orderBy($sort, $dir);
        }else {
            $orders->latest();
        }

        return Response::json($orders->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "event_ticket_id" => "required|exists:event_tickets,id",
            "payment_type" => "required|in:bri_va,bca_va,bni_va,permata_va,indomaret,alfamart,gopay",
            "name" => "required|max:255",
            "identity_id" => "required|exists:identities,id",
            "identity" => "required|max:255",
            "email" => "required|max:255|email",
            "whatsapp" => "required",
            "tickets" => "required",
            "tickets.*.name" => "required|max:255",
            "tickets.*.identity_id" => "required|exists:identities,id",
            "tickets.*.identity" => "required|max:255",
            "tickets.*.email" => "required|max:255|email",
            "tickets.*.whatsapp" => "required",
        ]);

        if ($validation->fails()) {
            return Response::json([
                "status" => false,
                "message" => $validation->errors()
            ], 400);
        }

        $event_ticket = Event_ticket::where("id", $request->event_ticket_id)->where("start_date", "<=", now())->where("end_date", ">=", now())->first();

        if ($event_ticket == null) {
            return Response::json([
                "status" => false,
                "message" => "Ticket not found."
            ], 404);
        }

        $dateNow = now();

        $latest_order = Order::whereDate("created_at", $dateNow)->orderBy("invoice_index", "DESC")->first();

        $pay = 0;

        $order = [];
        $order["event_ticket_id"] = $event_ticket["id"];
        $order["identity_id"] = $request->identity_id;
        $order["name"] = $request->name;
        $order["identity"] = "$request->identity";
        $order["email"] = $request->email;
        $order["whatsapp"] = $request->whatsapp;

        $invoice_index = 1;

        if ($latest_order != null) {
            $invoice_index = $latest_order["invoice_index"] + 1;
        }

        $pay = $event_ticket["price"] * count($request->tickets);

        $order["invoice_index"] = $invoice_index;

        $order["invoice"] = "INV-" . date("Y/m/d-h-i-s") . ".00" . $invoice_index;
        $order["price"] = $event_ticket["price"];
        $order["pay"] = $pay;
        $order["payment_type"] = $request->payment_type;

        Config::$serverKey=config("services.midtrans.server_key");
        Config::$clientKey=config("services.midtrans.client_key");
        Config::$isProduction=config("services.midtrans.is_production");

        $orderId = $order["invoice"];

        $charge = [];

        if ($request->payment_type == "bri_va" || $request->payment_type == "bca_va" || $request->payment_type == "mandiri_va" || $request->payment_type == "bni_va" || $request->payment_type == "permata_va") {
            $charge["payment_type"] = "bank_transfer";
            $charge["bank_transfer"] = [
                "bank" => str_replace("_va", "", $request->payment_type)
            ];

            $order["admin_fee"] = 4000;
        }else if($request->payment_type == "indomaret" || $request->payment_type == "alfamart") {
            $charge["payment_type"] = "cstore";
            $charge["cstore"] = [
                "store" => $request->payment_type
            ];

            $order["admin_fee"] = 5000;
        }
        else {
            $charge["payment_type"] = "gopay";
            $order["admin_fee"] = 1500;
        }

        $order["total_pay"] = $order["pay"] + $order["admin_fee"];

        $transaction_details = [
            "order_id" => $orderId,
            "gross_amount" => $order["total_pay"]
        ];

        $customer_detail = [
            "first_name" => "Pak/Ibu",
            "last_name" => $request->name,
            "email" => $request->email,
            "phone" => "+62" . str_replace("+62", "", $request->whatsapp)
        ];

        $charge["transaction_details"] = $transaction_details;
        $charge["customer_details"] = $customer_detail;

        $transaction = CoreApi::charge($charge);

        $order["payment_response"] = json_encode($transaction);
        $order["payment_status"] = $transaction->transaction_status;

        $id = Order::create($order)->id;

        $tickets = [];
        $ticket_index = 0;

        foreach ($request->tickets as $key) {
            $tickets[$ticket_index++] = [
                "order_id" => $id,
                "identity_id" => $key["identity_id"],
                "code" => date("Ymd") . Str::random(5),
                "name" => $key["name"],
                "identity" => $key["identity"],
                "email" => $key["email"],
                "whatsapp" => $key["whatsapp"],
                "created_at" => now(),
                "updated_at" => now()
            ];
        }

        Ticket::insert($tickets);

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => $transaction
        ], 200);
    }

    public function handleNotificationMidtrans(Request $request)
    {
        $req = $request->all();

        $order = Order::where("invoice", $req['order_id'])->where("payment_status", "pending")->first();

        $payment_response = json_decode($order["payment_response"]);

        if ($req['transaction_id'] != $payment_response->transaction_id) {
            return Response::json([
                "status" => false,
                "message" => "Order not found."
            ], 404);
        }

        $order->update([
            "payment_status" => $req['transaction_status'],
            "payment_response" => json_encode($req)
        ]);

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => [
                "id" => (int) $order["id"]
            ]
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($invoice)
    {
        $user = User::where("id", Auth::id())->with("level")->first();

        $order = Order::where("invoice", $invoice)->with(["event_ticket" => function($q) {
            $q->with(["event" => function($q) {
                $q->with("category")->select(DB::raw("id, category_id, slug, title, start_time, end_time, location, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/notfound.jpg')) AS picture"));
            }, "event_ticket_type"]);
        }, "tickets.identity"])->first();

        if ($order == null) {
            return Response::json([
                "status" => false,
                "message" => "Order not found. "
            ], 404);
        }

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => $order
        ], 200);
    }
}
