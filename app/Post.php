<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes; //soft-delete機能を追加する

class Post extends Model
{
    use SoftDeletes;

    protected $date = [ //published_atをdateオブジェクトとして扱うためにはこのように宣言する必要がある
        'published_at'
    ];


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
        $path = Storage::disk('s3')->url($this->image);
        Storage::delete($path);
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
    public function scopePublished($query) //publishedされたもの(過去のものだけを取得するquery)
    {
        return $query->where('published_at', '<=', now()); //now()はlaravelにビルトインされたdateオブジェクト
    }
    public function scopeSearched($query) //QueryScopeでsqlのクエリ文を使い回す
    {
        $search = request()->query('search');
        if (!$search) {
            return $query->published();
        }
        return $query->published()->where('title', 'LIKE', "%{$search}%");
    }
}
