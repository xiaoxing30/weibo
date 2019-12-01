@foreach(['danger','warning','success','info'] as $msg)
{{--  session()->has($msg) 可用于判断会话中 $msg 键对应的值是否为空，若为空则在页面上不进行显示。--}}
  @if(session()->has($msg))
    <div class="flash-message">
      <p class="alert alert-{{ $msg }}">
{{--        通过 session()->get($msg) 来取出对应的值并在页面上进行显示。--}}
        {{ session()->get($msg) }}
      </p>
    </div>
  @endif
@endforeach
