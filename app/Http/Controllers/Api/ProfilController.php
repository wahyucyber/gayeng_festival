<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfilController extends Controller
{
    public function update(Request $request)
    {
        $user = User::where("id", Auth::id())->with("level")->first();

        $validation = Validator::make($request->all(), [
            "picture" => "nullable|max:2048|mimes:png,jpg,jpeg",
            "name" => "required|max:255",
            "email" => "required|unique:users,email," . $user["id"],
            "password" => "nullable|max:50"
        ]);

        if ($validation->fails()) {
            return Response::json([
                "status" => false,
                "message" => $validation->errors()
            ], 404);
        }

        $put = $request->all();
        unset($put["picture"]);
        unset($put["password"]);

        if ($request->file("picture")) {
            Storage::delete(str_replace("storage/", "", $user["picture"]));
            $put["picture"] = "storage/" . $request->file("picture")->storeAs("users", date("Y-m-d H:i:s") . Str::random() . "." . $request->file("picture")->getClientOriginalExtension(), "public");
        }

        if ($request->password) {
            $put["password"] = Hash::make($request->password);
        }

        $user->update($put);

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => [
                "id" => (int) $user["id"]
            ]
        ], 200);
    }
}
