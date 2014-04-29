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
	<?php //var_dump($rsettings) ?>
	<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<table class="form-table">
			<p>
			<?php
				global $wpdb,$post;
				$custom = get_post_custom($post->ID);

				$settings = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."div_billing_settings WHERE setting_key='active_payment'");

				$fd = $pp = "";
				if($settings->setting_value == 'Paypal'){
					$fd = ' disabled="disabled" ';
				}

				if($settings->setting_value == 'FirstData'){
					$pp = ' disabled="disabled" ';
				}

				$data = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."div_billing_membership");
				foreach($data as $d){
					?><tr><?php
					//$arrdata = explode(',', $setting[$d->mm_id]);
					$fd_checked = "";
					$pp_checked = "";

					if(count($rsetting[$d->mm_id])){
						if(in_array('Paypal',$rsetting[$d->mm_id]))
							$pp_checked = ' checked="checked" ';

						if(in_array('FirstData',$rsetting[$d->mm_id]))
							$fd_checked = ' checked="checked" ';
					}
						

					//var_dump($rsetting[$d->mm_id]);
					?><td><label><?php echo $d->mm_name;?></label></td><td><input <?php echo $pp ?> type="checkbox" <?php echo $pp_checked ?> name="membership[<?php echo $d->mm_id; ?>][]" value="Paypal" />&nbsp;Paypal&nbsp;&nbsp;<?php
					?><input <?php echo $fd ?> type="checkbox" <?php echo $fd_checked ?> name="membership[<?php echo $d->mm_id; ?>][]" value="FirstData" />&nbsp;First Data</td><?php
					?></tr><?php
				}
			?>
			</p>
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