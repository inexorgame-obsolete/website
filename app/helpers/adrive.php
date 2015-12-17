<?php
namespace Helpers;

/**
 * This helper provides functionality to retrieves files from the ADrive API
 * It is really nestingly written up using plain DOMDocument and curl but there's no other way around
 * @author fohlen
 *
 */

class ADrive extends \Prefab {
	// Statically define the filePaths. Screw, noone should ever do this.
	// The files actually are compiled into a <table> tree, linked in with an external IFrame on ADrive.com
	// LIKE: WHO THE FUCK WOULD DO THIS, IF HE HAS A BRAIN AND IS NOT STONED AS HELL?
	const SHARE_URL = 'https://www.adrive.com/public/79zarV/nightly/';
	const FILELIST_URL = 'https://www31.adrive.com/public/79zarV.html';
	
	public function getFileList() {
		// Check if the document has been retrieved
		// TODO: Add caching
		// TODO: Add error handling to handleDocument
		// TODO: HARDCODED COOKIES, HAHA. I LOVE ADRIVE
		return $this->handleDocument($this->retrieveDocument());
		//return $this->handleDocument($document);
		
		// Check if the document has proccessed
		
	}
	
	private function retrieveDocument() {
		try {		
			$curl = curl_init();
			
			/* We firstly need to retrieve the shared folder page, which will 
			 * instruct ADrive to send a folder cookie.
			 * This cookie needs to be sent along the second request, otherwise
			 * we lack permissions to retrieve the filelist
			 */
			
			//$cookieFileLocation = \Base::instance()->get('TEMP') . 'cookie.file';
			//curl_setopt($curl, CURLOPT_COOKIEJAR, $cookieFileLocation);
			//curl_setopt($curl, CURLOPT_COOKIEFILE, $cookieFileLocation);
			
			$headers = array();
			$headers[] = 'Cookie: 79zarV=5d9ecf2b2641af866d95751a9c05c3a3:6:0';
			if (!$curl) throw(new \Exception(curl_error($curl)));
			// We don't really care about the shared folder page
			// curl_setopt($curl, CURLOPT_URL, self::SHARE_URL);
			// $sharedFolderDocument = curl_exec($curl);
			
			// Let's grab the IFrame
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl, CURLOPT_URL, self::FILELIST_URL);
			$fileListDocument = curl_exec($curl);
			
			curl_close($curl);
			return $fileListDocument;
		} catch (\Exception $e) {
			$logger = new \Log('error.log');
			$logger->write($e->getMessage());
			// Trigger an error
			\Base::instance()->error(500);
		}
	}
	
	private function handleDocument($document) {
		$domDocument = DOMDocument::fromHTML($document);
		return $domDocument->getElementsByTagName('tr');
	}
	
	/**
	 * Author's note @anyone who might ever read this in the ADrive team
	 * This is the single most worst implementation that I've seen in plenty of years.
	 * Your CEO should have probably kicked you off, since what you do is really, really dumb architecturing with the following aspects
	 *	- a matter of speed
	 *		From a user perspective, compiling everything into a single DOMDocument with 1373 files and counting, is just, bad. 
	 *		It's about 2 MB plain HTML-text, what the fuck.
	 *	- a matter of usability
	 *		You cut yourselves off from every ajax-based architecture, which means no search, no extandability, it's slow. Again, what the fuck
	 *	- it's dumb from an architecturing perspective
	 *		You take additional resources to store cookies, and assign integers to public-shared files (filenames). That uses resources for something it shouldn't.
	 *	- you don't actually protect your API
	 *		As you see, hacking a cookie-protected page is something really easy.
	 *		Every simple whois-based-lookup routine would have done better than that. Seriously.
	 *	- last but not least, it's dumb from a security perspective
	 *		you offer HTTP and store the share-access to a folder in a cookie.
	 *		LIKE, NOONE GONNA EVER BE ABLE TO STEAL THAT COOKIE AND GAIN ACCESS TO PRIVATELY SHARED FOLDERS
	 *		NAH, that's never gonna happen, and surely isn't possible with XSS. Good work.
	 *
	 *	Doing things like this really pisses me off, and I hope you can learn and improve your product.
	 *	I take this argumentary as a permission to hack your API, since it's a single point of failures in any given field.
	 */
}