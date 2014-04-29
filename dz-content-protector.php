<?php
/*
Plugin Name: DZ Content Protector
Plugin URI: http://dizduz.com
Description: DZ Content Protector is wordpress plugin that protect your files from everyone
Version: 1.0
Author: Fahri Arrasyid
Author URI: http://dizduz.com
*/

//Initialize when plugin actived  
register_activation_hook(__FILE__,'div_billing_install');
function div_billing_install(){
	require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
	global $wpdb;
	$table = $wpdb->prefix . 'div_billing_settings';
	if($wpdb->get_var( "SHOW TABLES LIKE '$table'" ) != $table){
		$sql = 'CREATE TABLE IF NOT EXISTS '.$table.' (
			  `setting_id` bigint(20) NOT NULL AUTO_INCREMENT,
			  `setting_key` varchar(255) NOT NULL,
			  `setting_value` text NOT NULL,
			  PRIMARY KEY (`setting_id`)
		)';
		dbDelta($sql);
	}
	
	$table = $wpdb->prefix . 'div_billing_trans';
	if($wpdb->get_var( "SHOW TABLES LIKE '$table'" ) != $table){
		$sql = 'CREATE TABLE IF NOT EXISTS '.$table.' (
			  `trans_id` bigint(20) NOT NULL AUTO_INCREMENT,
			  `billing_id` bigint(20) NOT NULL,
			  `user_id` bigint(20) NOT NULL,
			  `amount` float(10,2) NOT NULL,
			  `create_date` TIMESTAMP DEFAULT NOW(),
			  `status` varchar(20),
			  `meta_trans` text,
			  PRIMARY KEY (`trans_id`) )';
		dbDelta($sql);
	}
	
	$table = $wpdb->prefix . 'div_billing_membership';
	if($wpdb->get_var( "SHOW TABLES LIKE '$table'" ) != $table){
		$sql = 'CREATE TABLE IF NOT EXISTS '.$table.' (
			  `mm_id` bigint(20) NOT NULL AUTO_INCREMENT,
			  `mm_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `mm_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
			  `mm_onetime` float(10,2) NOT NULL,
			  `mm_duration` int(20) NOT NULL,
			  `mm_duration_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
			  `mm_amount` float(10,2) DEFAULT NULL,
			  `mm_allow_subuser` int(1) NOT NULL,
			  `mm_status_subuser` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
			  `mm_number_subuser` text COLLATE utf8_unicode_ci NOT NULL,
			  PRIMARY KEY (`mm_id`)
		)';
		dbDelta($sql);
	}

	$table = $wpdb->prefix . 'div_billing_subuser_list';
	if($wpdb->get_var( "SHOW TABLES LIKE '$table'" ) != $table){
		$sql = 'CREATE TABLE IF NOT EXISTS '.$table.' (	
				`subuser_list_id` bigint(10) NOT NULL AUTO_INCREMENT,
				`subuser_id` bigint(10) NOT NULL,
			  	`user_id` bigint(10) NOT NULL,
			  	`join_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`subuser_list_id`),
				UNIQUE KEY `unik` (`user_id`) )';
		dbDelta($sql);
	}

	$table = $wpdb->prefix . 'div_billing_discount';
	if($wpdb->get_var( "SHOW TABLES LIKE '$table'" ) != $table){
		$sql = 'CREATE TABLE IF NOT EXISTS '.$table.' (
			  `discount_id` bigint(20) NOT NULL AUTO_INCREMENT,
			  `discount_name` varchar(255) NOT NULL,
			  `type_id` bigint(20) NOT NULL,
			  `start_date` DATE DEFAULT NULL,
			  `end_date` DATE DEFAULT NULL,
			  `discount_amount` varchar(20),
			  PRIMARY KEY (`discount_id`)
		)';
		dbDelta($sql);
	}

	$table = $wpdb->prefix . 'div_billing_subuser_master';
	if($wpdb->get_var( "SHOW TABLES LIKE '$table'" ) != $table){
		$sql = 'CREATE TABLE IF NOT EXISTS '.$table.' (
			  `mm_subuser_id` bigint(10) NOT NULL AUTO_INCREMENT,
			  `mm_id` bigint(10) NOT NULL,
			  `onetime_fee` float(10,2) NOT NULL,
			  `from_user` int(10) NOT NULL,
			  `to_user` int(10) NOT NULL,
			  `recurring_fee` float(10,2) NOT NULL,
			  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`mm_subuser_id`)
			)';
		dbDelta($sql);
	}

	$table = $wpdb->prefix . 'div_billing_coupon';
	if($wpdb->get_var( "SHOW TABLES LIKE '$table'" ) != $table){
		$sql = 'CREATE TABLE IF NOT EXISTS '.$table.' (
			  `coupon_id` bigint(20) NOT NULL AUTO_INCREMENT,
			  `coupon_code` varchar(255) NOT NULL,
			  `start_date` DATE DEFAULT NULL,
			  `end_date` DATE DEFAULT NULL,
			  `coupon_amount` varchar(20),
			  PRIMARY KEY (`coupon_id`)
		)';
		dbDelta($sql);
	}

	$table = $wpdb->prefix . 'div_billing_subuser';
	if($wpdb->get_var( "SHOW TABLES LIKE '$table'" ) != $table){
		$sql = 'CREATE TABLE IF NOT EXISTS '.$table.' (
			  `subuser_id` bigint(10) NOT NULL AUTO_INCREMENT,
			  `mm_subuser_id` bigint(10) NOT NULL,
			  `user_id` bigint(10) NOT NULL,
			  `subuser_status` int(1) NOT NULL,
			  `subuser_created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`subuser_id`)
			)';
		dbDelta($sql);
	}


	$table = $wpdb->prefix . 'div_billing_membership_payment';
	if($wpdb->get_var( "SHOW TABLES LIKE '$table'" ) != $table){
		$sql = 'CREATE TABLE IF NOT EXISTS '.$table.' (
			  `mm_payment_id` bigint(20) NOT NULL AUTO_INCREMENT,
			  `mm_id` bigint(20) NOT NULL,
			  `payment_id` varchar(20) NOT NULL,
			  PRIMARY KEY (`mm_payment_id`)
			)';
		dbDelta($sql);
	}

	$table = $wpdb->prefix . 'div_billing_membership_list';
	if($wpdb->get_var( "SHOW TABLES LIKE '$table'" ) != $table){
		$sql = 'CREATE TABLE IF NOT EXISTS '.$table.' (
			  `member_id` bigint(10) NOT NULL AUTO_INCREMENT,
			  `mm_id` bigint(10) NOT NULL,
			  `subuser_id` bigint(10) NOT NULL,
			  `user_id` bigint(10) NOT NULL,
			  `parent_member_id` bigint(10) NOT NULL,
			  `member_status` int(1) NOT NULL,
			  `member_created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`member_id`)
			) ';
		dbDelta($sql);
	}

	$search = "#created by WPContentProtector";
	$file = get_home_path()."wp-content/uploads/.htaccess";
	$strings = file($file);
	if(in_array($search."\n", $strings)){
		
	} else {
		$fh = fopen($file, 'w') or die("can't open file");
		$get_url = get_bloginfo('url');
		$split_values = explode("/", $get_url);

		if( !empty($split_values[3]) ) $split_values[3] = '/'.$split_values[3];

		$stringData = "#created by divtext\n";
		fwrite($fh, $stringData);
		$stringData = "<IfModule mod_rewrite.c>\n";
		$stringData .= "RewriteEngine On\n";
		$stringData .= "RewriteCond %{REQUEST_URI} \.(doc|zip|pdf|flv|jpg|png|gif|xls|docx|xlsx|ppt|pptx|pps|svg)$ [NC]\n";
		$stringData .= "RewriteRule ^(.*)$ ".$split_values[3]."/wp-content/plugins/div-billing/getfile.php?filename=$1 [L, QSA]\n";
		$stringData .= "</IfModule>";
		fwrite($fh, $stringData);
		fclose($fh);
	}
	
}

