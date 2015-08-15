<?php 
namespace Helpers;

class Post extends \Prefab {
	private $_meta;
	public $link;
	
	public function __construct($path) {
		try {
			$delimiterCount = 0;
			$metaDelimiter = '---';
			$handle = fopen('data/post/' . $path, 'r');
			
			while (($line = fgets($handle)) !== false && $delimiterCount < 2) {				
				// Check if markup stops or ends
				if (strstr($line, $metaDelimiter)) {
					$delimiterCount++;
					continue;
				}
				
				$line = explode(':', $line);
				$key = $line[0];
				$value = trim($line[1]);
				
				$this->_meta[$key] = $value;
			}
			
			$this->content = stream_get_contents($handle);
			fclose($handle);

			$this->link = 'blog/' . $path;
			$this->link = str_replace('.md', '', $this->link);
		} catch (\Exception $e) {
			// Need's error logging
		}
	}
	
	public function __get($name) {
		if (array_key_exists($name, $this->_meta)) {
			return $this->_meta[$name];
		}
	}
}

