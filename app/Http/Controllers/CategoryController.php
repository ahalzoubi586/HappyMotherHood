<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Category;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("Pages.Admin.Categories.index");
    }
    public function categories_list()
    {
        if (\request()->ajax()) {

            $categories = Category::withTrashed()->get();
                        Log::info($categories);
            return DataTables::of($categories)
                ->addColumn("id", function ($row) {
                    static $counter = 0;
                    $counter++;
                    return $counter;
                })
                ->editColumn("title", function ($row) {
                    return $row->title;
                })
                ->editColumn("image", function ($row) {
                    $category_image = $row->image;
                    return view("Pages.Admin.Categories.parts.category_image", compact("category_image"));
                })
                ->addColumn("created_at", function ($row) {
                    return $row->created_at;
                })
                ->addColumn("status", function ($row) {
                    return $row->deleted_at == null ? view('components.alert-component', ['class' => 'success','slot' =>'فعّال'])->render() : view("components.alert-component", ["class" => "danger", "slot" => "غير فعّال"])->render();
                })
                ->addColumn("action", function ($row) {
                    $data["id"] = $row->id;
                    return view("Pages.Admin.Categories.parts.actions", $data)->render();
                })
                ->rawColumns(["id", "status", "action"])
                ->make(true);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("Pages.Admin.Categories.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'category_title' => 'required|string|max:255',
                'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);


            if ($request->hasFile('category_image')) {
                $image = $request->file('category_image');
                $imagePath = "images/categories/";
                if (!File::isDirectory($imagePath)) {
                    File::makeDirectory($imagePath, 0777, true, true);
                }
                $imageName = now()->format('Ymd_His') . '_' . $image->getClientOriginalName();
                $image->move($imagePath, $imageName);
            }

            Category::create(['title' => $request->category_title, 'image' => $imageName, 'status' => 1]);
            $users = User::where("user_type","0")->get();
            foreach ($users as $user) {
                $user->notify(new GeneralNotification($request->category_title, "تم إضافة تصنيف جديد بعنوان: " . $request->category_title));
            }

            $topic = "blogs";
            $msg = array(
                "title" => $request->category_title,
                "body"  => 'تم إضافة تصنيف جديد بعنوان: ' . $request->category_title,
                "type" => $topic
            );
            try{
            Helpers::send_to_topic($msg, $topic);
            }
            catch(Exception $e){
                Log::info($e->getMessage());
            }
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
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($category_id)
    {
        try {
            $category_id = Crypt::decrypt($category_id);
            $category = Category::withTrashed()->find($category_id);
            return view("Pages.Admin.Categories.edit", compact('category'));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'category_title' => 'sometimes|required|string|max:255',
                'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $category_id = Crypt::decrypt($request->category_id);
            $category = Category::withTrashed()->find($category_id);
            $category->title = $request->category_title;
            if ($request->hasFile('category_image')) {
                $image = $request->file('category_image');
                $imagePath = "images/categories/";
                if (!File::isDirectory($imagePath)) {
                    File::makeDirectory($imagePath, 0777, true, true);
                }
                $imageName = now()->format('Ymd_His') . '_' . $image->getClientOriginalName();
                $image->move($imagePath, $imageName);
                if ($category->image && File::exists($imagePath . $category->image)) {
                    File::delete($imagePath . $category->image);
                }
                $category->image = $imageName;
            }
            $category->deleted_at = $request->status == null ? Carbon::now() : null;
            $category->save();
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
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
