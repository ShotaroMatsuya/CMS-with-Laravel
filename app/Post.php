<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes; //soft-delete機能を追加する

class Post extends Model
{
    use SoftDeletes;


    protected $fillable = [
        'title', 'description', 'content', 'image', 'published_at', 'category_id', 'user_id'
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
    public function category() //自動的にpostsテーブルのcategory_idを探しに行く(oneToManyなのでこちらは単数形)
    {
        return $this->belongsTo(Category::class); //一つのcategoryを返す
    }
    public function tags()
    { //ManyToManyなので複数形
        return $this->belongsToMany(Tag::class);
    }
    /**
     * check if post has tag
     *
     * @return bool
     */
    public function hasTag($tagId)
    {
        return in_array($tagId, $this->tags->pluck('id')->toArray()); //変数tagIdが配列に含まれて入ればtrueを返す
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
