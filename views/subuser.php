<div>
	<form id="user_form" action="" method="POST">
		<table>
		<tr>
		<td>Username</td>
		<td><input class="autocomplete" type="text" id="user_text" name="user_text" />
		<input type="hidden" id="user_id" name="user_id">
		<input type="submit" name="user_button" value="Add New" /></td>
		</tr>
		</table>
	</form>
	<br />
	<table id="hor-minimalist-a">
		<thead>
		<tr>
			<th>Username</th>
			<th>Action</th>
		</tr>
		</thead>
		<tbody>
			<?php foreach ($results as $r) : ?>
				<tr>
					<td><?php echo get_userdata( $r->user_id )->user_login ; ?></td>
					<td>
						<form action="" method="POST">
						<input type="hidden" name="act" value="delete" />
						<input type="hidden" name="subuser_id" value="<?php echo $r->subuser_id ?>" />
						<input type="submit" value="x" />
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<script type="text/javascript">
jQuery(document).ready(function($){
	$(".autocomplete").autocomplete({
	source: "<?php echo div_billing_pluginurl() ?>front-ajax.php?action=div_billing_user_auto",
	minLength: 2,//search after two characters
	select: function(event,ui){
	    //do something
	    	$('#user_id').val( ui.item.id );
	    }
	});
})
</script>