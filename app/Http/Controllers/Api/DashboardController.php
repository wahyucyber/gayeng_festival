<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::where("end_time" , ">=", now())->count();

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => [
                "events" => $events,
                "tickets_sold" => 0,
                "orders" => 0
            ]
        ], 200);
    }
}
