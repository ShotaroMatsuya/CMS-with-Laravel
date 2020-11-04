<?php

namespace App\Http\Controllers\Blog;

use App\Tag;
use App\Post;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostsController extends Controller
{
    public function show(Post $post)
    {
        return view('blog.show')->with('post', $post)->with('categories', Category::all())->with('tags', Tag::all());
    }
    public function category(Category $category)
    {
        // $search = request()->query('search'); //検索対象をcategoryでフィルタリングしたい場合

        // if ($search) {
        //     $posts = $category->posts()->where('title', 'LIKE', "%{$search}%")->simplePaginate(3);
        // } else {
        //     $posts = $category->posts()->simplePaginate(3);
        // }


        return view('blog.category')
            ->with('category', $category)
            ->with('posts', $category->posts()->searched()->simplePaginate(3)) //QueryScopeを用いてクエリ分を簡略化
            ->with('categories', Category::all())
            ->with('tags', Tag::all());
    }
    public function tag(Tag $tag)
    {
        return view('blog.tag')
            ->with('tag', $tag)
            ->with('categories', Category::all())
            ->with('tags', Tag::all())
            ->with('posts', $tag->posts()->searched()->simplePaginate(3));
    }
}
