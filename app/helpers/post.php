<?php 
namespace Helpers;

class Post extends \Prefab {
	private $_meta;
	private $filePath;
	
	public function __construct($path) {
		try {
			$this->filePath = $path;
			$delimiterCount = 0;
			$metaDelimiter = '---';
			$handle = fopen('data/post/' . $path, 'r');
			
			while (($line = fgets($handle)) !== false && $delimiterCount < 2) {				
				// Check if markup stops or ends
				if (strstr($line, $metaDelimiter)) {
					$delimiterCount++;
					continue;
				}
				
				$line = explode(':', $line, 2);
				$key = $line[0];
				$value = trim($line[1]);
				$this->_meta[$key] = $value;
			}
			
			$this->content = stream_get_contents($handle);
			fclose($handle);

		} catch (\Exception $e) {
			$logger = new \Log('error.log');
			$logger->write($e->getMessage());
			// Trigger an error
			\Base::instance()->error(500);
		}
	}
	
	private function _getDefault($key) {
		switch($key) {
			case 'layout': return 'post';
			case 'title': return 'A blog entry';
			case 'date': return \DateTime('now')->format('d.m.Y H:i O');
			case 'author': return 'Inexor team';
			case 'summary': return 'No summary available';
			case "link":
				if($this->layout == "post")
				{
					$tmpLink = "blog/" . $this->filePath;
				}
				
				return str_replace(".md", "", $tmpLink);
				break;
		}
	}
	
	public function __get($name) {
		if (array_key_exists($name, $this->_meta)) {
			$value = $this->_meta[$name];
		} else {
			$value = $this->_getDefault($name);
		}
		
		switch($name) {
			case "date": return date_format(date_create($value), "d.m.Y H:i O");
			default: return $value;
		}
	}
}
