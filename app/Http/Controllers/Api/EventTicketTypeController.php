<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event_ticket_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class EventTicketTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $event_ticket_types = Event_ticket_type::orderBy("id", "DESC")->get();

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => $event_ticket_types
        ], 200);
    }
}
