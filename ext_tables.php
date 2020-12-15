<?php
defined('TYPO3_MODE') || die('Access denied.');

function()
{
	
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_iqtp13db_domain_model_teilnehmer', 'EXT:iqtp13db/Resources/Private/Language/locallang.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_iqtp13db_domain_model_teilnehmer');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_iqtp13db_domain_model_beratung', 'EXT:iqtp13db/Resources/Private/Language/locallang.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_iqtp13db_domain_model_beratung');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_iqtp13db_domain_model_folgekontakt', 'EXT:iqtp13db/Resources/Private/Language/locallang.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_iqtp13db_domain_model_folgekontakt');
                
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_iqtp13db_domain_model_berater', 'EXT:iqtp13db/Resources/Private/Language/locallang.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_iqtp13db_domain_model_berater');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_iqtp13db_domain_model_dokument', 'EXT:iqtp13db/Resources/Private/Language/locallang.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_iqtp13db_domain_model_dokument');

};
    
$extensionName = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase('iqtp13db'));

$pluginName = strtolower('Iqtp13dbadmin');
$pluginSignature = $extensionName.'_'.$pluginName;

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:iqtp13db/Configuration/FlexForms/PluginFormAdmin.xml');

$pluginName2 = strtolower('Iqtp13dbwebapp');
$pluginSignature2 = $extensionName.'_'.$pluginName2;

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature2] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature2, 'FILE:EXT:iqtp13db/Configuration/FlexForms/PluginFormWebapp.xml');

## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder

