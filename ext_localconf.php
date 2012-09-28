<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY, 'Shortener', array(
			'Url' => 'display'
	)
);

	// Hook von RealURL zum Kuerzen nutzen
$TYPO3_CONF_VARS['EXTCONF']['realurl']['encodeSpURL_postProc'][$_EXTKEY] = 'EXT:shorts/Classes/Hooks/ShorteningHook.php:user_Tx_Shortener_Hooks_ShorteningHook->generateShortUrl';

	// redirects ueber eid - Schneller und schlanker
$TYPO3_CONF_VARS['FE']['eID_include'][$_EXTKEY] = 'EXT:shorts/Resources/Public/Eid/Redirect.php';

if (!is_array($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['shorts'])) {
	$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['shorts'] = array();
}
?>