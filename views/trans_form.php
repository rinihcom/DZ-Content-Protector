<div class="wrap">
	<?php screen_icon( 'post' ); ?>
	<h2> <?php echo __( ucfirst($_GET['action']).' Membership' ); ?> <a href="<?php echo admin_url('admin.php').'?page='.$_GET['page']; ?>" class="add-new-h2">List Membership</a> </h2>
	<p>Fill in the form below to add a new membership. <strong>Required fields are marked *</strong></p>
	<p>
		<?php if($this->messages) : ?>
			<?php foreach($this->messages as $m) : ?>
				<div><?php echo $m;?></div>
			<?php endforeach; ?>
		<?php endif; ?>
	</p>
	<form id="member_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="name">User *</label></th>
					<td><select name="user">
						<?php 
							$users = get_users('role=subscriber');
							foreach ($users as $user) {
								if($user->ID == $data->user_id)	$checked = ' selected="selected" ';
								else $checked = "";
						        echo '<option '.$checked.' value="'.$user->ID.'">' . $user->display_name . '</option>';
							}
						?>
						</select>
					</td>
				</tr>

				<tr>
					<td><label>Membership *</label>
					<td>
						<select name="membership">
						<?php
							global $wpdb,$post;
							$datam = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."div_billing_membership");
							foreach($datam as $d){
								if($d->mm_id == $data->billing_id){
									$checked = ' selected="selected" ';
								}
								else {
									$checked = "";
								}
								?><option <?php echo $checked ?> value="<?php echo $d->mm_id;?>"><?php echo $d->mm_name;?></option> 
							<?php }
						?>
						</select>
					</td>
				</tr>

				<tr>
					<td>Amount</td>
					<td><input type="text" size="10" name="amount" value="<?php echo $data->amount; ?>" /> USD</td>
				</tr>

				<tr>
					<td>Status</td>
					<?php $statuses = array('Accepted Payment','Received Order','Incomplete Sale', 'Payment Declined'); ?>
					<td>
					<select id="status" name="status">
						<?php foreach($statuses as $r): ?>
							<?php if($r == $data->status): ?>
								<option selected="selected" value="<?php echo $r ?>"><?php echo $r ?></option>
							<?php else: ?>
								<option value="<?php echo $r ?>"><?php echo $r ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
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
</div>