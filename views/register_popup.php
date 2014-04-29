<?php
wp_head();
global $wpdb;
	$display = '';
		if($_POST){
			if($_POST['div_action_popup'] == 'register'){
				if( !empty($_POST['log']) AND (strlen($_POST['pwd']) > 3) AND div_billing_member_verifyemail($_POST['email'])){
					$login_total = $wpdb->get_row("SELECT * FROM $wpdb->users WHERE user_login = '".$_POST['log']."'");
					if($login_total){
						$messages[] = 'Username exists, Please choose other username';
					}
					else{
						$email_total = $wpdb->get_row("SELECT * FROM $wpdb->users WHERE user_email = '".$_POST['email']."'");
						if($email_total){
							$messages[] = 'Email exists, Please choose other email';
						}
						else{
							$user_data = array(
								'ID' => '',
								'user_pass' => wp_generate_password(),
								'user_login' => $_POST['log'],
								'display_name' => $_POST['log'],
								'first_name' => $_POST['firstname'],
								'last_name' => $_POST['lastname'],
								'role' => 'subscriber'
							);
								
							$user_id = wp_insert_user( $user_data );
							wp_set_password($_POST['pwd'], $user_id);

							$ndata = array(
										'subuser_id' => $_POST['subuser'],
										'user_id' => $user_id
									);
							$wpdb->insert($wpdb->prefix.'div_billing_subuser_list', $ndata);
								
							wp_update_user( array ('ID' => $user_id, 'user_email' => $_POST['email']) ) ;
							//wp_safe_redirect( $_POST['redirect_to'] );

							//Bukan sub
							if(empty($_POST['sub'])){
								$main = $_POST['form_id'];
								$form_id = $_POST['form_id'];
								$sub = '';
								$form = 'form_paypal_'.$main;
							}
							else{ //Sub
								$main = $_POST['sub'];
								$form_id = $_POST['form_id'];
								$sub = $_POST['sub'];
								$form = 'form_paypal_sub_'.$main;
							}
							$amount = 'amount_'.$main;
							?>
								<script type="text/javascript">
									/* Ganti Notify URL, close popup dan call Form */
									var newval = "<?php echo div_billing_pluginurl().'ipn.php?userid='.$user_id ?>&payment_id=<?php echo $form_id?>&subuser=<?php echo $sub ?>";
									//jQuery('#notify_url_<?php echo $main ?>').val( newval );
									self.parent.tb_remove();
									self.parent.gopaypal('#notify_url_<?php echo $main ?>',newval,'#<?php echo $form ?>','#<?php echo $amount ?>');
									///jQuery('#<?php echo $form ?>').submit();
								</script>
							<?php
						}
					}
				}
				else{
					if(!div_billing_member_verifyemail($_POST['email'])) $messages[] = 'Email invalid';
					else $messages[] = 'Please fill all fields correctly';
				}
			}
			else if($_POST['div_action_popup'] == 'login'){
				$display = 'login';
				$check = wp_authenticate_username_password( NULL, $_POST['log'], $_POST['pwd'] );
				if(is_wp_error( $check )){
					$messages[] = 'ERROR: The password you entered for the username is incorrect';
				}
				else{
							$user = get_user_by('login', $_POST['log']);

							//assign user ID based on user login name
							$user_id = $user->ID;
							//Bukan sub
							if(empty($_POST['sub'])){
								$main = $_POST['form_id'];
								$form_id = $_POST['form_id'];
								$sub = '';
								$form = 'form_paypal_'.$main;
							}
							else{ //Sub
								$main = $_POST['sub'];
								$form_id = $_POST['form_id'];
								$sub = $_POST['sub'];
								$form = 'form_paypal_sub_'.$main;
							}
							$amount = 'amount_'.$main;
							?>
								<script type="text/javascript">
									/* Ganti Notify URL, close popup dan call Form */
									var newval = "<?php echo div_billing_pluginurl().'ipn.php?userid='.$user_id ?>&payment_id=<?php echo $form_id?>&amount=<?php echo $sr->onetime_fee ?>&subuser=<?php echo $sub ?>";
									//jQuery('#notify_url_<?php echo $main ?>').val( newval );
									self.parent.tb_remove();
									self.parent.gopaypal('#notify_url_<?php echo $main ?>',newval,'#<?php echo $form ?>','#<?php echo $amount ?>');
									///jQuery('#<?php echo $form ?>').submit();
								</script>
							<?php
				}
			}	
		}
?>

