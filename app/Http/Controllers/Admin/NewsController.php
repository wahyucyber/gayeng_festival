<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        return view("admin.news.index");
    }

    public function create()
    {
        return view("admin.news.action", [
            "update" => false,
            "slug" => ""
        ]);
    }

    public function update($slug)
    {
        return view("admin.news.action", [
            "update" => true,
            "slug" => $slug
        ]);
    }
}
