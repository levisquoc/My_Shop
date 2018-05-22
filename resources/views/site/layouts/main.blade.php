<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="title" content="@yield('meta-title')">
    <meta name=”description” content=”@yield('meta-description')”/>
    <meta name=”keywords” content=”@yield('meta-keywords')”/>
    <title>@yield('title')</title>
    <base href="{{asset('')}}">
    <link rel="stylesheet" href="vendor/bootstrap-4.0.0-beta/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/jquery-ui-1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" href="vendor/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="site/css/site.css">
    <!-- toast CSS -->
    <link href="http://creeper.dev/Modules/Main/plugins/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- Custom css -->
    <style>
        {!! Creeper::setting('custom-css') !!}
    </style>

    @stack('style')
</head>
<body>

<div id="wrapper">

    <!-- include header-->
@include('site.layouts.header')

<!-- Content-->
@yield('content')

<!-- include footer-->
    @include('site.layouts.footer')

</div>

@yield('modals')

<!-- Analytics -->
{!! Creeper::setting('site-analytics') !!}

<!-- inlucde js -->
<script src="vendor/jquery-3.2.1/jquery-3.2.1.min.js"></script>
<script src="vendor/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
        integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
        crossorigin="anonymous"></script>
<script src="vendor/bootstrap-4.0.0-beta/dist/js/bootstrap.min.js"></script>
<script src="site/lib/trunk8/trunk8.min.js"></script>
<script src="site/lib/jquery-advanced-news-ticker/jquery.newsTicker.min.js"></script>
<script src="site/js/myscript.js"></script>
<script src="http://creeper.dev/Modules/Main/plugins/toast-master/js/jquery.toast.js"></script>

@if (session()->has('mes'))
    <script type="text/javascript">
        $(function () {
            $.toast({
                text: '{{session()->get('mes')}}',
                position: 'top-right',
                loaderBg: '#ff6849',
                @if(session()->get('status') == 'error')
                icon: 'error',
                @else
                icon: 'success',
                @endif
                hideAfter: 5000,
                stack: 6
            });
        });
    </script>
@endif
@stack('scripts')
</body>
</html>