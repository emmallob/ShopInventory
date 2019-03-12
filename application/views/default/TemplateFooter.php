	<?php GLOBAL $config; ?><!--FOOTER SECTION-->
</div>
</div>
</div>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/jquery/js/jquery.min.js"></script>

<?php IF(!IN_ARRAY($SITEURL[0], ARRAY("stocks","products","sales","customers","suppliers","administrators","history","activity-logs","orders","tickets"))) { ?>

<script src="<?php print $config->base_url(); ?>assets/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/popper.js/js/popper.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/jquery-slimscroll/js/jquery.slimscroll.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/modernizr/js/modernizr.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/modernizr/js/css-scrollbars.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/moment/js/moment.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/pages/widget/calender/pignose.calendar.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/classie/js/classie.js"></script>
<script src="<?php print $config->base_url(); ?>assets/bower_components/c3/js/c3.js"></script>
<script src="<?php print $config->base_url(); ?>assets/pages/chart/knob/jquery.knob.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/pages/widget/jquery.sparkline.js"></script>
<script src="<?php print $config->base_url(); ?>assets/bower_components/d3/js/d3.js"></script>
<script src="<?php print $config->base_url(); ?>assets/bower_components/rickshaw/js/rickshaw.js"></script>
<script src="<?php print $config->base_url(); ?>assets/bower_components/raphael/js/raphael.min.js"></script>
<script src="<?php print $config->base_url(); ?>assets/bower_components/morris.js/js/morris.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/pages/todo/todo.js"></script>
<script src="<?php print $config->base_url(); ?>assets/pages/chart/float/jquery.flot.js"></script>
<script src="<?php print $config->base_url(); ?>assets/pages/chart/float/jquery.flot.categories.js"></script>
<script src="<?php print $config->base_url(); ?>assets/pages/chart/float/jquery.flot.pie.js"></script>
<script src="assets/pages/chart/echarts/js/echarts-all.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/pages/dashboard/horizontal-timeline/js/main.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/pages/dashboard/amchart/js/amcharts.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/pages/dashboard/amchart/js/serial.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/pages/dashboard/amchart/js/light.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/pages/dashboard/amchart/js/custom-amchart.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/i18next/js/i18next.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/i18next-xhr-backend/js/i18nextXHRBackend.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/i18next-browser-languagedetector/js/i18nextBrowserLanguageDetector.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/jquery-i18next/js/jquery-i18next.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/pages/dashboard/custom-dashboard.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/js/script.js"></script>
<script src="<?php print $config->base_url(); ?>assets/js/pcoded.min.js"></script>
<script src="<?php print $config->base_url(); ?>assets/js/demo-12.js"></script>
<script src="<?php print $config->base_url(); ?>assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="<?php print $config->base_url(); ?>assets/js/jquery.mousewheel.min.js"></script>
<?php } ?>

