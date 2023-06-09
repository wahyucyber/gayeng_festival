<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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

    public function countTotalPay(Request $request)
    {
        $date = $request->date;

        $orders = Order::where("payment_status", "settlement");

        if ($date) {
            $explode_date = explode(" to ", $date);
            if (count($explode_date) == 1) {
                $orders->whereDate("created_at", $explode_date[0]);
            }else {
                $orders->whereBetween("created_at", [$explode_date[0], $explode_date[1]]);
            }
        }

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => [
                "total_pay" => (int) $orders->sum("total_pay")
            ]
        ]);
    }
}
