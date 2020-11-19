<?php
namespace Ud\Iqtp13db\Controller;

/***
 *
 * This file is part of the "IQ TP13 Datenbank Anerkennungserstberatung NRW" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Uli Dohmen <edv@whkt.de>, WHKT
 * 
 ***/

/**
 * BeratungController
 */
class BeratungController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    public $dokumente = NULL;

    /**
     * beratungRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\BeratungRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $beratungRepository = NULL;

    /**
     * teilnehmerRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\TeilnehmerRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $teilnehmerRepository = NULL;

    /**
     * dokumentRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\DokumentRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $dokumentRepository = NULL;

    /**
     * beraterRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\BeraterRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $beraterRepository = NULL;      
    
    /**
     * action init
     *
     * @param void
     */
    public function initializeAction()
    {
        if (isset($this->arguments['beratungseite4'])) {
            $this->arguments->getArgument('beratungseite4')->getPropertyMappingConfiguration()->forProperty('datum')->setTypeConverterOption('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter', \TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'd.m.Y');
        }
        if (isset($this->arguments['beratung'])) {
            $this->arguments->getArgument('beratung')->getPropertyMappingConfiguration()->forProperty('datum')->setTypeConverterOption('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter', \TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'd.m.Y');
        }
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $beratungs = $this->beratungRepository->findAll();
        $this->view->assign('beratungs', $beratungs);
    }

    /**
     * action show
     *
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function showAction(\Ud\Iqtp13db\Domain\Model\Beratung $beratung, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $dokumente = $this->dokumentRepository->findByBeratung($beratung);
        $berater = $this->beraterRepository->findAll();
        $this->view->assign('berater', $berater);
        $this->view->assign('beratung', $beratung);
        $this->view->assign('teilnehmer', $teilnehmer);
        $this->view->assign('dokumente', $dokumente);
    }

    /**
     * action new
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function newAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $berater = $this->beraterRepository->findAll();
        $this->view->assign('berater', $berater);
        $this->view->assign('teilnehmer', $teilnehmer);
    }

    /**
     * action create
     *
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function createAction(\Ud\Iqtp13db\Domain\Model\Beratung $beratung, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $this->addFlashMessage('Beratung wurde erstellt.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->beratungRepository->add($beratung);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        $anzberatungen = count($this->beratungRepository->findByTeilnehmer($teilnehmer->getUid()));
        $teilnehmer->setAnzBeratungen($anzberatungen);
        $this->teilnehmerRepository->update($teilnehmer);
        $this->redirect('show', 'Beratung', null, array('beratung' => $beratung, 'teilnehmer' => $teilnehmer));
    }

    /**
     * action edit
     *
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("beratung")
     * @return void
     */
    public function editAction(\Ud\Iqtp13db\Domain\Model\Beratung $beratung, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $berater = $this->beraterRepository->findAll();
        $this->view->assign('berater', $berater);
        $this->view->assign('beratung', $beratung);
        $this->view->assign('teilnehmer', $teilnehmer);
    }

    /**
     * action update
     *
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function updateAction(\Ud\Iqtp13db\Domain\Model\Beratung $beratung, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $this->addFlashMessage('Beratung aktualisiert.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->beratungRepository->update($beratung);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        $anzberatungen = count($this->beratungRepository->findByTeilnehmer($teilnehmer->getUid()));
        $teilnehmer->setAnzBeratungen($anzberatungen);
        $this->teilnehmerRepository->update($teilnehmer);
        $this->redirect('show', 'Beratung', null, array('beratung' => $beratung, 'teilnehmer' => $teilnehmer));
    }

    /**
     * action delete
     *
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function deleteAction(\Ud\Iqtp13db\Domain\Model\Beratung $beratung, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $this->addFlashMessage('Beratung gelöscht.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->beratungRepository->remove($beratung);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        $anzberatungen = count($this->beratungRepository->findByTeilnehmer($teilnehmer->getUid()));
        $teilnehmer->setAnzBeratungen($anzberatungen);
        $this->teilnehmerRepository->update($teilnehmer);
        $this->redirect('show', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer));
    }

    /******************** ANMELDUNG *********************/
    /**
     * action anmeldseite4
     *
     * @param \Ud\Iqtp13db\Domain\Model\BeratungSeite4 $beratungseite4
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("beratungseite4")
     * @return void
     */
    public function anmeldseite4Action(\Ud\Iqtp13db\Domain\Model\BeratungSeite4 $beratungseite4 = NULL)
    {
        if ($GLOBALS['TSFE']->fe_user->getKey('ses', 'beratungseite4') && $beratungseite4 == NULL) {
            $beratungseite4 = unserialize($GLOBALS['TSFE']->fe_user->getKey('ses', 'beratungseite4'));
        }
        $berater = $this->beraterRepository->findAll();
       
        $this->view->assign('beratungseite4', $beratungseite4);
        $this->view->assign('heute', time());
        $this->view->assign('berater', $berater);
    }

    /**
     * action anmeldseite4redirect
     *
     * @param \Ud\Iqtp13db\Domain\Model\BeratungSeite4 $beratungseite4
     * @return void
     */
    public function anmeldseite4redirectAction(\Ud\Iqtp13db\Domain\Model\BeratungSeite4 $beratungseite4)
    {
        $valArray = $this->request->getArguments();
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'beratungseite4', serialize($beratungseite4));
                
        
        if (isset($valArray['btnzurueck'])) {
            $this->redirect('anmeldseite3', 'Teilnehmer', null, array('tnseite3' => NULL));
        } elseif(isset($valArray['btncancel'])) {
            $this->cleanUpSessionData();
            $this->redirectToURI('https://www.iq-netzwerk-nrw.de/startseite-webapp-anerkennungserstberatung/');
        } else {
            if ($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnuid') == NULL) {
                $teilnehmer = $this->getTeilnehmerFromSession();
                $this->teilnehmerRepository->add($teilnehmer);
                
                // Daten sofort in die Datenbank schreiben
                $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
                $persistenceManager->persistAll();
                $GLOBALS['TSFE']->fe_user->setKey('ses', 'tnuid', $teilnehmer->getUid());

                $beratung = $this->getBeratungFromSession();
                $beratung->setTeilnehmer($teilnehmer);
                $this->beratungRepository->add($beratung);
                
                // Daten sofort in die Datenbank schreiben
                $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
                $persistenceManager->persistAll();
            } else {
                $teilnehmer = $this->teilnehmerRepository->findByUid($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnuid'));
                $teilnehmer = $this->getTeilnehmerFromSession($teilnehmer);
                $this->teilnehmerRepository->update($teilnehmer);
                $beratung = $this->objectManager->get('Ud\\Iqtp13db\\Domain\\Model\\Beratung');
                $beratung = $this->beratungRepository->findOneByTeilnehmer($teilnehmer);
                $beratung = $this->getBeratungFromSession($beratung);
                $this->beratungRepository->update($beratung);
            }
            $this->redirect('anmeldungcomplete', 'Beratung', 'Iqtp13db', array('beratung' => $beratung, 'teilnehmer' => $teilnehmer));
        }
    }

    /**
     * action anmeldungcomplete
     *
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("beratung"), @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function anmeldungcompleteAction(\Ud\Iqtp13db\Domain\Model\Beratung $beratung, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {        
        $valArray = $this->request->getArguments();
        
        $newFilePath = 'Beratene/' . $teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid(). '/';
        $storage = $this->getTP13Storage($newFilePath);
        $foldersize = $this->getFolderSize($storage->getConfiguration()['basePath'].$newFilePath);
        
        $berater = $beratung->getBerater();
        $teilnehmer = $beratung->getTeilnehmer();
        $dokumente = $this->dokumentRepository->findByBeratung($beratung);
        $this->view->assign('heute', time());
        $this->view->assign('teilnehmer', $teilnehmer);
        $this->view->assign('dokumente', $dokumente);
        $this->view->assign('berater', $berater);
        $this->view->assign('beratung', $beratung);
        $this->view->assign('foldersize', 100-(intval(($foldersize/30000)*100)));
    }

    /**
     * action anmeldungcompleteredirect
     *
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @return void
     */
    public function anmeldungcompleteredirectAction(\Ud\Iqtp13db\Domain\Model\Beratung $beratung)
    {
        $valArray = $this->request->getArguments();

        if (isset($valArray['btnzurueck'])) {
            $this->redirect('anmeldseite4');
        } elseif(isset($valArray['btncancel'])) {
            $this->cleanUpSessionData();
            $this->redirectToURI('https://www.iq-netzwerk-nrw.de/startseite-webapp-anerkennungserstberatung/');
        } elseif (isset($valArray['btnAbsenden'])) {
            
            $this->cleanUpSessionData();
            $teilnehmer = $beratung->getTeilnehmer();
            $recipient = $teilnehmer->getEmail();
            $bcc = $this->settings['bccmail'];
            $sender = $this->settings['sender'];
            $templateName = 'Mailtoconfirm';
            $confirmmailtext = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmmailtext', 'Iqtp13db');
            $subject = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmsubject', 'Iqtp13db');
            
            $variables = array(
                'teilnehmer' => $teilnehmer,
                'confirmmailtext' => $confirmmailtext,
            	'startseitelink' => $this->settings['startseitelink'],
            	'logolink' => $this->settings['logolink']
            );
            $this->sendTemplateEmail(array($recipient), array($bcc), array($sender), $subject, $templateName, $variables, false);
            
            $this->redirect(null, null, null, null, $this->settings['redirectValidationInitiated']); // TODO: url aus id hier einfügen
        } else {
            //
        }
    }

    /**
     * action confirm
     *
     * @return void
     */
    public function confirmAction()
    {
        if($this->request->hasArgument('code')) {
            $teilnehmer = $this->teilnehmerRepository->findByVerificationCode($this->request->getArgument('code'));
        }
        
        if($teilnehmer) {
            // it's a valid verificationCode
            $teilnehmer->setVerificationDate(new \DateTime);
            $teilnehmer->setVerificationIp($_SERVER['REMOTE_ADDR']);
            $this->teilnehmerRepository->update($teilnehmer);
            
            $this->sendconfirmedMail($teilnehmer);
            
            $uriBuilder = $this->controllerContext->getUriBuilder();
            $uriBuilder->reset();
            $uriBuilder->setTargetPageUid($this->settings['anmeldendeseite']);
            $uri = $uriBuilder->build();
            $this->redirectToUri($uri);
        } else {
            $this->redirect('validationFailed');
        }
    }
    
    /**
     * action validationFailed
     *
     * @return void
     */
    public function validationFailedAction()
    {
        $this->redirect(null, null, null, null, $this->settings['redirectValidationFailed']);
    }   
    
    
    /**
     * sendconfirmedMail
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function sendconfirmedMail(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $this->cleanUpSessionData();
        $recipient = $teilnehmer->getEmail();
        $bcc = $this->settings['bccmail'];
        $sender = $this->settings['sender'];
        $subject = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('subject', 'Iqtp13db');        
        $templateName = 'Mail';
        $mailtext = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mailtext', 'Iqtp13db');
        $uriBuilder = $this->controllerContext->getUriBuilder();
        $uriBuilder->reset();
        $uriBuilder->setTargetPageUid($this->settings['anmeldeditseite']);
        $uriedit = $uriBuilder->build();
        $uriBuilder = $this->controllerContext->getUriBuilder();
        $uriBuilder->reset();
        $uriBuilder->setTargetPageUid($this->settings['anmelddeleteseite']);
        $uridelete = $uriBuilder->build();
        $variables = array(
            'editLink' => $uriedit . '&uid=' . $teilnehmer->getUid() . '&ts=' . $teilnehmer->getCrdate(),
            'deleteLink' => $uridelete . '&uid=' . $teilnehmer->getUid() . '&ts=' . $teilnehmer->getCrdate(),
            'anrede' => 'Sehr geehrte' . ($teilnehmer->getGeschlecht() == 2 ? 'r Herr ' : ' Frau ') . $teilnehmer->getNachname(),
            'mailtext' => $mailtext,
        	'startseitelink' => $this->settings['startseitelink'],
        	'logolink' => $this->settings['logolink']
        );
        $this->sendTemplateEmail(array($recipient), array($bcc), array($sender), $subject, $templateName, $variables, true);
    }
    
    /**
     * Collects the Teilnehmer from the multiple steps form stored in session variables
     * and returns an teilnehmer object.
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     */
    protected function getTeilnehmerFromSession(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer = NULL)
    {
        $tnseite1 = unserialize($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnseite1'));
        $tnseite2 = unserialize($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnseite2'));
        $tnseite3 = unserialize($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnseite3'));
        if ($teilnehmer == NULL) {
            $teilnehmer = $this->objectManager->get('Ud\\Iqtp13db\\Domain\\Model\\Teilnehmer');
        }
        $teilnehmer->setEinwilligung($tnseite1->getEinwilligung());
        $teilnehmer->setNachname($tnseite1->getNachname());
        $teilnehmer->setVorname($tnseite1->getVorname());
        $teilnehmer->setStrasse($tnseite1->getStrasse());
        $teilnehmer->setPlz($tnseite1->getPlz());
        $teilnehmer->setOrt($tnseite1->getOrt());
        $teilnehmer->setEmail($tnseite1->getEmail());
        $teilnehmer->setTelefon($tnseite1->getTelefon());
        $teilnehmer->setGeburtsjahr($tnseite1->getGeburtsjahr());
        $teilnehmer->setGeburtsland($tnseite1->getGeburtsland());
        $teilnehmer->setGeschlecht($tnseite1->getGeschlecht());
        $teilnehmer->setErsteStaatsangehoerigkeit($tnseite1->getErsteStaatsangehoerigkeit());
        $teilnehmer->setZweiteStaatsangehoerigkeit($tnseite1->getZweiteStaatsangehoerigkeit());
        $teilnehmer->setEinreisejahr($tnseite1->getEinreisejahr());
        $teilnehmer->setWohnsitzDeutschland($tnseite1->getWohnsitzDeutschland());
        $teilnehmer->setWohnsitzJaBundesland($tnseite1->getWohnsitzJaBundesland());
        $teilnehmer->setWohnsitzNeinIn($tnseite1->getWohnsitzNeinIn());
        $teilnehmer->setGeplanteEinreise($tnseite1->getGeplanteEinreise());
        $teilnehmer->setKontaktVisastelle($tnseite1->getKontaktVisastelle());
        $teilnehmer->setVisumsantrag($tnseite1->getVisumsantrag());        
        $teilnehmer->setDeutschkenntnisse($tnseite2->getDeutschkenntnisse());
        $teilnehmer->setZertifikatSprachniveau($tnseite2->getZertifikatSprachniveau());
        $teilnehmer->setZertifikatdeutsch($tnseite2->getZertifikatdeutsch());
        $teilnehmer->setSprachen($tnseite2->getSprachen());
        $teilnehmer->setAbschlussartA($tnseite2->getAbschlussartA());
        $teilnehmer->setAbschlussartH($tnseite2->getAbschlussartH());
        $teilnehmer->setErwerbsland1($tnseite2->getErwerbsland1());
        $teilnehmer->setDauerBerufsausbildung1($tnseite2->getDauerBerufsausbildung1());
        $teilnehmer->setAbschlussjahr1($tnseite2->getAbschlussjahr1());
        $teilnehmer->setAusbildungsinstitution1($tnseite2->getAusbildungsinstitution1());
        $teilnehmer->setAusbildungsort1($tnseite2->getAusbildungsort1());
        $teilnehmer->setAbschluss1($tnseite2->getAbschluss1());
        $teilnehmer->setDeutschAbschlusstitel1($tnseite2->getDeutschAbschlusstitel1());
        $teilnehmer->setBerufserfahrung1($tnseite2->getBerufserfahrung1());
        $teilnehmer->setDeutscherReferenzberuf1($tnseite2->getDeutscherReferenzberuf1());
        $teilnehmer->setWunschberuf1($tnseite2->getWunschberuf1());
        $teilnehmer->setOriginalDokumenteAbschluss1($tnseite2->getOriginalDokumenteAbschluss1());
        $teilnehmer->setErwerbsland2($tnseite2->getErwerbsland2());
        $teilnehmer->setDauerBerufsausbildung2($tnseite2->getDauerBerufsausbildung2());
        $teilnehmer->setAbschlussjahr2($tnseite2->getAbschlussjahr2());
        $teilnehmer->setAusbildungsinstitution2($tnseite2->getAusbildungsinstitution2());
        $teilnehmer->setAusbildungsort2($tnseite2->getAusbildungsort2());
        $teilnehmer->setAbschluss2($tnseite2->getAbschluss2());
        $teilnehmer->setDeutschAbschlusstitel2($tnseite2->getDeutschAbschlusstitel2());
        $teilnehmer->setBerufserfahrung2($tnseite2->getBerufserfahrung2());
        $teilnehmer->setDeutscherReferenzberuf2($tnseite2->getDeutscherReferenzberuf2());
        $teilnehmer->setWunschberuf2($tnseite2->getWunschberuf2());
        $teilnehmer->setOriginalDokumenteAbschluss2($tnseite2->getOriginalDokumenteAbschluss2());
        $teilnehmer->setErwerbsstatus($tnseite3->getErwerbsstatus());
        $teilnehmer->setLeistungsbezug($tnseite3->getLeistungsbezug());
        $teilnehmer->setFruehererAntrag($tnseite3->getFruehererAntrag());
        $teilnehmer->setFruehererAntragReferenzberuf($tnseite3->getFruehererAntragReferenzberuf());
        $teilnehmer->setFruehererAntragInstitution($tnseite3->getFruehererAntragInstitution());
        $teilnehmer->setBescheidfruehererAnerkennungsantrag($tnseite3->getBescheidfruehererAnerkennungsantrag());
        return $teilnehmer;
    }

    /**
     * Collects the Beratung from the last step form stored in session variables
     * and returns an  beratung object.
     *
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @return \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     */
    protected function getBeratungFromSession(\Ud\Iqtp13db\Domain\Model\Beratung $beratung = NULL)
    {
        $beratungseite4 = unserialize($GLOBALS['TSFE']->fe_user->getKey('ses', 'beratungseite4'));
        if ($beratung == NULL) {
            $beratung = $this->objectManager->get('Ud\\Iqtp13db\\Domain\\Model\\Beratung');
        }
        $beratung->setDatum($beratungseite4->getDatum());
        $beratung->setWegBeratungsstelle($beratungseite4->getWegBeratungsstelle());
        $beratung->setBerater($beratungseite4->getBerater());
        return $beratung;
    }

    /**
     * Removes all session variables from the multiple steps form
     *
     * @return void
     */
    protected function cleanUpSessionData()
    {
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'tnseite1', '');
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'tnseite2', '');
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'tnseite3', '');
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'tnuid', '');
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'beratungseite4', '');

    }

    public function errorAction()
    {
        $this->clearCacheOnError();
        $referringRequest = $this->request->getReferringRequest();
        if ($referringRequest !== NULL) {
            $originalRequest = clone $this->request;
            $this->request->setOriginalRequest($originalRequest);
            $this->request->setOriginalRequestMappingResults($this->arguments->validate());
            $this->forward($referringRequest->getControllerActionName(), $referringRequest->getControllerName(), $referringRequest->getControllerExtensionName(), $referringRequest->getArguments());
        }
    }

    /**
     * @param array $recipient recipient of the email in the format array('recipient@domain.tld' => 'Recipient Name')
     * @param array $bcc
     * @param array $sender
     * @param $subject
     * @param $templateName
     * @param array $variables
     * @param $addattachment
     * @return boolean TRUE on success, otherwise false
     */
    protected function sendTemplateEmail(array $recipient, array $bcc, array $sender, $subject, $templateName, array $variables = array(), $addattachment)
    {
        $emailView = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('iqtp13db');
        $templateRootPath = $extPath."Resources/Private/Templates/";
        
        $templatePathAndFilename = $templateRootPath . 'Beratung/' . $templateName . '.html';
        $emailView->setTemplatePathAndFilename($templatePathAndFilename);
        $emailView->assignMultiple($variables);
        $emailBody = $emailView->render();
        $message = $this->objectManager->get('TYPO3\\CMS\\Core\\Mail\\MailMessage');
        $message->setTo($recipient)->setFrom($sender)->setSubject($subject);        
        if($templateName != 'Mailtoconfirm') $message->setBcc($bcc);
        
        if($this->settings['mailattacheinwilligung'] != '') {
            $publicRootPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($this->settings['mailattacheinwilligung']);
            if($publicRootPath != '' && $addattachment) {
                $message->attach(\Swift_Attachment::fromPath($publicRootPath));
            }
        }
        // HTML Email
        $message->setBody($emailBody, 'text/html');
        $message->send();
        return $message->isSent();
    }

    function getTP13Storage($pfad) {
        $storageRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
        // Speicher 'tp13data' muss im Typo3-Backend auf der Root-Seite als "Dateispeicher" angelegt sein!
        // wenn der Speicher mal nicht verfügbar war (temporär), muss er im Backend im Bereich "Dateispeicher" manuell wieder "online" geschaltet werden mit der Checkbox "ist online?" in den Eigenschaften des jeweiligen Dateispeichers
        $storages = $storageRepository->findAll();
        foreach ($storages as $s) {
            $storageObject = $s;
            $storageRecord = $storageObject->getStorageRecord();
            if ($storageRecord['name'] == 'tp13data') {
                $storage = $s;
                break;
            }
        }
        
        return $storage;
    }
    
    function getFolderSize($folderpath) {
        $io = popen ( '/usr/bin/du -sk ' . $folderpath, 'r' );
        $size = fgets ( $io, 4096);
        $size = substr ( $size, 0, strpos ( $size, "\t" ) );
        pclose ( $io );
        
        return $size;
    }
}
