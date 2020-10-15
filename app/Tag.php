<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    protected $fillable = ['name'];
    public function posts() //ManyToManyなのでpostモデルと同じことを書く
    {
        return $this->belongsToMany(Post::class);
    }
}
