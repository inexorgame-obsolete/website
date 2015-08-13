<?php
namespace Controllers;

class Blog {
	
	public function index()
	{
		//\Base::instance()->set('content', 'dashboard/board.htm');
		
		echo \View::instance()->render('layout.htm');
	}
}