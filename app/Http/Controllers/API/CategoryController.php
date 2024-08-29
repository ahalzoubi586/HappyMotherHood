<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends BaseController
{
    public function getAllCategories(Request $request)
    {
        try {
            $categories = Category::all();
            foreach ($categories as $category) {
                $category->image = url('images/categories/' . $category->image);
            }
            return $this->sendResponse($categories);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
