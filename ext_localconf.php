<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
	{

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Ud.Iqtp13db',
            'Iqtp13dbadmin',
            [
        		'Teilnehmer' => 'start, listangemeldet, listerstberatung, listarchiv, checkniqconnection, sendtoniq, show, new, create, edit, update, delete, status, export, askconsent, listdeleted, undelete, savedatenblattpdf',
        		'Folgekontakt' => 'list, show, new, create, edit, update, delete',
                'Dokument' => 'saveFileTeilnehmer, deleteFileTeilnehmer, openfile',
        		'Historie' => 'list',
                'Abschluss' => 'show, new, create, edit, update, delete',
                'Berater' => 'list, new, create, edit, update, delete'
            ], 

            // non-cacheable actions
            [
        		'Teilnehmer' => 'start, listangemeldet, listerstberatung, listarchiv, checkniqconnection, sendtoniq, show, new, create, edit, update, delete, status, export, askconsent, listdeleted, undelete, savedatenblattpdf',
        		'Folgekontakt' => 'list, show, new, create, edit, update, delete',
                'Dokument' => 'saveFileTeilnehmer, deleteFileTeilnehmer, openfile',
        		'Historie' => 'list',
                'Abschluss' => 'show, new, create, edit, update, delete',
                'Berater' => 'list, new, create, edit, update, delete'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Ud.Iqtp13db',
            'Iqtp13dbwebapp',
            [
                'Teilnehmer' => 'start, startseite, anmeldseite1, anmeldseite1redirect, anmeldseite2, anmeldseite2redirect, anmeldseite3, anmeldseite3redirect, anmeldseite4, anmeldseite4redirect, anmeldungcomplete, anmeldungcompleteredirect, confirm, validationFailed, wartung',
                'Dokument' => 'saveFileWebapp, deleteFileWebapp',
                'Abschluss' => 'addupdateWebapp, deleteWebapp, selectWebapp'
            ],
            // non-cacheable actions
            [
               'Teilnehmer' => 'start, startseite, anmeldseite1, anmeldseite1redirect, anmeldseite2, anmeldseite2redirect, anmeldseite3, anmeldseite3redirect, anmeldseite4, anmeldseite4redirect, anmeldungcomplete, anmeldungcompleteredirect, confirm, validationFailed, wartung',
               'Dokument' => 'saveFileWebapp, deleteFileWebapp',
               'Abschluss' => 'addupdateWebapp, deleteWebapp, selectWebapp'
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
	
	\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)->registerImplementation(\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup::class, \Ud\Iqtp13db\Domain\Model\UserGroup::class);
	
    },
    'iqtp13db'
);


## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder
