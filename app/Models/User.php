<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;
use App\Models\Status;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public static function boot()
    {
        //在用户模型类完成初始化之后进行加载
        parent::boot();
        static::creating(function($user){
            $user->activation_token = str_random(30);
        });
    }


    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    //一对多，寻找该用户的所有动态
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }


    //获取用户发布的动态
    public function feed()
    {
        return $this->statuses()
                    ->orderBy('created_at','desc');
    }


}
