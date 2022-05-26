<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ env('APP_NAME') }} | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('assets') }}/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('assets') }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets') }}/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page" >
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-warning">
    <div class="card-header text-center">
        <img src="{{ asset('assets') }}/dist/img/icon/2.png"  alt="User Image">
      {{-- <a href="#" class="h1"><b>{{ env('APP_NAME') }}</b></a> --}}
    </div>
    <div class="card-body">
      {{-- <p class="login-box-msg">Masukkan Nama dan Password anda</p> --}}

      <form action="{{ route('login.attempt') }}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Username" name="username" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
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
          <div class="col-12">
            <button type="submit" class="btn btn-warning btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <div class="img-logo">
        <img src="" alt="">
      </div>
      <div class="foot_box">
          <p><em>Seek the LORD and His strength; seek His presence continually!
              <br>
            (1 Chronicles 16:11) </em></p>

      </div>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('assets') }}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets') }}/dist/js/adminlte.min.js"></script>
<!-- Workspace Dark Mode -->
<script src="{{ asset('assets') }}/workspace/adminLte3DarkMode.js"></script>

@include('_partials.toast')
</body>
</html>

<style>
.card-header img {
    max-width: 200px;
}

.card-header  {
 padding: 0
}
.foot_box {

    text-align: center;
}
em {
    font-size: 12px;
    color:cadetblue
}
</style>
