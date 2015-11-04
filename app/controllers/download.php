<?php
namespace Controllers;

class Download {
	public function index()
	{	
		\Base::instance()->set('content', 'download.htm');
		echo \View::instance()->render('layout.htm');
	}
}