<!DOCTYPE @lang('eng')>
<html>
<title>@yield('title') | Hacker News
</title>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @include('layouts.partials.head')
</head>
<body class="body-content">
<div class="container-fluid">
@include('layouts.partials.header')
@yield('content')
</div>
@include('layouts.partials.footer')
@include('layouts.partials.scripts')
</body>
</html>
