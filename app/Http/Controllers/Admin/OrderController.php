<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function index()
    {
        return view("admin.order.index");
    }

    public function show($invoice)
    {
        $response = $this->_show_order($invoice);

        return view("admin.order.show", compact("invoice", "response"));

        // return $response;
    }

    private function _show_order($invoice)
    {
        $req = Request::create("/api/admin/order/$invoice/show", "GET");

        $req->headers->set("Accept", "application/json");
        $req->headers->set("Authorization", "Bearer " . Session::get("authorization"));

        return json_decode(App::handle($req)->getContent(), true);
    }
}
