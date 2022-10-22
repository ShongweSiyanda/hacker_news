<!DOCTYPE @lang('eng')>
<html>
<title>Hacker News - @yield('title')</title>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @include('layouts.partials.head')
</head>
<body class="body-content">
@yield('content')
@include('layouts.partials.footer')
@include('layouts.partials.scripts')
</body>
</html>
