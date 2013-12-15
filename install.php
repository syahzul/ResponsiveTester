<?php
/**
 * @package		ResponsiveTester
 * @copyright	Copyright (C) 2013 Syahril Zulkefli. All rights reserved.
 * @license		http://opensource.org/licenses/MIT
 */
 
require 'functions.php'; 
$done = false;
if ($_POST) {
	$data = array(
		'base_path' => filter_input(INPUT_POST, 'base_path', FILTER_SANITIZE_URL),
		'base_url'  => filter_input(INPUT_POST, 'base_url', FILTER_SANITIZE_URL),
		'excludes'  => filter_input(INPUT_POST, 'excludes', FILTER_SANITIZE_STRING),
	);

	if (create_configs($data)) {
		$done = true;
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Install - BootstrapStyler Tester</title>

		<!-- Bootstrap core CSS -->
		<link href="bootstrap/css/bootstrap.min.css?v=3.0.2" rel="stylesheet">

		<!-- Font Awesome CSS -->
		<link href="fonts/font-awesome/css/font-awesome.min.css?v=4.0.3" rel="stylesheet">

		<!-- Icomoon CSS -->
		<link href="fonts/icomoon/style.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="css/styler/style.css" rel="stylesheet" type="text/css">

		<style>
		body {
			background: #eee;
			text-align: left;
		}

		.container {
			width: 80%;
		}

		.page-title {
			border-bottom: dotted 1px #ccc;
			font-weight: 300;
			margin-bottom: 25px;
			padding-bottom: 10px;
			text-align: center;
		}

		.list-permission li i {
			float: right;
			margin-top: 3px;
		}

		.list-permission li i.green {
			color: green;
		}

		.list-permission li i.red {
			color: red;
		}
		</style>


		<!-- jQuery -->
		<script src="js/libs/jquery-1.10.2.min.js"></script>

		<!-- Bootstrap core JavaScript -->
		<script src="bootstrap/js/bootstrap.min.js?v=3.0.2"></script>

		<script src="js/styler/script.js"></script>
	</head>
	<body>
		<div class="container">
			<?php if ($done) : ?>
			<div class="alert alert-info">Your local project have been setup successfully. Please refresh the page using <code>CMD + R</code> on Mac or <code>F5</code> on PC.</div>
			<?php else : ?>
			<?php if ( ! is_writable(__DIR__)) : ?>
			<div class="alert alert-danger">Current folder is <strong>not writable</strong>, that means installer cannot create the configuration file.</div>
			<?php endif; ?>
			<div class="row">
				<div class="col-sm-9">
					<div class="panel panel-default">
						<div class="panel-body">
							<h1 class="page-title">Configuration</h1>

							<form action="install.php" class="form-horizontal" role="form" method="post">
								<fieldset <?php echo ( ! is_writable(__DIR__)) ? 'class="disabled" disabled' : ''; ?>>
									<div class="form-group">
										<label for="base_path" class="col-sm-2 control-label">The project location</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" value="" id="input_base_path" name="base_path" required>
											<p class="help-block small">Enter the folder location on your computer, make sure it's absolute 
											path to the project folder. For example: <code>/Applications/MAMP/htdocs/themes</code> on Mac OS X or 
											<code>C:\xampp\htdocs\themes</code> on Windows.</p>
										</div>
									</div>

									<div class="form-group">
										<label for="base_url" class="col-sm-2 control-label">The project URL</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="input_base_url" name="base_url" value="" required>
											<p class="help-block small">Enter the URL for the main project, not individual item. For example, 
											if your project URL is <code>http://localhost:8080/themes</code>, enter <code>http://localhost:8080</code> 
											in the field above.</p>
										</div>
									</div>

									<div class="form-group">
										<label for="excludes" class="col-sm-2 control-label">Exclude folders</label>
										<div class="col-sm-10">
											<textarea type="text" class="form-control" rows="5" id="input_excludes" name="excludes"></textarea>
											<p class="help-block small">Enter folder name to be excluded, separate by comma. Please never ever use 
											folder name that contain <code>space</code>.</p>
										</div>
									</div>

									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
											<button type="submit" class="btn btn-primary">Save Configuration</button>
										</div>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Permissions</h3>
						</div>
						<div class="panel-body">
							<ul class="list-unstyled list-permission">
								<li>
									Folder is writable 
									<?php if (is_writable(__DIR__)) : ?>
									<i class="fa fa-check green"></i>
									<?php else : ?>
									<i class="fa fa-times red"></i>
									<?php endif; ?>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</body>
</htmlt>