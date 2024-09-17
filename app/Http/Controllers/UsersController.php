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
        $users = User::where("user_type", "0")->get();
        return view("Pages.Admin.Users.index", compact("users"));
    }
    public function users_list()
    {
        if (\request()->ajax()) {

            $users = User::where("user_type", "0")->get();
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
                    $milliseconds = $row->duration; // Assuming $row->duration is in milliseconds
                    $totalSeconds = floor($milliseconds / 1000);
                    $hours = floor($totalSeconds / 3600);
                    $minutes = floor(($totalSeconds % 3600) / 60);
                    $seconds = $totalSeconds % 60;
                    return sprintf('%02dh:%02dm:%02ds', $hours, $minutes, $seconds);
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
