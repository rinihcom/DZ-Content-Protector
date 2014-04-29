<?php
define('DOING_AJAX', true);
define('WP_ADMIN', true);
require_once('../../../wp-load.php');
 
require_once('../../../wp-admin/includes/admin.php');
@header('Content-Type: text/html; charset=' . get_option('blog_charset'));
send_nosniff_header();
 
do_action('admin_init');

global $wpdb;
$res_pp_sandbox = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."div_billing_settings WHERE setting_key='pp_mode'");
$res_pp_account = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."div_billing_settings WHERE setting_key='pp_account'");

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
}
// post back to PayPal system to validate
$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
if( $res_pp_sandbox->setting_value =='Live'){
	$header .= "Host: www.paypal.com\r\n";
	$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
}
else{
	$header .= "Host: www.sandbox.paypal.com\r\n";
	$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
}

$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";





$paypal_account = $res_pp_account->setting_value;

$email = $_POST['payer_email'];
$admin_email = get_bloginfo( 'admin_email' );

if (!$fp) {
// HTTP ERROR
	fputs ($fp, $header . $req);
	$filename = 'paypal_log.txt'; //create a file telling me we're verified
    $filehandle=fopen($filename, 'w');
   	fwrite($filehandle,'HTTP ERROR : : '.$header . $req . "\n\n");
    fclose($filehandle);

		$to      = $email;  
			$subject = 'Failed Area';  
			$message = ' 
			 
			Thank you for your purchase, your membership is actived
			             
			You can now login at '.site_url();

			$headers = 'From:' . $admin_email . "\r\n";  
			  
			mail($to, $subject, $message, $headers);
} 
else {
	fputs ($fp, $header . $req);
	$filename = 'paypal_log.txt'; //create a file telling me we're verified
    $filehandle=fopen($filename, 'w');
   	
   	$fs = "";
	while (!feof($fp)) {
		$res = fgets ($fp, 1024);
		$fs .= $res."\n=============\n";
		if (strcmp ($res, "VERIFIED") == 0) {
			// PAYMENT VALIDATED & VERIFIED!
			$to      = $email;  
			$subject = 'Download Area';  
			$message = ' 
			 
			Thank you for your purchase, your membership is actived
			             
			You can now login at '.site_url();

			$headers = 'From:' . $admin_email . "\r\n";  
			  
			mail($to, $subject, $message, $headers);


			$to      = "$admin_email,$paypal_account";  
			$subject = 'Download Area | Accepted Payment';  
			$message = ' 
			 
			Dear Administrator, 
			 
			A payment has been made and flagged as ACCEPTED. 
			Please verify the payment. 
			 
			Buyer Email: '.$email;   

			$headers = 'From:' . $admin_email . "\r\n";  
			  
			mail($to, $subject, $message);

			
			$data = array(
				'billing_id' => $_REQUEST['payment_id'],
				'user_id' => $_REQUEST['userid'],
				'amount' => $_REQUEST['amount'],
				'status' => 'Accepted Payment'
			);
			$insert = $wpdb->insert($wpdb->prefix.'div_billing_trans',$data);

			if(empty($_REQUEST['subuser']))
				$_subuser = 0;
			else
				$_subuser = intval($_REQUEST['subuser']);

			$check = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."div_billing_membership_list WHERE mm_id = '".$_REQUEST['payment_id']."' AND subuser_id = '".$_subuser."' AND user_id='".$_REQUEST['userid']."'");

			if(!empty($check)){
				$datas = array(	'member_status' => '1' );
				$wpdb->update( $wpdb->prefix.'div_billing_membership_list',$datas,array('member_id' => $check->member_id) );
			}
			else{
				$datas = array(	'mm_id' 		=> $_REQUEST['payment_id'],
							'subuser_id' 	=> $_REQUEST['subuser'] ,
							'user_id' 		=> $_REQUEST['userid'], 
							'member_status' => '1' );

				$wpdb->insert( $wpdb->prefix.'div_billing_membership_list',$datas );
			}
			

		}
		else if (strcmp ($res, "INVALID") == 0) {
			// PAYMENT INVALID & INVESTIGATE MANUALY!
			$data = array(
				'billing_id' => $_REQUEST['payment_id'],
				'user_id' => $_REQUEST['userid'],
				'amount' => $_REQUEST['amount'],
				'status' => 'Received Order'
			);
			$insert = $wpdb->insert($wpdb->prefix.'div_billing_trans',$data);


			$to      = "$admin_email,$paypal_account";  
			$subject = 'Download Area | Invalid Payment';  
			$message = ' 
			 
			Dear Administrator, 
			 
			A payment has been made but is flagged as INVALID. 
			Please verify the payment manualy and contact the buyer. 
			 
			Buyer Email: '.$email;   

			$headers = 'From:' . $admin_email . "\r\n";  
			  
			mail($to, $subject, $message);
		}
		else{
			$to      = "$admin_email,$paypal_account";  
			$subject = 'Download Area | Invalid Payment';  
			$message = ' 
				 
				Dear Administrator, 
				 
				A payment has been made but is flagged as FAILED.
				Please verify the payment manualy and contact the buyer.
				 
				Buyer Email: '.$email;   

				$headers = 'From:' . $admin_email . "\r\n";  
				  
				//mail($to, $subject, $message);
		}
	}
	fwrite($filehandle,$fs."\n\n".date('Y-m-d h:i:s'));
    fclose($filehandle);


	fclose ($fp);
}

?>