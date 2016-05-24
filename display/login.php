<div class='container'>
	<div class='row'>
		<div class='thread-head valign-wrapper'>
			<div class='valign full-width'>
				<h1 class='align-center'>Admin Login - Help-PHP</h1>
				<div class='container'>
					<div class='row'>
						<div class='col-md-8 col-md-offset-2 col-xs-12'>
							<ol class="breadcrumb">
							    <li><a href="<?php echo $url; ?>">Home</a></li>
							    <li class="active">Login</li>
							</ol>
							<?php if (isset($error)) { echo "<div class='alert alert-danger'><strong>Error:</strong> {$error}</div>"; } ?>
							<form method='post'>
								<div class='form-group'>
									<input type='text' name='username' placeholder='Your Username...' class='form-control'>
								</div>
								<div class='form-group'>
									<input type='password' name='password' placeholder='Your Password...' class='form-control'>
								</div>
								<button class='btn btn-primary center-block' type='submit'>Login</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
