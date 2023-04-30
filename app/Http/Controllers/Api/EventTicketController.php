<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Event_ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class EventTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 10;

        $sort = $request->sort;
        $dir = $request->dir;

        $name = $request->search;
        $event_id = $request->event_id;

        $event_tickets = Event_ticket::with(["event_ticket_type", "event" => function($q) {
            $q->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/notfound.jpg')) AS picture"));
        }]);

        if ($event_id) {
            $event_tickets->where("event_id", $event_id);
        }

        if ($name) {
            $event_tickets->where("name", "LIKE", "%$name%");
        }

        if ($sort && $dir) {
            $event_tickets->orderBy($sort, $dir);
        }else {
            $event_tickets->latest();
        }

        return Response::json($event_tickets->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "event_id" => "required|exists:events,id",
            "event_ticket_type_id" => "required|exists:event_ticket_types,id",
            "category" => "required|max:255",
            "name" => "required|max:255",
            "stock" => "required|integer",
            "amount_per_transaction" => "required|integer",
            "price" => "required|integer",
            "start_date" => "required",
            "end_date" => "required",
            "on_sale" => "required|in:true,false"
        ]);

        if ($validation->fails()) {
            return Response::json([
                "status" => false,
                "message" => $validation->errors()
            ], 400);
        }

        $event = Event::where("id", $request->event_id)->first();

        if ($request->start_date > $event["start_time"] || $request->end_date > $event["end_time"]) {
            return Response::json([
                "status" => false,
                "message" => "Tanggal penjualan tidak boleh lebih tanggal acara."
            ], 400);
        }

        $post = $request->all();
        $post["on_sale"] = $request->on_sale == "true" ? 1 : 0;

        $id = Event_ticket::create($post)->id;

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => [
                "id" => (int) $id
            ]
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event_ticket = Event_ticket::where("id", $id)->with(["event_ticket_type", "event" => function($q) {
            $q->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/notfound.jpg')) AS picture"));
        }])->first();

        if ($event_ticket == null) {
            return Response::json([
                "status" => false,
                "message" => "Ticket not found.",
                "data" => []
            ], 404);
        }

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => $event_ticket
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event_ticket = Event_ticket::where("id", $id)->first();

        if ($event_ticket == null) {
            return Response::json([
                "status" => false,
                "message" => "Ticket not found.",
                "data" => []
            ], 404);
        }

        $validation = Validator::make($request->all(), [
            "event_id" => "required|exists:events,id",
            "event_ticket_type_id" => "required|exists:event_ticket_types,id",
            "category" => "required|max:255",
            "name" => "required|max:255",
            "stock" => "required|integer",
            "amount_per_transaction" => "required|integer",
            "price" => "required|integer",
            "start_date" => "required",
            "end_date" => "required",
            "on_sale" => "required|in:true,false"
        ]);

        if ($validation->fails()) {
            return Response::json([
                "status" => false,
                "message" => $validation->errors()
            ], 400);
        }

        $event = Event::where("id", $request->event_id)->first();

        if ($request->start_date > $event["start_time"] || $request->end_date > $event["end_time"]) {
            return Response::json([
                "status" => false,
                "message" => "Tanggal penjualan tidak boleh lebih tanggal acara."
            ], 400);
        }

        $put = $request->all();
        $put["on_sale"] = $request->on_sale == "true" ? 1 : 0;

        $event_ticket->update($put);

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => [
                "id" => (int) $id
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event_ticket = Event_ticket::where("id", $id)->first();

        if ($event_ticket == null) {
            return Response::json([
                "status" => false,
                "message" => "Ticket not found.",
                "data" => []
            ], 404);
        }

        $event_ticket->delete();

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => [
                "id" => (int) $id
            ]
        ], 200);
    }
}
