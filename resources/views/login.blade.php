<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>{{ $tabTitle }} | Login</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Bootstrap 3.3.2 -->
    <link href="{{ asset('AdminLTE/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Font Awesome Icons -->
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Ionicons -->
    <link href="{{ asset('css/ionicons.min.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset('AdminLTE/dist/css/AdminLTE.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('AdminLTE/dist/css/skins/skin-blue.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('AdminLTE/dist/css/skins/skin-red.min.css') }}" rel="stylesheet" type="text/css" />

    <!--js-->
    <script src="{{ asset('js/jquery/1.11.0/jquery.min.js') }}"></script>

    <!-- jquery ui -->
    <link rel="stylesheet" href="{{ asset('css/1.12.1/jquery-ui.css') }}"/>
    <script src="{{ asset('js/jquery/1.12.1/jquery-ui.js') }}"></script>

    <!-- jquery validate plugin -->
    <script src="{{ asset('js/jquery.validate.js') }}"></script>
    <script src="{{ asset('js/additional-methods.js') }}"></script>

    <!-- lightbox -->
    <link href="{{ asset('css/lightbox.css') }}" rel="stylesheet">
    <script src="{{ asset('js/lightbox.js') }}"></script>

    <!-- peity sparkline -->
    <script src="{{ asset('js/jquery.sparkline.js') }}"></script>

    <!-- select2 -->
    <link href="{{ asset('css/select2/4.0.3/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('js/select2/4.0.3/select2.min.js') }}"></script>

    <!-- css -->
    <script src="{{ asset('AdminLTE/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/dist/js/app.min.js') }}"></script>

  </head>
  <body>
    <div>
      <div>
        <section class="content">
            <div class="login-box">
			  <div class="login-logo">
                <img src="{{ asset('images/logo.png') }}"/>
			  </div>
			  <div class="login-box-body">
				<div>
					@if ($errors->any())
						<div class="alert alert-danger">
							@foreach ($errors->all() as $error)
								{{ $error }}<br>
							@endforeach
						</div>
					@elseif(Session::has('success'))
						<div class="alert alert-success" id="success-alert">
							<a href="#" class="close" data-dismiss="alert">&times;</a>
							{{ Session::get('success') }}
						</div>
					@elseif(Session::has('fail'))
						<div class="alert alert-danger">
							<a href="#" class="close" data-dismiss="alert">&times;</a>
							{{ Session::get('fail') }}
						</div>
					@elseif (Session::has('message'))
						<div class='bg-danger alert'>{{ Session::get('message') }}</div>
					@endif
				</div><!-- /.col -->

				 <form method="post" id="loginForm" action="{{ asset('login') }}">
				  <div class="form-group has-feedback">
					<input type="text" name="username" placeholder="Username" class="form-control" value="{{ old('username') }}"><br>
					<span class="glyphicon glyphicon-user form-control-feedback"></span>
				  </div>
				  <div class="form-group has-feedback">
					<input name="password" placeholder="Password" type="password" class='form-control'>
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				  </div>
				  <div class="row">
					<div class="col-xs-4 pull-right text-right" style="width: 100%;text-align: center;">
					  <button type="submit" class="btn btn-danger">Login</button>
					</div><!-- /.col -->
				  </div>
				</form>
			  </div>
			</div>
        </section>
      </div>
    </div>
  </body>
<script>
var APP_URL = {!! json_encode(url('/')) !!};

$("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
    $("#success-alert").slideUp(500);
});
</script>
</html>
