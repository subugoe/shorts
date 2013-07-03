<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Ingo Pfennigstorf <pfennigstorf@sub.uni-goettingen.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * ************************************************************* */

/**
 * Ruft vor dem Kodieren von RealURL den Shortenenerservice 
 *
 * @author Ingo Pfennigstorf <pfennigstorf@sub.uni-goettingen.de>
 */
class user_Tx_Shortener_Hooks_ShorteningHook {

	/**
	 * @var int
	 */
	protected $pageId;

	/**
	 * @var Tx_Shorts_Service_ShorteningService
	 */
	protected $shorteningService;

	/**
	 * Initializes defaults
	 */
	public function initialize() {
		$this->shorteningService = t3lib_div::makeInstance('Tx_Shorts_Service_ShorteningService');
	}

	/**
	 * Start des Hookabfangs
	 * @param type $hookParams
	 * @param type $pObj 
	 */
	public function generateShortUrl($hookParams, $pObj) {

		$this->initialize();

		$urlParameters = $this->shorteningService->removeChashParamaterFromString($hookParams['params']['LD']['totalURL']);

			// UID der Seite mit speichern
		$this->pageId = $hookParams['params']['args']['page']['uid'];

			// Checken ob bereits ein Eintrag vorhanden ist
		if ($this->shorteningService->isAlreadyInDB($urlParameters) === FALSE) {
			$urlHash = $this->shorteningService->generateHash($urlParameters);

				// Hashwert mit langer URL in die DB
			$this->shorteningService->insertShortUrlIntoDB($urlParameters, $urlHash, $this->pageId);
		}
	}

}
?>