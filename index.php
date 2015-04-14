<?php

// Kickstart the framework
$f3=require('lib/base.php');

$f3->set('DEBUG',1);
if ((float)PCRE_VERSION<7.9)
	trigger_error('PCRE version is out of date');

// Load configuration
$f3->config('config.ini');

$f3->route('GET /', function($f3) {
		$commits = array_filter(explode("\n", shell_exec('cd data; git log --pretty="format:%an - %ad" --name-only -n 2')));
		$entries = array_chunk($commits, 2);
		$f3->set('entries', $entries);	
		echo View::instance()->render('index.htm');
});

$f3->route('GET / [ajax]', function($f3) {
	
});

$f3->route('GET /blog/@entry', function($f3) {
	$entry = $f3->clean($f3->get('PARAMS.entry'));
	
	if (file_exists('data/' . $entry . '.md')) {
		$f3->set('content', $entry);
		echo View::instance()->render('single-entry.htm');
	} else {
		$f3->error(404);
	}
});

$f3->run();
