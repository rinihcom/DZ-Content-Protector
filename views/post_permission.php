<label class="big_title">Permission:</label>
<p>
<?php
	global $wpdb,$post;
	$custom = get_post_custom($post->ID);
	$data = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."div_billing_membership");
	foreach($data as $d){
		$arrdata = explode(',', $custom['div_post_permission'][0]);
		if(in_array($d->mm_id,$arrdata))
			$checked = ' checked="checked" ';
		else 
			$checked = "";
		?><input type="checkbox" <?php echo $checked ?> name="membership[]" value="<?php echo $d->mm_id; ?>" />&nbsp;<?php echo $d->mm_name;?> <br />
	<?php }
?>
</p>