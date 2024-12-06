<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- favicon link-->
  <link rel="shortcut icon" type="image/icon" href="{{ asset('images/') }}/logo/{{ $website_info->fav_icon }}" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('backend/')}}/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('backend/')}}/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('backend/')}}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('backend/')}}/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page" style="background-color: #8EC5FC;
background-image: linear-gradient(62deg, #8EC5FC 0%, #E0C3FC 100%);
">
<div class="login-box">
  <div class="login-logo">
        <a href="/"><img src="{{ asset('images/') }}/logo/{{ $website_info->logo }}" alt=""></a>
      </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body" style="background-color: #FAACA8;background-image: linear-gradient(19deg, #FAACA8 0%, #DDD6F3 100%);">
      
      <h2 class="text-center text-bold">{{ $website_info->name }}</h2>
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="{{ route('login') }}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email" autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
        <div class="input-group mb-3">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-eye" id="togglePassword"></span>
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <script>
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');

            togglePassword.addEventListener('click', function (e) {
                // Toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle the eye icon (optional)
                this.classList.toggle('fa-eye-slash');
            });
        </script>

        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
              <label for="remember">
                {{ __('Remember Me') }}
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!-- <div class="social-auth-links text-center mb-3">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
      </div> -->
      <!-- /.social-auth-links -->
      @if (Route::has('password.request'))
      <!-- <p class="mb-1">
        <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
      </p> -->
      @endif
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{asset('backend/')}}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('backend/')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="{{asset('backend/')}}/dist/js/adminlte.min.js"></script>

</body>
</html>