register_deactivation_hook( __FILE__, 'div_billing_deactive' );
function div_billing_deactive(){
	$file = get_home_path()."wp-content/uploads/.htaccess";
	$fh = fopen($file, 'w') or die("can't open file");
	fwrite($fh, '');
	fclose($fh);
}

add_action("admin_menu", "div_billing_menu");
function div_billing_menu() {
     add_menu_page( 'WP Content Protector', 'WP Content Protector', 'add_users', 'div-billing', 'div_billing_type', '', '9.3' );
	 add_submenu_page( "div-billing", "Membership", "Membership", 0, "div-billing", "div_billing_type" );
	 add_submenu_page( "div-billing", "Coupon", "Coupon", 0, "div-billing-coupon", "div_billing_coupon" );
	 add_submenu_page( "div-billing", "Transaction", "Transaction", 0, "div-billing-trans", "div_billing_trans" );
	 add_submenu_page( "div-billing", "Payment Settings", "Payment Settings", 0, "div-billing-payment-settings", "div_billing_payment_settings" );
	 add_submenu_page( "div-billing", "Settings", "Settings", 0, "div-billing-settings", "div_billing_settings" );
}

add_action("admin_init", "div_billing_init");
 
function div_billing_init(){
	add_meta_box("div_billing_post_permission", "Post Permission", "div_billing_post_permission", "post");
	add_meta_box("div_billing_post_permission", "Page Permission", "div_billing_post_permission", "page");
}

