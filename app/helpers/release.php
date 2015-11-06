<?php
namespace Helpers;
use \Curl\Curl;

class Release extends \Prefab {
	private $_meta;
	
	public function __construct() {
		try {
			$curl = new Curl();
			$releases = $curl->get('https://api.github.com/repos/inexor-game/code/releases');
			/* 
			 * This is a nasty little "hack". $releases is an stdObject filled with stacks of
			 * stdObjects. Using {0} as an index and using quick convert (array) will return
			 * the first result from the Github API as an Array
			 */
			$this->_meta = (array)$releases{0};
				
			if ($curl->error) throw(new \Exception($curl->errorMessage));
		} catch (\Exception $e) {
			$logger = new \Log('error.log');
			$logger->write($e->getMessage());
			// Trigger an error
			\Base::instance()->error(500);
		}
	}
	
	public function __get($key) {
		if (key_exists($key, $this->_meta)) {
			return $this->_meta[$key];
		}
	}
}