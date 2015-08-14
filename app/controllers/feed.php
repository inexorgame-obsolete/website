<?php
namespace Controllers;
use PicoFeed\Syndication;
use Helpers;

class Feed {
	public function index()
	{
		// Setup an RSS writer
		$writer = new Syndication\Rss20();
		$writer->title = "Inexor";
		$writer->description = "Stays sauer. Becomes better";
		
		// The currently used uri
		$f3 = \Base::instance();
		$uri = $f3->get('SCHEME').'://'.$f3->get('HOST').':'.$f3->get('PORT').$f3->get('BASE').'/';
		$writer->site_url = $uri;
		$writer->feed_url = $uri . 'feed';
		
		$posts = \Helpers\Posts::instance()->get_latest(15);

		foreach($posts as $post) {
			$meta = new \Helpers\Post($post);
		
			// Get the markdown stuff
			$content = \Markdown::instance()->convert($meta->get_article());
		
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
	}
}