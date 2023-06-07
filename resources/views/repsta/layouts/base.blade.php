<!doctype html>
<html lang="{{ Lang::locale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {!! SEO::generate() !!}

    <link href="/repgui/css/main.css" rel="stylesheet"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,700;0,900;1,400&display=swap"
          rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ url('img/favicon.png') }}" rel="preload">
</head>

<body>
  @section('content')
      @yield('content_body')
  @show
  <script src="{{ asset('/repgui/js/stats.js') }}"></script>
</body>
</html>
