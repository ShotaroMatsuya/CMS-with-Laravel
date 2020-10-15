@extends('layouts.app')



@section('content')

<div class="card card-default">

    <div class="card-header">
        {{ isset($tag) ? 'Edit tag' : 'Create tag'}}
    </div>
    <div class="card-body">
        @include('partials.errors')
        <!-- name routeを使用-->
    <form action="{{ isset($tag) ? route('tags.update',$tag->id):route('tags.store')}}" method="POST">
            @csrf
            @if (isset($tag))
            @method('PUT') {{-- put ,patch , deleteなどのメソッドはこのように指定する必要がある  --}}

            @endif
            <div class="form-group">
                <label for="name">Name</label>
            <input type="text" class="form-control" name="name" value="{{isset($tag)? $tag->name: ''}}">

            </div>
            <div class="form-group">
                <button class="btn btn-success">
                    {{isset($tag) ? 'Update tag': 'Add tag'}}
                </button>
            </div>
        </form>
    </div>

</div>

@endsection
