<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::where("email", "<>", "admin@admin.com")->get();
        return view("Pages.Admin.Users.index", compact("users"));
    }
    public function users_list()
    {
        if (\request()->ajax()) {

            $users = User::where("email", "<>", "admin@admin.com")->get();
            Log::info($users);
            return DataTables::of($users)
                ->addColumn("id", function ($row) {
                    static $counter = 0;
                    $counter++;
                    return $counter;
                })
                ->editColumn("name", function ($row) {
                    return $row->name;
                })
                ->editColumn("email", function ($row) {
                    return $row->email;
                })
                ->editColumn("phone_number", function ($row) {
                    return $row->phone_number;
                })
                ->addColumn("created_at", function ($row) {
                    return $row->created_at;
                })
                ->addColumn("duration", function ($row) {
                    return $row->duration;
                })
                ->addColumn("action", function ($row) {
                    $data["id"] = $row->id;
                    return view("Pages.Admin.Users.parts.actions", $data)->render();
                })
                ->rawColumns(["id", "status", "action"])
                ->make(true);
        }
    }
    public function viewMoreData()
    {
        try {
            $id = request()->get('id');
            $user = User::where('id', $id)->first();
            return view('Pages.Admin.Users.parts.details', compact("user"));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
