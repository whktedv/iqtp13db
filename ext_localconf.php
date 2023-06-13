<?php

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {
        
        ExtensionUtility::configurePlugin(
            'Iqtp13db',
            'Iqtp13dbadmin',
            [
                \Ud\Iqtp13db\Controller\BackendController::class => 'start, listangemeldet, listerstberatung, listarchiv, checkniqconnection, sendtoniq, sendtoarchiv, show, new, create, edit, update, delete, status, export, askconsent, listdeleted, undelete, savedatenblattpdf, takeover, setBeratungsstellebyPLZ',
                \Ud\Iqtp13db\Controller\FolgekontaktController::class => 'show, new, create, edit, update, delete',
                \Ud\Iqtp13db\Controller\DokumentController::class => 'saveFileBackend, deleteFileBackend, openfile, updateBackend',
                \Ud\Iqtp13db\Controller\HistorieController::class => 'list',
                \Ud\Iqtp13db\Controller\AbschlussController::class => 'show, new, create, edit, update, delete',
                \Ud\Iqtp13db\Controller\BeraterController::class => 'list, edit, update, delete',
                \Ud\Iqtp13db\Controller\AdministrationController::class => 'adminuebersicht'
            ],
            
            // non-cacheable actions
            [
                \Ud\Iqtp13db\Controller\BackendController::class => 'start, listangemeldet, listerstberatung, listarchiv, checkniqconnection, sendtoniq, sendtoarchiv, show, new, create, edit, update, delete, status, export, askconsent, listdeleted, undelete, savedatenblattpdf, takeover, setBeratungsstellebyPLZ',
                \Ud\Iqtp13db\Controller\FolgekontaktController::class => 'show, new, create, edit, update, delete',
                \Ud\Iqtp13db\Controller\DokumentController::class => 'saveFileBackend, deleteFileBackend, openfile, updateBackend',
                \Ud\Iqtp13db\Controller\HistorieController::class => 'list',
                \Ud\Iqtp13db\Controller\AbschlussController::class => 'show, new, create, edit, update, delete',
                \Ud\Iqtp13db\Controller\BeraterController::class => 'list, edit, update, delete',
                \Ud\Iqtp13db\Controller\AdministrationController::class => 'adminuebersicht'
            ]
            );
        
        ExtensionUtility::configurePlugin(
            'Iqtp13db',
            'Iqtp13dbwebapp',
            [
                \Ud\Iqtp13db\Controller\TeilnehmerController::class => 'start, startseite, startseiteplz, anmeldseite0, anmeldseite1, anmeldseite1redirect, anmeldseite2, anmeldseite2redirect, anmeldseite3, anmeldseite3redirect, anmeldseite4, anmeldseite4redirect, anmeldungcomplete, anmeldungcompleteredirect, confirm, validationFailed, wartung, bereitsberaten',
                \Ud\Iqtp13db\Controller\DokumentController::class => 'saveFileWebapp, deleteFileWebapp',
                \Ud\Iqtp13db\Controller\AbschlussController::class => 'newWebapp, createWebapp, editWebapp, updateWebapp, deleteWebapp'
            ],
            // non-cacheable actions
            [
                \Ud\Iqtp13db\Controller\TeilnehmerController::class => 'start, startseite, startseiteplz, anmeldseite0, anmeldseite1, anmeldseite1redirect, anmeldseite2, anmeldseite2redirect, anmeldseite3, anmeldseite3redirect, anmeldseite4, anmeldseite4redirect, anmeldungcomplete, anmeldungcompleteredirect, confirm, validationFailed, wartung, bereitsberaten',
                \Ud\Iqtp13db\Controller\DokumentController::class => 'saveFileWebapp, deleteFileWebapp',
                \Ud\Iqtp13db\Controller\AbschlussController::class => 'newWebapp, createWebapp, editWebapp, updateWebapp, deleteWebapp'
            ]
            );
        
        // Only include page.tsconfig if TYPO3 version is below 12 so that it is not imported twice.
        $versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
        if ($versionInformation->getMajorVersion() < 12) {
            ExtensionManagementUtility::addPageTSConfig('
                 @import "EXT:iqtp13db/Configuration/page.tsconfig"
            ');
        }
        
        
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
        GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
        ->registerImplementation(
            TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup::class,
            \Ud\Iqtp13db\Domain\Model\UserGroup::class
            );
        
        GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
        ->registerImplementation(
            TYPO3\CMS\Extbase\Domain\Model\FrontendUser::class,
            \Ud\Iqtp13db\Domain\Model\Berater::class
            );
        
        /****************
         * Scheduler TASK to delete old/deleted entries
         ****************/
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Ud\Iqtp13db\Task\Task'] = array(
            'extension' => 'iqtp13db',
            'title' => 'Recycler Task für IQ Webapp',
            'description' => 'Lösche gelöschte Datensätze nach 180 Tagen und lösche nicht abgeschlossen Anmeldungen nach 24 Stunden (beratungsstatus = 99)',
        );
},
'iqtp13db'
);


## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder
