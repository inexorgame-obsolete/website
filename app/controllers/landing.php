<?php
namespace Controllers;

class Landing {
	public function index() {
				
		// Retrieve the current release URL using Github's API
		if (! \Cache::instance()->exists('release')) {
			$release = \Helpers\Release::instance();
			\Cache::instance()->set('release', $release, 86400); //TTL = 1 day
		} else {
			$release = \Cache::instance()->get('release');
		}
		
		\Base::instance()->set('release_url', $release->html_url);
	}
	
	public function afterRoute() {
		echo \Template::instance()->render('landing.htm');
	}
}