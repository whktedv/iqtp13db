<?php
defined('TYPO3_MODE') or die();

call_user_func(function()
{
	
	/***************
	 * Plugin
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
			'Iqtp13db',
			'Iqtp13dbadmin',
			'IQ TP13 DB Adminbereich'
	);
	
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
			'Iqtp13db',
			'Iqtp13dbwebapp',
			'IQ TP13 DB Webapp'
	);
	
});

$extensionName = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase('iqtp13db'));

$pluginName = strtolower('Iqtp13dbadmin');
$pluginSignature = $extensionName.'_'.$pluginName;

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:iqtp13db/Configuration/FlexForms/PluginFormAdmin.xml');

$pluginName2 = strtolower('Iqtp13dbwebapp');
$pluginSignature2 = $extensionName.'_'.$pluginName2;

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature2] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature2, 'FILE:EXT:iqtp13db/Configuration/FlexForms/PluginFormWebapp.xml');
