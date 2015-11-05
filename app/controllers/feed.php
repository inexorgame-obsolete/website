<?php
namespace Controllers;
use PicoFeed\Syndication;
use Helpers;

class Feed {
	private $_posts = array();

	public function __construct()
	{
		// TODO: This could be cleaned up and cache could be added (add base_uri)
		$entries = \Helpers\Posts::instance()->getLatest(15);
			
		foreach($entries as $entry) {
			$meta = new \Helpers\Post($entry);
			
			// Get the markdown stuff
			$content = \Markdown::instance()->convert($meta->content);
			
			$item = array(
				'title' => $meta->title,
				'updated' => strtotime($meta->date),
				'url' => $uri . $meta->link,
				'content' => $content,
				'author' => array(
					'name' => $meta->author
				)
			);
				
			array_push($this->_posts, $item);
		}	
	}
	
	public function index()
	{
		// Execute RSS as standard
		$this->rss();
	}
	
	public function rss() {
		// Setup an RSS writer
		$writer = new Syndication\Rss20();
		$writer->title = "Inexor";
		$writer->description = "Stays sauer. Becomes better";
		
		// The currently used uri
		$f3 = \Base::instance();
		$uri = $f3->get('SCHEME').'://'.$f3->get('HOST').':'.$f3->get('PORT').$f3->get('BASE').'/';
		$writer->site_url = $uri;
		$writer->feed_url = $uri . 'feed/rss';
		
		$posts = \Helpers\Posts::instance()->getLatest(15);
		$writer->items = $this->_posts;
		
		echo $writer->execute();
	}
	
	public function atom() {
		// Setup an RSS writer
		$writer = new Syndication\Atom();
		$writer->title = "Inexor";
		$writer->description = "Stays sauer. Becomes better";
		
		// The currently used uri
		$f3 = \Base::instance();
		$uri = $f3->get('SCHEME').'://'.$f3->get('HOST').':'.$f3->get('PORT').$f3->get('BASE').'/';
		$writer->site_url = $uri;
		$writer->feed_url = $uri . 'feed/atom';
		
		$posts = \Helpers\Posts::instance()->getLatest(15);
		$writer->items = $this->_posts;
		
		echo $writer->execute();
	}

}