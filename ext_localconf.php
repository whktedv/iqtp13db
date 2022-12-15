<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {
        
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Ud.Iqtp13db',
            'Iqtp13dbadmin',
            [
                \Ud\Iqtp13db\Controller\TeilnehmerController::class => 'start, listangemeldet, listerstberatung, listarchiv, checkniqconnection, sendtoniq, show, new, create, edit, update, delete, status, export, askconsent, listdeleted, undelete, savedatenblattpdf',
                \Ud\Iqtp13db\Controller\FolgekontaktController::class => 'list, show, new, create, edit, update, delete',
                \Ud\Iqtp13db\Controller\DokumentController::class => 'saveFileBackend, deleteFileBackend, openfile',
                \Ud\Iqtp13db\Controller\HistorieController::class => 'list',
                \Ud\Iqtp13db\Controller\AbschlussController::class => 'show, new, create, edit, update, delete',
                \Ud\Iqtp13db\Controller\BeraterController::class => 'list, new, create, edit, update, delete'
            ],
            
            // non-cacheable actions
            [
                \Ud\Iqtp13db\Controller\TeilnehmerController::class => 'start, listangemeldet, listerstberatung, listarchiv, checkniqconnection, sendtoniq, show, new, create, edit, update, delete, status, export, askconsent, listdeleted, undelete, savedatenblattpdf',
                \Ud\Iqtp13db\Controller\FolgekontaktController::class => 'list, show, new, create, edit, update, delete',
                \Ud\Iqtp13db\Controller\DokumentController::class => 'saveFileBackend, deleteFileBackend, openfile',
                \Ud\Iqtp13db\Controller\HistorieController::class => 'list',
                \Ud\Iqtp13db\Controller\AbschlussController::class => 'show, new, create, edit, update, delete',
                \Ud\Iqtp13db\Controller\BeraterController::class => 'list, new, create, edit, update, delete'
            ]
            );
        
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Ud.Iqtp13db',
            'Iqtp13dbwebapp',
            [
                \Ud\Iqtp13db\Controller\TeilnehmerController::class => 'start, startseite, anmeldseite1, anmeldseite1redirect, anmeldseite2, anmeldseite2redirect, anmeldseite3, anmeldseite3redirect, anmeldseite4, anmeldseite4redirect, anmeldungcomplete, anmeldungcompleteredirect, confirm, validationFailed, wartung',
                \Ud\Iqtp13db\Controller\DokumentController::class => 'saveFileWebapp, deleteFileWebapp',
                \Ud\Iqtp13db\Controller\AbschlussController::class => 'addupdateWebapp, deleteWebapp, selectWebapp'
            ],
            // non-cacheable actions
            [
                \Ud\Iqtp13db\Controller\TeilnehmerController::class => 'start, startseite, anmeldseite1, anmeldseite1redirect, anmeldseite2, anmeldseite2redirect, anmeldseite3, anmeldseite3redirect, anmeldseite4, anmeldseite4redirect, anmeldungcomplete, anmeldungcompleteredirect, confirm, validationFailed, wartung',
                \Ud\Iqtp13db\Controller\DokumentController::class => 'saveFileWebapp, deleteFileWebapp',
                \Ud\Iqtp13db\Controller\AbschlussController::class => 'addupdateWebapp, deleteWebapp, selectWebapp'
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
        
        /************************************************************************
         * XCLASS (Extending Classes) für FrontendUsergroup Klasse
         ************************************************************************/
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup::class] = [
            'className' => \Ud\Iqtp13db\Domain\Model\UserGroup::class,
        ];
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][TYPO3\CMS\Extbase\Domain\Model\FrontendUser::class] = [
            'className' => \Ud\Iqtp13db\Domain\Model\Berater::class,
        ];
        
        // Register extended domain class
        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
        ->registerImplementation(
            TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup::class,
            \Ud\Iqtp13db\Domain\Model\UserGroup::class
            );
        
        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
        ->registerImplementation(
            TYPO3\CMS\Extbase\Domain\Model\FrontendUser::class,
            \Ud\Iqtp13db\Domain\Model\Berater::class
            );
        
},
'iqtp13db'
);


## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder
