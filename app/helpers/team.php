<?php 
namespace Helpers;

class Team extends \Prefab {
	
	public $config;
	
	public function __construct()
	{
		$handle = fopen("data/config/team.json", "r");
		$this->config = json_decode ( stream_get_contents($handle), true );
		fclose($handle);
	}
	
	public function getPublicName($githubID)
	{
		return $this->config[$githubID]["names"]["inexor"];
	}
}
