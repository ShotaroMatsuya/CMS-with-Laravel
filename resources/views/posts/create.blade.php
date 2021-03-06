@extends('layouts.app')

@include('includes.tinyeditor')
@section('content')

    <div class="card card-default">
        <div class="card-header">
            {{isset($post) ? 'Edit Post': 'Create Post'}}

        </div>
        <div class="card-body">
            @include('partials.errors')
        <form action="{{ isset($post) ? route('posts.update',$post->id) : route('posts.store')}}" method="POST" enctype="multipart/form-data"> <!-- multmediaファイルをsubmitする際にはenctypeをセットすること -->
            @csrf
            @if (isset($post))
            @method('PUT')

            @endif
            <div class="form-group">
                <label for="title">Title</label>
            <input type="text" class="form-control" name="title" id="title" value="{{isset($post) ? $post->title:''}}">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input id="description" type="hidden" name="description" value="{{isset($post) ? $post->description: ''}}">
                    <trix-editor input="description"></trix-editor>
            </div>
            <div class="form-group">
                <label for="content">Content</label>

                <textarea name="content" id="content" cols="5" rows="5" class="form-control">{{isset($post) ? $post->content:''}}</textarea>
            </div>
            <div class="form-group">
                <label for="published_at">Published At</label>
            <input type="text" class="form-control" name="published_at" id="published_at" value="{{isset($post) ? $post->published_at : ''}}">
            </div>
            @if (isset($post))
                <div class="form-group">
                <img src="{{Storage::disk('s3')->url($post->image)/*asset('/storage/'.$post->image)*/}}" alt="" style="width:100%;">
                </div>
            @endif
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control" name="image" id="image">
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select name="category" id="category" class="form-control">
                    @foreach ($categories as $category)

                <option value="{{$category->id}}"
                    @if (isset($post))
                        @if ($category->id == $post->category_id)
                            selected
                        @endif

                    @endif
                >
                    {{$category->name}}
                </option>
                    @endforeach
                </select>
            </div>
            @if ($tags->count() > 0)
            <div class="form-group">
                <label for="tags">Tags</label>

                {{-- 複数選択可の場合は配列としてRequestオブジェクトに渡す必要があるのでname属性を以下のようにセットする必要がある --}}
                <select name="tags[]" id="tags" class="form-control tags-selector" multiple>
                    @foreach ($tags as $tag)
                <option value="{{$tag->id}}"
                    @if (isset($post)) {{--　編集時 --}}
                        @if ($post->hasTag($tag->id)) {{-- postがtagを持っていたらselect状態にする --}}
                            selected

                        @endif

                    @endif
                >
                    {{$tag->name}}
                </option>

                    @endforeach
                </select>

            </div>
            @endif
            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    {{isset($post) ? 'Update Post' : 'Create Post'}}
                </button>
            </div>
        </form>

        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.0/trix.js" integrity="sha512-S9EzTi2CZYAFbOUZVkVVqzeVpq+wG+JBFzG0YlfWAR7O8d+3nC+TTJr1KD3h4uh9aLbfKIJzIyTWZp5N/61k1g==" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    flatpickr('#published_at',{
        enableTime:true,
        enableSeconds:true
    });
    $(document).ready(function() {
    $('.tags-selector').select2();
});
</script>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.0/trix.css" integrity="sha512-EQF8N0EBjfC+2N2mlaH4tNWoUXqun/APQIuFmT1B+ThTttH9V1bA0Ors2/UyeQ55/7MK5ZaVviDabKbjcsnzYg==" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />


@endsection
