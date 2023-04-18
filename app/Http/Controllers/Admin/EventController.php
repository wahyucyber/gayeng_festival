<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return view("admin.event.index");
    }

    public function show($slug)
    {
        return view("admin.event.show", compact("slug"));
    }

    public function create()
    {
        return view("admin.event.action", [
            "update" => false,
            "slug" => ""
        ]);
    }

    public function update($slug)
    {
        return view("admin.event.action", [
            "update" => true,
            "slug" => $slug
        ]);
    }
}
