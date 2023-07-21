<?php

	include_once ROOT_PATH."library/DB.php";
	include_once ROOT_PATH."library/FileManager.php";
	include_once ROOT_PATH."mvc/model/LoginModel.php";
	include_once ROOT_PATH."mvc/controller/LoginController.php";
	$message=null;
	
	$LoginController = new LoginController;
	
	$msgCaptchaGoogle = null;

	if (isset($_POST['login']) ){
		$msgCaptchaGoogle = $LoginController->verifyCaptcha();
	}
		

	else if (isset($_POST['register'])) {
		$LoginController->registerUser();
	}
	else if (isset($_POST['recoverEmail'])) {
		$LoginController->recoverEmail();
	}
	
	
	
	/*======================================================================
	MSG
	========================================================================*/
	if (isset($_GET['notuser']) )
		$message="Wrong email.";
	elseif (isset($_GET['notpwd'])) {
		$message="Incorrect password.";
	}
	elseif (isset($_GET['user_registered'])) {
		$message="Successfully registered user.";
	}
	elseif (isset($_GET['user_exist'])) {
		$message="Email already exists.";
	}
	elseif (isset($_GET['user_not_register'])) {
		$message="An error occurred while registering.";
	}
	
  ?>

<!DOCTYPE html>
<html lang="en">
<head>
	
	<title>Login</title>
	<meta charset="utf-8">
    <meta http-equiv="pragma" content="no-cache"/>
    <meta name="viewport" content="width-device-width, user-scalable=no, initial-scale=1.0, maiximum-scale1.0, minimum-scale=1.0">
    <link rel="icon"  href="<?= BASE_URL ?>public/img/icon/logoapp.png">

	<link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/plugins/ka-f.fontawesome.v5.15.4.free.min.css">
	<link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/general/msg.css">
	<link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/login.css">
	<!----========googleTranslate ======== -->
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/googleTranslate.css">
	<!----========reCAPTCHA de Google ======== -->
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>

	
	
