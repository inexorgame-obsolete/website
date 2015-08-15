<?php

// Composer autoloader for required packages and dependencies
require_once('lib/autoload.php');
$f3 = \Base::instance();

$f3->set('DEBUG',1);
if ((float)PCRE_VERSION<7.9)
	trigger_error('PCRE version is out of date');

// Load configuration
$f3->config('config.ini');

/*require_once("app/readPost.php");
require_once("app/posts.php");*/

$f3->route('GET /', 'Controllers\Blog->index');
$f3->route('GET /blog/@year', 'Controllers\Blog->year');
$f3->route('GET /blog/@year/@entry', 'Controllers\Blog->year');
$f3->route('GET /@controller', 'Controllers\@controller->index');
$f3->route('GET /@controller/@action', 'Controllers\@controller->@action');
$f3->route('GET /@controller/@action/@param', 'Controllers\@controller->@action');

/*

$f3->route('GET / [ajax]', function($f3) {
	
});
	
$f3->route('GET|POST /preview', function($f3) {
	$content = (null !== $f3->get('POST.content')) ? $f3->get('POST.content') : 'No content available.';
	$f3->set('content', $content);
	echo View::instance()->render('preview.htm');
});
	
*/

$f3->run();
