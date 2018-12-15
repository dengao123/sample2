<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        //游客访问设置
        $this->middleware('guest',[
            'only'=>['create']
        ]);
    }


    //登录页
    public function create()
    {
        return view('sessions.create');
    }

    //登录处理
    public function store(Request $request)
    {
        $credentials = $this->validate($request,[
            'email' => 'required|max:255|email',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials,$request->has('remember'))){
            //登录成功
            session()->flash('success','登录成功');
            return redirect()->intended(route('users.show',[Auth::user()]));
        }else{
            session()->flash('danger','很抱歉，您的邮箱和密码不匹配');
            return redirect()->back();
        }
    }

    //退出处理
    public function destroy()
    {
        Auth::logout();
        session()->flash('success','您已经成功退出');
        // return redirect('login'),两者一样
        return redirect()->route('login');
    }


}
