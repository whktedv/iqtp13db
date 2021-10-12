<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
	{

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Ud.Iqtp13db',
            'Iqtp13dbadmin',
            [
        		'Teilnehmer' => 'start, listangemeldet, show, new, create, edit, update, delete, status, export, askconsent, listdeleted, undelete',
                'Beratung' => 'listerstberatung, listniqerfassung, listarchiv, show, new, create, edit, update, delete',
        		'Folgekontakt' => 'list, show, new, create, edit, update, delete',
                'Dokument' => 'list, saveFileTeilnehmer, deleteFileTeilnehmer',
                'Berater' => 'list, show, new, create, edit, update, delete',
        		'Historie' => 'list'
            ], 

            // non-cacheable actions
            [
        		'Teilnehmer' => 'start, listangemeldet, show, new, create, edit, update, delete, status, export, askconsent, listdeleted, undelete',
                'Beratung' => 'listerstberatung, listniqerfassung, listarchiv, show, new, create, edit, update, delete',
        		'Folgekontakt' => 'list, show, new, create, edit, update, delete',
                'Dokument' => 'list, saveFileTeilnehmer, deleteFileTeilnehmer',
                'Berater' => 'list, show, new, create, edit, update, delete',
        		'Historie' => 'list'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Ud.Iqtp13db',
            'Iqtp13dbwebapp',
            [
                'Teilnehmer' => 'start, anmeldseite1, anmeldseite1redirect, anmeldseite2, anmeldseite2redirect, anmeldseite3, anmeldseite3redirect, anmeldseite4, anmeldseite4redirect, anmeldungcomplete, anmeldungcompleteredirect, confirm, validationFailed, wartung',
                'Dokument' => 'saveFileWebapp, deleteFileWebapp'
            ],
            // non-cacheable actions
            [
               'Teilnehmer' => 'start, anmeldseite1, anmeldseite1redirect, anmeldseite2, anmeldseite2redirect, anmeldseite3, anmeldseite3redirect, anmeldseite4, anmeldseite4redirect, anmeldungcomplete, anmeldungcompleteredirect, confirm, validationFailed, wartung',
               'Dokument' => 'saveFileWebapp, deleteFileWebapp'
            ]
        );
        

	// wizards
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
		'mod {
			wizards.newContentElement.wizardItems.plugins {
				elements {
					iqtp13dbadmin {
						icon = ' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('iqtp13db') . 'Resources/Public/Icons/user_plugin_iqtp13dbadmin.svg
						title = LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_iqtp13dbadmin
						description = LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_iqtp13dbadmin.description
						tt_content_defValues {
							CType = list
							list_type = iqtp13db_iqtp13dbadmin
						}
					}
					iqtp13dbwebapp {
						icon = ' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('iqtp13db') . 'Resources/Public/Icons/user_plugin_iqtp13dbwebapp.svg
						title = LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_iqtp13dbwebapp
						description = LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_iqtp13dbwebapp.description
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
    'iqtp13db'
);
## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder
