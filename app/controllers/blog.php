<?php
namespace Controllers;

class Blog {
	
	public function index()
	{
		\Base::instance()->set('content', 'blog.htm');		
		$posts = \Helpers\Posts::instance()->getLatest(15);		
		$entries = array();
		
		foreach($posts as $post)
		{
			$meta = new \Helpers\Post($post);
			$entries[] = array("title" => $meta->title, "link" => $meta->link, "date" => $meta->date, "summary" => $meta->summary);
		}
		
		\Base::instance()->set("entries", $entries);		
		echo \View::instance()->render('layout.htm');
	}
	
	public function year()
	{
		$year = \Base::instance()->get('PARAMS.year');
		$entry = \Base::instance()->get('PARAMS.entry');

		$post = new \Helpers\Post($year . '/' . $entry . '.md');
		\Base::instance()->set('post', $post);
		\Base::instance()->set('content', 'single_page.htm');
		
		echo \View::instance()->render('layout.htm');
	}
}