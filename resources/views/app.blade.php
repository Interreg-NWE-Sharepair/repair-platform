<!doctype html>
<html lang="{{ Lang::locale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <meta property="og:title" content="{{ trans('seo.meta_title') }}"/>
    <meta name="description" content="{{ trans('seo.meta_description') }}">
    <meta property="og:description" content="{{ trans('seo.meta_description') }}"/>
    <meta name="twitter:title" content="{{ trans('seo.meta_title') }}" />
    <meta name="twitter:description" content="{{ trans('seo.meta_description') }}" />
    <meta property="og:url" content="{{ Request::url() }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:image" content="{{ url('/img/hero.png') }}"/>
    <meta property="og:image:width" content="1200"/>
    <meta property="og:image:height" content="1200"/>
    <meta property="og:image:alt" content="Banner foto"/>
    <meta property="og:site_name" content="Repair Connects"/>
    <meta property="og:locale" content="{{ Lang::locale() }}"/>
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="" />
    <meta name="twitter:url" content="{{ Request::url() }}" />
    <meta name="twitter:image" content="{{ url('/img/hero.png') }}" />

    <link rel="canonical" href="{{ Request::url() }}" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @routes

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-5Z3D6HN');
    </script>
    <!-- End Google Tag Manager -->

    @if (App::environment('live') && config('flare.key'))
        <script>
            window.FLARE_KEY = '{{ config('flare.key') }}';
        </script>
        <script src="{{ asset('js/flare.js') }}" crossorigin="anonymous"></script>
    @endif

        <script src="{{ asset('js/polyfill.js') }}"></script>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,700;0,900;1,400&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ url('img/favicon.png') }}" rel="preload">
    {{-- <title>{{ __('messages.page_title') }}</title> --}}
</head>
@if (file_exists(public_path().'/js/app.js'))
    <body data-components="form @yield('body_components')"@if (isset($print) && $print) onload="window.print()"@endif>
@else
    <body class="hmr" data-components="form @yield('body_components')"@if (isset($print) && $print) onload="window.print()"@endif>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5Z3D6HN"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
@endif

@inertia

@yield('no-inertia')

@if (file_exists(public_path().'/js/app.js'))
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/styles.js') }}"></script>
@else
    {{ logger('hot reload enabled!!') }}
    <script type="text/javascript" src="{{ env('DEV_SERVER_URL') }}js/app.js"></script>
    <script type="text/javascript" src="{{ env('DEV_SERVER_URL') }}js/styles.js"></script>
@endif
    </body>
</html>
