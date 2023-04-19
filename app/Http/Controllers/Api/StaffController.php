<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
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

        $user = User::whereHas("level", function($q) {
            $q->where("name", "Staff");
        });

        if ($name) {
            $user->where("name", "LIKE", "%$name%");
        }

        if ($sort && $dir) {
            $user->orderBy($sort, $dir);
        }else {
            $user->latest();
        }

        return Response::json($user->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "name" => "required|max:255",
            "email" => "required|max:255|unique:users,email",
            "password" => "required|max:50"
        ]);

        if ($validation->fails()) {
            return Response::json([
                "status" => false,
                "message" => $validation->errors()
            ], 400);
        }

        $level = Level::where("name", "Staff")->first();

        $post = $request->all();
        $post["level_id"] = $level["id"];
        $post["password"] = Hash::make($request->password);

        $id = User::create($post)->id;

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
        $user = User::where("id", $id)->whereHas("level", function($q) {
            $q->where("name", "Staff");
        })->with("level")->first();

        if ($user == null) {
            return Response::json([
                "status" => false,
                "message" => "User not found."
            ], 404);
        }

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => $user
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::where("id", $id)->whereHas("level", function($q) {
            $q->where("name", "Staff");
        })->first();

        if ($user == null) {
            return Response::json([
                "status" => false,
                "message" => "User not found."
            ], 404);
        }

        $validation = Validator::make($request->all(), [
            "name" => "required|max:255",
            "email" => "required|max:255|unique:users,email, " . $id,
            "password" => "required|max:50"
        ]);

        if ($validation->fails()) {
            return Response::json([
                "status" => false,
                "message" => $validation->errors()
            ], 400);
        }

        $level = Level::where("name", "Staff")->first();

        $put = $request->all();
        $put["level_id"] = $level["id"];
        unset($put["password"]);

        if ($request->password) {
            $put["password"] = Hash::make($request->password);
        }

        $user->update($put);

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
        //
    }
}
