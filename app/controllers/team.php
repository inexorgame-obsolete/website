<?php
namespace Controllers;
use \Curl\Curl;

class Team {
	public function index()
	{
		if (!\Cache::instance()->exists('members')) {
			$curl = new Curl;
			$members = $curl->get('https://api.github.com/orgs/inexor-game/members');
			
			$teamConfig = \Helpers\Team::instance()->config;
			
			\Cache::instance()->set('teamConfig', $teamConfig, 3600);
			\Cache::instance()->set('members', $members, 3600); //TTL = 1h
		} else {
			$members = \Cache::instance()->get('members');
			$teamConfig = \Cache::instance()->get('teamConfig');
		}

		\Base::instance()->set('content', 'team.htm');
		\Base::instance()->set('members', $members);
		\Base::instance()->set('config', $teamConfig);
		
		echo \View::instance()->render('layout.htm');
	}
}