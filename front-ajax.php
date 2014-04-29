<?php
/**
 * WordPress AJAX Process Execution for Front side.
 */
define('DOING_AJAX', true);
define('WP_ADMIN', true);
require_once('../../../wp-load.php');
 
if ( ! isset( $_REQUEST['action'] ) )
	die();
 
require_once('../../../wp-admin/includes/admin.php');
@header('Content-Type: text/html; charset=' . get_option('blog_charset'));
send_nosniff_header();
 
do_action('admin_init');
 
if ( ! is_user_logged_in() ) {
 
	if ( !empty( $_REQUEST['action'] ) )
		do_action( 'wp_ajax_' . $_REQUEST['action'] ); 
	die();
}
 
if ( isset( $_GET['action'] ) ) :
switch ( $action = $_GET['action'] ) :
default :
	do_action( 'wp_ajax_' . $_GET['action'] );
	break;
endswitch;
endif;
 
$id = isset($_POST['id'])? (int) $_POST['id'] : 0;
switch ( $action = $_POST['action'] ) :
default :
	do_action( 'wp_ajax_' . $_POST['action'] );
	break;
endswitch;
?>