<?php
namespace Helpers;

class Text extends \Prefab {
	/**
	 * @method preview
	 * @param string
	 * @return string
	 * 
	 * Returns a shortened version of a text..
	 */
	public function preview($content) {
		return substr($content, 0, 250);
	}
}