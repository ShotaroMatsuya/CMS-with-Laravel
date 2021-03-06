<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Post;
use App\Category;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Posts\UpdatePostRequest;
use App\Http\Requests\Posts\CreatePostsRequest; //artisan make:requestコマンドにより自動的に生成される
// use Illuminate\Support\Facades\Storage; //storageファイルを操作するために必要

class PostsController extends Controller
{
    public function __construct() //middlewareを特定のactionにのみ適用させたい場合constructを使用する
    {
        $this->middleware('verifyCategoriesCount')->only(['create', 'store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // dd(Category::first()->posts); //Collectionオブジェクト
        // dd(Category::first()->posts()); //HasManyオブジェクト
        // dd(Category::first()->posts()->where('published_at', '<', now())->get()); //HasManyオブジェクトからCollectionオブジェクトを取得
        // dd(Post::orderBy('published_at', 'desc')->get());
        $posts = Post::orderBy('published_at', 'desc')->paginate(10);

        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('posts.create')->with('categories', Category::all())->with('tags', Tag::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostsRequest $request)
    {

        // upload the image to storage

        // dd($request->image->store('posts')); //Requestオブジェクト内のimageはUploadedFileクラスのインスタンスになっていてstoreメソッドを使用することができる(storage/~/postsフォルダにimageファイルが保存される)
        // $image = $request->image->store('posts'); //storageファイル内のpathが取得できる
        $image = $request->file('image');
        $path = Storage::disk('s3')->put('/', $image, 'public');

        //create the post
        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'image' => $path,
            'published_at' => $request->published_at,
            'category_id' => $request->category,
            'user_id' => auth()->user()->id
        ]);

        if ($request->tags) {
            $post->tags()->attach($request->tags); //attachメソッドManyToManyのとき使用できる
        }
        //flash message
        session()->flash('success', '記事の投稿に成功しました。');
        //redirect user
        return redirect(route('posts.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        // dd($post->tags->pluck('id')->toArray());//Collectionオブジェクトにはpluckメソッドを使用して特定のカラムの値を取得する事ができる
        return view('posts.create')->with('post', $post)->with('categories', Category::all())->with('tags', Tag::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->only(['title', 'description', 'published_at', 'content']); //セキュリティ対策で想定外のデータをフィルタリングしておく
        //check if new image
        if ($request->hasFile('image')) {
            //upload it
            // $image = $request->image->store('posts'); //storageフォルダのpostsフォルダに保存
            $image = $request->file('image');
            $path = Storage::disk('s3')->put('/', $image, 'public');
            //delete old one
            $post->deleteImage(); //Postクラス内でカスタムしたpublic function
            $data['image'] = $path; //$dataは連想配列になっている
        }

        if ($request->tags) {
            $post->tags()->sync($request->tags); //ManyToManyで使用できるsyncメソッド
        }
        if ($request->category) {

            $data['category_id'] = $request->category;
        }

        //update attributes
        $post->update($data);

        //flash message
        session()->flash('success', '記事の変更が保存されました。');

        //redirect user
        return redirect(route('posts.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) //trashされたdataにアクセスする際にはroute-model-bindingは使用できないので注意!
    {

        $post = Post::withTrashed()->where('id', $id)->firstOrFail(); //もし取得できなかったらexceptionを投げて404pageを表示してくれる


        if ($post->trashed()) {
            $post->deleteImage(); //Postクラス内でカスタムしたpublic function（storageフォルダ内のimageを削除）
            $post->forceDelete(); //permanent-deleteされる
        } else {
            $post->delete(); //soft-deleteされる(trash)
        }
        //flash message
        session()->flash('success', '記事の削除に成功しました.');
        //redirect user
        return redirect(route('posts.index'));
    }

    /**
     * Display a list of all trashed posts
     *

     * @return \Illuminate\Http\Response
     */

    public function trashed()
    {
        $trashed = Post::onlyTrashed()->paginate(10); //trashされたpostのみを取得
        // dd($trashed); //コレクション型で取得
        return view('posts.index')->with('posts', $trashed);
        // return view('posts.index')->withPosts($trashed);この書き方でもok
    }
    public function restore($id) //soft-deleteされたデータにアクセスする場合はroute-model-bindingは使用できないので注意!
    {
        $post = Post::withTrashed()->where('id', $id)->firstOrFail(); //もし取得できなかったらexceptionを投げて404pageを表示してくれる
        $post->restore();
        session()->flash('success', '記事のリストアに成功しました。');
        return redirect()->back();
    }
}
