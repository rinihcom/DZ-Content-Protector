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
					<th scope="row"><label for="name">Name *</label></th>
					<td>
						<input class="regular-text" type="text" id="name" name="name" value="<?php echo $data->mm_name; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label>Type *</label></th>
					<td>
						<?php $type = array(0=>'Free',1=>'Initial Payment',2=>'Recurring Payment',3=>'Initial then Recurring'); ?>
						<select id="member_type" name="type">
							<?php foreach($type as $t=>$v): ?>
								<?php if($t == $data->mm_type): ?>
									<option selected="selected" value="<?php echo $t ?>"><?php echo $v ?></option>
								<?php else: ?>
									<option value="<?php echo $t ?>"><?php echo $v ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr id="duration_tr" <?php if($data->mm_type == 0) echo 'style="display:none"'?> valign="top">
					<th scope="row"><label for="duration">Duration *</label></th>
					<td>
						<table>
						<tr id="onetime_tr" <?php if($data->mm_type == 2 || $data->mm_allow_subuser == 1) echo 'style="display:none"'?> valign="top">
							<td>Initial Fee</td>
							<td <?php if($data->mm_allow_subuser == 1) echo 'style="display:none"' ?> id="td_onetime"><input type="text" id="onetime" name="onetime" class="small-text" value="<?php echo $data->mm_onetime; ?>" /> USD</td>
						</tr>
						<tr id="recuring_tr" <?php if($data->mm_type == 1) echo 'style="display:none"'?>>
							<td><input type="text" id="duration" name="duration" class="small-text" value="<?php echo $data->mm_duration; ?>" />
							<?php $recuring_type = array('Days' , 'Weeks' , 'Months' , 'Years'); ?>
								<select id="duration_type" name="duration_type">
									<?php foreach($recuring_type as $r): ?>
										<?php if($r == $data->mm_duration_type): ?>
											<option selected="selected" value="<?php echo $r ?>"><?php echo $r ?></option>
										<?php else: ?>
											<option value="<?php echo $r ?>"><?php echo $r ?></option>
										<?php endif; ?>
									<?php endforeach; ?>
								</select>
							</td>
							<td <?php if($data->mm_allow_subuser == 1) echo 'style="display:none"'?> id="td_amount"><input type="text" id="amount" name="amount" class="small-text" value="<?php echo $data->mm_amount; ?>" /> USD</td>
						</tr>
						</table>
					</td>
				</tr>
				<tr id="allow_sub_tr" <?php if($data->mm_type == 0) echo 'style="display:none"'?>>
					<th scope="row"><label for="allow_sub_user"><input <?php if($data->mm_allow_subuser == 1) echo 'checked="checked"'; ?> type="checkbox" name="allow_sub_user" value="1" /> Allow sub user *</label></th>
					<td>
						
					</td>
				</tr>
				<!--
				<tr id="number_sub_tr" <?php if($data->mm_type == 0 || $data->mm_allow_subuser == 0) echo 'style="display:none"'?>>
					<th scope="row"><label for="number_user">Number of sub user</label></th>
					<td>
						<input type="radio" <?php if($data->mm_status_subuser == 'unlimited' || $data->mm_status_subuser == '') echo 'checked="checked"'; ?> name="status_number_user" value="unlimited" /> Unlimited<br />
						<input type="radio" <?php if($data->mm_status_subuser == 'custom') echo 'checked="checked"'; ?> name="status_number_user" value="custom" /> <input type="text" size="8" name="number_user" value="<?php echo $data->mm_number_subuser ?>"  /> <br />
						<span>Number of sub user allowed to join per level</span>
					</td>
				</tr>
				-->
				<tr id="number_sub_tr" <?php if($data->mm_type == 0 || $data->mm_allow_subuser == 0) echo 'style="display:none"'?>>
					<th scope="row"><label for="number_user">Number of sub user</label></th>
					<td>
						<div id="payment_list">
						<?php if($data->mm_allow_subuser == 1): ?>
							<?php foreach($data_sub as $vals): $i++; ?>
								<div id="pay_<?php echo $i ?>">
									<input type="text" size="6" value="<?php echo $vals->from_user ?>" name="edit_from_user[<?php echo $vals->mm_subuser_id ?>]" /> to <input type="text" value="<?php echo $vals->to_user ?>" size="6" name="edit_to_user[<?php echo $vals->mm_subuser_id ?>]" /> Users &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<span <?php if($data->mm_type == 2) echo 'style="display:none"' ?> class="initial"> Initial Fee <input type="text" size="6" value="<?php echo $vals->onetime_fee ?>" name="edit_list_amount[<?php echo $vals->mm_subuser_id ?>]"> USD</span>
									<span <?php if($data->mm_type == 1) echo 'style="display:none"' ?> class="recurring"> &nbsp;&nbsp;&nbsp;&nbsp; Recurring Fee <input type="text" size="6" value="<?php echo $vals->recurring_fee ?>" name="edit_list_amount[<?php echo $vals->mm_subuser_id ?>]"> USD </span>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
						</div>
						<input type="hidden" name="loop" id="loop" value="1" />
						<input type="button" name="add_payment" value="Add Payment" />
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

