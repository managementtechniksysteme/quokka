<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Die {{ config('app.name') }} Projektmanagement Applikation.">

    <!-- PWA -->
    <!-- Android  -->
    <meta name="theme-color" content="white">
    <meta name="mobile-web-app-capable" content="yes">

    <!-- iOS -->
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="white">

    <!-- Windows  -->
    <meta name="msapplication-navbutton-color" content="white">
    <meta name="msapplication-TileColor" content="white">
    <meta name="msapplication-TileImage" content="icons/icon_144.png">

    <!-- Pinned Sites  -->
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="msapplication-tooltip" content="{{ config('app.name') }}">
    <meta name="msapplication-starturl" content="/">

    <!-- Tap highlighting  -->
    <meta name="msapplication-tap-highlight" content="no">

    <!-- UC Mobile Browser  -->
    <meta name="full-screen" content="yes">
    <meta name="browsermode" content="application">

    <!-- Disable night mode for this page  -->
    <meta name="nightmode" content="disable">

    <!-- Layout mode -->
    <meta name="layoutmode" content="standard">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Main Link Tags  -->
    <link href="icons/icon_48.png" rel="icon" type="image/png" sizes="48x48">
    <link href="icons/icon_72.png" rel="icon" type="image/png" sizes="72x72">
    <link href="icons/icon_96.png" rel="icon" type="image/png" sizes="96x96">
    <link href="icons/icon_144.png" rel="icon" type="image/png" sizes="144x144">
    <link href="icons/icon_192.png" rel="icon" type="image/png" sizes="192x192">
    <link href="icons/icon_512.png" rel="icon" type="image/png" sizes="512x512">

    <!-- iOS  -->
    <link href="icons/icon_72.png" rel="apple-touch-icon">
    <link href="icons/icon_96.png" rel="apple-touch-icon" sizes="96x96">
    <link href="icons/icon_144.png" rel="apple-touch-icon" sizes="144x144">
    <link href="icons/icon_192.png" rel="apple-touch-icon" sizes="192x192">

    <!-- Startup Image  -->
    <link href="icons/icon_512.png" rel="apple-touch-startup-image">

    <!-- Others -->
    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon">

    <!-- UC Browser  -->
    <link href="icons/icon_48.png" rel="apple-touch-icon-precomposed" sizes="48x48">
    <link href="icons/icon_72.png" rel="apple-touch-icon" sizes="72x72">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Manifest -->
    <link rel="manifest" href="manifest.json">
</head>

<body>

<div id="app">
    @include('partials.notifications')
    @include('partials.navbar')

    <main>
        @yield('content')
    </main>
</div>
</body>

</html>
