<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\Tags\CreateTagRequest; //バリデーションルールを適用するためにimportが必要
use App\Http\Requests\Tags\UpdateTagsRequest;
use App\Tag; //categoriesテーブルにアクセスするためにimportが必要

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('tags.index')->with('tags', Tag::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        return view('tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTagRequest $request) //自動的にRequestインスタンスを変数$requestに代入してくれる省略的な書き方
    {
        //staticメソッドでcreateする場合はmass-assignmentエラーに注意
        Tag::create([
            'name' => $request->name
        ]);
        session()->flash('success', 'タグの作成に成功しました。');
        return redirect(route('tags.index'));
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
    public function edit(Tag $tag) //Tagモデルのインスタンスを変数に代入
    {
        return view('tags.create')->with('tag', $tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTagsRequest $request, Tag $tag)
    {
        // $category->name = $request->name; //フォームから送信された値はrequestインスタンスで取得可能

        // $category->save();

        $tag->update([
            'name' => $request->name
        ]); //この書き方だとsaveメソッド不要

        session()->flash('success', 'タグの更新に成功しました。');

        return redirect(route('tags.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        //
        if ($tag->posts->count() > 0) {
            session()->flash('error', 'このタグはいくつかの記事に使用されているため、削除できません。');
            return redirect()->back();
        }
        $tag->delete();

        session()->flash('success', 'タグの削除に成功しました。');

        return redirect(route('tags.index'));
    }
}
