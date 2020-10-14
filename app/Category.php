<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //mass-assignmentエラーを回避するために必要
    protected $fillable = ['name'];
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
