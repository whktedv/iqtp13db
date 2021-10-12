<?php
defined('TYPO3_MODE') || die('Access denied.');

function()
{
	
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_iqtp13db_domain_model_teilnehmer', 'EXT:iqtp13db/Resources/Private/Language/locallang.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_iqtp13db_domain_model_beratung', 'EXT:iqtp13db/Resources/Private/Language/locallang.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_iqtp13db_domain_model_folgekontakt', 'EXT:iqtp13db/Resources/Private/Language/locallang.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_iqtp13db_domain_model_berater', 'EXT:iqtp13db/Resources/Private/Language/locallang.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_iqtp13db_domain_model_dokument', 'EXT:iqtp13db/Resources/Private/Language/locallang.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_iqtp13db_domain_model_historie', 'EXT:iqtp13db/Resources/Private/Language/locallang.xlf');
};

## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder

