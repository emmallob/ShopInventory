<?php
#fetch the css files
global $SITEURL, $config, $session, $admin_user;
global $PAGETITLE, $DB;

load_core('security');
$notices = load_class('notifications', 'models');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php print $PAGETITLE; ?> :: <?php print STORE_NAME; ?> <?php print " >> ".config_item('site_name'); ?></title>
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta name="description" content="#">
<meta name="author" content="<?php print config_item('developer'); ?>">
<link rel="icon" href="<?php print $config->base_url(); ?>assets/images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/bower_components/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/icon/themify-icons/themify-icons.css">
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/icon/icofont/css/icofont.css">
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/pages/flag-icon/flag-icon.min.css">
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/pages/menu-search/css/component.css">
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/pages/dashboard/horizontal-timeline/css/style.css">
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/pages/widget/calender/pignose.calendar.min.css">
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/fonts/font-awesome/css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/css/style.css">
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/css/linearicons.css">
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/css/simple-line-icons.css">
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/css/ionicons.css">
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/css/jquery.mCustomScrollbar.css">
<?php if(in_array($SITEURL[0], array("profile","administrators-new"))) { ?>
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/pages/j-pro/css/j-pro-modern.css">
<?php } ?>
<style>.pcoded-content {background-size:cover; background:url(<?php print $config->base_url(); ?>assets/images/bg.jpg) no-repeat center center fixed;height:100%} .pcoded-content .pcoded-inner-content .page-header-title h3 {color:#fff;text-transform:uppercase} .pcoded-content .pcoded-inner-content .breadcrumb-item a {color:#fff;} .pcoded-content .pcoded-inner-content .breadcrumb-item a:hover {text-decoration:underline;}</style>
</head>
<body>
<div id="successModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">MODAL RESPONSE</h4>
      </div>
      <div class="modal-body">
       <p class="successModalResult"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="pcoded" class="pcoded">
<div class="pcoded-overlay-box"></div>
<div class="pcoded-container navbar-wrapper">
<nav class="navbar header-navbar pcoded-header">
<div class="navbar-wrapper">
<div class="navbar-logo" data-navbar-theme="theme4">
<a class="mobile-menu" id="mobile-collapse" href="#!">
<i class="ti-menu"></i>
</a>
<a class="mobile-search morphsearch-search" href="#">
<i class="ti-search"></i>
</a>
<a href="<?php print $config->base_url(); ?>dashboard">
<img class="img-fluid" src="<?php print $config->base_url(); ?>assets/images/logo.png" alt="" />
</a>
<a class="mobile-options">
<i class="ti-more"></i>
</a>
</div>
<div class="navbar-container container-fluid">
<div>

<ul class="nav-right">
<li class="header-notification lng-dropdown">
<a href="#" id="dropdown-active-item">
<i class="flag-icon flag-icon-gb m-r-5"></i> English
</a>
</li>

<li class="header-notification">
<a href="#" id="dropdown-active-item">
<i class="ion-ios-cart"></i> <span class="cart_counter"> <?php ($session->userdata('CaaAPcart_array')) ? PRINT COUNT($session->userdata('CaaAPcart_array')) : PRINT "0 item(s) - GH&#8373;0.00"; ?></span>
</a>
<ul class="show-notification">
<li>
<h6>Shopping Cart</h6>
</li>

<div class="list_cart_info"></div>

</ul>
</li>
<li class="user-profile header-notification">
<a href="#!">
<img src="<?php print $config->base_url(); ?>assets/images/user.png" alt="User-Profile-Image">
<span><?php print $admin_user->return_fullname(); ?></span>
<i class="ti-angle-down"></i>
</a>
<ul class="show-notification profile-notification">
<li>
<a href="<?php print $config->base_url(); ?>profile">
<i class="ti-user"></i> Profile
</a>
</li>
<li>
<a href="<?php print $config->base_url(); ?>messages">
<i class="ti-email"></i> My Messages
</a>
</li>
<li>
<a href="<?php print $config->base_url(); ?>activity-logs">
<i class="ti-list"></i> Activity Logs
</a>
</li>
<li>
<a href="<?php print $config->base_url(); ?>tickets">
<i class="fa fa-question-circle"></i> Support Tickets
</a>
</li>
<?php if($admin_user->confirm_admin_user() == true) { ?>
<li>
<a href="<?php print $config->base_url(); ?>settings">
<i class="ti-settings"></i> Settings
</a>
</li>
<?php } ?>
<li>
<a href="<?php print $config->base_url(); ?>lockscreen?true">
<i class="ti-lock"></i> Lock Screen
</a>
</li>
<li>
<a href="<?php print $config->base_url(); ?>login/logout">
<i class="ti-layout-sidebar-left"></i> Logout
</a>
</li>
</ul>
</li>
</ul>

</div>
</div>
</div>
</nav>

<div class="pcoded-main-container">
<div class="pcoded-wrapper">

<nav class="pcoded-navbar">
<div class="sidebar_toggle"><a href="<?php print $config->base_url(); ?>"><i class="icon-close icons"></i></a></div>
<div class="pcoded-inner-navbar main-menu">


<div class="pcoded-navigatio-lavel" data-i18n="nav.category.navigation">NAVIGATION</div>
<ul class="pcoded-item pcoded-left-item">
<li class="<?php if(in_array($SITEURL[0], array("dashboard","error","index"))) print "active" ?> pcoded-trigger">
<a href="<?php print $config->base_url(); ?>dashboard">
<span class="pcoded-micon"><i class="ti-home"></i></span>
<span class="pcoded-mtext" data-i18n="nav.dash.main">Dashboard</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
<li class="pcoded-hasmenu <?php if(in_array($SITEURL[0], array("products-view","stocks","stocks-new","stocks-view","stocks-add"))) print "active pcoded-trigger" ?>">
<a href="javascript:void(0)">
<span class="pcoded-micon"><i class="ti-layout"></i></span>
<span class="pcoded-mtext" data-i18n="nav.page_layout.main">Products</span>
<span class="pcoded-mcaret"></span>
</a>
<ul class="pcoded-submenu">
<li class="<?php if(in_array($SITEURL[0], array("stocks","products-details","stocks-view"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>stocks" data-i18n="nav.widget.main">
<span class="pcoded-micon"><i class="ion-gear-a"></i></span>
<span class="pcoded-mtext">Products Stock</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
<?php if($admin_user->confirm_admin_user() == true) { ?>
<li class="<?php if(in_array($SITEURL[0], array("stocks-add"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>stocks-add" data-i18n="nav.widget.main">
<span class="pcoded-micon"><i class="icon-plus"></i></span>
<span class="pcoded-mtext">Add New Stock</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
<li class="<?php if(in_array($SITEURL[0], array("stocks-new"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>stocks-new" data-i18n="nav.widget.main">
<span class="pcoded-micon"><i class="icon-plus"></i></span>
<span class="pcoded-mtext">Update Stock</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
<?php } ?>
</ul>
</li>
<li class="pcoded-hasmenu <?php if(in_array($SITEURL[0], array("customers","customers-new","customers-history","customers-view"))) print "active pcoded-trigger" ?>">
<a href="javascript:void(0)">
<span class="pcoded-micon"><i class="icon-user"></i></span>
<span class="pcoded-mtext" data-i18n="nav.page_layout.main">Customers</span>
<span class="pcoded-mcaret"></span>
</a>
<ul class="pcoded-submenu">
<li class="<?php if(in_array($SITEURL[0], array("customers"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>customers" data-i18n="nav.page_layout.box-layout">
<span class="pcoded-micon"><i class="icon-pie-chart"></i></span>
<span class="pcoded-mtext">List Customers</span>
<span class="pcoded-badge label label-danger"><?php print $DB->num_rows($DB->custom_where("_customers", " and status='1' and store_id='".STORE_ID."'")); ?></span>
<span class="pcoded-mcaret"></span>
</a>
</li>
<li class="<?php if(in_array($SITEURL[0], array("customers-new"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>customers-new" data-i18n="nav.page_layout.box-layout">
<span class="pcoded-micon"><i class="icon-plus"></i></span>
<span class="pcoded-mtext">Add Customer</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
</ul>
</li>


<li class="pcoded-hasmenu <?php if(in_array($SITEURL[0], array("suppliers","suppliers-new","suppliers-view"))) print "active pcoded-trigger" ?>">
<a href="javascript:void(0)">
<span class="pcoded-micon"><i class="ti-layout-grid2-alt"></i></span>
<span class="pcoded-mtext" data-i18n="nav.page_layout.main">Suppliers</span>
<span class="pcoded-mcaret"></span>
</a>
<ul class="pcoded-submenu">
<li class="<?php if(in_array($SITEURL[0], array("suppliers"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>suppliers" data-i18n="nav.page_layout.box-layout">
<span class="pcoded-micon"><i class="icon-pie-chart"></i></span>
<span class="pcoded-mtext">List Suppliers</span>
<span class="pcoded-badge label label-danger"><?php print $DB->num_rows($DB->custom_where("_suppliers","and status='1' and store_id='".STORE_ID."'")); ?></span>
<span class="pcoded-mcaret"></span>
</a>
</li>

<li class="<?php if(in_array($SITEURL[0], array("suppliers-new"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>suppliers-new" data-i18n="nav.page_layout.box-layout">
<span class="pcoded-micon"><i class="icon-plus"></i></span>
<span class="pcoded-mtext">Add Supplier</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
</ul>
</li>
</ul>

<div class="pcoded-navigatio-lavel" data-i18n="nav.category.ui-element">SALES MANAGER</div>
<ul class="pcoded-item pcoded-left-item">
<li class="pcoded-hasmenu <?php if(in_array($SITEURL[0], array("products","return"))) print "active pcoded-trigger" ?>">
<a href="javascript:void(0)">
<span class="pcoded-micon"><i class="icon-pie-chart"></i></span>
<span class="pcoded-mtext" data-i18n="nav.page_layout.main">Sell / Return Product</span>
<span class="pcoded-mcaret"></span>
</a>
<ul class="pcoded-submenu">
<li class="<?php if(in_array($SITEURL[0], array("products"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>products" data-i18n="nav.page_layout.box-layout">
<span class="pcoded-micon"><i class="icon-pie-chart"></i></span>
<span class="pcoded-mtext">Sell Product</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
<li class="<?php if(in_array($SITEURL[0], array("return"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>return" data-i18n="nav.page_layout.box-layout">
<span class="pcoded-micon"><i class="fa ion-reply"></i></span>
<span class="pcoded-mtext">Return Sold Product</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
</ul>
</li>

<?php /* confirm store wants to operate a sale on credit system */ if(CREDIT_SALES == 1) { ?>
<li class="<?php if(in_array($SITEURL[0], array("receive"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>receive" data-i18n="nav.page_layout.box-layout">
<span class="pcoded-micon"><i class="fa fa-money"></i></span>
<span class="pcoded-mtext">Receive Payment</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
<?php } ?>
<?php if(isset($_SESSION["CaaAPcart_array"]) and count($_SESSION["CaaAPcart_array"]) > 0) { ?>
<li class="<?php if(in_array($SITEURL[0], array("cart", "checkout"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>cart" data-i18n="nav.widget.main">
<span class="pcoded-micon"><i class="ti-view-list"></i></span>
<span class="pcoded-mtext">Store Cart </span>
<span class="pcoded-badge label label-success">(Sell Product)</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
<?php } ?>
</ul>


<div class="pcoded-navigatio-lavel" data-i18n="nav.category.ui-element">RECORDS MANAGER</div>
<ul class="pcoded-item pcoded-left-item">
<li class="pcoded-hasmenu <?php if(in_array($SITEURL[0], array("sales","sales-view","orders","orders-view","stocks-history","stocks-details"))) print "active pcoded-trigger" ?>">
<a href="javascript:void(0)">
<span class="pcoded-micon"><i class="fa fa-tripadvisor"></i></span>
<span class="pcoded-mtext" data-i18n="nav.page_layout.main">Sale <?php /* confirm store wants to operate a sale on credit system */ if(CREDIT_SALES == 1) { ?>/Credit Orders<?php } else { print "Orders"; } ?> </span>
<span class="pcoded-mcaret"></span>
</a>
<ul class="pcoded-submenu">
<li class="<?php if(in_array($SITEURL[0], array("sales","sales-view"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>sales" data-i18n="nav.widget.main">
<span class="pcoded-micon"><i class="ti-shopping-cart"></i></span>
<span class="pcoded-mtext">View Sales History</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
<?php /* confirm store wants to operate a sale on credit system */ if(CREDIT_SALES == 1) { ?>
<li class="<?php if(in_array($SITEURL[0], array("orders","orders-view"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>orders" data-i18n="nav.widget.main">
<span class="pcoded-micon"><i class="ti-shopping-cart"></i></span>
<span class="pcoded-mtext">Credit Orders History</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
<?php } ?>
<?php if($admin_user->confirm_admin_user() == true) { ?>
<li class="<?php if(in_array($SITEURL[0], array("stocks-history","stocks-details"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>stocks-history" data-i18n="nav.widget.main">
<span class="pcoded-micon"><i class="fa fa-tripadvisor"></i></span>
<span class="pcoded-mtext">Stock Entry History</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
<?php } ?>
</ul>
</li>


<li class="<?php if(in_array($SITEURL[0], array("records-manager"))) print "active" ?>">
<a target="_blank" href="<?php print $config->base_url(); ?>records-manager" data-i18n="nav.widget.main">
<span class="pcoded-micon"><i class="ion-printer"></i></span>
<span class="pcoded-mtext">Print Records</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
</ul>


<div class="pcoded-navigatio-lavel" data-i18n="nav.category.ui-element">PROFILE MANAGER</div>

<?php if($admin_user->confirm_admin_user() == true) { ?>
<ul class="pcoded-item pcoded-left-item">

<li class="pcoded-hasmenu <?php if(in_array($SITEURL[0], array("administrators","locked-accounts","administrators-new","review-requests"))) print "active pcoded-trigger" ?>">
<a href="javascript:void(0)">
<span class="pcoded-micon"><i class="ti-layout-grid2-alt"></i></span>
<span class="pcoded-mtext" data-i18n="nav.page_layout.main">Admin Manager</span>
<span class="pcoded-mcaret"></span>
</a>
<ul class="pcoded-submenu">
<li class="<?php if(in_array($SITEURL[0], array("administrators"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>administrators" data-i18n="nav.widget.main">
<span class="pcoded-micon"><i class="icon-user"></i></span>
<span class="pcoded-mtext">Administrators</span>
<span class="pcoded-badge label label-danger"><?php print $DB->num_rows($DB->custom_where("_admin","and status='1' and role !='1001' and store_id='".STORE_ID."'")); ?></span>
<span class="pcoded-mcaret"></span>
</a>
</li>
<li class="<?php if(in_array($SITEURL[0], array("administrators-new"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>administrators-new" data-i18n="nav.widget.main">
<span class="pcoded-micon"><i class="icon-plus"></i></span>
<span class="pcoded-mtext">Add Admin</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
<?php if($notices->password_change_requests($admin_user->return_username())->change_request1 == true) { ?>
<li class="<?php if(in_array($SITEURL[0], array("review-requests"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>review-requests" data-i18n="nav.widget.main">
<span class="pcoded-micon"><i class="fa fa-street-view"></i></span>
<span class="pcoded-mtext">Change Requests</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
<?php } ?>
<?php if($notices->locked_account()->locked_acs == true) { ?>
<li class="<?php if(in_array($SITEURL[0], array("locked-accounts"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>locked-accounts" data-i18n="nav.widget.main">
<span class="pcoded-micon"><i class="fa fa-street-view"></i></span>
<span class="pcoded-mtext">Locked Accounts</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
<?php } ?>
</ul>
</li>
</ul>
<?php } ?>
<ul class="pcoded-item pcoded-left-item">
<li class="pcoded-hasmenu <?php if(in_array($SITEURL[0], array("profile","messages","history","activity-logs"))) print "active pcoded-trigger" ?>">
<a href="javascript:void(0)">
<span class="pcoded-micon"><i class="fa fa-user"></i></span>
<span class="pcoded-mtext" data-i18n="nav.page_layout.main">User Profile</span>
<span class="pcoded-mcaret"></span>
</a>
<ul class="pcoded-submenu">
<li class="<?php if(in_array($SITEURL[0], array("profile"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>profile" data-i18n="nav.widget.main">
<span class="pcoded-micon"><i class="icon-user"></i></span>
<span class="pcoded-mtext">Profile</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
<li class="<?php if(in_array($SITEURL[0], array("history"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>history" data-i18n="nav.widget.main">
<span class="pcoded-micon"><i class="icon-list"></i></span>
<span class="pcoded-mtext">Login History</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
<!--<li class="<?php if(in_array($SITEURL[0], array("messages"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>messages" data-i18n="nav.widget.main">
<span class="pcoded-micon"><i class="icon-envelope-open"></i></span>
<span class="pcoded-mtext">Messages</span>
<span class="pcoded-mcaret"></span>
</a>
</li>-->
<li class="<?php if(in_array($SITEURL[0], array("activity-logs"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>activity-logs?true" data-i18n="nav.widget.main">
<span class="pcoded-micon"><i class="icon-logout"></i></span>
<span class="pcoded-mtext">Activity History</span>
<span class="pcoded-mcaret"></span>
</a>
</li>

</ul>
<?php if($admin_user->confirm_admin_user() == true) { ?>
<li class="<?php if(in_array($SITEURL[0], array("settings"))) print "active" ?>">
<a href="<?php print $config->base_url(); ?>settings" data-i18n="nav.widget.main">
<span class="pcoded-micon"><i class="icon-settings"></i></span>
<span class="pcoded-mtext">Settings</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
<?php /* check the automatic backup state*/ if(AUTOMATIC_BACKUP == 0) { ?>
<li class="<?php if(in_array($SITEURL[0], array("backup"))) print "active" ?>">
<a href="#" onclick="return system_backup();" data-i18n="nav.widget.main">
<span class="pcoded-micon"><i class="fa fa-database"></i></span>
<span class="pcoded-mtext">Backup System</span>
<span class="pcoded-mcaret"></span>
</a>
</li>
<?php } ?>
<?php } ?>
</li>
</ul>

</div>
</nav>