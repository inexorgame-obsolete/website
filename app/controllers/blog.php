<?php
namespace Controllers;

class Blog {
	
	public function index()
	{
		if (!\Cache::instance()->exists('entries')) {
			$posts = \Helpers\Posts::instance()->getLatest(15);
			$entries = array();
			
			foreach($posts as $post)
			{
				$meta = new \Helpers\Post($post);
				$entries[] = $meta;
			}
			
			\Cache::instance()->set('entries', $entries, 600); //TTL = 10 minutes
		} else {
			$entries = \Cache::instance()->get('entries');
		}
		
		\Base::instance()->set('entries', $entries);		
	}
	
	public function year()
	{
		$year = \Base::instance()->get('PARAMS.year');
		$entry = \Base::instance()->get('PARAMS.entry');

		$post = new \Helpers\Post($year . '/' . $entry . '.md');
		\Base::instance()->set('post', $post);
	}
}