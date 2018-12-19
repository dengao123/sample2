<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use APP\Models\User;

class Status extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content'
    ];

    //一对一模型，一条微博对应一个用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
