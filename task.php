<?php
/**
 * @package		ResponsiveTester
 * @copyright	Copyright (C) 2013 Syahril Zulkefli. All rights reserved.
 * @license		http://opensource.org/licenses/MIT
 */

require 'functions.php';

// get the task
$task = filter_input(INPUT_GET, 'task', FILTER_SANITIZE_STRING);
$id   = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

switch ($task) {

	// get the theme details
	case 'build':
		$config = read_config();
		$data = array(
			'url' => rtrim($config['base_url'], '/').'/'.$id
		);
		echo json_encode($data);
		break;

	// build the theme
	case 'detail':
		echo json_encode(get_builds($id));
		break;

	// save current theme
	case 'set':
		set_current_theme($id);
		break;

	// save config
	case 'save':
		$data = array(
			'base_path' => filter_input(INPUT_GET, 'base_path', FILTER_SANITIZE_URL),
			'base_url' => filter_input(INPUT_GET, 'base_url', FILTER_SANITIZE_URL),
			'excludes' => filter_input(INPUT_GET, 'excludes', FILTER_SANITIZE_STRING),
		);
		save_configs($data);

		$config = read_config();
		echo json_encode($config);
		break;	

	// get config
	case 'config':
		$config = read_config();
		echo json_encode($data);
		break;

	default:
		die;
}
