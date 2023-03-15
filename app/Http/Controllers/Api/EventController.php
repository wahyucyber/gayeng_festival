<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 10;

        $sort = $request->sort;
        $dir = $request->dir;

        $title = $request->search;
        $date = $request->date;
        $start_time = $request->start_time;
        $end_time = $request->end_time;
        $price = $request->price;
        $stock = $request->stock;

        $events = Event::with(["user" => function($q) {
            $q->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/default-user.png')) AS picture"));
        }]);

        if ($title) {
            $events->where("title", "LIKE", "%$title%");
        }

        if ($date) {
            $events->where("date", $date);
        }

        if ($start_time) {
            $events->where("start_time", $start_time);
        }

        if ($end_time) {
            $events->where("end_time", $end_time);
        }

        if ($price) {
            $events->where("price", "LIKE", "%$price%");
        }

        if ($stock) {
            $events->where("stock", "LIKE", "%$stock%");
        }

        if ($sort && $dir) {
            $events->orderBy($sort, $dir);
        }else {
            $events->latest();
        }

        return Response::json($events->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
