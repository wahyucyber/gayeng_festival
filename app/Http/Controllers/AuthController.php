<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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

        return $response;
    }
}
