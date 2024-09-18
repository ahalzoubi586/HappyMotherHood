<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlogController extends BaseController
{
    public function getAllBlogs(Request $request)
    {
        try {
            $blogs = Blog::where("category_id", $request->category_id)->get();
            if (count($blogs) > 0) {
                return $this->sendResponse($blogs);
            } else {
                return $this->sendResponse("empty");
            }
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    public function getBlogDetails(Request $request)
    {
        try {
            $blog = Blog::find($request->blog_id);
            return $this->sendResponse($blog);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
