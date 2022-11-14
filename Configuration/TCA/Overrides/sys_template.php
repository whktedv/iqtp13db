<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('iqtp13db', 'Configuration/TypoScript', 'IQ Webapp Anerkennungserstberatung NRW');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_iqtp13db_domain_model_teilnehmer', 'EXT:iqtp13db/Resources/Private/Language/locallang.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_iqtp13db_domain_model_folgekontakt', 'EXT:iqtp13db/Resources/Private/Language/locallang.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_iqtp13db_domain_model_dokument', 'EXT:iqtp13db/Resources/Private/Language/locallang.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_iqtp13db_domain_model_historie', 'EXT:iqtp13db/Resources/Private/Language/locallang.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_iqtp13db_domain_model_abschluss', 'EXT:iqtp13db/Resources/Private/Language/locallang.xlf');