<?php IF(IN_ARRAY($SITEURL[0], ARRAY("products-view","customers-view","stocks-view","stocks-new","stocks-add"))) { ?>
<script src="<?php print $config->base_url(); ?>assets/pages/form-masking/inputmask.js"></script>
<script src="<?php print $config->base_url(); ?>assets/pages/form-masking/jquery.inputmask.js"></script>
<script src="<?php print $config->base_url(); ?>assets/pages/form-masking/autoNumeric.js"></script>
<script src="<?php print $config->base_url(); ?>assets/pages/form-masking/form-mask.js"></script>
<?php } ?>
<?php IF(IN_ARRAY($SITEURL[0], ARRAY("stocks-new"))) { ?>
<link rel="stylesheet" href="<?php print $config->base_url(); ?>assets/bower_components/select2/css/select2.min.css" />
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/bower_components/bootstrap-multiselect/css/bootstrap-multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php print $config->base_url(); ?>assets/bower_components/multiselect/css/multi-select.css" />
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/select2/js/select2.full.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/pages/advance-elements/select2-custom.js"></script>
<?php } ?>
<?php IF(IN_ARRAY($SITEURL[0], ARRAY("stocks","products","sales","customers","suppliers","administrators","history","activity-logs","orders","tickets"))) { ?>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/popper.js/js/popper.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/jquery-slimscroll/js/jquery.slimscroll.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/modernizr/js/modernizr.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/modernizr/js/css-scrollbars.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/classie/js/classie.js"></script>
<script src="<?php print $config->base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php print $config->base_url(); ?>assets/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php print $config->base_url(); ?>assets/pages/data-table/js/jszip.min.js"></script>
<script src="<?php print $config->base_url(); ?>assets/pages/data-table/js/pdfmake.min.js"></script>
<script src="<?php print $config->base_url(); ?>assets/pages/data-table/js/vfs_fonts.js"></script>
<script src="<?php print $config->base_url(); ?>assets/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?php print $config->base_url(); ?>assets/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?php print $config->base_url(); ?>assets/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php print $config->base_url(); ?>assets/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php print $config->base_url(); ?>assets/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/i18next/js/i18next.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/i18next-xhr-backend/js/i18nextXHRBackend.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/i18next-browser-languagedetector/js/i18nextBrowserLanguageDetector.min.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/bower_components/jquery-i18next/js/jquery-i18next.min.js"></script>
<script src="<?php print $config->base_url(); ?>assets/pages/data-table/js/data-table-custom.js"></script>
<script type="text/javascript" src="<?php print $config->base_url(); ?>assets/js/script.js"></script>
<script src="<?php print $config->base_url(); ?>assets/js/pcoded.min.js"></script>
<script src="<?php print $config->base_url(); ?>assets/js/demo-12.js"></script>
<script src="<?php print $config->base_url(); ?>assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="<?php print $config->base_url(); ?>assets/js/jquery.mousewheel.min.js"></script>
<?php } ?>
<script>
cart_listing();
<?php IF(IN_ARRAY($SITEURL[0], ARRAY("index", "dashboard", "products"))) { ?>
function add_to_cart(item_id) {
	var qty = $("#quantity"+item_id).val();
	$.ajax({
		type: "POST",
		data: "add_item&item="+item_id+"&quantity="+qty,
		url: "<?php print $config->base_url(); ?>doAddToCart",
		cache: false,
		success:function(response) {
			cart_listing();
			$('#myModal'+item_id).modal("hide");
			$(".successModalResult").html(response);
			$('#successModal').modal({show:true});
		}
	});
}
<?php } ?>
<?php IF(IN_ARRAY($SITEURL[0], ARRAY("dashboard", "cart", "checkout"))) { ?>
function adjust_item_quantity(item_id, reload=false) {
	var nquant = $("#quantity"+item_id).val();
	if(nquant < 1) {
		alert("Sorry! You have entered an invalid numeric figure");
		$("#quantity"+item_id).focus();
	} else {
		$.ajax({
			type: "POST",
			data: "adjust_item&item_to_adjust="+item_id+"&quantity="+nquant,
			url: "<?php print $config->base_url(); ?>doAddToCart",
			cache: false,
			success:function(response) {
				if(reload == false) {
					cart_listing();
					tabulate_sub_total(item_id);
					tabultate_total_price();
					$('#myModal'+item_id).modal("hide");
					$(".successModalResult").html(response);
					$('#successModal').modal({show:true});
				} else {
					window.location.href='<?php print $config->base_url(); ?>checkout';
				}
			}
		});
	}
}
function tabulate_sub_total(item_id) {
	$.ajax({
		type: "POST",
		data: "tabulate_sub_total&item_id="+item_id,
		url: "<?php print $config->base_url(); ?>doAddToCart",
		success:function(response) {
			$(".sub_"+item_id).html(response);
		}
	});
}
function tabultate_total_price() {
	$.ajax({
		type: "POST",
		data: "tabultate_total_price&total_price=",
		url: "<?php print $config->base_url(); ?>doAddToCart",
		success:function(response) {
			$(".total_all_here").html(response);
		}
	});
}
<?php } ?>
<?php IF(IN_ARRAY($SITEURL[0], ARRAY("cart"))) { ?>
function empty_cart() {
	if(confirm("Are you sure you want to empty your cart?")) {
		$.ajax({
			type: "POST",
			data: "empty_cart",
			url: "<?php print $config->base_url(); ?>doAddToCart",
			cache: false,
			success:function(response) {
				window.location.href='';
			}
		});
	}
}
<?php } ?>
function cart_listing() {
	var pageurl = $("#pageurl").val();
	$.ajax({
		type: "POST",
		data: "list_cart_info&",
		url: "<?php print $config->base_url(); ?>doAddToCart",
		success:function(response) {
			cart_counter();
			$(".list_cart_info").html(response);
		}
	});
}
function remove_item_(index_id, item_id, redirect) {
	var pageurl = $("#pageurl").val();
	if(confirm("Are you sure you want to delete this item from your cart?")) {
		$.ajax({
			type: "POST",
			data: "remove_item&index_to_remove="+index_id+"&_item_id="+item_id,
			url: "<?php print $config->base_url(); ?>doAddToCart",
			success:function(response) {
				cart_listing();
			}
		});
	}
}
function cart_counter() {
	var pageurl = $("#pageurl").val();
	$.ajax({
		type: "POST",
		data: "run_counter&",
		url: "<?php print $config->base_url(); ?>doAddToCart",
		success:function(response) {
			$(".cart_counter").html(response);
		}
	});
}
<?php if($SITEURL[0] == "dashboard") { ?>
function remove_system_notices(type, item_id, alert_div) {
	if(confirm("Are you sure you want to remove this notification?")) {
		$.ajax({
			type: "POST",
			data: "remove_notice&type="+type+"&item_id="+item_id,
			url: "<?php print $config->base_url(); ?>doNotifications",
			success:function(response) {
				$("#"+alert_div).slideUp();
			}
		});
	}
}
<?php } ?>
<?php if($admin_user->confirm_admin_user()) { ?>
function system_backup() {
	if(confirm("Are you sure you want to backup the system?")) {
		$.ajax({
			type: "POST",
			data: "backup_system&process_form",
			url: "<?php print $config->base_url(); ?>doBackup",
			success:function(response) {
				alert(response);
			}
		});
	}
}
<?php } ?>
<?php if(($SITEURL[0] == "dashboard") and ($admin_user->confirm_super_user())) { ?>
function change_store_id() {
	var store_id = $("#change_store_id").val();
	if(store_id != "000012") {
		if(confirm("Are you sure you want to run the system as a "+store_id+" owner?")) {
			$.ajax({
				type: "POST",
				data: "change_id&process_form&store_id="+store_id,
				url: "<?php print $config->base_url(); ?>doStoreId",
				success:function(response) {
					alert(response);
					window.location.href='<?php print $config->base_url(); ?>dashboard';
				}
			});
		}
	}
}
<?php } ?>
$('#simpletable').dataTable({
"pageLength": [<?php (isset($_GET["limit"])) ? print $functions->clean_words($_GET["limit"]) : print DISPLAY_LIMIT; ?>],
"aaSorting": [0,'desc']
});
</script>
</body>
</html>