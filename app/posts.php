<?php

class posts
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
