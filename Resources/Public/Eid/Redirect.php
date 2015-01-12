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
function redirectToLongURL($shortUrl) {

    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
    // What
        'pid, uid, short_url, url',
        // From
        'tx_shorts_domain_model_url',
        // Where
        'short_url = "' . $shortUrl . '"',
        // order by
        'uid DESC', '',
        // limit
        '1'
    );

    $match = '/index.php\?id=[0-9]*/';

    while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

        $parameters = preg_replace($match, '', $row['url']);
        $pagePath = tx_pagepath_api::getPagePath($row['pid'], $parameters);
    }

    return $pagePath;
}

$shortUrl = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('shortUrl');

//Alles was laenger als 5 Zeichen ist sollte potenziell verdaechtig sein
if (strlen($shortUrl) <= 5) {

    if ($weiterleitung = redirectToLongURL($shortUrl)) {

        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $weiterleitung);
        die;
    } else {
        //Schmeisse einen 404 Fehler - @dirty
        header("HTTP/1.0 404 Not Found");

        //404 Seite einbinden
        echo \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($hostname . '404/');
    }
}
?>
