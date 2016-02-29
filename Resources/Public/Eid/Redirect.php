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
require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('pagepath', 'class.tx_pagepath_api.php'));
/**
 * Ausgabe des Parameter der langen URL
 *
 * @param string $shortUrl
 * @return string $longUrl
 */
function redirectToLongURL($shortUrl)
{

    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
        'pid, uid, short_url, url',
        'tx_shorts_domain_model_url',
        'BINARY short_url = "' . $shortUrl . '"',
        'uid DESC', '',
        '1'
    );

    $match = '/index.php\?id=[0-9]*/';

    while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
        $parameters = preg_replace($match, '', $row['url']);
        $pagePath = \tx_pagepath_api::getPagePath($row['pid'], $parameters);
    }

    return $pagePath;
}

$shortUrl = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('shortUrl');

//Alles was laenger als 5 Zeichen ist sollte potenziell verdaechtig sein
if (strlen($shortUrl) <= 5) {

    $redirect = redirectToLongURL($shortUrl);

    if ($redirect) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $redirect);
        die;
    } else {
        header('HTTP/1.1 404 Not Found');
        echo \TYPO3\CMS\Core\Utility\GeneralUtility::getUrl('http://www.sub.uni-goettingen.de/404/');
    }
}
