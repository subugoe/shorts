<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Subugoe.' . $_EXTKEY,
	'Shortener',
	array (
		'Url' => 'display'
	),
	array (
		'Url' => 'display'
	)
);

	// Hook von RealURL zum Kuerzen nutzen
$TYPO3_CONF_VARS['EXTCONF']['realurl']['encodeSpURL_postProc'][$_EXTKEY] = 'EXT:shorts/Classes/Hooks/ShorteningHook.php:user_Tx_Shortener_Hooks_ShorteningHook->generateShortUrl';

	// redirects ueber eid - Schneller und schlanker
$TYPO3_CONF_VARS['FE']['eID_include'][$_EXTKEY] = 'EXT:shorts/Resources/Public/Eid/Redirect.php';
