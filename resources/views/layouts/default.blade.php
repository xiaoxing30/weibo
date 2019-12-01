<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title','Weibo App') - xiaoxing30 </title>
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    </head>

    <body>
        @include('layouts._header')


        <div class="container">
            <div class="offset-md-1 col-md-10">
                @include('shared._messages')
                @yield('content')
                @include('layouts._footer')
            </div>
        </div>
{{--        在全局默认视图中引用编译后的 app.js 文件。--}}
    <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
