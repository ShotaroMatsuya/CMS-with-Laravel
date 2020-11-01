<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Post;
use App\Category;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    //
    public function index()
    {
        // if (request()->query('search')) {
        //     dd(request()->query('search')); //GETリクエストのurlクエリを取得できる
        // }
        // $search = request()->query('search');
        // if ($search) {
        //     $posts = Post::where('title', 'LIKE', "%{$search}%")->simplePaginate(1);
        // } else {
        //     $posts = Post::simplePaginate(3);
        // }

        return view('welcome')->with('categories', Category::all())->with('tags', Tag::all())->with('posts', Post::searched()->orderBy('published_at', 'desc')->simplePaginate(4));


        //QueryScopeを使えば冗長なデータベースのクエリ文を使い回すことができる（上の例ではscopeSearchedメソッドで定義されたクエリを呼び出している）
        /*Post::paginate(2)*/
        //paginateメソッドでかんたんにペジネーションリンクを生成できる
        //simplePaginateメソッドは前後のページリンクのみを生成
    }
}
