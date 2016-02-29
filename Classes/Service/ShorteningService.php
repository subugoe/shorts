<?php
namespace Subugoe\Shorts\Service;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Ingo Pfennigstorf <pfennigstorf@sub-goettingen.de>
 *      Goettingen State Library
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
 * ************************************************************* */
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ArrayUtility;

require_once(ExtensionManagementUtility::extPath('shorts') . 'vendor/autoload.php');

/**
 * Service for shortening and verifying Urls
 */
class ShorteningService
{

    /**
     * Removes Chash Parameter from a string
     *
     * @param string $urlParams
     * @return string
     */
    public function removeChashParamaterFromString($urlParams)
    {

        $pattern = '/(\&|\?)cHash(.*)/';

        if (preg_match($pattern, $urlParams)) {
            $sanitizedGetParams = preg_replace($pattern, '', $urlParams);
            $return = $sanitizedGetParams;
        } else {
            $return = $urlParams;
        }

        return $return;
    }

    /**
     * @param string $urlParameters
     * @param array $additionalParameters
     *
     * @return string
     */
    public function removeConfiguredParametersFromString($url, $additionalParameters)
    {
        $parameters = ArrayUtility::trimExplode(',', $additionalParameters);
        $urlParameters = parse_url($url);
        $queryParameters = GeneralUtility::explodeUrl2Array($urlParameters['query']);
        foreach ($parameters as $parameter) {
            if (array_key_exists($parameter, $queryParameters)) {
                unset($queryParameters[$parameter]);
            }
        }

        $compiledUrl = $urlParameters['path'] . '?' . http_build_query($queryParameters);

        return urldecode($compiledUrl);
    }

    /**
     * Create a new unique String and return it
     * @return string
     */
    public function generateHash()
    {

        $shortHash = NULL;
        $hashLength = 5;

        $allowedCharacters = [
            'a',
            'b',
            'c',
            'd',
            'e',
            'f',
            'g',
            'h',
            'j',
            'k',
            'm',
            'n',
            'p',
            'q',
            'r',
            's',
            't',
            'u',
            'v',
            'w',
            'x',
            'y',
            'z',
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'J',
            'K',
            'L',
            'M',
            'N',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9'
        ];
        $lastAllowedCharacter = count($allowedCharacters) - 1;

        for ($i = 0; $i < $hashLength; $i++) {
            // Calculate random string
            $random = rand(0, $lastAllowedCharacter);
            $shortHash .= $allowedCharacters[$random];
        }

        // if hash already exists create a new one until a unique one is found
        if ($this->isUniqueHash($shortHash) === FALSE) {
            self::generateHash();
        }

        return $shortHash;
    }

    /**
     * Test if hash is already present in the database
     *
     * @param string $shortHash
     * @return boolean $return einzigartiger Wert
     */
    protected function isUniqueHash($shortHash)
    {
        $return = TRUE;

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'short_url',
            'tx_shorts_domain_model_url',
            'BINARY short_url = "' . $shortHash . '"',
            '',
            '',
            ''
        );

        $counter = $GLOBALS['TYPO3_DB']->sql_num_rows($res);

        if ($counter > 0) {
            $return = FALSE;
        }

        return $return;
    }

    /**
     * Schreibe URL und zugehoerigen Kurzwert in die Datenbank
     * @param string $urlParams
     * @param string $urlHash
     * @param int $pageId
     */
    public function insertShortUrlIntoDB($urlParams, $urlHash, $pageId)
    {

        $values = [
            'url' => $urlParams,
            'short_url' => $urlHash,
            'crdate' => time(),
            'tstamp' => time(),
            'pid' => $pageId
        ];

        //Daten in die Tabelle schreiben
        $GLOBALS['TYPO3_DB']->exec_INSERTquery(
            'tx_shorts_domain_model_url',
            $values
        );
    }

    /**
     * Checkt, ob der die das URL bereits gekuerzt wurde
     * @param string $urlParameters
     * @return boolean $return URL bereits gekuerzt
     */
    public function isAlreadyInDB($urlParameters)
    {

        $return = FALSE;

        $query = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'url',
            'tx_shorts_domain_model_url',
            'url = "' . $urlParameters . '"',
            '',
            '',
            ''
        );

        $counter = $GLOBALS['TYPO3_DB']->sql_num_rows($query);

        if ($counter > 0) {
            $return = TRUE;
        }

        return $return;
    }
}
