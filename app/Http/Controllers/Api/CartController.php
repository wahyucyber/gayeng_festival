<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 10;

        $sort = $request->sort;
        $dir = $request->dir;

        $event_id = $request->event_id;

        $carts = Cart::where("user_id", Auth::id())->with(["user" => function($q) {
            $q->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/default-user.png')) AS picture"));
        }, "user.level", "event" => function($q) {
            $q->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/notfound.jpg')) AS picture"));
        }]);

        if ($event_id) {
            $carts->where("event_id", $event_id);
        }

        if ($sort && $dir) {
            $carts->orderBy($sort, $dir);
        }else {
            $carts->latest();
        }

        return Response::json($carts->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "event_id" => "required|exists:events,id",
            "qty" => "required"
        ]);

        if ($validation->fails()) {
            return Response::json([
                "status" => false,
                "message" => $validation->errors()
            ], 400);
        }

        $cart = Cart::where("user_id", Auth::id())->where("event_id", $request->event_id)->first();

        if ($cart != null) {
            return Response::json([
                "status" => false,
                "message" => "Event is exists."
            ], 400);
        }

        $post = $request->all();
        $post["user_id"] = Auth::id();

        $id = Cart::create($post)->id;

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
        $cart = Cart::where("user_id", Auth::id())->with(["user" => function($q) {
            $q->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/default-user.png')) AS picture"));
        }, "user.level", "event" => function($q) {
            $q->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/notfound.jpg')) AS picture"));
        }])->first();

        if ($cart == null) {
            return Response::json([
                "status" => false,
                "message" => "Cart not found."
            ], 404);
        }

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => $cart
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cart = Cart::where("user_id", Auth::id())->first();

        if ($cart == null) {
            return Response::json([
                "status" => false,
                "message" => "Cart not found."
            ], 404);
        }

        $validation = Validator::make($request->all(), [
            "event_id" => "required|exists:events,id",
            "qty" => "required"
        ]);

        if ($validation->fails()) {
            return Response::json([
                "status" => false,
                "message" => $validation->errors()
            ], 400);
        }

        $put = $request->all();
        $put["user_id"] = Auth::id();

        $cart->update($put);

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
        $cart = Cart::where("user_id", Auth::id())->first();

        if ($cart == null) {
            return Response::json([
                "status" => false,
                "message" => "Cart not found."
            ], 404);
        }

        $cart->delete();

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => [
                "id" => (int) $id
            ]
        ], 200);
    }
}
