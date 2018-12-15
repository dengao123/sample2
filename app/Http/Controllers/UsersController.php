<?php

namespace App\Http\Controllers;

use Dotenv\Validator;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth',[
            'except'=>['show','create','store','index']
        ]);

        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    //列表页
    public function index()
    {

        $users = User::paginate(10);
        return view('users.index',compact('users'));
    }


    //创建页面
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


    //编辑页面
    public function edit(User $user)
    {
        try {
            $this->authorize ('update', $user);
            return view ('users.edit', compact ('user'));
        } catch (AuthorizationException $e) {
            abort(403, $e->getMessage());
        }

    }

    //编辑保存页面
    public function update(User $user,Request $request)
    {

        $this->authorize('update', $user);
        $this->validate($request,[
            'name'=>'required|max:50',
            'password'=>'min:6|confirmed|nullable'
        ]);

        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);
        session()->flash('success','个人资料更新成功');
        return redirect()->route('users.show',$user->id);

    }


    //删除操作
   public function destroy(User $user)
    {
       $user->delete();
       session()->flash('success','用户删除成功');
       return back();
    }
}
