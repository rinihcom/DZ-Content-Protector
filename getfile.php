<?php
define('DOING_AJAX', true);
define('WP_ADMIN', true);
require_once('../../../wp-load.php');
 
require_once('../../../wp-admin/includes/admin.php');

global $wpdb;

$filename = site_url().'/wp-content/uploads/'.$_GET['filename'];

$id = div_billing_get_attachment_id_from_url( $filename );
$type = get_post_mime_type($id);
$file = $_GET['filename'];

$status = get_post_meta( $id, 'div_billing_download', true );

ob_start();
if(isset($_GET['user']) && isset($_GET['pass'])){
	$creds['user_login'] = $_REQUEST['user'];
	$creds['user_password'] = $_REQUEST['pass'];
	$creds['remember'] = true;
	$user = wp_signon( $creds, false );
}

//echo $filename;
//echo $status;
if(!empty($status)){
	if ( ! is_user_logged_in() ) {
		die('You are not logged in');
	}
	else{
		$user_id = get_current_user_id();

		if(substr($status, -1) == ',') $status = substr($status, 0,-1);
		
		$status_payment = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."div_billing_membership_list WHERE user_id='".$user_id."' AND mm_id IN ($status) AND member_status=1");
		if(count($status_payment) < 1)
			if (current_user_can( 'manage_options' ))
				div_download_now($file,$type);
			else
				die("Sorry you don't have priviledge to access this page");
		else
			div_download_now($file,$type);
	}
}
else{
	div_download_now($file,$type);
}

function div_download_now($file,$type){
	header("Content-Type: ".$type);
	//echo '../../wp-content/uploads/'.$file;
	readfile('../../../wp-content/uploads/'.$file);
}
?>