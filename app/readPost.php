<?php 

class readPost
{
	private $post = array();
	
	function __construct($filePath)
	{
		$this->post["filePath"] = $filePath;
		$handle = fopen($filePath, "r");
	
		$metaDelimiter = "---";
		$metaStarted = false;
		$line = 0;
		
		while (($buffer = fgets($handle)) !== false) 
		{
			$line++;
			
			if($metaStarted == false)
			{
				if(trim($buffer) == $metaDelimiter)
				{
					$metaStarted = true;
				}
			}
			else
			{
				if(trim($buffer) == $metaDelimiter)
				{
					break;
				}
				else
				{
					$bufferArray = explode(":", $buffer, 2);
					$key = trim($bufferArray[0]);
					$value = trim($bufferArray[1]);
					
					$this->post[$key] = $value;
				}
			}
		}
		
		$article = stream_get_contents($handle);
		$this->post["article"] = trim($article);
	}
	
	public function get_template()
	{
		if($this->post["layout"] == "post")
		{
			return "single-entry";
		}
		else
		{
			return $this->post["layout"];
		}
	}
	
	public function get_title()
	{
		return $this->post["title"];
	}
	
	public function get_date()
	{
		return $this->post["date"];
	}
	
	public function get_author()
	{
		if($this->post["author"] == "")
		{
			return "Inexor Team";
		}
		else
		{
			return $this->post["author"];
		}
	}
	
	public function get_summary()
	{
		return $this->post["author"];
	}
	
	public function get_article()
	{
		return $this->post["article"];
	}
	
	public function get_link()
	{
		if($this->post["layout"] == "post")
		{
			$tmpLink = str_replace("data/post/", "", $this->post["filePath"]);
		}
		
		return str_replace(".md", "", $tmpLink);
	}
}
