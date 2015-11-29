<?php 
namespace Helpers;

class Team extends \Prefab {
	
	public $config;
	
	public function __construct()
	{
		$file = \Base::instance()->get('site.aliases');
		
		try {
			$handle = fopen($file, 'r');
			$this->config = json_decode ( stream_get_contents($handle), true );
			if (!$handle) throw(new \Exception(error_get_last($handle)));
			fclose($handle);
		} catch (\Exception $e) {
			$logger = new \Log('error.log');
			$logger->write($e->getMessage());
			// Trigger an error
			\Base::instance()->error(500);
		}
	}
	
	public function getPublicName($githubID)
	{
		return $this->config[$githubID]['names']['inexor'];
	}
}
