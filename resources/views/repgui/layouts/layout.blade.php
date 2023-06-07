<!doctype html>
<html lang="{{ Lang::locale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {!! SEO::generate() !!}


    <link href="/repgui/css/main.css" rel="stylesheet"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Tag Manager -->
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PBSMML7');</script>
<!-- End Google Tag Manager -->
    <!-- End Google Tag Manager -->

    {{--    @if (App::environment('live') && config('flare.key'))--}}
    {{--        <script>--}}
    {{--            window.FLARE_KEY = '{{ config('flare.key') }}';--}}
    {{--        </script>--}}
    {{--        <script src="{{ asset('js/flare.js') }}" crossorigin="anonymous"></script>--}}
    {{--    @endif--}}

    <script src="{{ asset('js/polyfill.js') }}"></script>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,700;0,900;1,400&display=swap"
          rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ url('img/favicon.png') }}" rel="preload">
    {{-- <title>{{ __('messages.page_title') }}</title> --}}
    {{-- use this section in your template if you want custom styling specific for that page --}}
    <link rel="stylesheet" type="text/css" href="{{asset("vendor/cookie-consent/css/cookie-consent.css")}}">
    @section('custom_styles')
    @show
</head>

<body @if (isset($print) && $print) onload="window.print()"@endif>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBSMML7"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div class="page-wrap">

    @section('header')
        @include('repgui.partials.header')
    @show

    <main class="page-main" role="main">
        @section('content')
            @yield('content_header')

            @if ($errors->any())
                <div class="section section--default">
                    <div class="section__content">
                        <div class="container">
                            <div class="text--center">
                                @include('repgui.partials.errors')
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content_body')
        @show
    </main>

    @section('footer')
        @include('repgui.partials.footer')
    @show

    @section('flyout')
        @include('repgui.partials.flyout')
    @show
</div>
<script src="{{ asset('/repgui/js/main.js') }}"></script>
</body>
</html>
