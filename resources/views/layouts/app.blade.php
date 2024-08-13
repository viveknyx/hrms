<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!--  Title -->
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!--  Required Meta Tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('components.headscript')
    @yield('pagecss')
    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>

<body class="font-sans antialiased">
    @include('components.preloaders')
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-theme="blue_theme" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        @include('components.leftsidebar')
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            @include('components.header')
            <!--  Header End -->
            <div class="container-fluid">
                {{ $slot }}
            </div>
        </div>
    </div>
    @include('components.footscript')
    @yield('pagescript')
</body>

</html>
