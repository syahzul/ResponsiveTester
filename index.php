<?php
/**
 * @package		ResponsiveTester
 * @copyright	Copyright (C) 2013 Syahril Zulkefli. All rights reserved.
 * @license		http://opensource.org/licenses/MIT
 */

require 'functions.php';

$localProject 	= false;
$config  		= null;
$folders 		= null;
$initial['url'] = live_site().'/filler.php';

if (file_exists(__DIR__.'/config.ini')) {
	
	// get the configuration values
	$config = read_config();

	// scan the directory
	if ( ! is_dir($config['base_path'])) {
		die('Folder is not exists! '.$config['base_path']);
	}

	$data = get_folders($config);
	$folders = $data['folders'];
	$initial = $data['initial'];

	$localProject = true;
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
		<title>ResponsiveTester</title>

		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto+Slab:400,700,300,100">
		<link href='http://fonts.googleapis.com/css?family=Denk+One' rel='stylesheet' type='text/css'>
		
		<!-- Bootstrap core CSS -->
		<link href="bootstrap/css/bootstrap.min.css?v=3.0.2" rel="stylesheet">

		<!-- Font Awesome CSS -->
		<link href="fonts/font-awesome/css/font-awesome.min.css?v=4.0.3" rel="stylesheet">

		<!-- Icomoon CSS -->
		<link href="fonts/icomoon/style.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="css/style.css" rel="stylesheet" type="text/css">

		<!-- jQuery -->
		<script src="js/jquery-1.10.2.min.js"></script>

		<!-- Bootstrap core JavaScript -->
		<script src="bootstrap/js/bootstrap.min.js?v=3.0.2"></script>

		
		<script>
			var live_site = '<?php echo live_site(); ?>';

			<?php if ($localProject) : ?>
			var cur_theme = '<?php echo strtolower($initial['name']); ?>';
			<?php endif; ?>
		</script>
		
		<script src="js/script.js"></script>
	</head>
	<body>
		<nav class="navbar navbar-fixed-top" role="navigation">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<a class="navbar-brand" href="./"><i class="icon-phone-portrait"></i> ResponsiveTester</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

				<form class="navbar-form navbar-left" role="search">
					<div class="form-group" style="width: 400px;">
						<input type="text" id="site-url" class="form-control" placeholder="Enter URL">
					</div>
					<button type="submit" id="btn-viewsite" class="btn btn-default">View Site</button>
				</form>

				
				<ul class="nav navbar-nav navbar-left">
					<?php if ($localProject) : ?>
					<li class="dropdown theme-selector">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span id="theme-name"><?php echo $initial['name']; ?></span> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<?php foreach ($folders as $name => $url) : ?>
							<li style="<?php echo ($initial['name'] === $name) ? 'display: none' : ''; ?>"><a href="<?php echo $url; ?>" class="theme-item"><?php echo $name; ?></a></li>
							<?php endforeach; ?>
						</ul>
					</li>

					<li><a href="#" id="btn-config" class="btn btn-default" data-toggle="modal" data-target="#config-modal"><i class="fa fa-cogs"></i> Configs</a></li>
					<?php else : ?>
					<li><a href="#" id="btn-install" class="btn btn-danger">Set Local Project &raquo;</a></li>
					<?php endif; ?>
				</ul>

				<ul class="nav navbar-nav navbar-right">
					<li class="active"><a href="#" class="device-button btn btn-sm btn-default" data-toggle="tooltip" title="Device with screen 993px and up" id="lg"><i class="icon-screen"></i></a></li>
					<li><a href="#" class="device-button btn btn-sm btn-default" data-toggle="tooltip" title="Device with screen 992px" id="md"><i class="icon-tablet-landscape"></i></i></a></li>
					<li><a href="#" class="device-button btn btn-sm btn-default" data-toggle="tooltip" title="Device with screen 768px" id="sm"><i class="icon-tablet"></i></i></a></li>
					<li><a href="#" class="device-button btn btn-sm btn-default" data-toggle="tooltip" title="Device with screen 480px" id="xs"><i class="icon-phone"></i></i></a></li>
					<li><a href="#" class="device-button btn btn-sm btn-default" data-toggle="tooltip" title="Device with screen 320px" id="xxs"><i class="icon-phone"></i></i></a></li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</nav>

		
		<?php if ($localProject) : ?>
		<div class="modal fade" id="compile-modal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close close-modal" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Compiled History</h4>
					</div>
					<div class="modal-body">
						
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default close-modal" data-dismiss="modal">Close</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->


		<div class="modal fade" id="config-modal">
			<div class="modal-dialog">
				<form action="task.php">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close close-modal" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Configuration</h4>
					</div>
					<div class="modal-body">
						<?php
						$config = read_config();
						?>
						<div class="form-group">
							<label for="base_path">The project location</label>
							<input type="text" class="form-control" value="<?php echo $config['base_path']; ?>" id="input_base_path" name="base_path">
							<p class="help-block small">Enter the folder location on your computer, make sure it's absolute path the the project folder.</p>
						</div>

						<div class="form-group">
							<label for="base_url">The project URL</label>
							<input type="text" class="form-control" id="input_base_url" name="base_url" value="<?php echo $config['base_url']; ?>">
							<p class="help-block small">Enter the URL for the main project, not individual item. For example, 
							if your project URL is <code>http://localhost:8080/theme</code>, enter <strong>http://localhost:8080</strong> 
							in the field above.</p>
						</div>

						<div class="form-group">
							<textarea type="text" class="form-control" rows="5" id="input_excludes" name="excludes" placeholder="Folder to exclude"><?php echo $config['excludes']; ?></textarea>
							<p class="help-block small">Enter folder name to be excluded, separate by comma. Please never ever use folder name that contain <code>space</code>, pretty please, with sugar on top.</p>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default close-modal" data-dismiss="modal">Close</button>
						<button type="submit" id="btn-save-config" class="btn btn-primary">Save Config</button>
					</div>
				</div><!-- /.modal-content -->
				</form>
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<?php endif; ?>

		<iframe id="site" src="<?php echo $initial['url']; ?>"></iframe>

		<footer class="row">
			<div class="col-md-6">
				&copy; <a href="http://www.syahzul.com">Syahril Zulkefli</a>
			</div>
			<div class="col-md-6 align-right">
				<ul class="list-inline">
					<li><a href="https://github.com/syahzul"><i class="fa fa-github-square"></i></a></li>
					<li><a href="https://www.facebook.com/syahzul"><i class="fa fa-facebook-square"></i></a></li>
					<li><a href="https://twitter.com/syahzul"><i class="fa fa-twitter-square"></i></a></li>
					<li><a href="http://my.linkedin.com/in/syahzul/"><i class="fa fa-linkedin-square"></i></a></li>
				</ul>
			</div>
		</footer>
	</body>
</html>