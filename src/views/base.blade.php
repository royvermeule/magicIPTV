@php
    use Src\language\Language;
@endphp

        <!DOCTYPE html>
<html lang="{{ Language::current()->value }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'My App')</title>
    <script src="/libs/htmx.min.js"></script>
    <link rel="stylesheet" href="/styles/index.css">
</head>
<body hx-boost="true">
@yield('content')
</body>
</html>
