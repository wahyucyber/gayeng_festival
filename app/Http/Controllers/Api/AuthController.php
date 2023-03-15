<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "email" => "required|email|max:255|exists:users,email",
            "password" => "required|max:50"
        ]);

        if ($validation->fails()) {
            return Response::json([
                "status" => false,
                "message" => $validation->errors()
            ], 400);
        }

        $user = User::where("email", $request->email)->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/default-user.png')) AS picture"))->first();

        if (Hash::check($request->password, $user["password"])) {
            $laravel_sanctum = $user->createToken("authorization", ["admin"]);

            return Response::json([
                "status" => true,
                "message" => "success.",
                "data" => [
                    "authorization" => $laravel_sanctum,
                    "user" => $user
                ]
            ], 200);
        }else {
            return Response::json([
                "status" => false,
                "message" => [
                    "password" => "Password is not match."
                ]
            ], 400);
        }
    }

    public function show()
    {
        $user = User::where("id", Auth::id())->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/default-user.png')) AS picture"))->first();

        if ($user == null) {
            return Response::json([
                "status" => false,
                "message" => "User not found.",
            ], 404);
        }

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => $user
        ], 200);
    }

    public function logout(Request $request)
    {
        $user_id = Auth::id();

        $request->user()->currentAccessToken()->delete();

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => [
                "id" => (int) $user_id
            ]
        ], 200);
    }
}
