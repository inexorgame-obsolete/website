<?php
namespace Controllers;

class Landing {
	public function index() {
		
		echo \View::instance()->render('landing.htm');
	}
}