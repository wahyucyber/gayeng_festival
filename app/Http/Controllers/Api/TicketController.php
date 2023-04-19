<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 10;

        $sort = $request->sort;
        $dir = $request->dir;

        $code = $request->search;
        $event_id = $request->event_id;
        $status = $request->status;

        $tickets = Ticket::with(["identity", "order.event_ticket" => function($q) {
            $q->with(["event" => function($q) {
                $q->with("category")->select(DB::raw("id, category_id, slug, title, start_time, end_time, location, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/notfound.jpg')) AS picture"));
            }, "event_ticket_type"]);
        }]);

        if ($code) {
            $tickets->where("code", "LIKE", "%$code%");
        }

        if ($event_id) {
            $tickets->whereHas("order_item", function($q) use($event_id) {
                $q->where("event_id", $event_id);
            });
        }

        if ($status) {
            $tickets->where("status", $status);
        }

        if ($sort && $dir) {
            $tickets->orderBy($sort, $dir);
        }else {
            $tickets->latest();
        }

        return Response::json($tickets->paginate($limit));
    }

    /**
     * Display the specified resource.
     */
    public function show($code)
    {
        $ticket = Ticket::with(["order_item.order" => function($q) {
            $q->where("user_id", Auth::id());
        }, "order_item.event" => function($q) {
            $q->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/notfound.jpg')) AS picture"));
        }])->where("code", $code)->first();

        if ($ticket == null) {
            return Response::json([
                "status" => false,
                "message" => "Ticket not found."
            ], 404);
        }

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => $ticket
        ]);
    }

    public function confirmTicket(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "code" => "required|exists:tickets,code"
        ]);

        if ($validation->fails()) {
            return Response::json([
                "status" => false,
                "message" => $validation->errors()
            ], 400);
        }

        $ticket = Ticket::where("code", $request->code)->where("status", "pending")->with(["order_item.order.user.level", "order_item.event" => function($q) {
            $q->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/notfound.jpg')) AS picture"));
        }])->first();

        if ($ticket == null) {
            return Response::json([
                "status" => false,
                "message" => "Ticket not found."
            ], 400);
        }

        $ticket->update([
            "status" => "settlement"
        ]);

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => $ticket
        ], 200);
    }
}
