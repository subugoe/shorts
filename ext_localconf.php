<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if (!defined('TYPO3_COMPOSER_MODE') || TYPO3_COMPOSER_MODE === false) {
    require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('shorts') . 'vendor/autoload.php');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Subugoe.' . $_EXTKEY,
    'Shortener',
    [
        'Url' => 'display'
    ],
    [
        'Url' => 'display'
    ]
);

// use realurl hook for shortenings
$TYPO3_CONF_VARS['EXTCONF']['realurl']['encodeSpURL_postProc'][$_EXTKEY] = 'EXT:shorts/Classes/Hooks/ShorteningHook.php:user_Tx_Shortener_Hooks_ShorteningHook->generateShortUrl';

// redirects via eid
$TYPO3_CONF_VARS['FE']['eID_include'][$_EXTKEY] = 'EXT:shorts/Resources/Public/Eid/Redirect.php';
