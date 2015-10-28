<?php 
namespace Helpers;

class Team extends \Prefab {
	
	public $config;
	
	public function __construct()
	{
		$teamFile = \Base::instance()->get('team');
		
		try {
			$handle = fopen($teamFile);
			$this->config = json_decode ( stream_get_contents($handle), true );
			fclose($handle);
		} catch (\Exception $e) {
			// Add error handling
		}
	}
	
	public function getPublicName($githubID)
	{
		return $this->config[$githubID]['names']['inexor'];
	}
}
