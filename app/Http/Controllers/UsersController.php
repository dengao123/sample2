<?php

namespace App\Http\Controllers;

use Dotenv\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Mail;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth',[
            'except'=>['show','create','store','index','confirmEmail']
        ]);

        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    //列表页
    public function index()
    {

        $users = User::orderBy('id', 'desc')->paginate(10);
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


    //创建提交页面
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
       /* Auth::login($user);*/
       //发送注册确认邮件
        $this->senEmailConfirmation($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');
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


    public function  senEmailConfirmation($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $to = $user->email;
        $subject = "感谢注册 Sample2 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }


    public function confirmEmail($token)
    {
        $user = User::where('activation_token',$token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();
        Auth::login($user);
        session()->flash('success','恭喜你，激活成功');
        return redirect()->route('users.show',compact('user'));
    }
}
