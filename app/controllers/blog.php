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
				$meta->preview = \Helpers\Text::instance()->preview($meta->content);
				$entries[] = $meta;
			}
			
			\Cache::instance()->set('entries', $entries, 600); //TTL = 10 minutes
		} else {
			$entries = \Cache::instance()->get('entries');
		}
		
		\Base::instance()->set('entries', $entries);
		\Base::instance()->set('content', 'blog.htm');
		\Base::instance()->set('title', 'Blog');
	}
	
	public function entry() {
		$year = \Base::instance()->get('PARAMS.year');
		$entry = \Base::instance()->get('PARAMS.entry');
		
		$post = new \Helpers\Post($year . '/' . $entry . '.md');
		\Base::instance()->set('post', $post);
		\Base::instance()->set('content', 'single_page.htm');
		\Base::instance()->set('title', $post->title);		
	}
	
	public function year()
	{
		$year = \Base::instance()->get('PARAMS.year');
		
		if (!\Cache::instance()->exists('year_entries_'.$year)) {
			$posts = \Helpers\Posts::instance()->getPostsByYear($year);
			$entries = array();

			foreach($posts as $post)
			{
				$meta = new \Helpers\Post($post);
				$meta->preview = \Helpers\Text::instance()->preview($meta->content);
				$entries[] = $meta;
			}

			\Cache::instance()->set('year_entries_'.$year, $entries, 600); //TTL = 10 minutes
		} else {
			$entries = \Cache::instance()->get('year_entries_'.$year);
		}
		
		\Base::instance()->set('entries', $entries);
		\Base::instance()->set('content', 'blog.htm');
		\Base::instance()->set('title', 'Blog');
	}
	
	public function afterRoute() {
		echo \Template::instance()->render('layout.htm');
	}
}