function div_billing_type(){
	global $div_billing_type;
	if($_POST){
		$div_billing_type->post();
	}
	else if($_GET['action'] == 'add'){
		$div_billing_type->addNew();
	}else if($_GET['action'] == 'edit'){
		$div_billing_type->edit();
	}else{
		$div_billing_type->manage();
	}
	
}

function div_billing_discount(){
	global $div_billing_discount;
	if($_POST){
		$div_billing_discount->post();
	}
	else if($_GET['action'] == 'add'){
		$div_billing_discount->addNew();
	}else if($_GET['action'] == 'edit'){
		$div_billing_discount->edit();
	}else{
		$div_billing_discount->manage();
	}
}

function div_billing_settings(){
	$messages = array();
	$data = (object) NULL;
	if($_POST){
		div_billing_update('pp_account' , $_POST['pp_account']);
		div_billing_update('pp_mode' , $_POST['pp_mode']);
		div_billing_update('fd_account' , $_POST['fd_account']);
		div_billing_update('fd_mode' , $_POST['fd_mode']);
		div_billing_update('active_payment' , $_POST['active_payment']);
	}

	$data->pp_account = div_billing_data('pp_account');
	$data->pp_mode = div_billing_data('pp_mode');
	$data->fd_account = div_billing_data('fd_account');
	$data->fd_mode = div_billing_data('fd_mode');
	$data->active_payment = div_billing_data('active_payment');
	
	include_once( div_billing_pluginpath() . '/views/settings.php' );
}

function div_billing_update($key, $value){
	global $wpdb;
	$table = $wpdb->prefix."div_billing_settings";
	$sql = "SELECT * FROM ".$table." WHERE setting_key='".$key."'";
	$result = $wpdb->get_results($sql);

	if(count($result) > 0){
		$wpdb->update( $table, array( 'setting_value' => $value) , array('setting_key' => $key) );
	}
	else{
		$wpdb->insert( $table, array( 'setting_key' => $key , 'setting_value' => $value) );
	}
}

function div_billing_data($key){
	global $wpdb;
	$table = $wpdb->prefix."div_billing_settings";
	$sql = "SELECT * FROM ".$table." WHERE setting_key='".$key."'";
	$result = $wpdb->get_row($sql);

	if( count($result) > 0 )
		return $result->setting_value;
	else
		return '';
}

function div_billing_coupon(){
	global $div_billing_coupon;
	if($_POST){
		$div_billing_coupon->post();
	}
	else if($_GET['action'] == 'add'){
		$div_billing_coupon->addNew();
	}else if($_GET['action'] == 'edit'){
		$div_billing_coupon->edit();
	}else{
		$div_billing_coupon->manage();
	}
}

function div_billing_trans(){
	global $div_billing_trans;
	if($_POST){
		$div_billing_trans->post();
	}
	else if($_GET['action'] == 'add'){
		$div_billing_trans->addNew();
	}else if($_GET['action'] == 'edit'){
		$div_billing_trans->edit();
	}else{
		$div_billing_trans->manage();
	}
}

add_action('admin_head', 'div_billing_head');
function div_billing_head() {
 	$siteurl = get_option('siteurl');
    $pluginfolder = get_bloginfo('url') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));

	wp_enqueue_script('jquery');
	wp_deregister_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-core' , $pluginfolder.'/js/jquery.ui.core.min.js');
	wp_deregister_script('jquery-ui-datepicker');
	wp_enqueue_script('jquery-ui-datepicker' , $pluginfolder.'/js/jquery.ui.datepicker.min.js');
	wp_enqueue_style('jquery.ui.theme', $pluginfolder . '/css/jquery-ui-custom.css');
	
}

