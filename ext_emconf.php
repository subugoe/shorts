<?php

########################################################################
# Extension Manager/Repository config file for ext "shorts".
#
# Auto generated 20-04-2012 13:22
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Short URL',
	'description' => 'URL Verkuerzer auf Basis von RealURL',
	'category' => 'plugin',
	'author' => 'Ingo Pfennigstorf',
	'author_email' => 'pfennigstorf@sub.uni-goettingen.de',
	'author_company' => 'Goettingen State and University Library, Germany http://www.sub.uni-goettingen.de',
	'shy' => '',
	'dependencies' => 'cms,extbase,fluid,pagepath',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '2.0.0',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'extbase' => '',
			'fluid' => '',
			'pagepath' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
	'_md5_values_when_last_written' => 'a:27:{s:9:"build.xml";s:4:"9f61";s:12:"ext_icon.gif";s:4:"6f78";s:17:"ext_localconf.php";s:4:"f14c";s:14:"ext_tables.php";s:4:"528e";s:14:"ext_tables.sql";s:4:"77d1";s:36:"Classes/Controller/UrlController.php";s:4:"c267";s:28:"Classes/Domain/Model/Url.php";s:4:"2159";s:32:"Classes/Hooks/ShorteningHook.php";s:4:"1bd8";s:25:"Configuration/TCA/Url.php";s:4:"7da5";s:38:"Configuration/TypoScript/constants.txt";s:4:"5c3b";s:34:"Configuration/TypoScript/setup.txt";s:4:"6164";s:23:"Documentation/ChangeLog";s:4:"7adc";s:31:"Documentation/Manual/Manual.rst";s:4:"2eec";s:40:"Resources/Private/Language/locallang.xml";s:4:"45e2";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"89be";s:61:"Resources/Private/Scripts/Tx_Shorts_Scripts_RedisImporter.php";s:4:"e27e";s:44:"Resources/Private/Templates/Url/Display.html";s:4:"cc82";s:30:"Resources/Public/Css/tipsy.css";s:4:"1cb3";s:33:"Resources/Public/Eid/Redirect.php";s:4:"d289";s:37:"Resources/Public/Icons/tipsy-east.gif";s:4:"8669";s:38:"Resources/Public/Icons/tipsy-north.gif";s:4:"2154";s:37:"Resources/Public/Icons/tipsy-west.gif";s:4:"d520";s:53:"Resources/Public/Icons/tx_shorts_domain_model_url.gif";s:4:"6f78";s:29:"Resources/Public/Js/clippy.js";s:4:"8563";s:38:"Tests/Controller/UrlControllerTest.php";s:4:"f272";s:15:"build/phpcs.xml";s:4:"ab01";s:15:"build/phpmd.xml";s:4:"ab48";}',
);

?>