<?php if ( !is_user_logged_in() ) : ?>
<style type="text/css">
label{
	font-family: "Muli","Helvetica Neue","Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;
	display: block;
	float: left;
	margin-top: 2px;
    color: rgb(102, 102, 102);
}
tr{
	vertical-align: top;
	height: 45px;
}
input[type="text"] , input[type="password"] {
    width: 400px;
    color: rgb(102, 102, 102);
    float: left;
    font-size: 0.9em;
    padding: 6px;

    border: 1px solid rgb(204, 204, 204);
	border-radius: 3px 3px 3px 3px;
	-moz-box-sizing: border-box;
}

input[type="submit"] {
	background: -moz-linear-gradient(center bottom , rgb(86, 86, 86) 0%, rgb(104, 104, 104) 100%) repeat scroll 0% 0% transparent;
	border-width: 1px;
	border-style: solid;
	border-color: rgb(64, 64, 64) rgb(55, 55, 55) rgb(46, 46, 46);
	border-radius: 4px 4px 4px 4px;
	box-shadow: 0px 1px 0px rgba(255, 255, 255, 0.3) inset, 0px -1px 0px rgba(0, 0, 0, 0.05) inset, 0px 1px 2px rgba(0, 0, 0, 0.1);
	color: white;
	cursor: pointer;
	display: inline-block;
	font-size: 0.875em;
	padding: 9px 26px;
	text-decoration: none;
	text-shadow: 0px 1px 0px rgb(0, 0, 0);
}
</style>

<script type="text/javascript">
	jQuery(document).ready(function($){
		$('.login_link').click(function(){
			$('#div_billing_login').show();
			$('#div_billing_reg').hide();
		});

		$('#register_link').click(function(){
			$('#div_billing_reg').show();
			$('#div_billing_login').hide();
		});
	});
</script>
<div style="margin:30px;20px" id="dite_form">
	<div style="color:red"><?php if(count($messages)) : ?>
		<?php foreach ($messages as $mes) {
			# code...
			echo $mes. '<br />';
		} ?>
	<?php endif; ?>
	</div>
	<form <?php if($display == 'login') echo 'style="display:none;"'; else echo ''; ?> name="loginform" method="post" id="div_billing_reg" action="">
	<table >
		<tr height="50px">
			<td width="140px"><label class="dite_reg">Username *</label></td>
			<td><input type="text" name="log" id="div_username" value="" /></td>
		</tr>
		
		<tr>
			<td><label class="dite_reg">Password *</label></td>
			<td><input type="password" name="pwd" id="div_password" value="" /></td>
		</tr>

		<tr>
			<td><label class="dite_reg">Firstname *</label></td>
			<td><input type="text" name="firstname" id="div_firstname" value="" /></td>
		</tr>

		<tr>
			<td><label class="dite_reg">Lastname *</label></td>
			<td><input type="text" name="lastname" id="div_lastname" value="" /></td>
		</tr>

		<tr>
			<td><label class="dite_reg">Email *</label></td>
			<td><input type="text" name="email" id="div_email" value="" /></td>
		</tr>

		<tr>
			<td></td>
			<td><input type="submit" class="dreg_button" value="Register" />
			<a class="login_link" id="login_link" href="javascript:void(0)">Login</a></td>
		</tr>
	</table>
		<input type="hidden" name="sub" value="<?php echo $_GET['sub'] ?>">
		<input type="hidden" name="form_id" value="<?php echo $_GET['form_id'] ?>">
		<input type="hidden" name="div_action_popup" value="register">
		<input type="hidden" name="redirect_to" value="<?php echo div_billing_fullurl() ?>" />
		<input type="hidden" name="testcookie" value="1" />
	</form>


	<form <?php if($display=='login') echo '';else echo 'style="display:none"';?> name="loginform" method="post" id="div_billing_login" action="">
	<table>
		<tr>
			<td><label class="dite_reg">Username</label></td>
			<td><input type="text" name="log" id="dite_username" value="" /></td>
		</tr>
		
		<tr>
			<td><label class="dite_reg">Password</label></td>
			<td><input type="password" name="pwd" id="dite_password" value="" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" class="dreg_button" value="Login" />
			<a id="register_link" href="javascript:void(0)">Register</a></td>
		</tr>
	</table>
		<input type="hidden" name="sub" value="<?php echo $_GET['sub'] ?>">
		<input type="hidden" name="form_id" value="<?php echo $_GET['form_id'] ?>">
		<input type="hidden" name="div_action_popup" value="login">
		<input type="hidden" name="redirect_to" value="<?php echo div_billing_fullurl() ?>" />
		<input type="hidden" name="testcookie" value="1" />
	</form>
</div>
<?php else: ?>

<?php endif; ?>