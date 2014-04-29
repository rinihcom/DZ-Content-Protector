<?php
/**
 * Author : Budi S
 * Author URI: http://inef.web.id
 * License: GPL2
 */

class div_billing_subuser{
	public $wpdb;
	public $title;
	public $titles;
	public $table;
	
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->title = 'Sub User';
		$this->table = $wpdb->prefix.'div_billing_subuser';
	}
		
	public function manage(){

	}

	public function post(){
		global $wpdb;
		$current_user = wp_get_current_user();
		if($_POST){
			$table = $wpdb->prefix.'div_billing_subuser';

			if($_POST['act'] == 'delete'){
				$data = array('subuser_id' => $_POST['subuser_id']);
				$wpdb->delete($table,$data);
			}
			else{
				//Cek User

				//Check if user already added
				$data = array( 'user_id' => $_POST['user_id'], 'parent_id' => $current_user->ID, 'mm_id' => '');

				//var_dump($data);

				//Save if not exists
				$wpdb->insert( $table , $data);
			}
			
		}
	}

	public function show(){
		if ( !is_user_logged_in() )
			die("Sorry you don't have priviledge to access this page");
		
		global $wpdb;
		$current_user = wp_get_current_user();
		$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."div_billing_subuser WHERE parent_id='$current_user->ID'");
		include_once(div_billing_pluginpath() . '/views/subuser.php');
	}
}

$div_billing_subuser = new div_billing_subuser;
?>