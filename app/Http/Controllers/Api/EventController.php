<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Event_ticket;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
        }, "user.level", "category"])->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/notfound.jpg')) AS picture"));

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

    public function indexSelect2(Request $request)
    {
        $term = $request->term;

        $event = Event::latest();

        if ($term) {
            $event->where("title", "LIKE", "%$term%");
        }

        $data = $event->simplePaginate(10);

        $morePages = false;

        if ($data->nextPageUrl() != null) {
            $morePages = true;
        }

        $items = [];
        $index = 0;

        foreach ($data->items() as $key) {
            $items[$index++] = [
                "id" => $key["id"],
                "text" => $key["title"]
            ];
        }

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => [
                "items" => $items,
                "pagination" => [
                    "more" => $morePages
                ]
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "category_id" => "required|exists:categories,id",
            "picture" => "nullable|max:2048|mimes:png,jpg,jpeg",
            "title" => "required|max:255|unique:events,title",
            "start_time" => "required",
            "end_time" => "required",
            "description" => "required",
            "location" => "required|max:255",
            "term_and_condition" => "required"
        ]);

        if ($validation->fails()) {
            return Response::json([
                "status" => false,
                "message" => $validation->errors()
            ], 400);
        }

        $post = $request->all();
        $post["slug"] = Str::slug($request->title);
        $post["user_id"] = Auth::id();

        unset($post["picture"]);

        if ($request->file("picture")) {
            $post["picture"] = "storage/" . $request->file("picture")->storeAs("events", Str::random() . "." . $request->file("picture")->getClientOriginalExtension(), "public");
        }

        $id = Event::create($post)->id;

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
    public function show(string $slug)
    {
        $event = Event::where("slug", $slug)->with(["user" => function($q) {
            $q->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/default-user.png')) AS picture"));
        }, "user.level", "category"])->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/notfound.jpg')) AS picture"))->first();

        if ($event == null) {
            return Response::json([
                "status" => false,
                "message" => "Event not found."
            ], 404);
        }

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => $event
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        $event = Event::where("slug", $slug)->first();

        if ($event == null) {
            return Response::json([
                "status" => false,
                "message" => "Event not found."
            ], 404);
        }

        $validation = Validator::make($request->all(), [
            "category_id" => "required|exists:categories,id",
            "picture" => "nullable|max:2048|mimes:png,jpg,jpeg",
            "title" => "required|max:255|unique:events,title," . $event["id"],
            "start_time" => "required",
            "end_time" => "required",
            "description" => "required"
        ]);

        if ($validation->fails()) {
            return Response::json([
                "status" => false,
                "message" => $validation->errors()
            ], 400);
        }

        $put = $request->all();
        $put["slug"] = Str::slug($request->title);
        $put["user_id"] = Auth::id();

        unset($put["picture"]);

        if ($request->file("picture")) {
            Storage::delete([str_replace("storage/", "", $event["picture"])]);
            $put["picture"] = "storage/" . $request->file("picture")->storeAs("events", Str::random() . "." . $request->file("picture")->getClientOriginalExtension(), "public");
        }

        $event->update($put);

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => [
                "id" => (int) $event["id"]
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $event = Event::where("slug", $slug)->first();

        if ($event == null) {
            return Response::json([
                "status" => false,
                "message" => "Event not found."
            ], 404);
        }

        Storage::delete([str_replace("storage/", "", $event["picture"])]);

        $event->delete();

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => [
                "id" => (int) $event["id"]
            ]
        ], 200);
    }
}
