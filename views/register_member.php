		<script type="text/javascript">
		function gopaypal(notif_id,new_url,form_id,new_amount){
			var n_amount = jQuery(new_amount).val();
			jQuery(notif_id).val( new_url+'&amount='+n_amount );
			jQuery(form_id).submit();

			//alert( new_amount + jQuery(notif_id).val() ) ;
		}
		</script>	

			<?php	
				if($results->mm_allow_subuser == 1):
				$sub_results = $this->wpdb->get_results("SELECT * FROM ".$this->wpdb->prefix."div_billing_subuser_master WHERE mm_id='".$id."'");

				if($show_pp != "BUTTON_ONLY") { 
					if($r->mm_type == '1') echo 'Initial Payment';
					else if($r->mm_type == '2') echo $r->mm_duration.' months';
					else if($r->mm_type == '3') echo 'Initial Payment and '.$r->mm_duration.' months';
				}

				foreach ($sub_results as $sr) { 
					if($show_pp != "BUTTON_ONLY") { ?>
					<table>
						<tr>
							<td>
								From user <?php echo $sr->from_user; ?> to <?php echo $sr->to_user; ?>
							</td>
							<td>
					<?php } ?>

								<?php if(!is_user_logged_in()) : ?>
									<a class="openbox" title="Register / Login" href="javascript:void(0)" data-href="<?php echo div_billing_pluginurl().'front-ajax.php?sub='.$sr->mm_subuser_id.'&form_id='.$r->mm_id; ?>&action=div_billing_register_ajax_form"><img src="http://www.paypal.com/en_US/i/btn/btn_subscribe_LG.gif" /></a>
								<?php endif;?>

								<form  id="form_paypal_sub_<?php echo $sr->mm_subuser_id ?>" <?php echo $show ?> name="_xclick" action="<?php echo $paypal_url ?>" method="post">
								<?php if($r->mm_type == '1') : ?>
									<input type="hidden" name="cmd" value="_xclick">
									<input type="hidden" name="business" value="<?php echo $paypal_account?>">
									<input type="hidden" name="currency_code" value="USD">
									<input type="hidden" name="item_name" value="Initial Payment (From user <?php echo $sr->from_user; ?> to <?php echo $sr->to_user; ?>)">
									<input type="hidden" id="amount_<?php echo $sr->mm_subuser_id ?>" name="amount" value="<?php echo $sr->onetime_fee ?>">
									<input id="notify_url_<?php echo $sr->mm_subuser_id ?>" type="hidden" name="notify_url" value="<?php echo div_billing_pluginurl().'ipn.php?userid='.$user_id ?>&payment_id=<?php echo $sr->mm_id?>&amount=<?php echo $sr->onetime_fee ?>&subuser=<?php echo $sr->mm_subuser_id ?>">
									<input type="image" src="http://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
									
								<?php elseif($r->mm_type == '2') : ?>
									<input type="hidden" name="cmd" value="_xclick-subscriptions">
									<input type="hidden" name="business" value="<?php echo $paypal_account?>">
									<input type="hidden" name="item_name" value="Initial Payment (From user <?php echo $sr->from_user; ?> to <?php echo $sr->to_user; ?>) of Membership with ID <?php echo  $r->mm_id ?>">
									<input type="hidden" name="currency_code" value="USD">
									<input type="hidden" name="no_shipping" value="1">
									<input type="image" src="http://www.paypal.com/en_US/i/btn/btn_subscribe_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
									<input type="hidden" name="return" value="<?php echo div_billing_fullurl() ?>">
									<input id="notify_url_<?php echo $sr->mm_subuser_id ?>" type="hidden" name="notify_url" value="<?php echo div_billing_pluginurl().'ipn.php?userid='.$user_id ?>&payment_id=<?php echo $r->mm_id?>&amount=<?php echo $sr->recurring_fee ?>">
									<input type="hidden" id="amount_<?php echo $sr->mm_subuser_id ?>" name="a3" value="<?php echo $sr->recurring_fee ?>">
									<input type="hidden" name="p3" value="<?php echo $r->mm_duration ?>">
									<input type="hidden" name="t3" value="<?php echo substr($r->mm_duration_type, 0,1); ?>">
									<input type="hidden" name="src" value="1">
									<input type="hidden" name="sra" value="1">
								<?php elseif($r->mm_type == '3'): ?>
									<input type="hidden" name="cmd" value="_xclick-subscriptions">
									<input type="hidden" name="business" value="<?php echo $paypal_account?>">
									<input type="hidden" name="item_name" value="Initial Payment (From user <?php echo $sr->from_user; ?> to <?php echo $sr->to_user; ?>) of Membership with ID <?php echo  $r->mm_id ?>">
									<input type="hidden" name="currency_code" value="USD">
									<input type="hidden" name="no_shipping" value="1">
									<input type="image" src="http://www.paypal.com/en_US/i/btn/btn_subscribe_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
									<input type="hidden" name="return" value="<?php echo div_billing_fullurl() ?>">
									<input id="notify_url_<?php echo $sr->mm_subuser_id ?>" type="hidden" name="notify_url" value="<?php echo div_billing_pluginurl().'ipn.php?userid='.$user_id ?>&payment_id=<?php echo $sr->mm_id?>&amount=<?php echo $sr->onetime_fee ?>&subuser=<?php echo $sr->mm_subuser_id ?>">
									<input type="hidden" id="amount_<?php echo $sr->mm_subuser_id ?>" name="a1" value="<?php echo $sr->onetime_fee ?>">
									<input type="hidden" name="p1" value="<?php echo $r->mm_duration ?>">
									<input type="hidden" name="t1" value="<?php echo substr($r->mm_duration_type, 0,1); ?>">
									<input type="hidden" name="a3" value="<?php echo $sr->recurring_fee ?>">
									<input type="hidden" name="p3" value="<?php echo $r->mm_duration ?>">
									<input type="hidden" name="t3" value="<?php echo substr($r->mm_duration_type, 0,1); ?>">
									<input type="hidden" name="src" value="1">
									<input type="hidden" name="sra" value="1">
								<?php endif; ?>
								</form>
					<?php if($show_pp != "BUTTON_ONLY") { ?> 
							</td>
						</tr>
					</table> 
					<?php }
				} 
			else: 
				if($show_pp != "BUTTON_ONLY") { ?>
				<table>
					<tr>
						<td>
							<?php 
								if($r->mm_type == '1') echo 'Initial Payment';
								else if($r->mm_type == '2') echo $r->mm_duration.' months';
								else if($r->mm_type == '3') echo 'Initial Payment and '.$r->mm_duration.' months';
							?>
						</td>
						<td>
				<?php } ?>
								<?php if(!is_user_logged_in()) : ?>
									<a class="openbox" title="Register / Login" href="javascript:void(0)" data-href="<?php echo div_billing_pluginurl().'front-ajax.php?form_id='.$r->mm_id; ?>&action=div_billing_register_ajax_form"><img src="http://www.paypal.com/en_US/i/btn/btn_subscribe_LG.gif" /></a>
								<?php endif;?>


							<?php if($r->mm_type == '1') : ?>

								<form id="form_paypal_<?php echo $r->mm_id ?>" <?php echo $show ?> name="_xclick" action="<?php echo $paypal_url ?>" method="post">
								<input type="hidden" name="cmd" value="_xclick">
								<input type="hidden" name="business" value="<?php echo $paypal_account?>">
								<input type="hidden" name="currency_code" value="USD">
								<input type="hidden" name="item_name" value="Initial Payment of Membership with ID <?php echo  $r->mm_id ?>">
								<input type="hidden" id="amount_<?php echo $r->mm_id ?>" name="amount" value="<?php echo $r->mm_onetime ?>">
								<input id="notify_url" type="hidden" name="notify_url" value="<?php echo div_billing_pluginurl().'ipn.php?userid='.$user_id ?>&payment_id=<?php echo $r->mm_id?>&amount=<?php echo $r->mm_onetime ?>">
								<input type="image" src="http://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
								</form>
							<?php elseif($r->mm_type == '2'): ?>
								<form  <?php echo $show ?> id="form_paypal_<?php echo $r->mm_id ?>" name="_xclick" action="<?php echo $paypal_url ?>" method="post">
								<input type="hidden" name="cmd" value="_xclick-subscriptions">
								<input type="hidden" name="business" value="<?php echo $paypal_account?>">
								<input type="hidden" name="item_name" value="Recurring Payment of Membership with ID <?php echo  $r->mm_id ?>">
								<input type="hidden" name="currency_code" value="USD">
								<input type="hidden" name="no_shipping" value="1">
								<input type="image" src="http://www.paypal.com/en_US/i/btn/btn_subscribe_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
								<input type="hidden" name="return" value="<?php echo div_billing_fullurl() ?>">
								<input id="notify_url" type="hidden" name="notify_url" value="<?php echo div_billing_pluginurl().'ipn.php?userid='.$user_id ?>&payment_id=<?php echo $r->mm_id?>&amount=<?php echo $r->mm_amount ?>">
								<input type="hidden" id="amount_<?php echo $r->mm_id ?>" name="a3" value="<?php echo $r->mm_amount ?>">
								<input type="hidden" name="p3" value="<?php echo $r->mm_duration ?>">
								<input type="hidden" name="t3" value="<?php echo substr($r->mm_duration_type, 0,1) ?>">
								<input type="hidden" name="src" value="1">
								<input type="hidden" name="sra" value="1">
								</form>
							<?php elseif($r->mm_type == '3'): ?>
								<form  <?php echo $show ?> id="form_paypal_<?php echo $r->mm_id ?>" name="_xclick" action="<?php echo $paypal_url ?>" method="post">
								<input type="hidden" name="cmd" value="_xclick-subscriptions">
								<input type="hidden" name="item_name" value="Recurring Payment of Membership with ID <?php echo  $r->mm_id ?>">
								<input type="hidden" name="business" value="<?php echo $paypal_account?>">
								<input type="hidden" name="currency_code" value="USD">
								<input type="hidden" name="no_shipping" value="1">
								<input type="image" src="http://www.paypal.com/en_US/i/btn/btn_subscribe_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
								<input type="hidden" name="return" value="<?php echo div_billing_fullurl() ?>">
								<input id="notify_url" type="hidden" name="notify_url" value="<?php echo div_billing_pluginurl().'ipn.php?userid='.$user_id ?>&payment_id=<?php echo $r->mm_id?>&amount=<?php echo $r->mm_onetime ?>">
								<input type="hidden" id="amount_<?php echo $r->mm_id ?>" name="a1" value="<?php echo $r->mm_onetime ?>">
								<input type="hidden" name="p1" value="<?php echo $r->mm_duration ?>">
								<input type="hidden" name="t1" value="<?php echo substr($r->mm_duration_type, 0,1); ?>">
								<input type="hidden" name="a3" value="<?php echo $r->mm_amount ?>">
								<input type="hidden" name="p3" value="<?php echo $r->mm_duration ?>">
								<input type="hidden" name="t3" value="<?php echo substr($r->mm_duration_type, 0,1) ?>">
								<input type="hidden" name="src" value="1">
								<input type="hidden" name="sra" value="1">
								</form>
							<?php endif; ?>
				<?php if($show_pp != "BUTTON_ONLY") { ?>
						</td>
					</tr>
				</table>
				<?php } 
			endif;