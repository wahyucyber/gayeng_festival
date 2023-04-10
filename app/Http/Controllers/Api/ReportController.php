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

        $payment_status = $request->payment_status;

        $order = Order::latest()->with(["user" => function($q) {
            $q->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/default-user.png')) AS picture"));
        }, "user.level"]);

        if ($payment_status) {
            $order->where("payment_status", $payment_status);
        }

        if ($sort && $dir) {
            $order->orderBy($sort, $dir);
        }else {
            $order->latest();
        }

        return Response::json($order->paginate($limit));
    }
}
