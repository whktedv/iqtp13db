<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

call_user_func(
    function($extKey)
    {
        
        ExtensionUtility::configurePlugin(
            'Iqtp13db',
            'Iqtp13dbadmin',
            [
                \Ud\Iqtp13db\Controller\BackendController::class => 'start, listangemeldet, listerstberatung, listarchiv, checkniqconnection, sendtoniq, sendtoarchiv, unarchive, show, new, create, edit, update, delete, status, export, askconsent, listdeleted, undelete, savedatenblattpdf, takeover, setBeratungsstellebyPLZ, saveAVpdf, showsearchresult, editsettings, updatesettings, mail4editextern, mail4datenblatt',
                \Ud\Iqtp13db\Controller\FolgekontaktController::class => 'show, new, create, edit, update, delete, listall',
                \Ud\Iqtp13db\Controller\DokumentController::class => 'saveFileBackend, savemultiFileBackend, deleteFileBackend, openfile, updateBackend, dokdownload',
                \Ud\Iqtp13db\Controller\HistorieController::class => 'list',
                \Ud\Iqtp13db\Controller\AbschlussController::class => 'show, new, create, edit, update, delete',
                \Ud\Iqtp13db\Controller\BeraterController::class => 'list, edit, update, delete, enableGruppenberatungen',
                \Ud\Iqtp13db\Controller\AdministrationController::class => 'adminuebersicht',
                \Ud\Iqtp13db\Controller\GruppenberatungController::class => 'listgruppenberatung, show, new, create, edit, update, delete, removeFromGroupConsultation, removeAllFromGroupConsultation, archiveAllFromGroupConsultation'
            ],
            
            // non-cacheable actions
            [
                \Ud\Iqtp13db\Controller\BackendController::class => 'start, listangemeldet, listerstberatung, listarchiv, checkniqconnection, sendtoniq, sendtoarchiv, unarchive, show, new, create, edit, update, delete, status, export, askconsent, listdeleted, undelete, savedatenblattpdf, takeover, setBeratungsstellebyPLZ, saveAVpdf, showsearchresult, editsettings, updatesettings, mail4editextern, mail4datenblatt',
                \Ud\Iqtp13db\Controller\FolgekontaktController::class => 'show, new, create, edit, update, delete, listall',
                \Ud\Iqtp13db\Controller\DokumentController::class => 'saveFileBackend, savemultiFileBackend, deleteFileBackend, openfile, updateBackend, dokdownload',
                \Ud\Iqtp13db\Controller\HistorieController::class => 'list',
                \Ud\Iqtp13db\Controller\AbschlussController::class => 'show, new, create, edit, update, delete',
                \Ud\Iqtp13db\Controller\BeraterController::class => 'list, edit, update, delete, enableGruppenberatungen',
                \Ud\Iqtp13db\Controller\AdministrationController::class => 'adminuebersicht',
                \Ud\Iqtp13db\Controller\GruppenberatungController::class => 'listgruppenberatung, show, new, create, edit, update, delete, removeFromGroupConsultation, removeAllFromGroupConsultation, archiveAllFromGroupConsultation'
            ]
            );
        
        ExtensionUtility::configurePlugin(
            'Iqtp13db',
            'Iqtp13dbwebapp',
            [
                \Ud\Iqtp13db\Controller\TeilnehmerController::class => 'start, startseite, startseiteplz, anmeldseite0, anmeldseite1, anmeldseite1redirect, anmeldseite2, anmeldseite2redirect, anmeldseite3, anmeldseite3redirect, anmeldseite4, anmeldseite4redirect, anmeldungcomplete, anmeldungcompleteredirect, confirm, validationFailed, wartung, bereitsberaten, cancelregistration, editextern, editexternredirect, editexternmenu',
                \Ud\Iqtp13db\Controller\DokumentController::class => 'saveFileWebapp, savemultiFileWebapp, deleteFileWebapp, openfileextern, deletefileextern, uploadfileextern',
                \Ud\Iqtp13db\Controller\AbschlussController::class => 'newWebapp, createWebapp, editWebapp, updateWebapp, deleteWebapp'
            ],
            // non-cacheable actions
            [
                \Ud\Iqtp13db\Controller\TeilnehmerController::class => 'start, startseite, startseiteplz, anmeldseite0, anmeldseite1, anmeldseite1redirect, anmeldseite2, anmeldseite2redirect, anmeldseite3, anmeldseite3redirect, anmeldseite4, anmeldseite4redirect, anmeldungcomplete, anmeldungcompleteredirect, confirm, validationFailed, wartung, bereitsberaten, cancelregistration, editextern, editexternredirect, editexternmenu',
                \Ud\Iqtp13db\Controller\DokumentController::class => 'saveFileWebapp, savemultiFileWebapp, deleteFileWebapp, openfileextern, deletefileextern, uploadfileextern',
                \Ud\Iqtp13db\Controller\AbschlussController::class => 'newWebapp, createWebapp, editWebapp, updateWebapp, deleteWebapp'
            ]
            );
        
        ExtensionUtility::configurePlugin(
            'Iqtp13db',
            'Json',
            [
                \Ud\Iqtp13db\Controller\TeilnehmerController::class => 'show'
            ],
            // non-cacheable actions
            [
                \Ud\Iqtp13db\Controller\TeilnehmerController::class => 'show'
            ]
            );
        
        // Register eID calls
        $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['doksave'] = \Ud\Iqtp13db\Controller\RequestController::class . '::doksaveEidAction';        
        $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['tneditlinksave'] = \Ud\Iqtp13db\Controller\RequestController::class . '::tneditlinksaveEidAction';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['iqtp13db_download'] = \Ud\Iqtp13db\Controller\DownloadController::class . '::processRequest';
        // diese drei für die Gruppenberatungsfunktion
        $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['iqtp13db_update'] = \Ud\Iqtp13db\Controller\RequestController::class . '::updateSelectionAction';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['iqtp13db_toggle'] = \Ud\Iqtp13db\Controller\RequestController::class . '::toggleCheckboxesAction';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['iqtp13db_submit'] = \Ud\Iqtp13db\Controller\RequestController::class . '::addToGroupConsultationAction';

        
        /****************
         * Scheduler TASK to delete old/deleted entries
         ****************/
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Ud\Iqtp13db\Task\Task'] = array(
            'extension' => 'iqtp13db',
            'title' => 'Recycler Task für IQ Webapp',
            'description' => 'Lösche gelöschte Datensätze nach 180 Tagen und lösche nicht abgeschlossen Anmeldungen nach 24 Stunden (beratungsstatus = 99)',
        );
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Ud\Iqtp13db\Task\StatistikCacheTask'] = array(
            'extension' => 'iqtp13db',
            'title' => 'Statistik-Cache neu berechnen für IQ Webapp',
            'description' => 'Berechnet täglich aggregierte Statistiken pro Beratungsstelle und schreibt sie in tx_iqtp13db_domain_model_statistik_cache.',
            'additionalFields' => \Ud\Iqtp13db\Task\StatistikCacheTaskAdditionalFieldProvider::class,
        );        
},
'iqtp13db'
);


## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder
