<?php
namespace Ud\Iqtp13db\Controller;
use \Datetime;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Annotation\Validate;

use Psr\Http\Message\ResponseInterface;
use Ud\Iqtp13db\Domain\Repository\UserGroupRepository;
use Ud\Iqtp13db\Domain\Repository\TeilnehmerRepository;
use Ud\Iqtp13db\Domain\Repository\DokumentRepository;
use Ud\Iqtp13db\Domain\Repository\BeraterRepository;
use Ud\Iqtp13db\Domain\Repository\AbschlussRepository;
use TYPO3\CMS\Core\Resource\StorageRepository;
use Ud\Iqtp13db\Domain\Repository\StaatenRepository;

/***
 *
 * This file is part of the "IQ Webapp Anerkennungserstberatung" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2023 Uli Dohmen <edv@whkt.de>, WHKT
 *
 ***/

/**
 * TeilnehmerController
 */
class TeilnehmerController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{    
    protected $generalhelper;
    
    protected $userGroupRepository;
    protected $teilnehmerRepository;
    protected $dokumentRepository;
    protected $beraterRepository;
    protected $abschlussRepository;
    protected $storageRepository;
    protected $staatenRepository;
    
    public function __construct(UserGroupRepository $userGroupRepository, TeilnehmerRepository $teilnehmerRepository, DokumentRepository $dokumentRepository, BeraterRepository $beraterRepository, AbschlussRepository $abschlussRepository, StorageRepository $storageRepository, StaatenRepository $staatenRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
        $this->teilnehmerRepository = $teilnehmerRepository;
        $this->dokumentRepository = $dokumentRepository;
        $this->beraterRepository = $beraterRepository;
        $this->abschlussRepository = $abschlussRepository;
        $this->storageRepository = $storageRepository;
        $this->staatenRepository = $staatenRepository;
    }
    
    /**
     * action init
     *
     * @param void
     */
    public function initializeAction()
    {
        
        /*
         * PropertyMapping für die multiple ankreuzbaren Checkboxen.
         * Annehmen eines String-Arrays, das im Setter und Getter des Models je per implode/explode wieder in Strings bzw. Array (of Strings) konvertiert wird
         */
        
        if ($this->arguments->hasArgument('abschluss')) {
            $this->arguments->getArgument('abschluss')->getPropertyMappingConfiguration()->allowProperties('abschlussart');
            $this->arguments->getArgument('abschluss')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('abschlussart', 'array');
        }
        
        if ($this->arguments->hasArgument('teilnehmer')) {
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('sonstigerstatus');
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('sonstigerstatus', 'array');
            
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('einwAnerkstellemedium');
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('einwAnerkstellemedium', 'array');
            
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('einwPersonmedium');
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('einwPersonmedium', 'array');
            
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('beratungsart');
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('beratungsart', 'array');
            
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('anerkennungsberatung');
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('anerkennungsberatung', 'array');
            
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('qualifizierungsberatung');
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('qualifizierungsberatung', 'array');
            
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('wieberaten');
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('wieberaten', 'array');
            
        }
        
        if ($this->arguments->hasArgument('tnseite1')) {
            $this->arguments->getArgument('tnseite1')->getPropertyMappingConfiguration()->allowProperties('sonstigerstatus');
            $this->arguments->getArgument('tnseite1')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('sonstigerstatus', 'array');
        }
        
        /* Propertymapping bis hier */
        
        $this->generalhelper = new \Ud\Iqtp13db\Helper\Generalhelper();
        
    }
        
    /*
     * API-Funktion, um die Zahlen als JSON zwecks Abruf für den Counter auf der IQ-Webseite zu generieren.
     * 
     * Folgendes muss ins Typoscript der IQ Webapp eingetragen werden, damit ein Aufruf der API über 
     * https://www.iq-webapp.de/frontend-iq-webapp/anmeldung?type=112233
     * möglich ist.
     * 
     * myJson = PAGE
     *   myJson {
     *   	typeNum = 112233
     *   	config {
     *          additionalHeaders = Content-Type: application/json
     *           additionalHeaders.10.header = Content-Type: application/json
     *           no_cache = 1
     *           disableAllHeaderCode = 1
     *           disablePrefixComment = 1
     *           xhtml_cleaning = 0
     *           admPanel = 0
     *           debug = 0
     *       }
     *       10 < tt_content.list.20.iqtp13db_json
     *   }
     */
    public function showAction(): ResponseInterface
    {
        $reftag = date("d.m.Y");
        $neuanmeldungenheute = $this->teilnehmerRepository->count4Status($reftag, $reftag, '%', 1)[0]['anzahl'];
        
        $aktuelleanmeldungenunbestaetigt = $this->teilnehmerRepository->countAllOrder4Status(0, '%')[0]['anzahl'];
        $aktuelleanmeldungenbestaetigt = $this->teilnehmerRepository->countAllOrder4Status(1, '%')[0]['anzahl'];
        $aktuelleanmeldungen = $aktuelleanmeldungenbestaetigt + $aktuelleanmeldungenunbestaetigt;
        
        $aktuellerstberatungen = $this->teilnehmerRepository->countAllOrder4Status(2, '%')[0]['anzahl'];
        $aktuellberatungenfertig = $this->teilnehmerRepository->countAllOrder4Status(3, '%')[0]['anzahl'];
        
        $statsgesamtfertigberaten = $this->teilnehmerRepository->count4Status("01.1.1970", "31.12.".date('Y'), '%', 3)[0]['anzahl'];
        
        $data = [
            'anmeldungenheute' => $neuanmeldungenheute,
            'anmeldungenwartend' => $aktuelleanmeldungen,
            'erstberatungenlaufend' => $aktuellerstberatungen,
            'erstberatungenabgeschlossen' => $statsgesamtfertigberaten
        ];
        $jsonOutput = json_encode($data);
        return $this->jsonResponse($jsonOutput);
    }
    
