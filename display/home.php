<div class='valign-wrapper full-height'>
	<div class='valign full-width'>
		<h1 class='valign align-center'><?php echo $sitename; ?> Support System</h1>
		<div class='container'>
			<div class='row'>
				<div class='col-sm-3 col-sm-offset-2'>
					<a href='javascript:openOverlay("open-ticket");' class='btn btn-primary center-block'>Open New Ticket</a> 
				</div>
				<div class='col-sm-3 col-sm-offset-2'>
					<a href='javascript:openOverlay("see-tickets");' class='btn btn-primary center-block'>See Your Tickets</a> 
				</div>
			</div>
		</div>
	</div>
</div>
<div class='overlay' id='open-ticket'>
	<div class='text-center full-width'>
		<a href='javascript:closeOverlay();'><i class='pe-7s-close pe-4x'></i></a>
		<h2>Open A New Ticket</h2>
		<div class='container'>
			<form method='post' class='row'>
				<div class='form-group col-md-6'>
					<input type='text' class='form-control' name='first_name' placeholder='Your First Name...'>
				</div>
				<div class='form-group col-md-6'>
					<input type='text' class='form-control' name='last_name' placeholder='Your Last Name...'>
				</div>
				<div class='form-group col-md-6'>
					<input type='text' class='form-control' name='email' placeholder='Your E-Mail Address...'>
				</div>
				<div class='form-group col-md-6'>
					<input type='text' class='form-control' name='website' placeholder='Your Website Address...'>
				</div>
				<div class='form-group col-md-12 align-left'>
					<select name='urgency' class='form-control'>
							<option default>Choose Importance Level</option>
							<option value=1>Urgent</option>
							<option value=2>Normal</option>
							<option value=3>Query</option>
					</select>
				</div>
				<?php if (isset($customs[0]['name'])) { foreach ($customs as $custom) { ?>
					<div class='form-group col-md-12 align-left'>
					<?php if ($custom['type']=="text") { ?>
						<input type='text' name='<?php echo $custom['name']; ?>' class='form-control' placeholder='<?php echo $custom['label']; ?>'>
					<?php } else if ($custom['type']=="select") { ?>
						<select class='form-control' name='<?php echo $custom['name']; ?>'>
							<option default><?php echo $custom['label']; ?></option>
							<?php foreach ($custom['values'] as $name=>$show) { echo "<option value='{$name}'>{$show}</option>"; } ?>
						</select>
					<?php } else if ($custom['type']=="radio") { ?>
						<label><?php echo $custom['label']; ?></label><br />
						<?php foreach ($custom['values'] as $name=>$show) { echo "<input type='radio' name='{$custom['name']}' value='{$name}'>{$show} "; } ?>
					<?php } else if ($custom['type']=="checkbox") { ?>
						<label><?php echo $custom['label']; ?></label><br />
						<?php foreach ($custom['values'] as $name=>$show) { echo "<input type='checkbox' name='{$custom['name']}' value='{$name}'>{$show} "; } ?>
					<?php } ?>
					</div>
				<?php } } ?>
				<div class='form-group col-md-12'>
					<textarea class='form-control' rows=8 name='message' placeholder='Describe your issue in detail here...'></textarea>
				</div>
				
				<button type='submit' class='btn btn-primary center-block'>Submit</button>
			</form>
		</div>
	</div>
</div>
<div class='overlay' id='see-tickets'>
	<div class='text-center full-width'>
		<a href='javascript:closeOverlay();'><i class='pe-7s-close pe-4x'></i></a>
		<h2>See Your Tickets</h2>
		<div class='container'>
			<form method='post' class='form-inline'>
				<div class='form-group'>
					<input type='text' class='form-control' name='ticket_id' placeholder='Your Unique Ticket ID:'>
				</div>
				<div class='form-group'>
					<input type='text' class='form-control' name='email' placeholder='Your Associated E-Mail ID:'>
				</div>
				<button type='submit' class='btn btn-primary'>Check</button>
			</form>
		</div>
	</div>
</div>