add_shortcode('div_billing', 'div_billing_shortcode');
function div_billing_shortcode($atts) {
	global $div_billing_shortcode;
	$div_billing_shortcode->show_list();
}

add_shortcode('div_billing_paid', 'div_billing_paid_shortcode');
function div_billing_paid_shortcode($atts) {
	global $div_billing_shortcode;
	$div_billing_shortcode->check_paid();
}

add_shortcode('div_billing_loggedin', 'div_billing_loggedin_shortcode');
function div_billing_loggedin_shortcode($atts) {
	global $div_billing_shortcode;
	$div_billing_shortcode->check_loggedin();
}

add_shortcode('div_billing_purchase', 'div_billing_purchase_shortcode');
function div_billing_purchase_shortcode($atts) {
	global $div_billing_shortcode;
	$div_billing_shortcode->show_purchase();
}


add_filter('attachment_fields_to_edit', 'div_billing_download_permission', 10, 2);
function div_billing_download_permission($form_fields, $post){
	global $wpdb;
	// Set up options
	$options = array( '0' => 'Everyone', '1' => 'Logged in User' , '2' => 'Paid User' );
	
	// Get currently selected value
	$selected = get_post_meta( $post->ID, 'div_billing_download', true );
	
	// If no selected value, default to 'No'
	if( !isset( $selected ) ) 
		$selected = '0';

	$html = "";

	$datam = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."div_billing_membership");
	foreach($datam as $d){
		$array_select = explode(',',$selected);
		if( in_array($d->mm_id, $array_select) )
			$checked = ' checked="checked" ';
		else
			$checked = "";

		$html .= "<input type='checkbox' value='{$d->mm_id}'$checked name='attachments[$post->ID][div-download-permission][]' /> $d->mm_name &nbsp;&nbsp;&nbsp;";


	}
 	$out[] = $html;
	// Construct the form field
	$form_fields['div-download-permission'] = array(
		'label' => 'Permisson Download',
		'input' => 'html',
		'html'  => join("\n", $out),
	);
	
	// Return all form fields
	return $form_fields;
}

add_filter( 'attachment_fields_to_save', 'div_billing_download_permission_save', 10, 2 );
function div_billing_download_permission_save( $post, $attachment ) {
	if( isset( $attachment['div-download-permission'] ) ){
		$val = "";
		foreach ($attachment['div-download-permission'] as $key) {
			if(isset($key))
				$val = $val.$key.",";

		}
		update_post_meta( $post['ID'], 'div_billing_download', $val );
	}
	
	return $post;
}


function div_billing_post_permission(){
	include( plugin_dir_path( __FILE__ ) . 'views/post_permission.php');
}

add_action('save_post', 'div_billing_save_post_permission');

function div_billing_save_post_permission(){
  global $post,$wpdb;
  $table_name = $wpdb->prefix . 'div_billing_membership';
 
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    	return $post->ID;
	}
	$member = "";
	if(count($_POST['membership'])){
		foreach($_POST['membership'] as $m){
			$member .= $m.',';
		}
		$member = substr($member, 0,-1);
	}

	update_post_meta($post->ID, "div_post_permission", $member);
}

function div_billing_payment_settings(){
	global $wpdb;
	
	//var_dump($setting);
	if($_POST){
		$membership = $_POST['membership'];
		if(count($membership)>0){
			foreach ($_POST['membership'] as $a=>$r) {
				if(count($membership[$a]) > 0){
					$data = implode(',', $membership[$a]);
				}
				else
					$data = "";
				
				$datas = array('mm_id'=>$a,'payment_id'=>$data);
				$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."div_billing_membership_payment WHERE mm_id='$a'");
				if($results){
					//echo 'input'.$a;
					$datas = array('payment_id'=>$data);
					$wpdb->update($wpdb->prefix.'div_billing_membership_payment',$datas,array('mm_id' => $a));
				}
				else{
					//echo 'update'.$a;
					$datas = array('mm_id' => $a,'payment_id' => $data);
					//var_dump($datas);
					$wpdb->insert($wpdb->prefix.'div_billing_membership_payment',$datas);
				}
				
			}
		}
	}

	$settings = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."div_billing_membership_payment");
	$rsetting = array();
	foreach ( $settings as $s ) {
		if (strpos($s->payment_id,',') !== false)
			$rsetting[$s->mm_id]=explode(',', $s->payment_id);
		else
			$rsetting[$s->mm_id]=array($s->payment_id);
	}
	
	include_once( div_billing_pluginpath() . '/views/payment_settings.php' );
}

