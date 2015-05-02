<?php

// Kickstart the framework
$f3=require('lib/base.php');

// Bootstrap composer
include_once('vendor/autoload.php');

$f3->set('DEBUG',1);
if ((float)PCRE_VERSION<7.9)
	trigger_error('PCRE version is out of date');

// Load configuration
$f3->config('config.ini');

// Only return one entry / file
function filter_entries($entries)
{
	$files = array();
	$filtered_entries = array_filter($entries, function($value) use(&$files) {
		if (in_array($value[1], $files)) {
			return false;
		} else {
			array_push($files, $value[1]);
			return true;
		}
	});
	
	return $filtered_entries;
}

$f3->route('GET /', function($f3) {
		$commits = array_filter(explode("\n", shell_exec('cd data; git log --pretty="format:%an - %ad" --name-only -n 5')));
		$entries = array_chunk($commits, 2);
		$entries = filter_entries($entries);
		$f3->set('entries', $entries);	
		echo View::instance()->render('index.htm');
});

$f3->route('GET / [ajax]', function($f3) {
	
});

$f3->route('GET /blog/@entry', function($f3) {
	$entry = $f3->clean($f3->get('PARAMS.entry'));
	
	if (file_exists('data/' . $entry . '.md')) {
		$details = explode("-", shell_exec('cd data; git log --pretty="format:%an - %ad" '.$entry.'.md'));
		
		$f3->set('details', $details);
		$f3->set('entry', $entry);
		echo View::instance()->render('single-entry.htm');
	} else {
		$f3->reroute('/');
	}
});

$f3->route('GET|POST /preview', function($f3) {
	$content = (null !== $f3->get('POST.content')) ? $f3->get('POST.content') : 'No content available.';
	$f3->set('content', $content);
	echo View::instance()->render('preview.htm');
});
	
$f3->route('GET /feed', function($f3){
	$writer = new PicoFeed\Syndication\Rss20();
	$writer->title = 'Inexor';
	
	$uri = $f3->get('SCHEME').'://'.$f3->get('HOST').':'.$f3->get('PORT').$f3->get('BASE').'/';
	$writer->site_url = $uri;
	$writer->feed_url = $uri . 'feed';
	
	$commits = array_filter(explode("\n", shell_exec('cd data; git log --pretty="format:%an - %ad" --name-only -n 10')));
	$entries = array_chunk($commits, 2);
	$entries = filter_entries($entries);
	
	foreach($entries as $entry)
	{
		$details = explode("-", $entry[0]);
		$ref = pathinfo($entry[1])['filename'];

		// Get the markdown stuff
		$file = Base::instance()->read('data/' . $ref . '.md');
 		$content = Markdown::instance()->convert($file);
		
		$item = array(
			'title' => $ref,
			'updated' => strtotime($details[1]),
			'url' => $uri . '/blog/'.$ref,
			'content' => $content,
			'author' => array(
				'name' => $details[0]
			)	
		);
		
		array_push($writer->items, $item);
	}
	
	echo $writer->execute();
});

$f3->run();
