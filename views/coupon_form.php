<div class="wrap">
	<?php screen_icon( 'post' ); ?>
	<h2> <?php echo __( ucfirst($_GET['action']).' '.$this->title ); ?> <a href="<?php echo admin_url('admin.php').'?page='.$_GET['page']; ?>" class="add-new-h2">List <?php echo $this->title ?></a> </h2>
	<p>Fill in the form below to add a new <?php echo $this->title?>. <strong>Required fields are marked *</strong></p>
	<p>
		<?php if($this->messages) : ?>
			<?php foreach($this->messages as $m) : ?>
				<div><?php echo $m;?></div>
			<?php endforeach; ?>
		<?php endif; ?>
	</p>
	<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="name">Coupon code *</label></th>
					<td>
						<input class="regular-text" type="name" id="code" name="name" value="<?php echo $data->coupon_code; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="end_date">Start Date</label></th>
					<td>
						<input type="text" class="datepicker" id="start_date" name="start_date" value="<?php echo $data->start_date; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="end_date">End Date</label></th>
					<td>
						<input type="text" class="datepicker" id="end_date" name="end_date" value="<?php echo $data->end_date; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="amount">Free days *</label></th>
					<td>
						<input type="text" id="amount" name="amount" class="small-text" value="<?php echo $data->coupon_amount; ?>" />
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