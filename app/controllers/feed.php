<?php
namespace Controllers;
use PicoFeed\Syndication;
use Helpers;

class Feed {
	private $_uri;
	private $_posts = array();

	public function __construct()
	{
		// Initialise the base URL. This should be done every single time since http/https could change..
		$f3 = \Base::instance();
		$this->_uri = $f3->get('SCHEME').'://'.$f3->get('HOST').':'.$f3->get('PORT').$f3->get('BASE').'/';
		
		// TODO: This could be cleaned up and cache could be added (add base_uri)
		$entries = \Helpers\Posts::instance()->getLatest(15);
		if (!\Cache::instance()->exists('feeds')) {
			$feeds = array();	
		
			foreach($entries as $entry) {
				$meta = new \Helpers\Post($entry);
			
				// Get the markdown stuff
				$content = \Markdown::instance()->convert($meta->content);
			
				$item = array(
					'title' => $meta->title,
					'updated' => strtotime($meta->date),
					'url' => $this->_uri . $meta->link,
					'content' => $content,
					'author' => array(
						'name' => $meta->author
					)
				);
				
				array_push($feeds, $item);
			}
			
			\Cache::instance()->set('feeds', $feeds, 600); //TTL = 10 minutes
			$this->_posts = $feeds;
		} else {
			$this->_posts = \Cache::instance()->get('feeds');
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
		
		$writer->site_url = $this->_uri;
		$writer->feed_url = $this->_uri . 'feed/rss';
		
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
		$writer->site_url = $this->_uri;
		$writer->feed_url = $this->_uri . 'feed/atom';
		
		$posts = \Helpers\Posts::instance()->getLatest(15);
		$writer->items = $this->_posts;
		
		echo $writer->execute();
	}

}