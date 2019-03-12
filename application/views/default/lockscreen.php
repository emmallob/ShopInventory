<?php
global $admin_user, $session;
if(!$session->userdata(":lifeSESS")) {
	header('Location: login');
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Lockscreen: <?php print config_item('site_name'); ?></title>
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="description" content="#">
<meta name="author" content="<?php print config_item('developer'); ?>">
<link rel="icon" href="<?php print $config->base_url(); ?>assets/images/favicon.ico" type="image/x-icon">
<link href="https://fonts.googleapis.com/css?family=Mada:300,400,500,600,700" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/bower_components/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/icon/themify-icons/themify-icons.css">
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/icon/icofont/css/icofont.css">
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/pages/flag-icon/flag-icon.min.css">
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/pages/menu-search/css/component.css">
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/css/style.css">
</head>
<body>
<div class="theme-loader">
<div class="ball-scale">
<div></div>
</div>
</div>


<?php if(isset($_GET["true"])) { $session->set_userdata(":lifeLockedOut", true); } ?>

<section class="login p-fixed d-flex text-center bg-info">

<div class="container">
<div class="row">
<div class="col-sm-12">

<div class="login-card card-block auth-body m-auto">
<form class="md-float-material" autocomplete="Off" id="user_login_form" method="post" style="box-shadow:0px 1px 2px #000;background:#000" action="<?php print $config->base_url(); ?>doAuth/doUnlock">
<div class="text-center">
<img class="img-fluid" src="<?php print $config->base_url(); ?>assets/images/logo.png" alt="Theme-Logo">
</div>
<div class="auth-box">
<div class="row">
<div class="col-md-12">
<h3 class="text-center"><i class="icofont icofont-lock text-primary f-80"></i></h3>
</div>
<span class="alert alert-success">Hello <strong><?php print $admin_user->return_fullname(); ?></strong>, you have been locked out; enter your password to continue</span>
</div>
<p class="text-inverse b-b-default text-right">Back to <a href="<?php print $config->base_url(); ?>login">Login.</a></p>
<div class="input-group">
<input type="password" autocomplete="Off" name="lock_password" class="form-control" placeholder="Please enter password">
<input type="hidden" id="unlock_screen" name="unlock_screen">
<span class="md-line"></span>
</div>
<div class="row">
<div class="col-md-12">
<input type="hidden" id="pageurl" value="<?php print SITE_URL; ?>">
<button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20"><i class="icofont icofont-lock"></i> Unlock Screen </button>
</div>
</div>
<div class="admin_login_div"></div>
<hr />
<div class="row">
<div class="col-md-10">
<p class="text-inverse text-left m-b-0">Thank you and enjoy the use of the system.</p>
<p class="text-inverse text-left"><b>@ VisamiNetSolutions.com</b></p>
</div>
<div class="col-md-2">
<img src="<?php print $config->base_url(); ?>assets/images/auth/Logo-small-bottom.png" alt="small-logo.png">
</div>
</div>
</div>
</form>

</div>

</div>

</div>

</div>

</section>



<div class="footer bg-inverse">
<p class="text-center">Copyright &copy; <?php print date("Y"); ?> <?php print config_item('site_name'); ?>, All rights reserved.</p>
</div>

<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/jquery/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/popper.js/js/popper.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/jquery-slimscroll/js/jquery.slimscroll.js"></script>

<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/modernizr/js/modernizr.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/modernizr/js/css-scrollbars.js"></script>

<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/classie/js/classie.js"></script>

<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/i18next/js/i18next.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/i18next-xhr-backend/js/i18nextXHRBackend.min.js"></script>
<script type="text/javascript" src="../bower_components/i18next-browser-languagedetector/js/i18nextBrowserLanguageDetector.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/jquery-i18next/js/jquery-i18next.min.js"></script>

<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/js/script.js"></script>
</body>
</html>