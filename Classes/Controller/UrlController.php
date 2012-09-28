<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Ingo Pfennigstorf <pfennigstorf@sub.uni-goettingen.de>, Goettingen State and University Library, Germany http://www.sub.uni-goettingen.de
*
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 3 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Controller for the URL object
 */
 class Tx_Shorts_Controller_UrlController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * @var int
	 */
	public $pageId;

	/**
	 * @var string
	 */
	public $currentPage;

	protected $cacheInstance;

	/**
	 * Initialisierung globaler Werte
	 *
	 * @return void
	 */
	public function initializeAction() {

		$this->initializeCache();
			// assign the page Id
		$this->pageId = $GLOBALS['TSFE']->id;

		$this->currentPage = substr(t3lib_div::getIndpEnv('SCRIPT_NAME'),1) . '?id=' . $this->pageId;

		if (t3lib_div::getIndpEnv('QUERY_STRING')){
			$this->currentPage = $this->removeChashParamaterFromString($this->currentPage . '&' . t3lib_div::getIndpEnv('QUERY_STRING'));
		}
	}

	/**
	* Initialize cache instance to be ready to use
	*
	* @return void
	*/
	protected function initializeCache() {
	  t3lib_cache::initializeCachingFramework();
 		try {
			$this->cacheInstance = $GLOBALS['typo3CacheManager']->getCache('shorts');
		} catch (t3lib_cache_exception_NoSuchCache $e) {
			$this->cacheInstance = $GLOBALS['typo3CacheFactory']->create(
			'shorts',
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['shorts']['frontend'],
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['shorts']['backend'],
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['myext_mycache']['options']
			);
		}
	}

	/**
	 * Zeigt die verkuerzte URL an
	 *
	 * @return void
	 */
	public function displayAction() {

			// Resources to head
			// Not doing this in the view part because it would break caching
		$GLOBALS['TSFE']->pSetup['includeJS.']['shorts'] = 'typo3conf/ext/shorts/Resources/Public/Js/Shorts.js';
		$GLOBALS['TSFE']->pSetup['includeCSS.']['shorts'] = 'typo3conf/ext/shorts/Resources/Public/Css/Shorts.css';

		$domainName = t3lib_div::getIndpEnv('TYPO3_REQUEST_HOST');
		$shortenURL = $this->getShortUrl();

		if ($this->cacheInstance->has('shorts_' . $shortenURL)) {
			return $this->cacheInstance->get('shorts_' . $shortenURL);
		} else {
			$this->view
					->assign('display', $shortenURL)
					->assign('domain', $domainName);

			$this->cacheInstance->set('shorts_' . $shortenURL, $this->view->render(), array(), 0);
		}

	}

	/**
	 * Checkt, ob der URL bereits gekuerzt wurde
	 *
	 * @param string $urlParams
	 * @return boolean $return URL bereits gekuerzt
	 */
	protected function getShortUrl() {

		$result = '';

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'short_url, url', //WHAT
			'tx_shorts_domain_model_url', //FROM
			'url = \'' . $this->currentPage . '\'', //WHERE
			'',
			'', //ORDER BY
			'' //LIMIT
		);

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$result = $row['short_url'];
		}

		if ($result == '' ) {
			$result = $this->generateShortUrl();
		}

		return $result;
	}

	/**
	 * Start des Hookabfangs
	 *
	 * @param type $hookParams
	 * @param type $pObj
	 */
	protected function generateShortUrl() {

		$urlHash = $this->generateHash($this->currentPage);

			//Hashwert mit langer URL in die DB
		$this->insertShortUrlIntoDB($this->currentPage, $urlHash);

		return $urlHash;
	}

	/**
	 * Chash Parameter entfernen
	 *
	 * @param string $urlParams
	 * @return string
	 */
	public function removeChashParamaterFromString($urlParams) {
		$sanitizedGetParams = preg_replace('/\&cHash(.*)/', '', $urlParams);
		return $sanitizedGetParams;
	}

	/**
	 * Schreibe URL und zugehoerigen Kurzwert in die Datenbank
	 *
	 * @param type $urlParams
	 * @param type $urlHash
	 */
	protected function insertShortUrlIntoDB($urlParams, $urlHash) {
		$values = array(
			'url' => $urlParams,
			'short_url' => $urlHash,
			'crdate' => time(),
			'tstamp' => time(),
			'pid' => $this->pageId
		);

			// Daten in die Tabelle schreiben
		$GLOBALS['TYPO3_DB']->exec_INSERTquery(
			'tx_shorts_domain_model_url', $values, $no_quote_fields = FALSE
		);
	}

	/**
	 * Erstelle einen eindeutigen String zum URL und liefere den zurueck
	 * @return string
	 */
	protected function generateHash() {
		$shortHash = null;
		$hashLength = 5;

		$allowedValues = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '2', '3', '4', '5', '6', '7', '8', '9');
		$lastAllowedValue = count($allowedValues) - 1;

		for ($i = 0; $i < $hashLength; $i++) {
				// calculate random number
			$random = rand(0, $lastAllowedValue);
			$shortHash .= $allowedValues[$random];
		}

			// Falls der Hash schon vorhanden ist ... solange neuen erzeugen bis unique
		if ($this->isUniqueHash($shortHash) === FALSE) {
			$this->generateHash();
		}

		return $shortHash;
	}

	/**
	 * Ueberpruefung ob der Hash schon in der Datenbank exisitiert
	 *
	 * @param string $shortHash
	 * @return boolean $return einzigartiger Wert
	 */
	protected function isUniqueHash($shortHash) {
		$return = TRUE;

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'short_url',
			'tx_shorts_domain_model_url',
			'short_url = \'' . $shortHash . '\'',
			'',
			'',
			''
		);

		$counter = 0;

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$counter = $counter + 1;
		}

		if ($counter > 0) {
			$return = FALSE;
		}

		return $return;
	}
}
?>