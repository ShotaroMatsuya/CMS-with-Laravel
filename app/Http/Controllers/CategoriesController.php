<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\Categories\CreateCategoryRequest; //バリデーションルールを適用するためにimportが必要
use App\Http\Requests\Categories\UpdateCategoriesRequest;
use App\Category; //categoriesテーブルにアクセスするためにimportが必要

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('categories.index')->with('categories', Category::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request) //自動的にRequestインスタンスを変数$requestに代入してくれる省略的な書き方
    {
        //staticメソッドでcreateする場合はmass-assignmentエラーに注意
        Category::create([
            'name' => $request->name
        ]);
        session()->flash('success', 'カテゴリーの作成に成功しました。');
        return redirect(route('categories.index'));
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
    public function edit(Category $category) //Categoryモデルのインスタンスを変数に代入
    {
        return view('categories.create')->with('category', $category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoriesRequest $request, Category $category)
    {
        // $category->name = $request->name; //フォームから送信された値はrequestインスタンスで取得可能

        // $category->save();

        $category->update([
            'name' => $request->name
        ]); //この書き方だとsaveメソッド不要

        session()->flash('success', 'カテゴリーの編集内容が保存されました。');

        return redirect(route('categories.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
        if ($category->posts->count() > 0) {
            session()->flash('error', 'このカテゴリーはすでにいくつかの記事に使用されているため削除できません。');
            return redirect()->back();
        }

        $category->delete();

        session()->flash('success', 'カテゴリーの削除に成功しました。');

        return redirect(route('categories.index'));
    }
}
