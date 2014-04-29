<?php if ( !is_user_logged_in() ) : ?>
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
<div id="dite_form">
	<form name="loginform" method="post" id="div_billing_reg" action="">
	<table >
		<tr>
			<td><label class="dite_reg">Username</label></td>
			<td><input type="text" name="log" id="div_username" value="" /></td>
		</tr>
		
		<tr>
			<td><label class="dite_reg">Password</label></td>
			<td><input type="password" name="pwd" id="div_password" value="" /></td>
		</tr>

		<tr>
			<td><label class="dite_reg">Firstname</label></td>
			<td><input type="text" name="firstname" id="div_firstname" value="" /></td>
		</tr>

		<tr>
			<td><label class="dite_reg">Lastname</label></td>
			<td><input type="text" name="lastname" id="div_lastname" value="" /></td>
		</tr>

		<tr>
			<td><label class="dite_reg">Email</label></td>
			<td><input type="text" name="email" id="div_email" value="" /></td>
		</tr>

		<tr>
			<td></td>
			<td><input type="submit" class="dreg_button" value="Register" />
			<a class="login_link" id="login_link" href="javascript:void(0)">Login</a></td>
		</tr>
	</table>
		<input type="hidden" name="subuser" value="<?php echo $id ?>">
		<input type="hidden" name="div_action" value="register">
		<input type="hidden" name="redirect_to" value="<?php echo div_billing_fullurl() ?>" />
		<input type="hidden" name="testcookie" value="1" />
	</form>


	<form style="display:none" name="loginform" method="post" id="div_billing_login" action="">
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
		<input type="hidden" name="subuser" value="<?php echo $id ?>">
		<input type="hidden" name="div_action" value="login">
		<input type="hidden" name="redirect_to" value="<?php echo div_billing_fullurl() ?>" />
		<input type="hidden" name="testcookie" value="1" />
	</form>
</div>
<?php else: ?>

<?php endif; ?>