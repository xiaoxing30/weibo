@extends('layouts.default')
@section('title','所有用户')

@section('content')
  <div class="offset-md-2 col-md-8">
    <h2 class="mb-4 text-center">所有用户</h2>
    <div class="list-group list-group-flush">
      @foreach($users as $user)
        @include('users._user')
        @endforeach
    </div>

    <div class="mt-3">
{{--      渲染分页视图的代码必须使用 {!! !!} 语法，而不是 {{　}}，这样生成 HTML 链接才不会被转义。--}}
      {!! $users->render() !!}
    </div>
  </div>
  @stop
