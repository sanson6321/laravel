<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title'){{ ' | ' . config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <!-- stylesheet -->
    <link rel="stylesheet" href="{{ FormatAsset::asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ FormatAsset::asset('css/modal.css') }}">
    @yield('css')
    <!-- /stylesheet -->
    <!-- fontawesome -->
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
    <!-- /fontawesome -->
    <!-- javascript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ FormatAsset::asset('js/common.js') }}"></script>
    <script src="{{ FormatAsset::asset('js/modal.js') }}"></script>
    <!-- /javascript -->
</head>

<body>
    <div id="toast">
        @if (session('message_success'))
            <div class="toast toast-succsess">
                <p>{{ session()->pull('message_success') }}</p>
                <div class="life"></div>
            </div>
        @endif
        @if (session('message_error'))
            <div class="toast toast-error">
                <p>{{ session()->pull('message_error') }}</p>
                <div class="life"></div>
            </div>
        @endif
        <div class="toast toast-error toast-ajax" style="display: none">
            <p id="message-error"></p>
            <div class="life"></div>
        </div>
    </div>
    @yield('content')
    <!-- javascript -->
    @yield('js')
    <script>
        $(function() {
            console.log('%c' + @json(config('app.name')), "color: #000; font-weight: bold; font-size: 25px;");
        });
    </script>
    <!-- /javascript -->
</body>

</html>