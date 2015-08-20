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
				
				// Only use ONE substr segment (explode would use any)
				// Unfortanly explode($line, ':', 1) does not work as expected
				$pos = strpos($line, ':');
				$key = substr($line, 0, $pos);
				$value = trim(substr($line, $pos + 1, strlen($line)));
				
				$this->_meta[$key] = $value;
			}
			
			// Set default for empty values
			foreach ($this->_meta as $meta) {
				if (empty($meta)) 
					$this->_meta[$meta] = $this->_setDefault($meta);
			}
			
			$this->content = stream_get_contents($handle);
			fclose($handle);

			$this->link = 'blog/' . $path;
			$this->link = str_replace('.md', '', $this->link);
		} catch (\Exception $e) {
			// Need's error logging
		}
	}
	
	private function _setDefault($key) {
		switch($key) {
			case 'layout': return 'post';
			case 'title': return 'A blog entry';
			case 'date': return \DateTime('now')->format('Y-m-d H:i:s O');
			case 'author': return 'Inexor team';
			case 'summary': return 'No summary available';
		}
	}
	
	public function __get($name) {
		if (array_key_exists($name, $this->_meta)) {
			return $this->_meta[$name];
		}
	}
}

