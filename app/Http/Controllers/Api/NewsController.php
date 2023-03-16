<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 10;

        $sort = $request->sort;
        $dir = $request->dir;

        $title = $request->search;

        $news = News::with(["user" => function($q) {
            $q->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/default-user.png')) AS picture"));
        }, "user.level"])->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/notfound.jpg')) AS picture"));

        if ($title) {
            $news->where("title", "LIKE", "%$title%");
        }

        if ($sort && $dir) {
            $news->orderBy($sort, $dir);
        }else {
            $news->latest();
        }

        return Response::json($news->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "picture" => "nullable|image|mimes:png,jpg,jpeg|max:2048",
            "title" => "required|max:255|unique:news,title",
            "text" => "required"
        ]);

        if ($validation->fails()) {
            return Response::json([
                "status" => false,
                "message" => $validation->errors()
            ], 400);
        }

        $post = $request->all();
        $post["slug"] = Str::slug($request->title);
        $post["user_id"] = Auth::id();
        unset($post["picture"]);

        if ($request->file("picture")) {
            $post["picture"] = "storage/" . $request->file("picture")->storeAs("news", Str::random() . "." . $request->file("picture")->getClientOriginalExtension(), "public");
        }

        $id = News::create($post)->id;

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
    public function show(string $slug)
    {
        $news = News::with(["user" => function($q) {
            $q->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/default-user.png')) AS picture"));
        }, "user.level"])->select(DB::raw("*, CONCAT('" . env("APP_URL") . "/', COALESCE(picture, 'assets/images/notfound.jpg')) AS picture"))->where("slug", $slug)->first();

        if ($news == null) {
            return Response::json([
                "status" => false,
                "message" => "News not found."
            ], 404);
        }

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => $news
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        $news = News::where("slug", $slug)->first();

        if ($news == null) {
            return Response::json([
                "status" => false,
                "message" => "News not found."
            ], 404);
        }

        $validation = Validator::make($request->all(), [
            "picture" => "nullable|image|mimes:png,jpg,jpeg|max:2048",
            "title" => "required|max:255|unique:news,title," . $news["id"] ,
            "text" => "required"
        ]);

        if ($validation->fails()) {
            return Response::json([
                "status" => false,
                "message" => $validation->errors()
            ], 400);
        }

        $put = $request->all();
        $put["slug"] = Str::slug($request->title);
        $put["user_id"] = Auth::id();
        unset($put["picture"]);

        if ($request->file("picture")) {
            Storage::delete([str_replace("storage/", "", $news["picture"])]);
            $put["picture"] = "storage/" . $request->file("picture")->storeAs("news", Str::random() . "." . $request->file("picture")->getClientOriginalExtension(), "public");
        }

        $news->update($put);

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => [
                "id" => (int) $news["id"]
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $news = News::where("slug", $slug)->first();

        if ($news == null) {
            return Response::json([
                "status" => false,
                "message" => "News not found."
            ], 404);
        }

        Storage::delete([str_replace("storage/", "", $news["picture"])]);

        $news->delete();

        return Response::json([
            "status" => true,
            "message" => "success.",
            "data" => [
                "id" => (int) $news["id"]
            ]
        ], 200);
    }
}
