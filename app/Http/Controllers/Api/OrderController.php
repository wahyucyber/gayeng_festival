<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Order_item;
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

        $orders = Order::with(["user" => function($q) {
            $q->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/default-user.png')) AS picture"));
        }, "user.level"]);

        if ($invoice) {
            $orders->where("invoice", "LIKE", "%$invoice%");
        }

        if ($user["level"]["name"] == "Customer") {
            $orders->where("user_id", Auth::id());
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
            "payment_type" => "required|in:bri_va,bca_va,bni_va,permata_va,indomaret,alfamart,qris"
        ]);

        if ($validation->fails()) {
            return Response::json([
                "status" => false,
                "message" => $validation->errors()
            ], 400);
        }

        $carts = Cart::where("user_id", Auth::id())->with("event");

        if ($carts->count() == 0) {
            return Response::json([
                "status" => false,
                "message" => "Cart is empty."
            ], 400);
        }

        $dateNow = now();

        $latest_order = Order::whereDate("created_at", $dateNow)->orderBy("invoice_index", "DESC")->first();

        $pay = 0;

        $order = [];
        $order["user_id"] = Auth::id();

        $invoice_index = 1;

        if ($latest_order != null) {
            $invoice_index = $latest_order["invoice_index"] + 1;
        }

        foreach ($carts->get() as $key) {
            $pay = $key["qty"] * $key["event"]["price"];
        }

        $order["invoice_index"] = $invoice_index;

        $order["invoice"] = "INV-" . date("Y/m/d-h-i-s") . ".00" . $invoice_index;
        $order["pay"] = $pay;
        $order["payment_type"] = $request->payment_type;

        $user = User::where("id", Auth::id())->first();

        Config::$serverKey=config("services.midtrans.server_key");
        Config::$clientKey=config("services.midtrans.client_key");
        Config::$isProduction=config("services.midtrans.is_production");

        $orderId = $order["invoice"];

        $transaction_details = [
            "order_id" => $orderId,
            "gross_amount" => $pay
        ];

        $customer_detail = [
            "first_name" => "Pak/Ibu",
            "last_name" => $user["name"],
            "email" => $user["email"],
            "phone" => "+62" . str_replace("+62", "", $user["phone"])
        ];

        $charge = [];
        $charge["transaction_details"] = $transaction_details;
        $charge["customer_details"] = $customer_detail;

        if ($request->payment_type == "bri_va" || $request->payment_type == "bca_va" || $request->payment_type == "mandiri_va" || $request->payment_type == "bni_va" || $request->payment_type == "permata_va") {
            $charge["payment_type"] = "bank_transfer";
            $charge["bank_transfer"] = [
                "bank" => str_replace("_va", "", $request->payment_type)
            ];
        }else if($request->payment_type == "indomaret" || $request->payment_type == "alfamart") {
            $charge["payment_type"] = "cstore";
            $charge["cstore"] = [
                "store" => $request->payment_type
            ];
        }else {
            $charge["payment_type"] = "qris";
            $charge["qris_action"] = "generate";
        }

        $transaction = CoreApi::charge($charge);

        $order["payment_response"] = json_encode($transaction);
        $order["payment_status"] = $transaction->transaction_status;

        $id = Order::create($order)->id;

        $order_items = [];
        $order_items_index = 0;

        foreach ($carts->get() as $key) {
            $order_items[$order_items_index++] = [
                "order_id" => $id,
                "event_id" => $key["event_id"],
                "qty" => $key["qty"],
                "price" => $key["event"]["price"],
                "total" => $key["qty"] * $key["event"]["price"],
                "created_at" => now(),
                "updated_at" => now()
            ];
        }

        Order_item::insert($order_items);

        Cart::where("user_id", Auth::id())->delete();

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

        if ($req["transaction_status"] == "settlement") {
            $order_item = Order_item::where("order_id", $order["id"])->get();

            $tickets = [];
            $tickets_index = 0;

            foreach ($order_item as $key) {
                for ($i=0; $i < $key["qty"]; $i++) {
                    $tickets[$tickets_index++] = [
                        "order_item_id" => $key["id"],
                        "code" => date("Ymd") . Str::random(5),
                        "created_at" => now(),
                        "updated_at" => now()
                    ];
                }
            }

            Ticket::insert($tickets);
        }

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
    public function show(string $id)
    {
        //
    }
}
