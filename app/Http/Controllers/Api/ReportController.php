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

        $orders = Order::with(["user" => function($q) {
            $q->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/default-user.png')) AS picture"));
        }, "user.level"]);

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
        $invoice = $request->search;
        $payment_status = $request->payment_status;
        $date = $request->date;

        $orders = Order::whereNotNull("id");

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

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => [
                "total_pay" => (int) $orders->sum("total_pay")
            ]
        ]);
    }
}
