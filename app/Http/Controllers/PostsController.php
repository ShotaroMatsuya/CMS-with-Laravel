<?php

namespace App\Http\Controllers;

use App\Http\Requests\Posts\CreatePostsRequest; //artisan make:requestコマンドにより自動的に生成される
use App\Http\Requests\Posts\UpdatePostRequest;
use Illuminate\Http\Request;
use App\Post;
// use Illuminate\Support\Facades\Storage; //storageファイルを操作するために必要

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('posts.index')->with('posts', Post::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('posts.create');
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
        $image = $request->image->store('posts'); //storageファイル内のpathが取得できる


        //create the post
        Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'image' => $image,
            'published_at' => $request->published_at
        ]);
        //flash message
        session()->flash('success', 'Post created successfully.');
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
        return view('posts.create')->with('post', $post);
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
            $image = $request->image->store('posts'); //storageフォルダのpostsフォルダに保存
            //delete old one
            $post->deleteImage(); //Postクラス内でカスタムしたpublic function
            $data['image'] = $image; //$dataは連想配列になっている
        }

        //update attributes
        $post->update($data);

        //flash message
        session()->flash('success', 'Post updated successfully.');

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
        session()->flash('success', 'Post deleted successfully.');
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
        $trashed = Post::onlyTrashed()->get(); //trashされたpostのみを取得
        return view('posts.index')->with('posts', $trashed);
        // return view('posts.index')->withPosts($trashed);この書き方でもok
    }
    public function restore($id) //soft-deleteされたデータにアクセスする場合はroute-model-bindingは使用できないので注意!
    {
        $post = Post::withTrashed()->where('id', $id)->firstOrFail(); //もし取得できなかったらexceptionを投げて404pageを表示してくれる
        $post->restore();
        session()->flash('success', 'Post restored successfully.');
        return redirect()->back();
    }
}
