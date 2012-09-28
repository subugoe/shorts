<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

Tx_Extbase_Utility_Extension::registerPlugin(
		$_EXTKEY, 'Shortener', 'Shortener'
);

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Short URL');

t3lib_extMgm::addLLrefForTCAdescr('tx_shorts_domain_model_url', 'EXT:shorts/Resources/Private/Language/locallang_csh_tx_shorts_domain_model_url.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_shorts_domain_model_url');
$TCA['tx_shorts_domain_model_url'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:shorts/Resources/Private/Language/locallang_db.xml:tx_shorts_domain_model_url',
		'label' => 'url',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'dividers2tabs' => true,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Url.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_shorts_domain_model_url.gif'
	),
);
?>