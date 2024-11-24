<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('includes.title_meta_favicon')

    @include('includes.login_style')

    @stack('header')
</head>

<body>
    @yield('content')

    @include('includes.login_script')

    @stack('footer')
</body>

</html>
