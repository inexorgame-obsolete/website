<?php
namespace Controllers;
use \Curl\Curl;

class Team {
	public function index()
	{
		if (!\Cache::instance()->exists('members')) {
			try {
				$curl = new Curl;
				$members = $curl->get('https://api.github.com/orgs/inexor-game/members');
				
				if ($curl->error) throw(new \Exception($curl->errorMessage));
			} catch (\Exception $e) {
				$logger = new \Log('error.log');
				$logger->write($e->getMessage());
				// Trigger an error
				\Base::instance()->error(500);
			}

			// Merge members with their aliases
			$aliases = $this->getAliases();
			foreach ($members as $member) {
				$member->alias = $aliases[$member->id]['names']['inexor'];
			}
			
			\Cache::instance()->set('members', $members, 3600); //TTL = 1h
		} else {
			$members = \Cache::instance()->get('members');
		}
		
		\Base::instance()->set('members', $members);
		\Base::instance()->set('content', 'members.htm');
	}
	
	public function afterRoute() {
		echo \Template::instance()->render('layout.htm');
	}
	
	private function getAliases() {
		if (!\Cache::instance()->exists('aliases')) {
			$aliases = \Helpers\Team::instance()->config;
			\Cache::instance()->set('aliases', $aliases, 3600); //TTL = 1h		
		} else {
			$aliases = \Cache::instance()->get('aliases');
		}
		
		return $aliases;
	}
}