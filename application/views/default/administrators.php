<?php
	if(!$admin_user->logged_InControlled()) { die(require("login.php")); }
	if($admin_user->lock_user_screen()) { die(require("lockscreen.php")); }
	if(!$admin_user->changed_password_first()) { die(require("change_password.php")); }
	
	$PAGETITLE="Administrators"; 
	require "TemplateHeader.php";
	
?>
<div class="pcoded-content">
<div class="pcoded-inner-content">
<div class="main-body">
<div class="page-wrapper">
<div class="page-header">
<div class="page-header-title">
<h3><?php print strtoupper($PAGETITLE); ?></h3>
</div>
<div class="page-header-breadcrumb">
<ul class="breadcrumb-title">
<li class="breadcrumb-item">
<a href="<?php print SITE_URL; ?>">
<i class="icofont icofont-home"></i>
</a>
</li>
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/dashboard">Dashboard</a></li>
<li class="breadcrumb-item"><a href="<?php print SITE_URL; ?>/products"><?php print $PAGETITLE; ?></a></li>
</ul>
</div>
</div>

<?php $notices->notice_board(); ?>

<div class="page-body">
<div class="row">
<div class="col-sm-12">


<div class="card">
<div class="card-header">
<h5><?php print $PAGETITLE; ?></h5>
<span>This page displays the list of adminis that are authorised to manage the system.</span>
</div>
<div class="card-block">

<?php if($admin_user->confirm_admin_user() == true) { ?>
<div class='modify_result'></div>
<div class="dt-responsive table-responsive">
<table id="simpletable" class="table table-striped table-bordered nowrap">
<thead>
<tr>
<th>ID</th>
<th>FULLNAME</th>
<th>USERNAME</th>
<th>EMAIL</th>
<th>LEVEL</th>
<th>LAST LOGIN</th>
<th>MANAGE</th>
</tr>
</thead>
<tbody>
<?php
#initialization
$where_clause = "1 and status='1'";
#check if the form has been submitted
if(($admin_user->confirm_super_user() == true) and (STORE_ID==0)) {
	#list the store administrators
	$list_admins = $DB->query("select * from _admin where role != '1001' and store_id !='0' and status='1' and admin_deleted='0'");
} elseif(($admin_user->confirm_super_user() == true) and (STORE_ID!=0)) {
	#list the store administrators
	$list_admins = $DB->query("select * from _admin where status='1' and store_id='".STORE_ID."' and admin_deleted='0'");
} else {
	#list the store administrators
	$list_admins = $DB->query("select * from _admin where role != '1001' and store_id='".STORE_ID."' and status='1' and admin_deleted='0'");
}
#list them here 
foreach($list_admins as $results):
?>
<tr id="admin_<?php print $results["id"]; ?>" <?php if($results["activated"] == 0) print "class='alert alert-danger'"; ?>>
<td><?php print $results["id"]; ?></td>
<td><?php print $results["fullname"]; ?></td>
<td><?php print$results["username"]; ?></td>
<td><?php print $results["email"]; ?></td>
<td><?php print $results["level"]; ?></td>
<td><?php print $results["lastaccess"]; ?></td>
<td>
<a href='<?php print SITE_URL; ?>/profile/<?php print $results["username"]; ?>' type="button" class="btn btn-success "><i class="fa ion-eye"></i></a>
<?php if($results["activated"] == 0) { ?>
<a href='javascript:modify_account("<?php print $results["id"]; ?>","Activate");' title="Activate this Administrator Account?" target="_blank" type="button" class="btn btn-info"><i class="fa fa-stop"></i></a>
<?php  } else { ?>
<a href='javascript:modify_account("<?php print $results["id"]; ?>","Disable");' title="Disable this Administrator Account?" target="_blank" type="button" class="btn btn-warning"><i class="fa fa-stop"></i></a>
<?php } ?>
<a href='javascript:modify_account("<?php print $results["id"]; ?>","Delete");' title="Click to delete this Administrator" target="_blank" type="button" class="btn btn-danger"><i class="fa fa-trash"></i></a>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<script>
function modify_account(id, content) {
	if(confirm("Are you sure you want to "+content+" this Admin Account?")) {
		$.ajax({
			type: "post",
			url: "<?php print SITE_URL; ?>/doAuth",
			data: "modify_account&type="+content+"&id="+id,
			success: function(response) {
				$(".modify_result").html(response);
			}
		});
	}
}
</script>
<?php } ?>


</div>
</div>

<link rel="stylesheet" type="text/css" href="<?php print SITE_URL; ?>/assets/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="<?php print SITE_URL; ?>/assets/pages/data-table/css/buttons.dataTables.min.css">

</div>
</div>
</div>
</div>
<?php require "TemplateFooter.php"; ?>