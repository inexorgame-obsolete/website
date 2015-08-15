<?php
namespace Controllers;
use PicoFeed\Reader\Reader;
use PicoFeed\PicoFeedException;

class Content {
	public function index()
	{
		try {
			$reader = new Reader;
		
			// Return a resource
			if (!\Cache::instance()->exists('resource')) {
				$resource = $reader->download('https://community.inexor.org/category/5.rss');
				\Cache::instance()->set('resource', $resource, 3600);
			} else {
				$resource = \Cache::instance()->get('resource');
			}
		
			// Return the right parser instance according to the feed format
			$parser = $reader->getParser(
					$resource->getUrl(),
					$resource->getContent(),
					$resource->getEncoding()
			);
		
			// Return a Feed object
			$feed = $parser->execute();
		
			// Print the feed properties with the magic method __toString()
			\Base::instance()->set('items', $feed->getItems());
			\Base::instance()->set('content', 'content.htm');
			
			echo \View::instance()->render('layout.htm');
		}
		catch (PicoFeedException $e) {
			// Do Something...
		}
	}
}
