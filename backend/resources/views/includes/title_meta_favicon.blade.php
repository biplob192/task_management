<meta charset="utf-8" />

<!-- effect on page title -->
<title>{{ $title ?? config('app.name'). ' | Dashboard' }}</title>
{{-- <title>{{ config('app.name', 'Laravel') }} | @yield('page-title')</title> --}}

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
<meta content="Themesbrand" name="author" />

<!-- App favicon -->
<link rel="shortcut icon" href="assets/images/favicon.ico">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
