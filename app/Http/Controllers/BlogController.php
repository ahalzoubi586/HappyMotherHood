<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("Pages.Admin.Blogs.index");
    }
    public function blogs_list()
    {
        if (\request()->ajax()) {

            $blogs = Blog::withTrashed()->get();
            return DataTables::of($blogs)
                ->addColumn("id", function ($row) {
                    static $counter = 0;
                    $counter++;
                    return $counter;
                })
                ->editColumn("category", function ($row) {
                    /*$data["category_id"] = $row->category->id;
                    $data["category_title"] = $row->category->title;
                    return view("Pages.Admin.Blogs.parts.url", $data)->render();*/
                    return $row->category->title;
                })
                ->editColumn("title", function ($row) {
                    return $row->title;
                })
                ->addColumn("created_at", function ($row) {
                    return $row->created_at;
                })
                ->addColumn("status", function ($row) {
                    return $row->deleted_at == null ? view("components.alert-component", ["class" => "success", "slot" => "فعّال"])->render() : view("components.alert-component", ["class" => "danger", "slot" => "غير فعّال"])->render();
                })
                ->addColumn("action", function ($row) {
                    $data["id"] = $row->id;
                    return view("Pages.Admin.Blogs.parts.actions", $data)->render();
                })
                ->rawColumns(["id", "status", "action"])
                ->make(true);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create($category_id = null)
    {
        $category_id = $category_id == null ? 0 : Crypt::decrypt($category_id);
        $categories = Category::all();
        return view("Pages.Admin.Blogs.create",compact("categories","category_id"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
          // Validate the request data
          $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        try {
            // Create a new blog post
            $blog = new Blog();
            $blog->category_id = $request->input('category_id');
            $blog->title = $request->input('title');
            $blog->description = $request->input('description');
            $blog->save();

            // Return a JSON response indicating success
            $users = User::where("user_type","0")->get();
            foreach ($users as $user) {
                $user->notify(new GeneralNotification($request->title, "تم إضافة مقال جديد بعنوان: " . $request->title));
            }

            $topic = "blogs";
            $msg = array(
                "title" => $request->title,
                "body"  => 'تم إضافة مقال جديد بعنوان: ' . $request->title,
                "type" => $topic
            );
            //Helpers::send_to_topic($msg, $topic);
            return response()->json(
                [
                    'result' => 'success',
                ],
                200
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['result' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($blog_id)
    {
        try {
            $blog_id = Crypt::decrypt($blog_id);
            $blog = Blog::withTrashed()->find($blog_id);
            $categories = Category::all();
            return view("Pages.Admin.Blogs.edit", compact('blog','categories'));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        //
    }
}