</head>
<body>
	
	<!-- ===============================================================================
	 SIGN UP
	=================================================================================-->
	
	<?php if (isset($_GET['sign_up']) ){?>
		<div class="center-login">
			<form class="login-container" action="<?= BASE_URL ?>login" method="POST">
				<div id="google_translate_element" class="translate_element_login pb-2"></div>
				<a href="#"><img src="<?= BASE_URL ?>public/img/icon/logoapp.png" class="logo"></a>
				<p class="organizapp">OrganizApp</p>
				<input type="text" hidden="" name="register">
				<div class="fields">
					<div class="data">
						<i class="fas fa-user"></i>
						<input type="text" placeholder="Name" name="nombre_c" required="">
					</div>
					<div class="data">
						<i class="fas fa-user"></i>
						<input type="email" placeholder="E-mail" name="email" required="">
					</div>
					<div class="data">
						<i class="fas fa-lock"></i>
						<input id="showPwd" type="password" placeholder="Write Password" name="pwd" required="">
						<i class="far fa-eye" id="showEye"></i>
						<i class="far fa-eye-slash" id="hideEye"></i>
					</div>
					<div class="data">
						<i class="fas fa-lock"></i>
						<input type="password" placeholder="Confirm Password" name="repeat_pwd" required="">
					</div>
				</div>
				
				<div class="cont-text-bottom">
					<button type="submit" class="btn-login">Register</button>
					<a href="?" class="text-login">Log in</a>
					<!-- <a href="public/privacy policies/Politica de privacidad.pdf" class="text-login">Privacy policies</a> -->
				</div>
			</form>
		</div>

		<!-- ===============================================================================
		 SIGN IN
		=================================================================================-->

	<?php }else { ?>

		<div class="center-login recaptcha-container">
			<form id="sign-in" class="login-container" action="" method="POST">
				
				<div id="google_translate_element" class="translate_element_login pb-2"></div>
				<a href="#"><img src="<?= BASE_URL ?>public/img/icon/logoapp.png" class="logo"></a>
				<p class="organizapp">OrganizApp</p>
				<input type="text" hidden="" name="login">
				<div class="fields">
					<div class="data">
						<i class="fas fa-user"></i>
						<input type="email" placeholder="E-mail" name="email" required="">
					</div>
					<div class="data">
						<i class="fas fa-lock"></i>
						<input type="password" id="showPwd" placeholder="Write Password" name="pwd" required="">
						<i class="far fa-eye" id="showEye"></i>
						<i class="far fa-eye-slash" id="hideEye"></i>
					</div>
					<!-- <div class="login-captcha">
						<i class="fas fa-shield-alt"></i>
						<label for="" >Captcha</label>
					</div> -->
					
					<div class="g-recaptcha" data-sitekey="6LdCNwEnAAAAABAC9JBG6Lm8NiGSKVbRjoILJcnI"></div>
					
				</div>

				<br>
				<?php if ($msgCaptchaGoogle != null){?>
					<div class="login-captcha">
						<i class="fas fa-exclamation-triangle" style="color:rgba(227, 60, 47,0.9)"></i>
						<label for="" style="color:rgba(227, 60, 47,0.9)"><?=$msgCaptchaGoogle?></label>
					</div>
				<?php } ?>
				<button type="submit" class="btn-login">Log in</button>
				
				<div class="cont-text-bottom">
					<a href="?sign_up" class="text-login">Sign up here!</a>
					<a id="recover_pwd" href="#" class="text-login">Recover password</a>
					<!-- <a href="public/privacy policies/Politica de privacidad.pdf" class="text-login">Privacy policies</a> -->
				</div>
				
			</form>
		</div>
	<?php } ?>
	<!-- ===============================================================================
	CAPTCHA
	=================================================================================-->
	<!-- <div class="msg-cont-modal" id="captcha" style="display: none;">
		<div class="msg-container-form">	
			<div class="msg-container-label">
				<label class="msg-lbl-txt"><p>Help us verify that you are not a robot.</p> 
				 	<label class="msg-lbl-delete"> </label> 
				</label>
			</div> 
			<div class="msg-line-form"></div> -->
			<!-- Cambia esta ID por otra deseada, asegúrate de cambiarla también en Los archivos CSS y JS. -->
			<!-- <div class="my-captcha" >
				<input type="text" id="randomfield" readonly>
				<label>Captcha</label>
				<input id="captchaEnter" size="20" maxlength="6" placeholder="Write the captcha" enabled="enabled">
			</div>
	  		<div class="msg-cont-buttons">
				<input type="button" class="ms-button msg-btn-refresh" value="Reload">
				<input type="button" class="ms-button msg-btn-ok" value="Ok">
			</div>
		</div>
	</div> -->


	<!-- ===============================================================================
	RECOVER PWD
	=================================================================================-->
	<div class="msg-cont-modal" id="cont_recover_pwd" style="display: none;">
		<div class="msg-container-form">	
			<div class="msg-container-label">
				<label class="msg-lbl-txt"><p>We will send the password to your email.</p> 
				 	<label class="msg-lbl-delete"> </label> 
				</label>
			</div> 
			<div class="msg-line-form"></div>
			<!-- Cambia esta ID por otra deseada, asegúrate de cambiarla también en Los archivos CSS y JS. -->
			<div class="my-captcha" >
				<input id="recover_email" type="email" placeholder="Write your email" enabled="enabled">
				<label id="res_recover_pwd" for=""></label>
			</div>
			
	  		<div class="msg-cont-buttons">
				<input type="button" class="ms-button msg-btn-close" value="Close modal">
				<input type="button" class="ms-button msg-btn-send" value="Send">
			</div>
		</div>
	</div>

	

	<!-- ===============================================================================
	JS
	=================================================================================-->
	<script src="<?= BASE_URL ?>public/js/plugins/jquery.min.3.3.1.js"></script>
	<script src="<?= BASE_URL ?>public/js/general/all.js"></script>
	<!-- ===============================================================================
	 MSG
	=================================================================================-->
	<?php if ($message!=null) { 
				include_once ROOT_PATH."mvc/view/general/msg.php";
				echo "<script src=".BASE_URL."public/js/general/msg.js></script>";
		  }
	?>
    <script src="<?= BASE_URL ?>public/js/login.js"></script>

	<!----=====GOOGLE TRANSLATE ===== -->
	<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script src="<?= BASE_URL ?>public/js/googleTranslate.js"></script>
		
	
</body>
</html>