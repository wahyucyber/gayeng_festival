<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Facades\Response;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::where("end_time" , ">=", now())->count();
        $ticket_sold = Ticket::whereHas("order", function($q) {
            $q->where('payment_status', "settlement");
        })->count();
        $total_order = Order::where("payment_status", "settlement")->sum("total_pay");

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => [
                "events" => $events,
                "tickets_sold" => $ticket_sold,
                "orders" => $total_order
            ]
        ], 200);
    }
}