    /**
     * action start
     *
     * @return void
     */
    public function startAction()
    {
        $wartungvon = new DateTime($this->settings['wartungvon'] == '' ? '01.01.2020 01:00' : $this->settings['wartungvon']);
        $wartungbis = new DateTime($this->settings['wartungbis'] == '' ? '01.01.2020 02:00' : $this->settings['wartungbis']);
        
        $datum = strtotime("now");
        
        if ($this->settings['modtyp'] == 'anmeldung' || $this->settings['modtyp'] == 'anmeldungplz') {
            if($datum >= $wartungvon->getTimestamp() AND $datum <= $wartungbis->getTimestamp())
            {
                $this->forward('wartung', 'Teilnehmer', 'Iqtp13db');
            }
            else
            {
                $valArray = $this->request->getArguments();
                $beratungsstellenid = $valArray['beratung'] ?? '';
                $direkt = $valArray['direkt'] ?? '';
                if($beratungsstellenid != '') {
                    $allusergroups = $this->userGroupRepository->findAllGroups($this->settings['beraterstoragepid']);
                    foreach ($allusergroups as $group) {
                        if($group->getNiqbid() == $beratungsstellenid) {
                            $this->view->assign('beratungsstelle', $group->getNiqbid());
                            $GLOBALS['TSFE']->fe_user->setKey('ses', 'beratungsstellenid', $group->getNiqbid());
                            
                            if($direkt == '1'){
                                $this->forward('anmeldseite0', 'Teilnehmer', 'Iqtp13db', array('direkt' => $direkt));
                            } else {
                                if($this->settings['modtyp'] == 'anmeldungplz') {
                                    $this->forward('startseiteplz', 'Teilnehmer', 'Iqtp13db');
                                } else {
                                    $this->forward('startseite', 'Teilnehmer', 'Iqtp13db');
                                }                                
                            }
                            break;
                        }
                    }
                }
                if($this->settings['modtyp'] == 'anmeldungplz') {
                    $this->forward('startseiteplz', 'Teilnehmer', 'Iqtp13db');
                } else {
                    $this->forward('startseite', 'Teilnehmer', 'Iqtp13db');
                }
            }
        }       
    }
    
    /**
     * action startseite
     *
     * @return void
     */
    public function startseiteAction()
    {
        // Beratungsstellen-ID aus Session-Cache löschen
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'beratungsstellenid', null);
        
