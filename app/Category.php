<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //mass-assignmentエラーを回避するために必要
    protected $fillable = ['name'];
}
