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
	 * Start des Hookabfangs
	 * @param type $hookParams
	 * @param type $pObj 
	 */
	public function generateShortUrl($hookParams, $pObj) {

		$urlParams = $this->removeChashParamaterFromString($hookParams['params']['LD']['totalURL']);

			// UID der Seite mit speichern
		$this->pageId = $hookParams['params']['args']['page']['uid'];

			// Checken ob bereits ein Eintrag vorhanden ist
		if (!$this->isAlreadyInDB($urlParams)) {
			$urlHash = $this->generateHash($urlParams);

				// Hashwert mit langer URL in die DB
			$this->insertShortUrlIntoDB($urlParams, $urlHash);
		}
	}

	/**
	 * Checkt, ob der die das URL bereits gekuerzt wurde
	 * @param string $urlParams
	 * @return boolean $return URL bereits gekuerzt
	 */
	private function isAlreadyInDB($urlParams) {

		$return = FALSE;

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'url', //WHAT
						'tx_shorts_domain_model_url', //FROM
						'url = "'     . $urlParams . '"', //WHERE
						'', '', //ORDER BY
						'' //LIMIT
		);

		$counter = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$counter = $counter + 1;
		}

		if ($counter > 0) {
			$return = TRUE;
		}

		return $return;
	}

	/**
	 * Chash Parameter entfernen
	 * @param string $urlParams
	 * @return string
	 */
	private function removeChashParamaterFromString($urlParams) {

		$sanitizedGetParams = preg_replace('/\&cHash(.*)/', '', $urlParams);
		return $sanitizedGetParams;
	}

	/**
	 * Schreibe URL und zugehoerigen Kurzwert in die Datenbank
	 * @param type $urlParams
	 * @param type $urlHash 
	 */
	private function insertShortUrlIntoDB($urlParams, $urlHash) {

		$werte = array(
		'url' => $urlParams,
		'short_url' => $urlHash,
		'crdate' => time(),
		'tstamp' => time(),
		'pid' => $this->pageId
		);

		//Daten in die Tabelle schreiben
		$GLOBALS['TYPO3_DB']->exec_INSERTquery(
				'tx_shorts_domain_model_url', $werte, $no_quote_fields = FALSE
		);
	}

	/**
	 * Erstelle einen eindeutigen String zum URL und liefere den zurueck
	 * @return string 
	 */
	private function generateHash() {

		$kurzHash = null;
		$zeichenLaenge = 5;

		$erlaubteZeichen = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '2', '3', '4', '5', '6', '7', '8', '9');
		$letztesErlaubtesZeichen = count($erlaubteZeichen) - 1;

		for ($i = 0; $i < $zeichenLaenge; $i++) {
				// Zufallszahl ermitteln
			$zufall = rand(0, $letztesErlaubtesZeichen);
			$kurzHash .= $erlaubteZeichen[$zufall];
		}

			// Falls der Hash schon vorhanden ist ... solange neuen erzeugen bis unique
		if ($this->isUniqueHash($kurzHash) === FALSE) {
			$this->generateHash();
		}

		return $kurzHash;
	}

	/**
	 * Ueberpruefung ob der Hash schon in der Datenbank exisitiert
	 * @param string $kurzHash
	 * @return boolean $return einzigartiger Wert
	 */
	private function isUniqueHash($kurzHash) {

		$return = TRUE;

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'short_url',
						'tx_shorts_domain_model_url',
						'short_url = "' . $kurzHash . '"',
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