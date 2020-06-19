<?php
namespace Ud\Iqtp13db\Controller;
use \Datetime;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Uli Dohmen <edv@whkt.de>, WHKT
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/***
 *
 * This file is part of the "IQ TP13 Datenbank Anerkennungserstberatung NRW" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Uli Dohmen <edv@whkt.de>, WHKT
 *
 ***/

/**
 * TeilnehmerController
 */
class TeilnehmerController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    // Variablen
    public $teilnehmer = NULL;

    /**
     * teilnehmerRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\TeilnehmerRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $teilnehmerRepository = NULL;

    /**
     * beratungRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\BeratungRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $beratungRepository = NULL;

    /**
     * beraterRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\BeraterRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $beraterRepository = NULL;

    /**
     * schulungRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\SchulungRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $schulungRepository = NULL;
    

    /**
     * action start
     *
     * @return void
     */
    public function startAction()
    {
        $wartungvon = new DateTime($this->settings['wartungvon']);
        $wartungbis = new DateTime($this->settings['wartungbis']);
        
        $datum = strtotime("now");
        
        
        if ($this->settings['modtyp'] == 'beratene') {
            $this->forward('list', 'Teilnehmer', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'schulungen') {
            $this->forward('list', 'Schulung', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'berater') {
            $this->forward('list', 'Berater', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'uebersicht') {
            $this->forward('status', 'Teilnehmer', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'export') {
            $this->forward('export', 'Teilnehmer', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'anmeldung') {
            if($datum >= $wartungvon->getTimestamp() AND $datum <= $wartungbis->getTimestamp())
            {
                $this->forward('wartung', 'Teilnehmer', 'Iqtp13db');
                
            }
            else
            {
                $this->forward('anmeldseite1', 'Teilnehmer', 'Iqtp13db');
                
            }
            
        }
        if ($this->settings['modtyp'] == 'bearbeiten') {
            $this->forward('editextern', 'Teilnehmer', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'loeschen') {
            $this->forward('deleteextern', 'Teilnehmer', 'Iqtp13db');
        }
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $valArray = $this->request->getArguments();
        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($valArray);
        if ($valArray['filteraus']) {
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fname', NULL);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fort', NULL);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fland', NULL);
        }
        if ($valArray['filteran']) {
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fname', $valArray['name']);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fort', $valArray['ort']);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fland', $valArray['land']);
        }
        $fname = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fname');
        $fort = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fort');
        $fland = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fland');
        if ($fname == '' && $fort == '' && $fland == '') {
            $teilnehmers = $this->teilnehmerRepository->findAllOrder4List();
        } else {
            $teilnehmers = $this->teilnehmerRepository->searchTeilnehmer($fname, $fort, $fland);
            $this->view->assign('filtername', $fname);
            $this->view->assign('filterort', $fort);
            $this->view->assign('filterland', $fland);
            $this->view->assign('filteron', 1);
        }
        $this->view->assign('teilnehmers', $teilnehmers);
        $this->view->assign('anztn', count($teilnehmers));
    }

    /**
     * action show
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function showAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $beratungen = $this->beratungRepository->findByTeilnehmer($teilnehmer->getUid());
        $this->view->assign('teilnehmer', $teilnehmer);
        $this->view->assign('beratungen', $beratungen);
        $this->view->assign('anzBeratungen', count($beratungen));
    }

    /**
     * action new
     *
     * @return void
     */
    public function newAction()
    {
       
    	
    }

    /**
     * action create
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function createAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $this->addFlashMessage('Teilnehmer wurde erstellt.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->teilnehmerRepository->add($teilnehmer);
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        $this->redirect('show', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer));
    }

    /**
     * action edit
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function editAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $this->view->assign('teilnehmer', $teilnehmer);
    }

    /**
     * action update
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function updateAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $this->teilnehmerRepository->update($teilnehmer);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function deleteAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $this->teilnehmerRepository->remove($teilnehmer);
        $tnberatungen = $this->beratungRepository->findByTeilnehmer($teilnehmer->getUid());
        foreach ($tnberatungen as $tnb) {
            $this->beratungRepository->remove($tnb);
        }
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        $this->redirect('list');
    }

    /**
     * action status
     *
     * @return void
     */
    public function statusAction()
    {
        $anzberatene = $this->teilnehmerRepository->count4status();
        $anzberatungen = $this->beratungRepository->countAll();
        $anzberater = $this->beraterRepository->countAll();
        $anzschulungen = $this->schulungRepository->countAll();
        $this->view->assign('anzberatene', $anzberatene);
        $this->view->assign('anzberatungen', $anzberatungen);
        $this->view->assign('anzberater', $anzberater);
        $this->view->assign('anzschulungen', $anzschulungen);
    }

    /**************** WARTUNG ****************/
    /**
     * action wartung
     *
     * @return void
     */
    public function wartungAction()
    {
        $this->view->assign('settings', $this->settings);
    }
    
    
    /**************** ANMELDUNG ****************/
    /**
     * action anmeldseite1
     *
     * @param \Ud\Iqtp13db\Domain\Model\TNSeite1 $tnseite1
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("tnseite1")
     * @return void
     */
    public function anmeldseite1Action(\Ud\Iqtp13db\Domain\Model\TNSeite1 $tnseite1 = NULL)
    {
        
        if ($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnseite1') && $tnseite1 == NULL) {
            $tnseite1 = unserialize($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnseite1'));
        }
        
        $this->view->assign('tnseite1', $tnseite1);
        $this->view->assign('settings', $this->settings);
    }
    

    /**
     * action anmeldseite1redirect
     *
     * @param \Ud\Iqtp13db\Domain\Model\TNSeite1 $tnseite1
     * @return void
     */
    public function anmeldseite1redirectAction(\Ud\Iqtp13db\Domain\Model\TNSeite1 $tnseite1)
    {
        $valArray = $this->request->getArguments();
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'tnseite1', serialize($tnseite1));
        $this->redirect('anmeldseite2');
    }

    /**
     * action anmeldseite2
     *
     * @param \Ud\Iqtp13db\Domain\Model\TNSeite2 $tnseite2
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("tnseite1")
     * @return void
     */
    public function anmeldseite2Action(\Ud\Iqtp13db\Domain\Model\TNSeite2 $tnseite2 = NULL)
    {
        if ($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnseite2') && $tnseite2 == NULL) {
            $tnseite2 = unserialize($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnseite2'));
        }
        $this->view->assign('tnseite2', $tnseite2);
        $this->view->assign('settings', $this->settings);
    }

    /**
     * action anmeldseite2redirect
     *
     * @param \Ud\Iqtp13db\Domain\Model\TNSeite2 $tnseite2
     * @return void
     */
    public function anmeldseite2redirectAction(\Ud\Iqtp13db\Domain\Model\TNSeite2 $tnseite2)
    {
        $valArray = $this->request->getArguments();
        //		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($valArray);
        //		die;
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'tnseite2', serialize($tnseite2));
        if (isset($valArray['btnzurueck'])) {
            $this->redirect('anmeldseite1');
        } else {
            $this->redirect('anmeldseite3');
        }
    }

    /**
     * action anmeldseite3
     *
     * @param \Ud\Iqtp13db\Domain\Model\TNSeite3 $tnseite3
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("tnseite3")
     * @return void
     */
    public function anmeldseite3Action(\Ud\Iqtp13db\Domain\Model\TNSeite3 $tnseite3 = NULL)
    {
        if ($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnseite3') && $tnseite3 == NULL) {
            $tnseite3 = unserialize($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnseite3'));
        }
        $this->view->assign('settings', $this->settings);
        $this->view->assign('tnseite3', $tnseite3);
    }

    /**
     * action anmeldseite3redirect
     *
     * @param \Ud\Iqtp13db\Domain\Model\TNSeite3 $tnseite3
     * @return void
     */
    public function anmeldseite3redirectAction(\Ud\Iqtp13db\Domain\Model\TNSeite3 $tnseite3)
    {
        $valArray = $this->request->getArguments();
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'tnseite3', serialize($tnseite3));
        if (isset($valArray['btnzurueck'])) {
            $this->redirect('anmeldseite2');
        } else {
            $this->redirect('anmeldseite4', 'Beratung', null, array('beratungseite4' => NULL));
        }
    }

    /**
     * action editextern
     *
     * @return void
     */
    public function editexternAction()
    {
        if (isset($_GET['uid']) && isset($_GET['ts'])) {
            if ($this->teilnehmerRepository->countByUid($_GET['uid']) != 0) {
                $teilnehmer = $this->teilnehmerRepository->findByUid($_GET['uid']);
                if ($teilnehmer->getCrdate() == $_GET['ts']) {
                    $this->setTeilnehmerToSession($teilnehmer);
                    $this->redirect('anmeldseite1');
                }
            }
        }
    }

    /**
     * action deleteextern
     *
     * @return void
     */
    public function deleteexternAction()
    {
        $valArray = $this->request->getArguments();
        if (isset($valArray['cancelbutton'])) {
            $this->redirectToUri($this->settings['startseitelink']);
        }
        if (isset($valArray['deletebutton'])) {
            $teilnehmer = $this->teilnehmerRepository->findByUid($valArray['uid']);
            $this->teilnehmerRepository->remove($teilnehmer);
            $beratungarr = $this->beratungRepository->findByTeilnehmer($valArray['uid']);
            foreach ($beratungarr as $beratung) {
                $this->beratungRepository->remove($beratung);
            }
            // Daten sofort in die Datenbank schreiben
            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
            $this->redirectToUri($this->settings['bestaetigunggeloeschtlink']);
        }
        if (isset($_GET['uid']) && isset($_GET['ts'])) {
            if ($this->teilnehmerRepository->countByUid($_GET['uid']) != 0) {
                $teilnehmer = $this->teilnehmerRepository->findByUid($_GET['uid']);
                if ($teilnehmer->getCrdate() == $_GET['ts']) {
                    $this->view->assign('uid', $_GET['uid']);
                }
            } else {
                // Teilnehmer nicht gefunden
                $this->redirectToUri($this->settings['startseitelink']);
            }
        }
    }

    public function errorAction()
    {
        $this->clearCacheOnError();
        $referringRequest = $this->request->getReferringRequest();
        if ($referringRequest !== NULL) {
            $originalRequest = clone $this->request;
            //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($originalRequest);
            $this->request->setOriginalRequest($originalRequest);
            $this->request->setOriginalRequestMappingResults($this->arguments->validate());
            $this->forward($referringRequest->getControllerActionName(), $referringRequest->getControllerName(), $referringRequest->getControllerExtensionName(), $referringRequest->getArguments());
        }
    }

    /**
     * Stores Values to session vars from Teilnehmer object
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return null
     */
    protected function setTeilnehmerToSession(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        if ($this->beratungRepository->countByTeilnehmer($teilnehmer->getUid()) > 0) {
            $beratung = $this->beratungRepository->findOneByTeilnehmer($teilnehmer->getUid());
            $beratungseite4 = $this->objectManager->get('Ud\\Iqtp13db\\Domain\\Model\\BeratungSeite4');
            $beratungseite4->setDatum($beratung->getDatum());
            $beratungseite4->setWegBeratungsstelle($beratung->getWegBeratungsstelle());
            $berater = $this->beraterRepository->findByUid($beratung->getBerater());
            if ($berater != NULL) {
                $beratungseite4->setBerater($berater);
            }
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'beratungseite4', serialize($beratungseite4));
        }
        $tnseite1 = $this->objectManager->get('Ud\\Iqtp13db\\Domain\\Model\\TNSeite1');
        $tnseite2 = $this->objectManager->get('Ud\\Iqtp13db\\Domain\\Model\\TNSeite2');
        $tnseite3 = $this->objectManager->get('Ud\\Iqtp13db\\Domain\\Model\\TNSeite3');
        $tnseite1->setEinwilligung($teilnehmer->getEinwilligung());
        $tnseite1->setNachname($teilnehmer->getNachname());
        $tnseite1->setVorname($teilnehmer->getVorname());
        $tnseite1->setStrasse($teilnehmer->getStrasse());
        $tnseite1->setPlz($teilnehmer->getPlz());
        $tnseite1->setOrt($teilnehmer->getOrt());
        $tnseite1->setEmail($teilnehmer->getEmail());
        $tnseite1->setTelefon($teilnehmer->getTelefon());
        $tnseite1->setGeburtsjahr($teilnehmer->getGeburtsjahr());
        $tnseite1->setGeburtsland($teilnehmer->getGeburtsland());
        $tnseite1->setGeschlecht($teilnehmer->getGeschlecht());
        $tnseite1->setErsteStaatsangehoerigkeit($teilnehmer->getErsteStaatsangehoerigkeit());
        $tnseite1->setZweiteStaatsangehoerigkeit($teilnehmer->getZweiteStaatsangehoerigkeit());
        $tnseite1->setEinreisejahr($teilnehmer->getEinreisejahr());
        $tnseite1->setWohnsitzDeutschland($teilnehmer->getWohnsitzDeutschland());
        $tnseite1->setWohnsitzJaBundesland($teilnehmer->getWohnsitzJaBundesland());
        $tnseite1->setWohnsitzNeinIn($teilnehmer->getWohnsitzNeinIn());
        $tnseite1->setGeplanteEinreise($teilnehmer->getGeplanteEinreise());
        $tnseite1->setKontaktVisastelle($teilnehmer->getKontaktVisastelle());
        $tnseite1->setVisumsantrag($teilnehmer->getVisumsantrag());
        $tnseite2->setDeutschkenntnisse($teilnehmer->getDeutschkenntnisse());
        $tnseite2->setZertifikatSprachniveau($teilnehmer->getZertifikatSprachniveau());
        $tnseite2->setZertifikatdeutsch($teilnehmer->getZertifikatdeutsch());
        $tnseite2->setSprachen($teilnehmer->getSprachen());
        $tnseite2->setAbschlussartA($teilnehmer->getAbschlussartA());
        $tnseite2->setAbschlussartH($teilnehmer->getAbschlussartH());
        $tnseite2->setErwerbsland1($teilnehmer->getErwerbsland1());
        $tnseite2->setDauerBerufsausbildung1($teilnehmer->getDauerBerufsausbildung1());
        $tnseite2->setAbschlussjahr1($teilnehmer->getAbschlussjahr1());
        $tnseite2->setAusbildungsinstitution1($teilnehmer->getAusbildungsinstitution1());
        $tnseite2->setAusbildungsort1($teilnehmer->getAusbildungsort1());
        $tnseite2->setAbschluss1($teilnehmer->getAbschluss1());
        $tnseite2->setDeutschAbschlusstitel1($teilnehmer->getDeutschAbschlusstitel1());
        $tnseite2->setBerufserfahrung1($teilnehmer->getBerufserfahrung1());
        $tnseite2->setDeutscherReferenzberuf1($teilnehmer->getDeutscherReferenzberuf1());
        $tnseite2->setWunschberuf1($teilnehmer->getWunschberuf1());
        $tnseite2->setOriginalDokumenteAbschluss1($teilnehmer->getOriginalDokumenteAbschluss1());
        $tnseite2->setErwerbsland2($teilnehmer->getErwerbsland2());
        $tnseite2->setDauerBerufsausbildung2($teilnehmer->getDauerBerufsausbildung2());
        $tnseite2->setAbschlussjahr2($teilnehmer->getAbschlussjahr2());
        $tnseite2->setAusbildungsinstitution2($teilnehmer->getAusbildungsinstitution2());
        $tnseite2->setAusbildungsort2($teilnehmer->getAusbildungsort2());
        $tnseite2->setAbschluss2($teilnehmer->getAbschluss2());
        $tnseite2->setDeutschAbschlusstitel2($teilnehmer->getDeutschAbschlusstitel2());
        $tnseite2->setBerufserfahrung2($teilnehmer->getBerufserfahrung2());
        $tnseite2->setDeutscherReferenzberuf2($teilnehmer->getDeutscherReferenzberuf2());
        $tnseite2->setWunschberuf2($teilnehmer->getWunschberuf2());
        $tnseite2->setOriginalDokumenteAbschluss2($teilnehmer->getOriginalDokumenteAbschluss2());
        $tnseite3->setErwerbsstatus($teilnehmer->getErwerbsstatus());
        $tnseite3->setLeistungsbezug($teilnehmer->getLeistungsbezug());
        $tnseite3->setFruehererAntrag($teilnehmer->getFruehererAntrag());
        $tnseite3->setFruehererAntragReferenzberuf($teilnehmer->getFruehererAntragReferenzberuf());
        $tnseite3->setFruehererAntragInstitution($teilnehmer->getFruehererAntragInstitution());
        $tnseite3->setBescheidfruehererAnerkennungsantrag($teilnehmer->getBescheidfruehererAnerkennungsantrag());
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'tnuid', $teilnehmer->getUid());
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'tnseite1', serialize($tnseite1));
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'tnseite2', serialize($tnseite2));
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'tnseite3', serialize($tnseite3));
        return;
    }

    /**
     * A template method for displaying custom error flash messages, or to
     * display no flash message at all on errors. Override this to customize
     * the flash message in your action controller.
     *
     * @api
     * @return string|boolean The flash message or FALSE if no flash message should be set
     */
    protected function getErrorFlashMessage()
    {
        return FALSE;
    }

    /**
     * @param $settingsarr
     * @param $ergarrwert
     */
    function getValu($settingsarr, $ergarrwert)
    {
        $ret = '';
        foreach ($settingsarr as $key => $value) {
            if ($ergarrwert == $value) {
                $ret = $key;
                break;
            }
        }
        return $ret;
    }
    
    
    /**
     * action export
     *
     * @return void
     */
    public function exportAction()
    {
        $valArray = $this->request->getArguments();
        
        // ******************** EXPORT ****************************
        if ($valArray['export'] == 'Daten exportieren') {
            //$this->addFlashMessage('Export.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $filename = 'export_' . date('Y-m-d_H-i', time()) . '.csv';
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename=' . $filename);
            $fp = fopen('php://output', 'w');
            
            // Get a query builder for a query on table "tt_content"
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_iqtp13db_domain_model_teilnehmer');
            // Remove all default restrictions (delete, hidden, starttime, stoptime)
            //$queryBuilder->getRestrictions()->removeAll();
            
            $rows = $queryBuilder
                ->select('*')
                ->from('tx_iqtp13db_domain_model_teilnehmer')
                ->leftJoin(
                    'tx_iqtp13db_domain_model_teilnehmer',
                    'tx_iqtp13db_domain_model_beratung',
                    'b',
                    $queryBuilder->expr()->eq('b.teilnehmer', $queryBuilder->quoteIdentifier('tx_iqtp13db_domain_model_teilnehmer.uid'))
                )
                ->execute()
                ->fetchAll();
            
            // output header row (if at least one row exists)
            if (count($rows) > 0) {
                fputcsv($fp, array_keys($rows[0]));
            }

            for($i=0; $i < count($rows); $i++) {
                fputcsv($fp, $rows[$i]);
            }
            fclose($fp);
            exit;
        }
        
    }
    
  
    
    
    /**
     * action anmeldung
     *
     * @return void
     */
    public function anmeldungAction()
    {

    }
}
