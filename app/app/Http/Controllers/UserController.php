<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterScreenNameRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where("id" , "!=" , auth()->user()->id)
            ->where("screen_name" , "!=" , null)
            ->where("admin_flag" , "!=" , "1")
            ->get()->toJson(JSON_PRETTY_PRINT);
        return $users;
    }

    public function registerScreenName(RegisterScreenNameRequest $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->screen_name = $request->screen_name;
        $user->save();
    }
}
