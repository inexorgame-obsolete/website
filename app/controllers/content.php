<?php
namespace Controllers;

class Content {
	public function index()
	{
		\Base::instance()->set('content', 'content.htm');
	
		echo \View::instance()->render('layout.htm');
	}
}
