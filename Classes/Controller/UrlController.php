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

	 /**
	  * @var
	  */
	protected $cacheInstance;

	/**
	 * @var Tx_Shorts_Service_ShorteningService
	 * @inject
	 */
	protected $shorteningService;

	/**
	 * @var Tx_Shorts_Domain_Repository_UrlRepository
	 * @inject
	 */
	protected $urlRepository;

	/**
	 * Initialisierung globaler Werte
	 *
	 * @return void
	 */
	public function initializeAction() {

			// assign the page Id
		$this->pageId = $GLOBALS['TSFE']->id;

		$this->addResourcesToHead();

		$this->currentPage = substr(t3lib_div::getIndpEnv('SCRIPT_NAME'),1) . '?id=' . $this->pageId;

		if (t3lib_div::getIndpEnv('QUERY_STRING')){
			$this->currentPage = $this->shorteningService->removeChashParamaterFromString($this->currentPage . '&' . t3lib_div::getIndpEnv('QUERY_STRING'));
		}
	}

	/**
	 * Zeigt die verkuerzte URL an
	 *
	 * @return void
	 */
	public function displayAction() {

		$domainName = t3lib_div::getIndpEnv('TYPO3_REQUEST_HOST');

		$shortenUrl = $this->urlRepository->findShortUrlByPage($this->currentPage);

		if (empty($shortenUrl)) {
			$shortenUrl = $this->generateShortUrl();
		}

		$this->view
				->assign('display', $shortenUrl)
				->assign('domain', $domainName);
	}

	/**
	 * Generate a short url
	 *
	 * @param type $hookParams
	 * @param type $pObj
	 */
	protected function generateShortUrl() {

		$urlHash = $this->shorteningService->generateHash($this->currentPage);

			//Hashwert mit langer URL in die DB
		$this->shorteningService->insertShortUrlIntoDB($this->currentPage, $urlHash, $this->pageId);

		return $urlHash;
	}

	protected function addResourcesToHead() {
				// Resources to head
			// Not doing this in the view part because it would break caching
		$GLOBALS['TSFE']->pSetup['includeJS.']['shorts'] = 'typo3conf/ext/shorts/Resources/Public/Js/Shorts.js';
		$GLOBALS['TSFE']->pSetup['includeCSS.']['shorts'] = 'typo3conf/ext/shorts/Resources/Public/Css/Shorts.css';
	}

}
?>