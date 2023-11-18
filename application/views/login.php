<!DOCTYPE html>
<html lang="en">
<?php 
	$ruta = base_url(); 
?>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo $ruta; ?>recursos/img/favicon.png">
	<title>SID - Sistema integral para droguerias</title>
	<!-- Bootstrap Core CSS -->
	<link href="<?php echo $ruta; ?>recursos/plugins/bower_components/bootstrap/dist/css/bootstrap.css"
				rel="stylesheet">
	<link href="<?php echo $ruta; ?>recursos/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css"
				rel="stylesheet">
	<!-- animation CSS -->
	<link href="<?php echo $ruta; ?>recursos/css/animate.css" rel="stylesheet">
	<!-- Custom CSS -->
	<link href="<?php echo $ruta; ?>recursos/css/style.min.css" rel="stylesheet">
	<link href="<?php echo $ruta; ?>recursos/plugins/bower_components/toast-master/css/jquery.toast.css"
				rel="stylesheet">
	<!-- color CSS -->
	<link href="<?php echo $ruta; ?>recursos/css/colors/megna.css" id="theme" rel="stylesheet">
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<link href="<?php echo $ruta; ?>recursos/plugins/bower_components/sweetalert/sweetalert.css" rel="stylesheet"
		type="text/css">
	<![endif]-->
</head>

<body>
<!-- Preloader -->
<div class="preloader">
	<div class="cssload-speeding-wheel"></div>
</div>
<section id="wrapper" class="login-register" style="background: url(<?= $imgbanner ?>) no-repeat center center/cover!important;">
	<div class="login-box login-sidebar">
		<div class="white-box">
			<form class="form-horizontal form-material" id="loginform" action="">
				<a href="javascript:void(0)" class="text-center db">
					<img src="<?php echo $imglogo; ?>" width="175" alt="SID"/>
				</a><br/>
				<div class="form-group m-t-40">
					<div class="col-xs-12">
						<input class="form-control" name="user" type="text" required="" placeholder="Username">
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-12">
						<input class="form-control" name="pw" type="password" required="" placeholder="Password">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<div class="checkbox checkbox-primary pull-left p-t-0">
							<input id="checkbox-signup" type="checkbox">
							<label for="checkbox-signup"> Recordarme </label>
						</div>
						<a href="javascript:void(0)" id="to-recover" class="text-dark pull-right">
							<i class="fa fa-lock m-r-5"></i> Olvidaste tu contraseña?
						</a>
					</div>
				</div>
				<div class="form-group text-center m-t-20">
					<div class="col-xs-12">
						<button class="btn btn-success btn-lg btn-block text-uppercase waves-effect waves-light"
							type="submit" id="">Log In
						</button>
					</div>
				</div>

				<ul class="fa-ul text-muted">
					<li><i class="fa fa-check fa-li text-success"></i> Limpio &amp; Moderno Diseño</li>
					<li><i class="fa fa-check fa-li text-success"></i> Adaptable a todos los dispositvos</li>
					<li><i class="fa fa-check fa-li text-success"></i> Control de ventas</li>
					<li><i class="fa fa-check fa-li text-success"></i> Registro de compras</li>
					<li><i class="fa fa-check fa-li text-success"></i> Manejo de inventario</li>
					<li><i class="fa fa-check fa-li text-success"></i> .. y muchas mas caracteristicas!</li>
				</ul>
			</form>
			<form class="form-horizontal" id="recoverform" action="index.html">
				<div class="form-group ">
					<div class="col-xs-12">
						<h3>Recover Password</h3>
						<p class="text-muted">Enter your Email and instructions will be sent to you! </p>
					</div>
				</div>
				<div class="form-group ">
					<div class="col-xs-12">
						<input class="form-control" type="text" required="" placeholder="Email">
					</div>
				</div>
				<div class="form-group text-center m-t-20">
					<div class="col-xs-12">
						<button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light"
							type="submit">Reset
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
<!-- jQuery -->
<script src="<?php echo $ruta; ?>recursos/plugins/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="<?php echo $ruta; ?>recursos/plugins/bower_components/bootstrap/dist/js/tether.min.js"></script>
<script src="<?php echo $ruta; ?>recursos/plugins/bower_components/bootstrap/dist/js/bootstrap.js"></script>
<script src="<?php echo $ruta; ?>recursos/plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js"></script>
<!-- Menu Plugin JavaScript -->
<script src="<?php echo $ruta; ?>recursos/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
<!--slimscroll JavaScript -->
<script src="<?php echo $ruta; ?>recursos/js/jquery.slimscroll.js"></script>
<script src="<?php echo $ruta; ?>recursos/plugins/bower_components/toast-master/js/jquery.toast.js"></script>
<script src="<?php echo $ruta; ?>recursos/js/controllers/Utilities.js"></script>
<!--Wave Effects -->
<script src="<?php echo $ruta; ?>recursos/js/waves.js"></script>
<!-- Custom Theme JavaScript -->
<script src="<?php echo $ruta; ?>recursos/js/custom.min.js"></script>
<script src="<?php echo $ruta; ?>recursos/plugins/bower_components/sweetalert/sweetalert.min.js"></script>
<!--Style Switcher -->
<script src="<?php echo $ruta; ?>recursos/plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
</body>
<script type="text/javascript">
	$(document).ready(function () {
		var err = '<?= isset($_GET["err"]) ? $_GET["err"] : ""?>';
		if (err === 'exp') {
			Utilities.alertModal('Su licencia de uso de sistema ha expirado','error','9000');
		}
		if (err === 'licerr') {
			Utilities.alertModal('Ha currido un error al validar su licencia, por favor contacte con soporte');
		}
		if (err === 'session') {
			Utilities.alertModal('Su sessión ha expirado');
		}
		$("#loginform").on('submit', function (event) {
			login(event);
		});
		$('body').on('keydown', function (e) {
			// console.log(e.keyCode );
			if (e.keyCode == 13) {
				login(event);
			}
		})
	});

	function login(event) {
		<?php $mensaje = "<a></a>"; ?>
		event.preventDefault();
		$.ajax({
			type: "POST",
			data: $('#loginform').serialize(),
			dataType: 'json',
			url: "<?php echo $ruta; ?>inicio/validar_login",
			success: function (data) {
				if (data.msj == 'ok') {
					sessionStorage.setItem('api_key', data.api_key);
					window.location.href = "<?php echo $ruta;?>principal/";
				} else {
					Utilities.alertModal('Usuario o clave incorrecta, por favor vuelva a intentar', 'error');
				}
			}
		});
	}
</script>

</html>