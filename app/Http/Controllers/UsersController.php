<?php

namespace App\Http\Controllers;

use Dotenv\Validator;
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


    //提交页面
    public function store(Request $request)
    {

        $this->validate($request,[
            'name'=>'required|max:50',
            'email' =>'required|email|unique:users',
            'password'=>'required|min:6|confirmed'
        ]);

        //返回创建的模型实例
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
        ]);

        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show',[$user]);
    }
}
