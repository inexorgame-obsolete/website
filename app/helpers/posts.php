<?php
namespace Helpers;

class Posts extends \Prefab {
	private $_posts; // A map of posts [year][post]
	
	public function __construct()
	{
		$path = 'data/post/';
		$years = array();
		
		foreach(glob($path . '*', GLOB_ONLYDIR) as $dir) {
			$years[] = basename($dir);
		}
		
		foreach($years as $year) {
			foreach(glob($path . $year . '/*.md', SCANDIR_SORT_DESCENDING) as $file) {
				$this->_posts[$year][] = basename($file);
			}
		}
		
		/* This will create a logic of files 
		 * $years[2015], $years[2014] ...
		 * $posts[2015][entry10], $posts[2015][entry9]..
		*/
	}
	
	public function getLatest($limit = 5)
	{
		$posts = array();
		foreach ($this->_posts as $year => $arr) {
			foreach($arr as $key => $post) {
				if (count($posts) >= $limit)
					break;

				$posts[] =  $year . '/' . $post;
			}
		}
		
		return $posts;
	}
	
	public function getPostsByYear($year, $limit = null) {
		return array_slice($this->_posts, null, $limit);
	}
}