add_shortcode('div_billing_subuser', 'div_billing_subuser');
function div_billing_subuser($atts) {
	global $div_billing_subuser;
	if($_POST)
		$div_billing_subuser->post();
	$div_billing_subuser->show();
}

add_shortcode('wpcp_register_form', 'div_billing_register_link');
add_shortcode('wpcp_register_link', 'div_billing_register_link');
function div_billing_register_link($args) {
	global $div_billing_shortcode;
	$div_billing_shortcode->show_membership_purchase($args['id']);
}

add_shortcode('wpcp_subuser','div_billing_register_subuser');
function div_billing_register_subuser($args){
	global $div_billing_shortcode;
	$div_billing_shortcode->show_register_subuser($args['id']);
}

add_action('wp_enqueue_scripts','div_billing_load_script');
function div_billing_load_script(){
	wp_enqueue_style('div-table', div_billing_pluginurl() . 'css/table.css');
	wp_enqueue_style('jquery.ui.theme', div_billing_pluginurl() . 'css/jquery-ui-custom.css');
	wp_enqueue_script( 'thickbox' , array('jquery') );
	wp_enqueue_style ('thickbox');
	wp_enqueue_script('jquery-ui-autocomplete',site_url().'/wp-includes/js/jquery/ui/jquery.ui.autocomplete.min.js', array('jquery','jquery-ui-core'));
}

add_action('wp_ajax_div_billing_user_auto', 'div_billing_user_auto');
function div_billing_user_auto(){
	global $wpdb;

	$blogusers = get_users('blog_id=1&orderby=nicename&role=subscriber');
	$all = array();
    foreach ($blogusers as $user) {
    	$users = array();
    	$users['id'] = $user->ID;
    	$users['value'] = $user->user_login;
    	$all[] = $users;
    }
	//echo '[{"value":"Some Name","id":1},{"value":"Some Othername","id":2}]';
	echo json_encode($all);
	die('');
}

add_action('template_redirect', 'div_billing_checklogin');

function div_billing_checklogin(){
	if($_POST){
	  	if($_POST['div_action'] == 'login'){
			$creds['user_login'] = $_POST['log'];
			$creds['user_password'] = $_POST['pwd'];
			$creds['remember'] = true;
			$user = wp_signon( $creds, false );
			if ( is_wp_error($user) )
				echo $user->get_error_message();
			else{
				wp_safe_redirect( $_POST['redirect_to'] );
				//exit();
			}
		}
	}
}

add_action('wp_ajax_div_billing_register_ajax_form', 'div_billing_register_ajax_form');
function div_billing_register_ajax_form() {
	include_once( div_billing_pluginpath().'views/register_popup.php' );
}

add_action('wp_ajax_remote_login', 'div_billing_remote_login');
add_action('wp_ajax_nopriv_remote_login', 'div_billing_remote_login');

function div_billing_remote_login(){
	ob_clean();
	if($_REQUEST){
		$user = get_user_by( 'login', $_REQUEST['log'] );
		if ( $user && wp_check_password( $_REQUEST['pwd'], $user->data->user_pass, $user->ID) ){
		   
		   	$creds['user_login'] = $_REQUEST['log'];
			$creds['user_password'] = $_REQUEST['pwd'];
			$creds['remember'] = true;
			$user = wp_signon( $creds, false );

			echo "right";
		}
		else
		   echo "wrong";
	}
	else{
		echo 'no';
	}

	die('');
}

include( plugin_dir_path( __FILE__ ) . 'controllers/billing_type.php');
include( plugin_dir_path( __FILE__ ) . 'controllers/discount.php');
include( plugin_dir_path( __FILE__ ) . 'controllers/coupon.php');
include( plugin_dir_path( __FILE__ ) . 'controllers/subuser.php');
include( plugin_dir_path( __FILE__ ) . 'controllers/transaction.php');
include( plugin_dir_path( __FILE__ ) . 'includes/functions.php');
include( plugin_dir_path( __FILE__ ) . 'includes/shortcode.php');