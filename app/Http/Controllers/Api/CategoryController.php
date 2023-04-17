<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::orderBy("id", "DESC");

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => $category->get()
        ], 200);
    }
}
