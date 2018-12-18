<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use APP\Models\User;

class Status extends Model
{
    //

    //一对一模型，一条微博对应一个用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
