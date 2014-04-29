<div class="wrap">
	<?php screen_icon( 'post' ); ?>
	<h2>Settings</h2>
	<p>Fill in the form below. <strong>Required fields are marked *</strong></p>
	<p>
		<?php if($messages) : ?>
			<?php foreach($messages as $m) : ?>
				<div><?php echo $m;?></div>
			<?php endforeach; ?>
		<?php endif; ?>
	</p>
	<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="name"><b>Paypal</b></label></th>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="name">Paypal Account *</label></th>
					<td>
						<input class="regular-text" type="text" id="pp_account" name="pp_account" value="<?php echo $data->pp_account; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="name">Paypal Live / Test Mode</label></th>
					<td>
						<select name="pp_mode" id="pp_mode">
							<option <?php if($data->pp_mode == 'Live') echo 'selected="selected"' ?> value="Live">Live</option>
							<option <?php if($data->pp_mode == 'Test') echo 'selected="selected"' ?> value="Test">Test</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="name"><b>FirstData</b></label></th>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="name">FirstData Account *</label></th>
					<td>
						<input class="regular-text" type="text" id="fd_account" name="fd_account" value="<?php echo $data->fd_account; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="name">FirstData Live / Test Mode</label></th>
					<td>
						<select name="fd_mode" id="fd_mode">
							<option <?php if($data->fd_mode == 'Live') echo 'selected="selected"' ?> value="Live">Live</option>
							<option <?php if($data->fd_mode == 'Test') echo 'selected="selected"' ?> value="Test">Test</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="name"><b>Active Payment</b></label></th>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="name">Set Payment</label></th>
					<td>
						<select name="active_payment" id="active_payment">	
							<option <?php if($data->active_payment == 'All') echo 'selected="selected"' ?> value="All">All</option>
							<option <?php if($data->active_payment == 'Paypal') echo 'selected="selected"' ?> value="Paypal">Paypal Only</option>
							<option <?php if($data->active_payment == 'FirstData') echo 'selected="selected"' ?> value="FirstData">FirstData Only</option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="hidden" value="<?php echo $_GET['id']?>" name="id" />
			<input type="submit" value="Submit" class="button-primary" name="Submit" />
		</p>
	</form>

	<script type="text/javascript">
		jQuery(document).ready(function() { 
			jQuery('.datepicker').datepicker({
				dateFormat : 'yy-mm-dd'
			});
			
		});
	</script>
</div>