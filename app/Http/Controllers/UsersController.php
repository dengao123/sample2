<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function create()
    {
        return view('users.create');
    }

    //单个页面显示
    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }
}
