<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //soft-delete機能を追加する
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use SoftDeletes;


    protected $fillable = [
        'title', 'description', 'content', 'image', 'published_at', 'category_id'
    ];

    /**
     * Delete post image from storage
     *
     * @return void
     */
    public function deleteImage()
    {
        Storage::delete($this->image);
    }
    public function category() //自動的にpostsテーブルのcategory_idを探しに行く
    {
        return $this->belongsTo(Category::class); //一つのcategoryを返す
    }
}
