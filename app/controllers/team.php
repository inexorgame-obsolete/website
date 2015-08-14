<?php
namespace Controllers;
use \Curl\Curl;

class Team {
	public function index()
	{
		if (!\Cache::instance()->exists('members')) {
			$curl = new Curl;
			$members = $curl->get('https://api.github.com/orgs/inexor-game/members');
			\Cache::instance()->set('members', $members);
		} else {
			$members = \Cache::instance()->get('members');
		}

		\Base::instance()->set('content', 'team.htm');
		\Base::instance()->set('members', $members);
		
		echo \View::instance()->render('layout.htm');
	}
}