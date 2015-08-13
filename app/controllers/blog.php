<?php
namespace Controllers;

class Blog {
	
	public function index()
	{
		\Base::instance()->set('content', 'blog.htm');
		
		echo \View::instance()->render('layout.htm');
	}
}