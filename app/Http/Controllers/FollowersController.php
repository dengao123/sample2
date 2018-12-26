<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class FollowersController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    //关注
    public function store(User $user)
    {
        //自己关注自己则返回首页
        if(Auth::user()->id ===$user->id)
        {
            return redirect('/');
        }

        //如果没关注，就进行关注
        if(!Auth::user()->isFollowing($user->id))
        {
            Auth::user()->follow($user->id);
        }

        //返回到关注者主页
        return redirect()->route('users.show',$user->id);

    }

    //取消关注
    public function destroy(User $user)
    {
        if(Auth::user()->id === $user->id)
        {
            return redirect('/');
        }

        if (Auth::user()->isFollowing($user->id))
        {
            Auth::user()->unfollow($user->id);
        }

        return redirect()->route('users.show', $user->id);

    }

}