        $valArray = $this->request->getArguments();
        $beratungsstellenid = $valArray['beratung'] ?? '';
        if($beratungsstellenid != '') {
            $allusergroups = $this->userGroupRepository->findAllGroups($this->settings['beraterstoragepid']);
            foreach ($allusergroups as $group) {
                if($group->getNiqbid() == $beratungsstellenid) {
                    $this->view->assign('beratungsstelle', $group->getNiqbid());
                    $GLOBALS['TSFE']->fe_user->setKey('ses', 'beratungsstellenid', $group->getNiqbid());
                }
            }
        } else {
            $this->view->assign('beratungsstelle', $GLOBALS['TSFE']->fe_user->getKey('ses', 'beratungsstellenid'));
        }
    }
    
    /**
     * action startseiteplz
     *
     * @return void
     */
    public function startseiteplzAction()
    {
        // Beratungsstellen-ID aus Session-Cache löschen
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'beratungsstellenid', null);
        
        $this->view->assign('anmeldungseiteuid', $this->settings['registrationpageuid']);        
    }
    
    /**
     * action wartung
     *
     * @return void
     */
    public function wartungAction()
    {
        $this->view->assign('settings', $this->settings);
    }
    
    /**
     * action anmeldseite0
     *
     * @return void
     */
    public function anmeldseite0Action()
    {       
        $valArray = $this->request->getArguments();
        
        $uriBuilder = $this->uriBuilder;
        $bstid = $GLOBALS['TSFE']->fe_user->getKey('ses', 'beratungsstellenid');
        
        $valarrwohnsitzdeutschland = $valArray['wohnsitzDeutschland'] ?? '';
        $direkt = $valArray['direkt'] ?? '0';
        
        if($valarrwohnsitzdeutschland == 2) {
            $uri = $uriBuilder->setTargetPageUid($this->settings['anmeldungzsbapageuid'])->build();
            $this->redirectToUri($uri, 0, 303);
        } elseif($valarrwohnsitzdeutschland == 1 && $valArray['plz'] == '' && $bstid == '') {
            $uri = $uriBuilder->setTargetPageUid($this->settings['anmeldungnichtwebapppageuid'])->build();
            $this->redirectToUri($uri, 0, 303);
        } else {
            
            if($valarrwohnsitzdeutschland == '' && $direkt != '1') {
                $this->redirect('startseite', 'Teilnehmer', 'Iqtp13db', null);
            }
            
            $plzberatungsstelle = array();
            if($bstid == '' && isset($valArray['plz'])) {
                $plzberatungsstelle = $this->userGroupRepository->getBeratungsstelle4PLZ($valArray['plz'], $this->settings['beraterstoragepid']);
                $bstid = count($plzberatungsstelle) > 0 ? $plzberatungsstelle[0]->getNiqbid() : $bstid;
            }
            
            if($bstid != '') {
                $GLOBALS['TSFE']->fe_user->setKey('ses', 'beratungsstellenid', $bstid);
                
                $this->view->assign('beratungsstelle', $bstid);
                $this->view->assign('wohnsitzDeutschland', $valarrwohnsitzdeutschland);
                $this->view->assign('plz', $valArray['plz'] ?? '');
                $this->view->assign('direkt', $valArray['direkt'] ?? '');
            } else {
                $uri = $uriBuilder->setTargetPageUid($this->settings['anmeldungnichtwebapppageuid'])->build();
                $this->redirectToUri($uri, 0, 303);
            }
        }
    }
    
    
    
    
    /**
     * action anmeldseite1
     *
     * @param \Ud\Iqtp13db\Domain\Model\TNSeite1 $tnseite1
     * @return void
     */
    public function anmeldseite1Action(\Ud\Iqtp13db\Domain\Model\TNSeite1 $tnseite1 = NULL)
    {
        $valArray = $this->request->getArguments();
        
        if ($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnseite1') && $tnseite1 == NULL) {
            $tnseite1 = unserialize($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnseite1'));
        }
        
        if(!isset($valArray['plz']) && $tnseite1 == NULL && !isset($valArray['direkt'])){
            // Link Anmeldeseite1 ohne vorherigen Aufruf der Anmeldseite0 geöffnet -> das ist nicht erlaubt!
            $this->redirect('startseite', 'Teilnehmer', 'Iqtp13db', null);
        }
        
        $context = GeneralUtility::makeInstance(Context::class);
        $site = $GLOBALS['TYPO3_REQUEST']->getAttribute('site');
        $langId = $context->getPropertyFromAspect('language', 'id');
        $language = $site->getLanguageById($langId);
        $langCode = $language->getTwoLetterIsoCode();
        
        $staaten = $this->staatenRepository->findByLangisocode($langCode);
        if(count($staaten) == 0) $staaten = $this->staatenRepository->findByLangisocode('en');
        
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }
        
        $altervonbis[-1000] = '-';
        $altervonbis[-1] = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('ka', 'iqtp13db');
        for ($i = 15; $i <= 80; $i++) {
            $altervonbis[$i] = $i;
        }
        
        $aktuellesJahr = (int)date("Y");
        $jahre = array();
        $jahre[-1] = 'k.A.';
        for($jahr = $aktuellesJahr; $jahr > $aktuellesJahr-60; $jahr--) {
            $jahre[$jahr] = (String)$jahr;
        }
        
        if($GLOBALS['TSFE']->fe_user->getKey('ses', 'beratungsstellenid') == '') {
            $bstid = $valArray['beratungsstelle'] ?? '';
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'beratungsstellenid', $bstid);
        } else {
            $bstid = $GLOBALS['TSFE']->fe_user->getKey('ses', 'beratungsstellenid');
        }
        $zugewieseneberatungsstelle = $this->userGroupRepository->findBeratungsstellebyNiqbid($this->settings['beraterstoragepid'], $bstid);
        $zugewieseneberatungsstelle = $zugewieseneberatungsstelle[0];
        
        $uriBuilder = $this->controllerContext->getUriBuilder();
        $uriBuilder->reset();
        if($zugewieseneberatungsstelle->getEinwilligungserklaerungsseite() != 0) {
            $uriBuilder->setTargetPageUid($zugewieseneberatungsstelle->getEinwilligungserklaerungsseite());
        } else {
            $uriBuilder->setTargetPageUid($this->settings['datenschutzeinwilligungurluid']);
        }
        $urleinwilligung = $uriBuilder->build();
        
        $this->view->assignMultiple(
            [
                'altervonbis' => $altervonbis,
                'staatenarr' => $staatenarr,
                'tnseite1' => $tnseite1,
                'settings' => $this->settings,
                'beratungsstelle' => $bstid,
                'wohnsitzdeutschland' => $valArray['wohnsitzDeutschland'] ?? '',
                'plz' => $valArray['plz'] ?? '',
                'jahre' => $jahre,
                'urleinwilligung' => $urleinwilligung,
                'direkt' => $valArray['direkt'] ?? ''
            ]
            );
        
    }
    
    /**
     * action anmeldseite1redirect
     *
     * @param \Ud\Iqtp13db\Domain\Model\TNSeite1 $tnseite1
     * @Validate("Ud\Iqtp13db\Domain\Validator\TNSeite1Validator", param="tnseite1")
     * @return void
     */
    public function anmeldseite1redirectAction(\Ud\Iqtp13db\Domain\Model\TNSeite1 $tnseite1 = NULL)
    {
        if($tnseite1 == NULL) {
            $this->redirect('anmeldseite1', 'Teilnehmer', null, null);
        } else {
            $valArray = $this->request->getArguments();
            $direkt = $valArray['direkt'] ?? '0';
            if(isset($valArray['btnweiter'])) {
                $GLOBALS['TSFE']->fe_user->setKey('ses', 'tnseite1', serialize($tnseite1));
                
                $bstid = $GLOBALS['TSFE']->fe_user->getKey('ses', 'beratungsstellenid');
                
                if ($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnuid') == NULL) {
                    $teilnehmer = $this->getTeilnehmerFromSession();
                    
                    // **** Doppelanmeldungen vermeiden *****
                    if(strtolower($teilnehmer->getNachname()) != 'anonym') {
                        $teilnehmerarr = $this->teilnehmerRepository->findDublette4Anmeldung($teilnehmer->getNachname(), $teilnehmer->getVorname(), $teilnehmer->getEmail());
                        if(count($teilnehmerarr) > 0) {
                            $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('tnuid', null);
                            $this->redirect('bereitsberaten', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmerarr[0]));
                        }
                    }
                    // **************************************
                    
                    if($teilnehmer->getPlz() == '') {
                        // keine PLZ eingegeben
                        $this->addFlashMessage("Error 102, PLZ missing / ZIP missing.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                        $this->redirect('anmeldseite1', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
                    } 
                    
                    $teilnehmer->setBeratungsstatus(99);
                    $teilnehmer->setCrdate(time());
                    
                    if($direkt != '1' && ($bstid == '' || $bstid == '12345')) {
                        $plzberatungsstelle = array();
                        $plzberatungsstelle = $this->userGroupRepository->getBeratungsstelle4PLZ($teilnehmer->getPlz(), $this->settings['beraterstoragepid']);
                        $bstid = count($plzberatungsstelle) > 0 ? $plzberatungsstelle[0]->getNiqbid() : '';                            
                    }  
                    $teilnehmer->setNiqidberatungsstelle($bstid);
                    $this->teilnehmerRepository->add($teilnehmer);
                } else {
                    
                    $teilnehmer = $this->teilnehmerRepository->findByUid($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnuid'));
                    
                    if($teilnehmer != NULL) {
                        $teilnehmer = $this->getTeilnehmerFromSession($teilnehmer);
                        $teilnehmer->setBeratungsstatus(99);
                        $this->teilnehmerRepository->update($teilnehmer);
                    } else {
                        $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('tnuid', null);
                        $this->addFlashMessage("Error 103.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                        $this->redirect('anmeldseite1', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
                    }
                }
                
                if($direkt == '1') $teilnehmer->setKooperationgruppe('Direktlink: '. $bstid);
                
                // Daten sofort in die Datenbank schreiben
                $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
                $persistenceManager->persistAll();
                
                $GLOBALS['TSFE']->fe_user->setKey('ses', 'tnuid', $teilnehmer->getUid());
                
                $this->redirect('anmeldseite2', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
            } else {
                if ($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnuid') != NULL) {
                    $this->cancelregistration($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnuid'));
                } else {
                    $this->cancelregistration(null);
                }
            }
        }
    }
    
    /**
     * action anmeldseite2
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function anmeldseite2Action(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $tnarr = $this->teilnehmerRepository->findByUid($teilnehmer->getUid());
        if($tnarr != NULL) {
            $abschluesse = new \Ud\Iqtp13db\Domain\Model\Abschluss();
            $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer->getUid());
        }
        
        $aktuellesJahr = (int)date("Y");
        $abschlussjahre = array();
        $abschlussjahre[-1] = 'k.A.';
        for($jahr = $aktuellesJahr; $jahr > $aktuellesJahr-60; $jahr--) {
            $abschlussjahre[$jahr] = (String)$jahr;
        }
        
        $this->view->assignMultiple(
            [
                'settings' => $this->settings,
                'abschluesse' => $abschluesse,
                'teilnehmer' => $teilnehmer,
                'beratungsstelle' => $GLOBALS['TSFE']->fe_user->getKey('ses', 'beratungsstellenid'),
                'abschlussjahre' => $abschlussjahre
            ]
            );
    }
    
    /**
     * action anmeldseite2redirect
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function anmeldseite2redirectAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();
        if (isset($valArray['btnabschlusshinzu'])) {
            $this->redirect('newWebapp', 'Abschluss', null, array('teilnehmer' => $teilnehmer));
        } elseif (isset($valArray['btnzurueck'])) {
            $this->redirect('anmeldseite1', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer, 'plz' => $teilnehmer->getPlz(), 'wohnsitzDeutschland' => $teilnehmer->getWohnsitzdeutschland()));
        } elseif(isset($valArray['btnweiter'])) {
            $this->redirect('anmeldseite3', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer));
        } else {
            if ($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnuid') != NULL) {
                $this->cancelregistration($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnuid'));
            } else {
                $this->cancelregistration(null);
            }
        }
    }
    
    /**
     * action anmeldseite3
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function anmeldseite3Action(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();
        
        $allusergroups = $this->userGroupRepository->findAllGroups($this->settings['beraterstoragepid']);
        foreach ($allusergroups as $group) {
            if($group->getNiqbid() == $teilnehmer->getNiqidberatungsstelle()) {
                $beratungsartenarray = $group->getBeratungsarten();
                $newwieberatenarray = array();
                foreach($this->settings['wieberaten'] as $key => $wieber){
                    if(in_array($key, $beratungsartenarray)) $newwieberatenarray[$key] = $wieber;
                }
                if(count($newwieberatenarray) != 0) {
                    $this->view->assign('wieberatenarr', $newwieberatenarray);
                } else {
                    $this->view->assign('wieberatenarr', $this->settings['wieberaten']);
                }
                break;
            }
        }
        
        $tnarr = $this->teilnehmerRepository->findByUid($teilnehmer->getUid());
        
        if($tnarr != NULL) {
            $this->view->assign('settings', $this->settings);
            $this->view->assign('teilnehmer', $teilnehmer);
        } else {
            $this->forward('startseite', 'Teilnehmer', 'Iqtp13db');
        }
    }
    
    /**
     * action anmeldseite3redirect
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function anmeldseite3redirectAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer = NULL)
    {
        if($teilnehmer == NULL) {
            $this->redirect('anmeldseite3', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer));
        } else {
            $valArray = $this->request->getArguments();
            $thisdate = new DateTime();
            
            if($teilnehmer->getEinwperson() == 1) {
                $teilnehmer->setEinwpersondatum($thisdate->format('d.m.Y'));
                $teilnehmer->setEinwpersonmedium(explode(',', 4));
            } else {
                $teilnehmer->setEinwpersondatum('');
                $teilnehmer->setEinwpersonmedium(array());
            }
            
            if (isset($valArray['btnzurueck'])) {
                $this->teilnehmerRepository->update($teilnehmer);
                $this->redirect('anmeldseite2', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
            } elseif(isset($valArray['btnweiter'])) {
                $this->teilnehmerRepository->update($teilnehmer);
                $this->redirect('anmeldungcomplete', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
            } else {
                if ($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnuid') != NULL) {
                    $this->cancelregistration($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnuid'));
                } else {
                    $this->cancelregistration(null);
                }
            }
        }
    }
    
    /**
     * action anmeldungcomplete
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function anmeldungcompleteAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();
        $tnarr = $this->teilnehmerRepository->findByUid($teilnehmer->getUid());
        if($tnarr != NULL) {
            $niqbid = $teilnehmer->getNiqidberatungsstelle();
            $beratungsstellenfolder = $niqbid;
            $newFilePath = $beratungsstellenfolder.'/' . $teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid(). '/';
            $storage = $this->generalhelper->getTP13Storage($this->storageRepository->findAll());
            $foldersize = $this->generalhelper->getFolderSize($storage->getConfiguration()['basePath'].$newFilePath);
            if(!is_numeric($foldersize)) $foldersize = 0;
            $dokumente = $this->dokumentRepository->findByTeilnehmer($teilnehmer);
            $abschluesse = new \Ud\Iqtp13db\Domain\Model\Abschluss();
            $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
            
            $context = GeneralUtility::makeInstance(Context::class);
            $site = $GLOBALS['TYPO3_REQUEST']->getAttribute('site');
            $langId = $context->getPropertyFromAspect('language', 'id');
            $language = $site->getLanguageById($langId);
            $langCode = $language->getTwoLetterIsoCode();
            
            $staaten = $this->staatenRepository->findByLangisocode($langCode);
            if(count($staaten) == 0) $staaten = $this->staatenRepository->findByLangisocode('en');
            unset($staaten[200]);
            
            $this->view->assignMultiple(
                [
                    'settings' => $this->settings,
                    'abschluesse' => $abschluesse,
                    'heute' => time(),
                    'teilnehmer' => $teilnehmer,
                    'dokumente' => $dokumente,
                    'foldersize' =>  100-(intval(($foldersize/30000)*100)),
                    'staaten' => $staaten
                ]
                );
        } else {
            $this->forward('startseite', 'Teilnehmer', 'Iqtp13db');
        }
    }
    
    /**
     * action anmeldungcompleteredirect
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function anmeldungcompleteredirectAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer = NULL)
    {
        if($teilnehmer == NULL) {
            $this->redirect('anmeldungcomplete', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer));
        } else {
            $valArray = $this->request->getArguments();
            
            if (isset($valArray['btnzurueck'])) {
                $this->redirect('anmeldseite3', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
            } elseif(isset($valArray['btnAbsenden'])) {
                $tfolder = $this->generalhelper->createFolder($teilnehmer, $this->storageRepository->findAll());
                if($teilnehmer->getVerificationDate() == 0) $teilnehmer->setBeratungsstatus(0);
                    
                if($teilnehmer->getNiqidberatungsstelle() == 0) {
                    // keine PLZ eingegeben
                    if($teilnehmer->getPlz() == '') {
                        $this->addFlashMessage("Error 401: PLZ fehlt / ZIP missing.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                        $this->redirect('anmeldseite1', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
                    } else {
                        $plzberatungsstelle = $this->userGroupRepository->getBeratungsstelle4PLZ($teilnehmer->getPlz(), $this->settings['beraterstoragepid']);
                        if(count($plzberatungsstelle) > 0) {
                            $tnniqid = $plzberatungsstelle[0]->getNiqbid();
                            $teilnehmer->setNiqidberatungsstelle($tnniqid);
                        } else {
                            $this->addFlashMessage("Error 402, PLZ unbekannt / ZIP unknown.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                            $this->redirect('anmeldseite1', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
                        }                        
                    }                    
                } else {
                    $tnniqid = $teilnehmer->getNiqidberatungsstelle();
                }                
                
                $tnberatungsstelle = $this->userGroupRepository->findBeratungsstellebyNiqbid($this->settings['beraterstoragepid'], $tnniqid);
                
                // Sonstiger Status in Feld "Gruppe" eintragen
                if($teilnehmer->getSonstigerstatus()[0] == '1') $teilnehmer->setKooperationgruppe('Ortskraft Afghanistan');
                if($teilnehmer->getSonstigerstatus()[0] == '2') $teilnehmer->setKooperationgruppe('Geflüchtet aus der Ukraine');
                
                if($tnberatungsstelle == NULL) {
                    $teilnehmer->setKooperationgruppe('keine Beratungsstelle zugeordnet');
                } else {
                    if(strstr($tnberatungsstelle[0]->getTitle(), 'ZSBA') != FALSE){
                        $teilnehmer->setKooperationgruppe($tnberatungsstelle[0]->getTitle() ?? 'Error 403');
                    }
                }          
                
                $this->teilnehmerRepository->update($teilnehmer);
                $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
                $persistenceManager->persistAll();
                
                $bcc = '';
                $sender = $this->settings['sender'];
                if($sender == '') {
                    $this->addFlashMessage('Error 403.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                    $this->redirect('anmeldungcomplete', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
                } else {
                    $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('tnseite1', null);
                    $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('tnuid', null);
                    
                    $recipient = $teilnehmer->getEmail();
                    $templateName = 'Mailtoconfirm';
                    $confirmmailtext1 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmmailtext1', 'Iqtp13db');
                    $confirmmailtext1 = str_replace("VORNAMENACHNAME", $teilnehmer->getVorname().' '.$teilnehmer->getNachname(), $confirmmailtext1);
                    $confirmlinktext = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmlinktext', 'Iqtp13db');
                    $confirmmailtext2 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmmailtext2', 'Iqtp13db');
                    $subject = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmsubject', 'Iqtp13db');
                    
                    $datenberatungsstelle = $tnberatungsstelle[0]->getDescription() ?? '';
                    if($datenberatungsstelle != '') $kontaktlabel = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('kontaktberatungsstelle', 'Iqtp13db');
                    else $kontaktlabel = '';
                        
                    $variables = array(
                        'teilnehmer' => $teilnehmer,
                        'confirmmailtext1' => $confirmmailtext1,
                        'confirmlinktext' => $confirmlinktext,
                        'confirmmailtext2' => $confirmmailtext2,
                        'datenberatungsstelle' => $datenberatungsstelle,
                        'kontaktlabel' => $kontaktlabel,
                        'startseitelink' => $this->settings['startseitelink'],
                        'logolink' => $this->settings['logolink'],
                        'registrationpageuid' => $this->settings['registrationpageuid'],
                        'askconsent' => '0',
                        'baseurl' => $this->request->getBaseUri()
                    );
                    $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
                    $this->generalhelper->sendTemplateEmail(array($recipient), array($bcc), array($sender), $subject, $templateName, $variables, $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView'), $this->controllerContext->getUriBuilder(), $extbaseFrameworkConfiguration);
                    
                    $this->redirect(null, null, null, null, $this->settings['redirectValidationInitiated']);
                }
            } else {
                if ($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnuid') != NULL) {
                    $this->cancelregistration($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnuid'));
                } else {
                    $this->cancelregistration(null);
                }
            }
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
            if($this->request->getArgument('code') == '') {
                $this->redirect('validationFailed');
            } else {
                $teilnehmer = $this->teilnehmerRepository->findByVerificationCode($this->request->getArgument('code'));
            }
        } else {
            $this->redirect('validationFailed');
        }
        
        if($this->request->hasArgument('askconsent')) {
            $askconsent = $this->request->getArgument('askconsent');
        }
        
        if($teilnehmer) {
            
            if($teilnehmer->getBeratungdatum() != '' && $teilnehmer->getErstberatungabgeschlossen() != ''){
                $teilnehmer->setBeratungsstatus(3);
            } elseif($teilnehmer->getBeratungdatum() != ''){
                $teilnehmer->setBeratungsstatus(2);
            } else {
                $teilnehmer->setBeratungsstatus(1);
            }
            
            $teilnehmer->setVerificationDate(new \DateTime);
            $teilnehmer->setVerificationIp($_SERVER['REMOTE_ADDR']);
            $this->teilnehmerRepository->update($teilnehmer);
            
            $this->sendconfirmedMail($teilnehmer);
            
            $uriBuilder = $this->controllerContext->getUriBuilder();
            $uriBuilder->reset();
            if($askconsent == 0) {
                $uriBuilder->setTargetPageUid($this->settings['anmeldendeseite']);
            } else {
                $uriBuilder->setTargetPageUid($this->settings['anmeldendeseiteaskconsent']);
            }
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
     * action bereitsberaten
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function bereitsberatenAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {        
        $bstid = $teilnehmer->getNiqidberatungsstelle() == 0 ? $GLOBALS['TSFE']->fe_user->getKey('ses', 'beratungsstellenid') : $teilnehmer->getNiqidberatungsstelle();

        $beratungsstelle = $this->userGroupRepository->findBeratungsstellebyNiqbid($this->settings['beraterstoragepid'], $bstid);
        $beratungsstellendaten = $beratungsstelle[0]->getDescription();            
        
        $this->view->assign('beratungsstellendaten', $beratungsstellendaten);
        $this->view->assign('teilnehmer', $teilnehmer);
    }
  
    /**
     * cancelregistration
     *
     * @return void
     */
    public function cancelregistration($tnuid)
    {
        if($tnuid != null) {
            
            $teilnehmer = $this->teilnehmerRepository->findByUid($tnuid);
            
            $niqbid = $teilnehmer->getNiqidberatungsstelle();
            $beratungsstellenfolder = $niqbid;
            $filePath = $beratungsstellenfolder.'/' . $teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid(). '/';
            $storage = $this->generalhelper->getTP13Storage( $this->storageRepository->findAll());
            $dokumente = $this->dokumentRepository->findByTeilnehmer($teilnehmer);
            
            if(count($dokumente) > 0) {
                foreach($dokumente as $dokument) {
                    $this->dokumentRepository->remove($dokument);
                    
                    $delfilepath = $filePath . $dokument->getName();
                    $delfile = $storage->getFile($delfilepath);
                    $erg = $storage->deleteFile($delfile);
                }
            }
            
            if(is_dir($storage->getConfiguration()['basePath'].$filePath)) {
                rmdir($storage->getConfiguration()['basePath'].$filePath);
            }
            
            $this->teilnehmerRepository->remove($teilnehmer);
            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
        }
        
        $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('tnseite1', null);
        $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('tnuid', null);
        $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('ses', null);
         
        $this->forward('startseite', 'Teilnehmer', 'Iqtp13db');
    }
    
    /**
     * sendconfirmedMail
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function sendconfirmedMail(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'tn', '');
        $recipient = $teilnehmer->getEmail();
        
        $tnniqid = $teilnehmer->getNiqidberatungsstelle() == 0 ? '12345' : $teilnehmer->getNiqidberatungsstelle();
        $zugewieseneberatungsstelle = $this->userGroupRepository->findBeratungsstellebyNiqbid($this->settings['beraterstoragepid'], $tnniqid);
       
        if($zugewieseneberatungsstelle == NULL) {
            $bcc = "edv@whkt.de";
        } else {
            $bcc = $zugewieseneberatungsstelle[0]->getGeneralmail() != '' ? $zugewieseneberatungsstelle[0]->getGeneralmail() : '';
            $custommailtext = $zugewieseneberatungsstelle[0]->getCustominfotextmail() != '' ? $zugewieseneberatungsstelle[0]->getCustominfotextmail() : '';
        }
        
        $sender = $this->settings['sender'];
        $subject = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('subject', 'Iqtp13db');
        $templateName = 'Mail';
        $anrede = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('anredemail', 'Iqtp13db');
        $mailtext = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mailtext', 'Iqtp13db');
        $mailtext = str_replace("WARTEZEITWOCHEN", $this->settings['wartezeitwochen'], $mailtext);
         
        $datenberatungsstelle = $zugewieseneberatungsstelle != NULL ? $zugewieseneberatungsstelle[0]->getDescription() : '';
        if($datenberatungsstelle != '') $kontaktlabel = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('kontaktberatungsstelle', 'Iqtp13db');
        else $kontaktlabel = '';
        
        $variables = array(
            'anrede' => $anrede . $teilnehmer->getVorname(). ' ' . $teilnehmer->getNachname() . ',',
            'mailtext' => $mailtext,
            'custommailtext' => $custommailtext,
            'datenberatungsstelle' => $datenberatungsstelle,
            'kontaktlabel' => $kontaktlabel,
            'startseitelink' => $this->settings['startseitelink'],
            'logolink' => $this->settings['logolink'],
            'baseurl' => $this->request->getBaseUri()
        );
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $this->generalhelper->sendTemplateEmail(array($recipient), array($bcc), array($sender), $subject, $templateName, $variables, $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView'), $this->controllerContext->getUriBuilder(), $extbaseFrameworkConfiguration);
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
        if ($teilnehmer == NULL) {
            $teilnehmer = $this->objectManager->get('Ud\\Iqtp13db\\Domain\\Model\\Teilnehmer');
        }
        
        if($tnseite1) {
            $teilnehmer->setEinwilligung($tnseite1->getEinwilligung() == true ? 1 : 0);
            $teilnehmer->setSchonberaten($tnseite1->getSchonberaten());
            $teilnehmer->setSchonberatenvon($tnseite1->getSchonberatenvon());
            $teilnehmer->setNachname(trim($tnseite1->getNachname()));
            $teilnehmer->setVorname(trim($tnseite1->getVorname()));
            $teilnehmer->setPlz(trim($tnseite1->getPlz()));
            $teilnehmer->setOrt(trim($tnseite1->getOrt()));
            $teilnehmer->setEmail(trim($tnseite1->getEmail()));
            $teilnehmer->setConfirmemail(trim($tnseite1->getConfirmemail()));
            $teilnehmer->setTelefon(trim($tnseite1->getTelefon()));
            $teilnehmer->setLebensalter($tnseite1->getLebensalter());
            $teilnehmer->setGeburtsland($tnseite1->getGeburtsland());
            $teilnehmer->setGeschlecht($tnseite1->getGeschlecht());
            $teilnehmer->setErsteStaatsangehoerigkeit($tnseite1->getErsteStaatsangehoerigkeit());
            $teilnehmer->setZweiteStaatsangehoerigkeit($tnseite1->getZweiteStaatsangehoerigkeit());
            $teilnehmer->setEinreisejahr($tnseite1->getEinreisejahr());
            $teilnehmer->setWohnsitzDeutschland($tnseite1->getWohnsitzDeutschland());
            $teilnehmer->setWohnsitzNeinIn($tnseite1->getWohnsitzNeinIn());
            $teilnehmer->setSonstigerstatus($tnseite1->getSonstigerstatus());
            $teilnehmer->setAufenthaltsstatus($tnseite1->getAufenthaltsstatus());
            $teilnehmer->setAufenthaltsstatusfreitext($tnseite1->getAufenthaltsstatusfreitext());
            $teilnehmer->setDeutschkenntnisse($tnseite1->getDeutschkenntnisse());
            $teilnehmer->setZertifikatSprachniveau($tnseite1->getZertifikatSprachniveau());            
        }
        
        return $teilnehmer;
    }
    
    
    /**
     * A template method for displaying custom error flash messages, or to
     * display no flash message at all on errors.
     * Override this to customize
     * the flash message in your action controller.
     *
     * @api
     *
     * @return string boolean flash message or FALSE if no flash message should be set
     */
    protected function getErrorFlashMessage() {
        return FALSE;
    }
    
    
}
