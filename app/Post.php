<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //soft-delete機能を追加する

class Post extends Model
{
    use SoftDeletes;


    protected $fillable = [
        'title', 'description', 'content', 'image', 'published_ad'
    ];
}
