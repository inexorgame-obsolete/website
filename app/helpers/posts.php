<?php
namespace Helpers;

class Posts extends \Prefab
{
	private function read_year($year)
	{
		$files = array();
		
		if(!file_exists("data/post/". $year))
		{
			return false;
		}
		
		foreach(scandir("data/post/". $year, SCANDIR_SORT_DESCENDING) AS $file)
		{
			if($file != "." AND $file != "..")
			{
				$files[] = "data/post/". $year."/". $file;
			}
		}
		
		return $files;
	}
	
	public function get_latest($limit)
	{
		$posts = array();
	
		for($year = date("Y"); count($posts) < $limit; $year--)
		{
			$newPosts = $this->read_year($year);
			if($newPosts == false)
			{
				return $posts;
			}
			else
			{
				$requiredPosts = $limit - count($posts);
				
				if($requiredPosts < count($newPosts))
				{
					$newPosts = array_diff($newPosts, array_slice($newPosts, $requiredPosts));
				}
				
				$posts = array_merge($posts, $newPosts);
			}
		}
		
		return $posts;
	}
	
}

/*
class Posts extends \Prefab {
	private $_years; // A list of years
	private $_posts; // A map of posts [year][post]
	
	public function __construct()
	{
		$path = 'data/post/';
		
		foreach(glob($path . '*', GLOB_ONLYDIR) as $dir) {
			$this->_years[] = basename($dir);
		}
		
		foreach($this->_years as $year) {
			foreach(glob($path . $year . '/*.md', SCANDIR_SORT_DESCENDING) as $file) {
				$this->_posts[$year][] = basename($file);
			}
		}
		
		/* This will create a logic of files 
		 * $years[2015], $years[2014] ...
		 * $posts[2015][entry10], $posts[2015][entry9]..
		 
	}
	
	public function getPosts($limit = 5)
	{
		// If next entry is invalid or posts array is full, abort the loop
		$posts = array();
		
		while (!next($this->_posts) && count($posts) < $limit) {
			if (is_array(current($this->_posts)))
				continue;
			
			$posts[] = current($this->_posts);
			next($posts);
		}
		
		return $this->_posts;
	}
}*/
