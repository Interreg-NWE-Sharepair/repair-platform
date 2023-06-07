<!doctype html>
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie10" lang="{{ Lang::locale() }}"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9 lt-ie10" lang="{{ Lang::locale() }}"> <![endif]-->
<!--[if IE 9]> <html class="no-js lt-ie10" lang="{{ Lang::locale() }}"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="{{ Lang::locale() }}"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEO::generate() !!}

    @if (config('app.env') === 'production')
        <script src="js/flare.js" crossorigin="anonymous"></script>
    @endif

    @if(env('GOOGLE_TAGMANAGER_ID'))
        <script>
          //GTM data layer
          dataLayer = [];
        </script>
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
              new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
          })(window,document,'script','dataLayer','{{ env('GOOGLE_TAGMANAGER_ID') }}');</script>
        <!-- End Google Tag Manager -->
    @endif
    @if (env('DEV_SERVER_URL'))
        <link rel="stylesheet" href="{{ env('DEV_SERVER_URL') }}css/main.css" rel="preload">
    @else
        <link rel="stylesheet" href="css/main.css" rel="preload">
    @endif
    @if (env('DEV_SERVER_URL'))
        <link rel="icon" type="image/png" href="{{ env('DEV_SERVER_URL') }}img/favicon.png" rel="preload">
    @else
        <link rel="icon" type="image/png" href="img/favicon.png" rel="preload">
    @endif
    @if (env('DEV_SERVER_URL'))
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather:400,400i,700">
    <link rel="stylesheet" href="https://use.typekit.net/mcn0tsm.css">
    <link rel="stylesheet" href="{{ env('DEV_SERVER_URL') }}../css/critical.min.css"> --}}
    @else
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather:400,400i,700">
    <link rel="stylesheet" href="https://use.typekit.net/mcn0tsm.css">
    <link rel="stylesheet" href="{{ asset('../css/critical.min.css') }}"> --}}
    @endif
    {{-- <script type="text/javascript">
      (function() {
        var wf = document.createElement('script');
        wf.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
        wf.type = 'text/javascript';
        wf.async = 'true';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(wf, s);
      })();
    </script> --}}
    <title>{{ __('messages.page_title') }}</title>
    <style type="text/css">
        body {
            text-align: center;
        }
    </style>
</head>
<body data-components="form @yield('body_components')"@if (isset($print) && $print) onload="window.print()"@endif>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ env('GOOGLE_TAGMANAGER_ID') }}"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<div class="page-wrap">

    @section('header')
        @include('partials.header')
    @show

    <main class="page-main" role="main">
        <noscript>
          <div class="section">
              <div class="container container--fluid">
                  <div class="notice notice--danger text text--center">
                      <span class="icon icon--warning" aria-hidden="true"></span> Je hebt Javascript nodig om deze tool te gebruiken.
                  </div>
              </div>
          </div>
        </noscript>

        @section('content')
            <div class="section section--default">
                <div class="section__content">
                    <div class="container">
                        <div class="text--center">
                            @yield('content_header')
                        </div>
                    </div>
                </div>
            </div>

            @include('partials.errors')

            @yield('content_body')
        @show
    </main>

    @section('footer')
        @include('partials.footer')
    @show

    @section('flyout')
        @include('partials.flyout')
    @show
</div>

{{--@include('cookieConsent::index')--}}

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript">
  window.app = window.app || {};
  app.variables = {
    assetsPath: '/'
  };
</script>
@if (env('DEV_SERVER_URL'))
    <script type="text/javascript" src="{{ env('DEV_SERVER_URL') }}js/main.js"></script>
@else
    <script type="text/javascript" src="js/main.js"></script>
@endif
</body>
</html>
