<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
	{

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Ud.Iqtp13db',
            'Iqtp13dbadmin',
            [
                'Teilnehmer' => 'start, list, show, new, create, edit, update, delete, status, export',
                'Beratung' => 'list, show, new, create, edit, update, delete',
                'Dokument' => 'list, saveFileBeratung, saveFileSchulung, deleteFileBeratung, deleteFileSchulung',
                'Berater' => 'list, show, new, create, edit, update, delete',
                'Schulung' => 'list, show, new, create, edit, update, delete'
            ], 

            // non-cacheable actions
            [
                'Teilnehmer' => 'start, list, show, new, create, edit, update, delete, status, export',
                'Beratung' => 'list, show, new, create, edit, update, delete',
                'Dokument' => 'list, saveFileBeratung, saveFileSchulung, deleteFileBeratung, deleteFileSchulung',
                'Berater' => 'list, show, new, create, edit, update, delete',
                'Schulung' => 'list, show, new, create, edit, update, delete'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Ud.Iqtp13db',
            'Iqtp13dbwebapp',
            [
                'Teilnehmer' => 'start, anmeldung, anmeldseite1, anmeldseite1redirect, anmeldseite2, anmeldseite2redirect, anmeldseite3, anmeldseite3redirect, editextern, deleteextern, wartung',
                'Beratung' => 'anmeldseite4, anmeldseite4redirect, anmeldungcomplete, anmeldungcompleteredirect, confirm, validationFailed',
                'Dokument' => 'saveFileBeratungExtern, deleteFileBeratungExtern'
            ],
            // non-cacheable actions
            [
               'Teilnehmer' => 'start, anmeldung, anmeldseite1, anmeldseite1redirect, anmeldseite2, anmeldseite2redirect, anmeldseite3, anmeldseite3redirect, editextern, deleteextern, wartung',
                'Beratung' => 'anmeldseite4, anmeldseite4redirect, anmeldungcomplete, anmeldungcompleteredirect, confirm, validationFailed',
                'Dokument' => 'saveFileBeratungExtern, deleteFileBeratungExtern'
            ]
        );

	// wizards
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
		'mod {
			wizards.newContentElement.wizardItems.plugins {
				elements {
					iqtp13dbadmin {
						icon = ' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extKey) . 'Resources/Public/Icons/user_plugin_iqtp13dbadmin.svg
						title = LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_iqtp13dbadmin
						description = LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_iqtp13dbadmin.description
						tt_content_defValues {
							CType = list
							list_type = iqtp13db_iqtp13dbadmin
						}
					}
					iqtp13dbwebapp {
						icon = ' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extKey) . 'Resources/Public/Icons/user_plugin_iqtp13dbwebapp.svg
						title = LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_iqtp13dbwebapp
						description = LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_iqtp13dbwebapp.description
						tt_content_defValues {
							CType = list
							list_type = iqtp13db_iqtp13dbwebapp
						}
					}
				}
				show = *
			}
	   }'
	);
    },
    $_EXTKEY
);
## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder
