<?php
namespace Subugoe\Shorts\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Ingo Pfennigstorf <pfennigstorf@sub.uni-goettingen.de>,
 *  Goettingen State and University Library, Germany http://www.sub.uni-goettingen.de
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
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Controller for the URL object
 */
class UrlController extends ActionController
{

    /**
     * @var int
     */
    public $pageId;

    /**
     * @var string
     */
    public $currentPage;

    /**
     * @var \Subugoe\Shorts\Service\ShorteningService
     * @inject
     */
    protected $shorteningService;

    /**
     * @var \Subugoe\Shorts\Domain\Repository\UrlRepository
     * @inject
     */
    protected $urlRepository;

    /**
     * @var \TYPO3\CMS\Core\Page\PageRenderer
     */
    protected $pageRenderer;

    /**
     * Initialisierung globaler Werte
     *
     * @return void
     */
    public function initializeAction()
    {

        // assign the page Id
        $this->pageId = $GLOBALS['TSFE']->id;
        $this->pageRenderer = $GLOBALS['TSFE']->getPageRenderer();

        $this->addResourcesToHead();

        $this->currentPage = substr(GeneralUtility::getIndpEnv('SCRIPT_NAME'), 1) . '?id=' . $this->pageId;

        if (GeneralUtility::getIndpEnv('QUERY_STRING')) {
            $this->currentPage = $this->shorteningService->removeChashParamaterFromString($this->currentPage . '&' . GeneralUtility::getIndpEnv('QUERY_STRING'));
            $this->currentPage = $this->shorteningService->removeConfiguredParametersFromString($this->currentPage,
                $this->settings['parametersToExclude']);
        }
    }

    /**
     * Show the shortened URL
     *
     * @return void
     */
    public function displayAction()
    {

        $domainName = GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST');

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
     * @return string
     */
    protected function generateShortUrl()
    {

        $urlHash = $this->shorteningService->generateHash();

        // Hashwert mit langer URL in die DB
        $this->shorteningService->insertShortUrlIntoDB($this->currentPage, $urlHash, $this->pageId);

        return $urlHash;
    }

    /**
     * Include CSS and JavaScript
     * @return void
     */
    protected function addResourcesToHead()
    {
        $this->pageRenderer->addJsFile(ExtensionManagementUtility::siteRelPath('shorts') . 'Resources/Public/JavaScript/Shorts.js');
        $this->pageRenderer->addCssFile(ExtensionManagementUtility::siteRelPath('shorts') . 'Resources/Public/Css/Shorts.css');
    }

}
