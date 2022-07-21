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
Tes
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
