<div class='container'>
	<div class='row'>
		<div class='thread-head valign-wrapper'>
			<div class='valign full-width'>
				<h1 class='align-center'><?php echo $sitename; ?> Customer Support</h1>
				<div class='container'>
					<div class='row'>
						<div class='col-md-8 col-md-offset-2 col-xs-12'>
							<ol class="breadcrumb">
							    <li><a href="<?php echo $url; ?>">Home</a></li>
							    <li class="active">Ticket</li>
							    <span class='pull-right'>Logged in as <?php if (!isset($_COOKIE['admin'])) echo $thread['name']; else echo $username; ?></span>
							</ol>
							<h2 class='pull-left'>Support Ticket #<?php echo $thread['id']; ?></h2>
							<?php if ($thread['status']==1) { ?><a href='<?php echo $url."ticket/{$thread['id']}/";?>close/' class='pull-right btn btn-primary btn-lg'><i class='pe-7s-power'></i> Close</a><?php } ?>
							<div class='clearfix'></div>
							<hr />
							<?php if (isset($error)) { echo "<div class='alert alert-danger'><strong>Error:</strong> {$error}</div>"; } ?>
							<?php if (isset($success)) { echo "<div class='alert alert-success'><strong>Yay!</strong> {$success}</div>"; } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php foreach ($thread['threads'] as $post) { ?>
			<div class='col-md-2 col-md-offset-2 col-xs-4'>
				<img src='<?php if ($post['posted_by']=="user") echo $user_grav_url; else echo $admin_grav_url; ?>' class='img-responsive'>
			</div>
			<div class='col-md-6 col-xs-8 subtle-bg'>
				<h3 class='small-margin'><?php if ($post['posted_by']=="user") echo $thread['name']; else echo $username; ?></h3>
				<p class='muted-text'>Posted on <?php echo date("j F Y, \a\\t H:m a",$post['time']); ?> <?php if (!empty($post['last_change'])) echo " | <span class='italic'>Last editted on ".date("j F Y, \a\\t H:m a",$post['last_change'])."</span>"; ?></p>
				<blockquote id='hide-<?php echo $post['number']; ?>'><?php echo $post['message']; ?></blockquote>
				<?php if ($thread['status']==1 && $post['posted_by']==$_COOKIE['user'] && ($_COOKIE['email']==$thread['email'] || isset($_COOKIE['admin']))) { ?>
					<div id='edit-<?php echo $post['number']; ?>' style='display:none;'>
						<form method='post' enctype="multipart/form-data" action='<?php echo $url."ticket/{$post['ticket_id']}/"; ?>'>
						<input type='hidden' name='number' value=<?php echo $post['number']; ?>><input type='hidden' name='edit-thread' value=1>
						<blockquote>
							<textarea rows=5 class='form-control' name='msg'><?php echo $post['message']; ?></textarea>
							<br />
							<div class='form-group'>
								<label>Add Attachments:</label>
								<input type='file' name='files[]' multiple=true class='form-control'>
							</div>
							<button type='submit' class='btn btn-primary btn-sm'>Submit</button>
						</blockquote>
						</form>
					</div>
					<p><a href='javascript:edit(<?php echo $post['number']; ?>);' class='btn btn-primary btn-sm'>Edit</a> <a href='<?php echo $url."ticket/{$post['ticket_id']}/delete/thread/{$post['number']}/"; ?>' class='btn btn-info btn-sm'><i class='pe-7s-trash pe-lg'></i></a></p>
				<?php } ?>
				<div class='container-fluid'><div class='row lightbox-gallery'>
				<?php foreach ($post['attachments'] as $attach) {
					echo "<div class='col-md-3 col-sm-4' id='attach-{$attach['id']}'>";
					if ($attach['type']=="image") echo "<a href='{$url}uploads/{$attach['filename']}' class='lightbox-item'><img src='{$url}uploads/th_{$attach['filename']}' class='thumbnail'><p>{$attach['filename']}</p></a>";

					else echo "<a href='{$url}uploads/{$attach['filename']}'><i class='file-icon pe-7s-download'></i><p>{$attach['filename']}</p></a>";
					if ($post['posted_by']==$_COOKIE['user'] && ($_COOKIE['email']==$thread['email'] || isset($_COOKIE['admin']))) echo "<p><a href='javascript:deleteAttachment({$attach['id']});'>Remove</a>";
					echo "</div>";
				} ?>
				</div></div>
			</div>
			<div class='col-md-8 col-md-offset-2 col-xs-12'><div class='end-thread'></div></div>
		<?php } ?>
		<div class='col-xs-12'>&nbsp;</div>
		<?php if ($thread['status']==1 && ($_COOKIE['email']==$thread['email'] || isset($_COOKIE['admin']))) { ?><div class='col-md-8 col-md-offset-2 col-xs-12'>
			<hr />
			<div class='thread-reply full-width'>
				<form method='post' enctype="multipart/form-data" action='<?php echo $url."ticket/".$thread['id']."/"; ?>'>
					<label>Reply to the thread</label>
					<textarea class='form-control' rows=6 name='post' placeholder='Add a reply...'></textarea>
					<div class='form-group'>
						<input type='file' name='files[]' multiple=true class='form-control'>
					</div>
					<p><button type='submit' class='btn btn-primary'>Reply</button></p>
				</form>
			</div>
		</div><?php }  else { ?>
			<div class='col-md-8 col-md-offset-2 col-xs-12'><div class='alert alert-info'>The ticket has been resolved.</div></div>
		<?php } ?>
	</div>
</div>
<script>
function edit(id) {
	$("#hide-"+id).velocity("fadeOut",{duration:100});
	$("#edit-"+id).velocity("fadeIn",{duration:500});
}
function deleteAttachment(id) {
	$.post("<?php echo $url; ?>delete/attachment/",{id:id},function(r){
		
		if (r=="done") { $("#attach-"+id).velocity("fadeOut",{duration:400}); }
		else { alert(r); }
	});
}
</script>