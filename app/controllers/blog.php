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
			$entries[] = array("title" => $meta->title, "link" => $meta->link, "date" => $meta->date);
		}
		
		\Base::instance()->set("entries", $entries);		
		echo \View::instance()->render('layout.htm');
	}
	
	public function year()
	{
		$params = \Base::instance()->get('PARAMS.year');
		var_dump($params);
	}
}