<script type="text/javascript">
jQuery(document).ready(function($){
	$('#member_type').change(function(){

		$('#allow_sub_tr').show();
		show_hide_sub_user();

		if( $('#member_type').val() == '0' ){
			$('#duration_tr').hide();
			$('#onetime_tr').show();
			$('#recuring_tr').hide();
			$('#allow_sub_tr').hide();
			$('#number_sub_tr').hide();
		}
		else if( $('#member_type').val() == '1' ){
			$('#duration_tr').show();
			$('#onetime_tr').show();
			$('#recuring_tr').hide();
			$('.recurring').hide();
			$('.initial').show();
		}
		else if( $('#member_type').val() == '2' ){
			$('#duration_tr').show();
			$('#onetime_tr').hide();
			$('#recuring_tr').show();
			$('.recurring').show();
			$('.initial').hide();
		}
		else if( $('#member_type').val() == '3' ){
			$('#duration_tr').show();
			$('#onetime_tr').show();
			$('#recuring_tr').show();
			$('.recurring').show();
			$('.initial').show();
		}
	});

	$('input[name=status_number_user]').change(function(){
		if($(this).val() == 'unlimited'){
			$('input[name=number_user]').attr('disabled','disabled');
		}
		else
			$('input[name=number_user]').attr('disabled',false);
	});

	$('input[name=allow_sub_user]').change(function(){
		show_hide_sub_user();
	})

	$('input[name="add_payment"]').click(function(){
		var loop = $('#loop').val();
		loop++;
		$('#loop').val(loop);
		var html = '<div id="pay_'+loop+'">';
		html += '<input type="text" size="6" name="from_user['+loop+']" /> to <input type="text" size="6" name="to_user['+loop+']" /> Users &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		
		var initial="",recurring = "";
		if( $('#member_type').val() == '1' ){
			recurring = "style='display:none;'";
		}
		else if( $('#member_type').val() == '2' ){
			initial = "style='display:none;'";
		}

		html += ' <span '+initial+' class="initial">Initial Fee <input type="text" size="6" name="add_amount_onetime['+loop+']"> USD</span>';
		html += ' &nbsp;&nbsp;&nbsp;&nbsp;';
		html += ' <span '+recurring+' class="recurring">Recurring Fee <input type="text" size="6" name="add_amount_recurring['+loop+']"> USD</span>';
		
		html += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="button" value="x" onclick="removeLoop('+loop+')" />';
		html += '</div>';

		$('#payment_list').append(html);
	});

	function show_hide_sub_user(){
		if($('input[name=allow_sub_user]').is(':checked')){
			$('#number_sub_tr').show();
			$('#td_amount').hide();
			$('#onetime_tr').hide();
		}
		else{
			$('#number_sub_tr').hide();
			$('#td_amount').show();
			$('#onetime_tr').show();
		}
	}
});

function removeLoop(id){
	jQuery('#pay_'+id).remove();
}
</script>