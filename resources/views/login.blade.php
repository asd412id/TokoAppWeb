<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ getenv('APP_NAME').' | '.$title }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="{{ asset('assets') }}/theme/img/app_icon.png" type="image/x-icon"/>
  <link rel="shortcut icon" href="{{ asset('assets') }}/theme/img/app_icon.png" type="image/x-icon"/>
  <link rel="stylesheet" href="{{ asset('assets') }}/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('assets/fonts/ionicicon/css/ionicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets') }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('assets/theme') }}/css/adminlte.min.css">
  <link href="{{ asset('assets/fonts/sourcesanspro/stylesheet.css') }}" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="{{ asset('assets/theme') }}/index2.html"><b>Halaman</b> Login</a>
  </div>
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg mb-1 pb-1">Masuk untuk mengelolah sistem</p>
      @foreach ($errors->all() as $key => $err)
        <p class="text-danger text-center m-1 pb-1">{{ $err }}</p>
      @endforeach
      <form {{ route('login.process') }} method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Username" name="username" autofocus required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember">
                Ingat Saya
              </label>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="{{ asset('assets') }}/plugins/jquery/jquery.min.js"></script>
<script src="{{ asset('assets') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/theme') }}/js/adminlte.min.js"></script>

</body>
</html>
