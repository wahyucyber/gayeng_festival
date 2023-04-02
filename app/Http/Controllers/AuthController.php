<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {
        return view("auth.index");
    }

    public function login_post(Request $request)
    {
        $req = Request::create("/api/auth/login", "POST", $request->all());

        $req->headers->set("Accpet", "application/json");

        $response = App::handle($req);

        $data = json_decode($response->getContent(), true);

        if ($data["status"] == true) {
            Session::put("authorization", $data["data"]['authorization']['plainTextToken']);
            Session::put("token_expired", $data["data"]['authorization']['accessToken']['expires_at']);
            Session::put("level", $data["data"]['user']['level']['name']);
        }

        return $response;
    }

    public function auth_check()
    {
        if (Session::exists("authorization") && Session::exists("token_expired") && Session::exists("level")) {
            if (Session::get("level") == "Admin") {
                return redirect()->route("admin.dashboard");
            }
        }else {
            return redirect()->route("auth.login")->with("error", "You must login first!");
        }
    }

    public function logout()
    {
        $req = Request::create("/api/auth/logout", "DELETE");

        $req->headers->set("Accept", "application/json");

        App::handle($req);

        Session::flush();

        return Response::json([
            "status" => true,
            "message" => "success."
        ], 200);
    }
}
