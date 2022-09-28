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
        $users = User::where("id" , "!=" , auth()->user()->id)->where("screen_name" , "!=" , null)->get()->toJson(JSON_PRETTY_PRINT);
        return $users;
    }

    public function registerScreenName(RegisterScreenNameRequest $request)
    {
        $user = Auth::user();
        $user->screen_name = $request->screen_name;
        $user->save();
    }
}
