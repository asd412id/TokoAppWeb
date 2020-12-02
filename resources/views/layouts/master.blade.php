@php
$user = auth()->user();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ getenv('APP_NAME').' | '.$title }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="{{ asset('assets') }}/theme/img/app_icon.png" type="image/x-icon"/>
  <link rel="shortcut icon" href="{{ asset('assets') }}/theme/img/app_icon.png" type="image/x-icon"/>
  <link rel="stylesheet" href="{{ asset('assets') }}/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('assets/fonts/ionicicon/css/ionicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets') }}/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="{{ asset('assets') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="{{ asset('assets') }}/plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="{{ asset('assets') }}/theme/css/adminlte.min.css">
  <link href="{{ asset('assets/fonts/sourcesanspro/stylesheet.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets') }}/theme/css/styles.min.css">
  @yield('head')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    @include('layouts.navbar')
    @include('layouts.sidebar')

    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-12">
              <h1>{{ $title }}</h1>
            </div>
          </div>
      </section>

      <section class="content">
        @yield('content')
      </section>
    </div>

    <footer class="main-footer">
      <div class="float-right d-none d-sm-block">
        <b>Version</b> 1.0.0
      </div>
      <strong>Copyright &copy; {{ date('Y') }} <a href="https://www.facebook.com/aezdar">asd412id</a>.</strong> All rights
      reserved.
    </footer>

  </div>

  <script src="{{ asset('assets') }}/plugins/jquery/jquery.min.js"></script>
  <script src="{{ asset('assets') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('assets') }}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <script src="{{ asset('assets') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
  <script src="{{ asset('assets') }}/plugins/toastr/toastr.min.js"></script>
  <script src="{{ asset('assets') }}/theme/js/adminlte.min.js"></script>
  <script src="{{ asset('assets') }}/theme/js/autoNumeric.js"></script>
  <script src="{{ asset('assets') }}/theme/js/scripts.js"></script>
  <script>var audio = new Audio('{{ asset('assets/theme/sounds/beep.mp3') }}');</script>
  @yield('foot')
</body>
</html>
