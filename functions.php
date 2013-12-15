<?php
/**
 * @package		ResponsiveTester
 * @copyright	Copyright (C) 2013 Syahril Zulkefli. All rights reserved.
 * @license		http://opensource.org/licenses/MIT
 */

function read_config()
{
	$file = __DIR__.'/config.ini';
	if ( ! file_exists($file)) {
		die('Config file not exists!');
	}
	return parse_ini_file($file);
}

function get_folders($config)
{
	$folders = array();
	$initial = array();

	// scan the directory
	$results = scandir($config['base_path']);

	// get folder to exclude
	$excludes = str_replace(' ', '', $config['excludes']);

	// populate to array
	$blacklisted = explode(',', $excludes);

	// set the initial theme
	if ( ! empty($config['current_theme'])) {
		$initial = array(
			'url' => $config['base_url'].'/'.$config['current_theme'],
			'name' => ucfirst($config['current_theme'])
		);
	}

	$output = array();

	foreach ($results as $result) {
		$result = ltrim(rtrim($result));

		if ($result === '.' OR $result === '.DS_Store' OR $result === '.htaccess' OR $result == '..') {
			continue;
		}
		elseif (is_dir($config['base_path'] . '/' . $result) AND $result !== '..' AND !in_array($result, $blacklisted)) {
			$name = ucfirst($result);
			$folders[$name] = $config['base_url'].'/'.$result;

			if (! count($initial)) {
				$initial['url'] = $folders[$name];
				$initial['name'] = $name;
			}
		}
	}
	$output['folders'] = $folders;
	$output['initial'] = $initial;
	return $output;
}

function get_builds($id)
{
	$config = read_config();
	$results = scandir($config['base_path'].'/'.$id);

	$output = array();
	$count = 0;
	foreach ($results as $result) {

		// get the file info
		$file = pathinfo($config['base_path'].'/'.$result);

		// do we found the $id in the file name?
		$found = strpos($file['filename'], $id, 0);


		// make sure the extension is zip and have $id in the string
		if ( ! empty($file['extension']) AND $file['extension'] === strtolower('zip') AND $found !== false) {

			// extract date from the file
			$parts = explode('_', $result);

			// re-construct date
			//$date = date ("F d Y H:i:s.", filemtime($config['base_path'].'/'.$result));

			// set the info 
			$output[$count] = array(
				'name' => $result,
				//'date' => $date,
				'url'  => $config['base_url'].'/'.$id.'/'.$result
			);
			//$output[]['filesize'] = stat
			$count++;
		}
	}
	return $output;
}

function set_current_theme($name)
{
	$config = read_config();
	$config['current_theme'] = $name;

	$base_path = rtrim($config['base_path'], '/');
	$file = __DIR__.'/config.ini';

	$data = prepare_config($config);
	pr($data);
	die;
	write_config($file, $data);
}

function save_configs($data)
{
	$config 			 = read_config();
	$config['base_path'] = $data['base_path'];
	$config['base_url']  = $data['base_url'];
	$config['excludes']  = str_replace("\n", ',', $data['excludes']);

	$base_path = rtrim($config['base_path'], '/');
	$file = __DIR__.'/config.ini';

	$data = prepare_config($config);
	write_config($file, $data);
}

function create_configs($data)
{
	$config['base_path'] 	 = $data['base_path'];
	$config['base_url']  	 = $data['base_url'];
	$config['excludes']  	 = str_replace("\n", ',', $data['excludes']);
	$config['current_theme'] = '';

	$base_path = rtrim($config['base_path'], '/');
	$file = __DIR__.'/config.ini';

	$data = prepare_config($config);
	if (write_config($file, $data)) {
		return true;
	}
	return false;
}

function write_config($file, $data) 
{
	$fp = fopen($file, 'w');
	if (fwrite($fp, $data)) {
		fclose($fp);
		return true;
	}
	return false;
}

function prepare_config($config)
{
	$buffer = '';
	foreach ($config as $key => $value) {
		$buffer .= $key . " = " . str_replace(' ', '', $value) . "\n";
	}

	return $buffer;
}

function live_site()
{
	$request = str_replace('index.php', '', $_SERVER['REQUEST_URI']);
	return 'http'.(empty($_SERVER['HTTPS']) ? '' : 's').'://'.$_SERVER['SERVER_NAME'].$request;
}

function pr($data) {
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}