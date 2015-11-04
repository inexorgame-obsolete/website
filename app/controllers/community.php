<?php
namespace Controllers;

class Community {
	public function index()
	{
		// Since this is static content we don't need to do anything especially
		\Base::instance()->set('content', 'community.htm');
		echo \View::instance()->render('layout.htm');
		
	}
}