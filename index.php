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
$f3->route('GET /@controller', 'Controllers\@controller->index');
$f3->route('GET /@controller/@action', 'Controllers\@controller->@action');
$f3->route('GET /@controller/@action/@param', 'Controllers\@controller->@action');

/*
$f3->route('GET /', function($f3) {
	$getPosts = new posts;
	
	$posts = $getPosts->get_latest(5); 
	
	$entries = array();
	
	foreach($posts AS $post)
	{
		$meta = new readPost($post);
		$entries[] = array("title" => $meta->get_title(), "link" => $meta->get_link(), "date" => $meta->get_date());
	}
	
	$f3->set("entries", $entries);	
	echo View::instance()->render('index.htm');
});

$f3->route('GET / [ajax]', function($f3) {
	
});

$f3->route('GET /blog/@year/@entry', function($f3) {
	$entry = $f3->clean($f3->get('PARAMS.entry'));
	$year = $f3->clean($f3->get('PARAMS.year'));
	
	$filePath = "data/post/" . $year . "/" . $entry . ".md";
	
	if (file_exists($filePath)) 
	{
		//$details = explode("-", shell_exec('cd data; git log --pretty="format:%an - %ad" '.$entry.'.md'));
		
		$meta = new readPost($filePath);
		
		$f3->set("author", $meta->get_author());
		$f3->set("title", $meta->get_title());
		$f3->set("date", $meta->get_date());
		$f3->set("article",  $meta->get_article());
		echo View::instance()->render($meta->get_template().".htm");
	} else {
		$f3->reroute('/');
	}
});

$f3->route("GET /blog", function($f3) {
	$f3->reroute("/"); // Placeholder
});


$f3->route("GET /blog/@year", function($f3) {
	$f3->reroute("/"); // Placeholder
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
	
	$getPosts = new posts;
	
	$posts = $getPosts->get_latest(15); 
	
	foreach($posts AS $post)
	{
		$meta = new readPost($post);
		
		// Get the markdown stuff
 		$content = Markdown::instance()->convert($meta->get_article());
		
		$item = array(
			'title' => $meta->get_title(),
			'updated' => strtotime($meta->get_date()),
			'url' => $uri . 'blog/'.$meta->get_link(),
			'content' => $content,
			'author' => array(
				'name' => $meta->get_author()
			)	
		);
		
		array_push($writer->items, $item);
	}
	
	echo $writer->execute();
});*/

$f3->run();
