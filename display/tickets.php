<div class='container'>
	<div class='row'>
		<div class='thread-head valign-wrapper'>
			<div class='valign full-width'>
				<h1 class='align-center'>Support Tickets</h1>
				<div class='container'>
					<div class='row'>
						<div class='col-md-8 col-md-offset-2 col-xs-12'>
							<ol class="breadcrumb">
							    <li><a href="<?php echo $url; ?>">Home</a></li>
							    <li class='active'>Admin Panel</li>
							    <span class='pull-right hidden-sm hidden-xs'>Logged in as <?php echo $_COOKIE['admin']; ?></span>
							</ol>
							Navigation: <a href="<?php echo $url; ?>admin/tickets.php" class='btn btn-info'>Unsolved Tickets</a> <a href="<?php echo $url; ?>admin/solved.php" class='btn btn-info'>Solved Issues</a> <a href='javascript:openOverlay("custom-fields");' class='btn btn-info'>Manage Custom Fields</a> <a href='javascript:openOverlay("add-custom-fields");' class='btn btn-info'>Add Custom Field</a> <a href="<?php echo $url; ?>admin/logout.php" class='btn btn-info'>Logout</a>
							<h2><?php echo $active; ?></h2>
							<hr />
							<?php if (isset($error)) { echo "<div class='alert alert-danger'><strong>Error:</strong> {$error}</div>"; } ?>
							<?php if (isset($success)) { echo "<div class='alert alert-success'><strong>Yay!</strong> {$success}</div>"; } ?>
							<div class='table-responsive'>
							<table class="table table-striped tickets-table">
								<thead>
									<tr>
										<th>Ticket Number</th>
										<th>From</th>
										<th>Urgency</th>
										<?php foreach ($customs as $custom) { echo "<th>{$custom['name']}</th>"; } ?>
										<th>Thread</th>
										<th>Close</th>
									</tr>
								</thead>
								<tbody>
								<?php foreach ($tickets as $ticket) { ?>
									<tr>
										<td>#<?php echo $ticket['id']; ?></td>
										<td><?php echo $ticket['name']; ?></td>
										<td><?php if ($ticket['urgency']==1) echo "Urgent"; else if ($ticket['urgency']==2) echo "Normal"; else if ($ticket['urgency']==3) echo "Query"; ?></td>
										<?php foreach ($customs as $key=>$custom) { if (isset($ticket['custom'][$custom['name']])) echo "<td>{$ticket['custom'][$custom['name']]}</td>"; else echo "<td>&nbsp;</td>"; } ?>
										<td><a href='<?php echo $url."ticket/{$ticket['id']}/"; ?>' class='btn btn-primary btn-sm no-expand'>View</a></td>
										<td><a href='<?php echo $url."ticket/{$ticket['id']}/close/"; ?>' target='_blank' class='btn btn-primary btn-sm no-expand'>Close</a></td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
							</div>
							<br />
							<?php if ($total>1) { ?><nav>
								<ul class='pagination'>
									<li>
								        <a href="<?php echo $url."admin/{$prev}/{$paginator}.php"; ?>"<?php if ($prev==$page) { ?> class='disabled'<?php } ?> aria-label="Previous">
								        	<span aria-hidden="true">&laquo;</span>
								        </a>
								    </li>
									<?php for ($f=1;$f<=$total;$f++) { ?>
									    <li class="<?php if ($page==$f) { ?>active<?php } ?>"><a href="<?php echo $url."admin/{$f}/{$paginator}.php"; ?>"><?php echo $f; ?></a></li>
									<?php } ?>
									<li>
								        <a href="<?php echo $url."admin/{$next}/{$paginator}.php"; ?>"<?php if ($next==$page) { ?> class='disabled'<?php } ?> aria-label="Next">
								        	<span aria-hidden="true">&raquo;</span>
								        </a>
								    </li>
								</ul>
							</nav><?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class='overlay' id='custom-fields'>
	<div class='text-center full-width'>
		<a href='javascript:closeOverlay();'><i class='pe-7s-close pe-4x'></i></a>
		<h2>Custom Fields</h2>
		<div class='container'>
			<div class='row'>
				<div class='col-md-10 col-md-offset-1'>
					<div class='table-responsive'>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Type</th>
									<th>Label</th>
									<th>Name</th>
									<th>Values</th>
									<th>Delete</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($customs as $custom) { ?>
								<tr id='show-<?php echo $custom['name']; ?>'>
									<td><?php echo $custom['type']; ?></td>
									<td><?php echo $custom['label']; ?></td>
									<td><?php echo $custom['name']; ?></td>
									<td><?php if (is_array($custom['values'])) echo json_encode($custom['values']); else $custom['values']; ?></td>
									<td><a href='javascript:showForm("<?php echo $custom['name']; ?>");' class='btn btn-primary btn-sm no-expand'>Edit</a> <a href='<?php echo $url."admin/delete/{$custom['name']}/"; ?>' class='btn btn-primary btn-sm no-expand'>Delete</a></td>
								</tr>
								<tr style='display:none;' id='form-<?php echo $custom['name']; ?>'>
									<form method='post' action='<?php echo $url; ?>admin/tickets.php'>
									<input type='hidden' name='edit' value=1>
									<td>
										<select name='type' class='form-control'>
											<option value='text' <?php if ($custom['type']=="text") echo "selected"; ?>>text</option>
											<option value='radio' <?php if ($custom['type']=="checkbox") echo "selected"; ?>>checkbox</option>
											<option value='checkbox' <?php if ($custom['type']=="radio") echo "selected"; ?>>radio</option>
											<option value='select' <?php if ($custom['type']=="select") echo "selected"; ?>>select</option>
										</select>
									</td>
									<td><input type='text' name='label' value='<?php echo $custom['label']; ?>' class='form-control'></td>
									<td><input type='hidden' value='<?php echo $custom['name']; ?>' name='name'><input type='text' value='<?php echo $custom['name']; ?>' disabled class='form-control'></td>
									<td><textarea name='customs' rows=5 class='form-control'><?php if (is_array($custom['values'])) echo json_encode($custom['values']); else $custom['values']; ?></textarea></td>
									<td><button type='submit' class='btn btn-primary btn-sm no-expand'>Edit</button></td>
									</form>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class='overlay' id='add-custom-fields'>
	<div class='text-center full-width'>
		<a href='javascript:closeOverlay();'><i class='pe-7s-close pe-4x'></i></a>
		<h2>Add Custom Field</h2>
		<div class='container'>
			<form method='post' action='<?php echo $url."admin/tickets.php"; ?>' class='row'>
				<input type='hidden' name='add' value=1>
				<div class='form-group col-md-12'>
					<input type='text' class='form-control' name='label' placeholder='Label displayed for the field:'>
				</div>
				<div class='form-group col-md-12'>
					<input type='text' class='form-control' name='name' placeholder='Name for the field:'>
				</div>
				<div class='form-group col-md-12'>
					<textarea name='customs' class='form-control' rows=6 placeholder='A JSON encoded string of default values in case of checkbox, radio and select. A simple string for text field. JSON string should be in following format: {"value-1":"display-1","value-2":"display-2"}'></textarea>
				</div>
				<div class='form-group col-md-12'>
					<select name='type' class='form-control'>
						<option value='select'>Select List</option>
						<option value='checkbox'>Checkbox</option>
						<option value='radio'>Radio</option>
						<option value='text'>Text field</option>
					</select>
				</div>
				<button type='submit' class='btn btn-primary'>Add Custom Field</button>
			</form>
		</div>
	</div>
</div>

<script>
function showForm(name) {
	$("#show-"+name).velocity("fadeOut",{duration:300});
	$("#form-"+name).velocity("fadeIn",{duration:600});
}
</script>