<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('includes.title_meta_favicon')

    @include('includes.layout_style')

    @stack('header')
</head>

<body>
    <!-- <body data-layout="horizontal"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">


        <!--  Top Navbar Start  -->
        <x-common.top-nav />
        <!--  Top Navbar End  -->

        <!--  Left Sidebar Start  -->
        <x-common.aside />
        <!-- Left Sidebar End -->


        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    @yield('header')
                    <!-- end page title -->

                    <x-common.alert />

                    <div class="row">
                        <div class="col-12">
                            @if (session('success'))
                                <div class="alert alert-success" id="alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                {{-- <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div> --}}

                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="list-unstyled">
                                        @foreach ($errors->all() as $error)
                                            <i class="mdi mdi-block-helper me-2"></i> {{ $error }}

                                            @if (!$loop->last)
                                                <br>
                                            @endif
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @yield('content')
                            {{-- {{ $slot }} --}}
                        </div>
                    </div>
                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <x-common.footer />
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <x-common.right />

    {{-- Script area --}}
    @include('includes.layout_script')
    @include('includes._alert-script')
    @stack('footer')
</body>

</html>
