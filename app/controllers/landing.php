<?php
namespace Controllers;

class Landing {
	public function index() {
		\Base::instance()->set("content", 'landing.htm');
		echo \View::instance()->render('layout.htm');
	}
}