@php
    use Src\language\Language;
@endphp

<!DOCTYPE html>
<html lang="{{ Language::current()->value }}">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'My App')</title>
    <script src="/libs/htmx.min.js"></script>
</head>
<body hx-boost="true">
@yield('content')

</body>
</html>
