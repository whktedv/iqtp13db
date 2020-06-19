<?php
defined('TYPO3_MODE') or die();

call_user_func(function()
{
	
	/***************
	 * Plugin
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
			'iqtp13db',
			'Iqtp13dbadmin',
			'IQ TP13 DB Adminbereich'
	);
	
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
			'iqtp13db',
			'Iqtp13dbwebapp',
			'IQ TP13 DB Webapp'
	);
	
});



