<?php
namespace Ud\Iqtp13db\Controller;
use \Datetime;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;

use Psr\Http\Message\ResponseInterface;
use Ud\Iqtp13db\Domain\Repository\UserGroupRepository;
use Ud\Iqtp13db\Domain\Repository\TeilnehmerRepository;
use Ud\Iqtp13db\Domain\Repository\FolgekontaktRepository;
use Ud\Iqtp13db\Domain\Repository\DokumentRepository;
use Ud\Iqtp13db\Domain\Repository\BeraterRepository;
use Ud\Iqtp13db\Domain\Repository\AbschlussRepository;
use TYPO3\CMS\Core\Resource\StorageRepository;
use Ud\Iqtp13db\Domain\Repository\BerufeRepository;
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
 * AdministrationController
 */
class AdministrationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    
    protected $generalhelper, $niqinterface, $niqapiurl, $usergroup, $niqbid, $groupbccmail;
    
    protected $userGroupRepository;
    protected $teilnehmerRepository;
    protected $folgekontaktRepository;
    protected $dokumentRepository;
    protected $beraterRepository;
    protected $abschlussRepository;
    protected $storageRepository;
    protected $berufeRepository;
    protected $staatenRepository;
    
    public function __construct(UserGroupRepository $userGroupRepository, TeilnehmerRepository $teilnehmerRepository, FolgekontaktRepository $folgekontaktRepository, DokumentRepository $dokumentRepository, BeraterRepository $beraterRepository, AbschlussRepository $abschlussRepository, StorageRepository $storageRepository, BerufeRepository $berufeRepository, StaatenRepository $staatenRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
        $this->teilnehmerRepository = $teilnehmerRepository;
        $this->folgekontaktRepository = $folgekontaktRepository;
        $this->dokumentRepository = $dokumentRepository;
        $this->beraterRepository = $beraterRepository;
        $this->abschlussRepository = $abschlussRepository;
        $this->storageRepository = $storageRepository;
        $this->berufeRepository = $berufeRepository;
        $this->staatenRepository = $staatenRepository;
    }
    
    /**
     * action init
     *
     * @param void
     */
    public function initializeAction()
    {
        
        $this->generalhelper = new \Ud\Iqtp13db\Helper\Generalhelper();
        
        $this->user=null;
        $context = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class);
        if($context->getPropertyFromAspect('frontend.user', 'isLoggedIn')){
            $this->user=$GLOBALS['TSFE']->fe_user->user;
        } else {
            $this->user = NULL;
        }
        
        if($this->user != NULL) {
            $standardniqidberatungsstelle = $this->settings['standardniqidberatungsstelle'];
            $standardbccmail = $this->settings['standardbccmail'];
            
            $this->usergroup = $this->userGroupRepository->findByIdentifier($this->user['usergroup']);
            
            if($this->usergroup != NULL) {
                $userniqidbstelle = $this->usergroup->getNiqbid();
                $userbccmail = $this->usergroup->getGeneralmail();
            }
            
            $this->niqbid = $userniqidbstelle == '' ? $standardniqidberatungsstelle : $userniqidbstelle;
            $this->groupbccmail = $userbccmail == '' ? $standardbccmail : $userbccmail;
        } else {
            $this->groupbccmail = $this->settings['standardbccmail'];
        }
        
        //if($this->user['username'] == 'admin') {
        //DebuggerUtility::var_dump($days4wartezeit);
        //}
        
    }
    
    /**
     * action adminuebersicht
     *
     * @return void
     */
    public function adminuebersichtAction()
    {       
        $valArray = $this->request->getArguments();
        $jahrselected = $valArray['jahrauswahl'] ?? 0;
        $bundeslandselected = $valArray['bundeslandauswahl'] ?? '';
        $staatselected = $valArray['filterstaat'] ?? '%';
        
        // Admin Gruppenwechsel Beratungsstelle
        $backenduser = $this->beraterRepository->findByUid($this->user['uid']);
        if(isset($valArray['remove'])) {
            $backenduser->removeUsergroup($backenduser->getUsergroup()[1]);
            $this->beraterRepository->update($backenduser);
            
            $thisberatungsstelle = $backenduser->getUsergroup()[0]->getTitle();
            $thisniqbid = $backenduser->getUsergroup()[0]->getNiqbid();
            $niqbidselected = $thisniqbid;
        }elseif(isset($valArray['bstellen'])) {
            if(count($backenduser->getUsergroup()) > 1) $backenduser->removeUsergroup($backenduser->getUsergroup()[1]); 
            $niqbidselected = $valArray['bstellen'];
            $selectedgroup = $this->userGroupRepository->findBeratungsstellebyNiqbid($this->settings['beraterstoragepid'], $niqbidselected);
            $backenduser->addUserGroup($selectedgroup[0]);
            $this->beraterRepository->update($backenduser);
                        
            $thisberatungsstelle = $selectedgroup[0] != NULL ? $selectedgroup[0]->getTitle() : $this->usergroup->getTitle();
            $thisniqbid = $selectedgroup[0] != NULL ? $selectedgroup[0]->getNiqbid() : $this->niqbid;
        } else {
            $currentbackendusergroup = count($backenduser->getUsergroup()) > 1 ? $backenduser->getUsergroup()[1] : $backenduser->getUsergroup()[0];
            $thisniqbid = $currentbackendusergroup->getNiqbid();
            $thisberatungsstelle = $currentbackendusergroup->getTitle();
            $niqbidselected = $thisniqbid;
        }
        //
        
        $allebundeslaender = $this->userGroupRepository->findAllBundeslaender();
        
        for($i=1;$i<13;$i++) {
            $monatsnamen[$i] = date("M", mktime(0, 0, 0, $i, 1, date('Y')));
        }
        $jahrarray = array();
        for($j=2023;$j<=date('Y');$j++){
            $jahrarray[$j] = $j;
        }
        
        $emptystatusarray = array(1 => 0,2 => 0,3 => 0,4 => 0,5 => 0,6 => 0,7 => 0,8 => 0,9 => 0,10 => 0,11 => 0, 12 => 0);
        $angemeldeteTN = $emptystatusarray;
        $erstberatung = $emptystatusarray;
        $beratungfertig = $emptystatusarray;
        $niqerfasst = $emptystatusarray;
        $qfolgekontakte =  $emptystatusarray;
        $days4wartezeit = $emptystatusarray;
        $days4beratung = $emptystatusarray;
        
        $ergarrayangemeldete = $this->teilnehmerRepository->countTNby('%', $bundeslandselected, 1, $jahrselected, $staatselected);
        foreach($ergarrayangemeldete as $erg) $angemeldeteTN[$erg['monat']] = $erg['anzahl'];
        $ergarrayerstberatung = $this->teilnehmerRepository->countTNby('%', $bundeslandselected, 2, $jahrselected, $staatselected);
        foreach($ergarrayerstberatung as $erg) $erstberatung[$erg['monat']] = $erg['anzahl'];
        $ergarrayberatungfertig = $this->teilnehmerRepository->countTNby('%', $bundeslandselected, 3, $jahrselected, $staatselected);
        foreach($ergarrayberatungfertig as $erg) $beratungfertig[$erg['monat']] = $erg['anzahl'];
        $ergarrayniqerfasst = $this->teilnehmerRepository->countTNby('%', $bundeslandselected, 4, $jahrselected, $staatselected);
        foreach($ergarrayniqerfasst as $erg) $niqerfasst[$erg['monat']] = $erg['anzahl'];
        $ergarrayfolgekontakte = $this->folgekontaktRepository->countFKby('%', $bundeslandselected, $jahrselected, $staatselected);
        foreach($ergarrayfolgekontakte as $erg) $qfolgekontakte[$erg['monat']] = $erg['anzahl'];
        $ergarraywartezeitanmeldung = $this->teilnehmerRepository->calcwaitingdays('%', $bundeslandselected,'anmeldung', $jahrselected, $staatselected);
        foreach($ergarraywartezeitanmeldung as $erg) $days4wartezeit[$erg['monat']] = $erg['wert'];
        $ergarraywartezeitberatung = $this->teilnehmerRepository->calcwaitingdays('%', $bundeslandselected,'beratung', $jahrselected, $staatselected);
        foreach($ergarraywartezeitberatung as $erg) $days4beratung[$erg['monat']] = $erg['wert'];
                       
        ksort($angemeldeteTN);
        ksort($qfolgekontakte);
        ksort($erstberatung);
        ksort($beratungfertig);
        ksort($niqerfasst);
        ksort($days4wartezeit);
        ksort($days4beratung);
        
        $aktuelleanmeldungenunbestaetigt = $this->teilnehmerRepository->countAllOrder4Status(0, '%')[0]['anzahl'];        
        $aktuelleanmeldungenbestaetigt = $this->teilnehmerRepository->countAllOrder4Status(1, '%')[0]['anzahl'];
        $aktuelleanmeldungen = $aktuelleanmeldungenbestaetigt + $aktuelleanmeldungenunbestaetigt;
        $aktuellerstberatungen = $this->teilnehmerRepository->countAllOrder4Status(2, '%')[0]['anzahl'];
        $aktuellberatungenfertig = $this->teilnehmerRepository->countAllOrder4Status(3, '%')[0]['anzahl'];
        $archivierttotal = $this->teilnehmerRepository->countAllOrder4Status(4, '%')[0]['anzahl'];
        $sumalleaktuell = $aktuelleanmeldungen + $aktuellerstberatungen + $aktuellberatungenfertig + $archivierttotal;
        
        // keine Berater vorhanden?
        $alleberater = $this->beraterRepository->findAllBerater($this->settings['beraterstoragepid']);
        if(count($alleberater) == 0) {
            $this->addFlashMessage('Es sind noch keine Berater:innen vorhanden. Bitte im Menü Berater*innen anlegen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        }
        $alleberatungsstellen = $this->userGroupRepository->findAllBeratungsstellen($this->settings['beraterstoragepid']);
        $alleberatungsstellensortiert = $this->userGroupRepository->findAllBeratungsstellenABC($this->settings['beraterstoragepid']);
        
        $anzberater = array();
        $anzratsuchendeanmeld0 = array();
        $anzratsuchendeanmeld1 = array();
        $anzratsuchendeerstb = array();
        $anzratsuchendearch = array();
        foreach ($alleberatungsstellen as $bst) {
            $anzberater[$bst->getUid()] = 0;
            $anzratsuchendeanmeld0[$bst->getUid()] = $this->teilnehmerRepository->countAllOrder4Status(0, $bst->getNiqbid())[0]['anzahl'];
            $anzratsuchendeanmeld1[$bst->getUid()] = $this->teilnehmerRepository->countAllOrder4Status(1, $bst->getNiqbid())[0]['anzahl'];
            $anzratsuchendeerstb[$bst->getUid()] = $this->teilnehmerRepository->countAllOrder4Status(2, $bst->getNiqbid())[0]['anzahl'] + $this->teilnehmerRepository->countAllOrder4Status(3, $bst->getNiqbid())[0]['anzahl'];
            $anzratsuchendearch[$bst->getUid()] = $this->teilnehmerRepository->countAllOrder4Status(4, $bst->getNiqbid())[0]['anzahl'];
            
            foreach ($alleberater as $brtr) {
                foreach ($brtr->getUsergroup() as $onegrp) {
                    if($onegrp->getUid() == $bst->getUid()) {
                        $anzberater[$bst->getUid()]++;
                    }
                }   
            }
        }
        
        $statsgesamtratsuchende = $this->teilnehmerRepository->count4Status("01.1.1970", "31.12.".date('Y'), '%', 1)[0]['anzahl'];
        $statsgesamtfertigberaten = $this->teilnehmerRepository->count4Status("01.1.1970", "31.12.".date('Y'), '%', 3)[0]['anzahl'];
        $statsgesamtarchiviert = $this->teilnehmerRepository->count4Status("01.1.1970", "31.12.".date('Y'), '%', 5)[0]['anzahl'];
        
        $neuanmeldungen7tage = array();
        for($i = 7; $i >= 0; $i--) {
            $reftag = date("d.m.Y", strtotime( '-'.$i.' days' ));
            $neuanmeldungen7tage[$i]["tag"] = date("l, d.m.Y", strtotime( '-'.$i.' days' ));
            $neuanmeldungen7tage[$i]["wert"] = $this->teilnehmerRepository->count4Status($reftag, $reftag, '%', 1)[0]['anzahl'];
        }
        
        $letzteanmeldungen = $this->teilnehmerRepository->findLast4Admin();
               
        
        
        // *********** Stats Bundesland/Beruf/Staatsangehörigkeit *************
        
        $berufe = $this->berufeRepository->findAll();
        foreach($berufe as $beruf) {
            $berufearr[$beruf->getBerufid()] = $beruf->getTitel();
        }
        $staaten = $this->staatenRepository->findByLangisocode('de');
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }
        
        if($jahrselected != 0) {
            $arrabschlussart =  array('' => 'nichts eingetragen', '-1' => 'keine Angabe', '1' => 'Ausbildungsabschluss', '2' => 'Universitätsabschluss', '1,2' => 'sowohl Uni, als auch Ausbildungsabschluss', '-1,1' => 'k.A. und Ausbildungsabschluss', '-1,2' => 'k.A. und Universitätsabschluss');
            
            $abschlussartanmeldungen = $this->teilnehmerRepository->showAbschlussart(0, $jahrselected, $bundeslandselected, $staatselected);
            $abschlussartberatungabgeschl = $this->teilnehmerRepository->showAbschlussart(4, $jahrselected, $bundeslandselected, $staatselected);
            
            if($staatselected == '%') {
                $herkunftanmeldungen = $this->teilnehmerRepository->showHerkunft(0, $jahrselected, $bundeslandselected);
                $herkunftberatungabgeschl = $this->teilnehmerRepository->showHerkunft(4, $jahrselected, $bundeslandselected);
            }
            
            $berufeanmeldungen = $this->teilnehmerRepository->showAbschluesseBerufe(0, $jahrselected, $bundeslandselected, $staatselected);
            $berufeberatungabgeschl = $this->teilnehmerRepository->showAbschluesseBerufe(4, $jahrselected, $bundeslandselected, $staatselected);
            
            $geschlechtartanmeldungen = $this->teilnehmerRepository->showGeschlecht(0, $jahrselected, $bundeslandselected, $staatselected);
            $geschlechtberatungabgeschl = $this->teilnehmerRepository->showGeschlecht(4, $jahrselected, $bundeslandselected, $staatselected);
            
            $arrgeschlecht =  array('0' => 'nichts eingetragen', '-1' => 'keine Angabe', '1' => 'weiblich', '2' => 'männlich', '3' => 'divers');
            
            $lebensalteranmeldungen = $this->teilnehmerRepository->showAlter(0, $jahrselected, $bundeslandselected, $staatselected);
            $lebensalterberatungabgeschl = $this->teilnehmerRepository->showAlter(4, $jahrselected, $bundeslandselected, $staatselected);
            
            //DebuggerUtility::var_dump($abschlussartanmeldungen);
        }
        
        // *****************************************************************
        
        // ********************* PLZ doppelt vergeben? *********************
        $plzarray = $this->userGroupRepository->getallplzarray();
        $bidplzarray = array();
        $i=0;
        foreach($plzarray as $bid) {
            $bidplz = explode(',', $bid['plzlist']);
            foreach($bidplz as $plz) {
                $bidplzarray[$i] = $plz;
                $bidarray[$i] =
                $i++;
            }
        }
        $unique = array_unique($bidplzarray);
        $doppelteplzarr = array_diff_assoc($bidplzarray, $unique);
        $doppelteplzberatungsstelle = array();
        foreach($doppelteplzarr as $doppelteplz) {
            if($doppelteplz != '') $doppelteplzberatungsstelle[$doppelteplz] = $this->userGroupRepository->getBeratungsstelle4PLZ($doppelteplz, $this->settings['beraterstoragepid']);
        }
        // ******************************************************************
        
        
        $this->view->assignMultiple(
            [
                'monatsnamen'=> $monatsnamen,
                'aktmonat'=> $jahrselected == 0 ? idate('m')-1 : '',
                'jahrauswahl' => $jahrarray,
                'jahrselected' => $jahrselected,
                'angemeldeteTN'=> $angemeldeteTN,
                'SUMangemeldeteTN'=> array_sum($angemeldeteTN),
                'qfolgekontakte'=> $qfolgekontakte,
                'SUMqfolgekontakte'=> array_sum($qfolgekontakte),
                'erstberatung'=> $erstberatung,
                'SUMerstberatung'=> array_sum($erstberatung),
                'beratungfertig'=> $beratungfertig,
                'SUMberatungfertig'=> array_sum($beratungfertig),
                'niqerfasst'=> $niqerfasst,
                'SUMniqerfasst'=> array_sum($niqerfasst),
                'totalavgmonthb'=> $days4beratung,
                'SUMtotalavgmonthb'=> array_sum($days4beratung)/count($days4beratung),
                'totalavgmonthw'=> $days4wartezeit,
                'SUMtotalavgmonthw'=> array_sum($days4wartezeit)/count($days4beratung),
                'aktuelleanmeldungen'=> $aktuelleanmeldungen,
                'aktuelleanmeldungenunbestaetigt' => $aktuelleanmeldungenunbestaetigt,
                'aktuelleanmeldungenbestaetigt' => $aktuelleanmeldungenbestaetigt,
                'aktuellerstberatungen'=> $aktuellerstberatungen,
                'aktuellberatungenfertig'=> $aktuellberatungenfertig,
                'archivierttotal'=> $archivierttotal,                
                'anzberatungsstellen' => count($alleberatungsstellen),
                'alleberatungsstellen' => $alleberatungsstellen,
                'alleberatungsstellensortiert' => $alleberatungsstellensortiert,
                'anzalleberater' => count($alleberater),
                'anzberater' => $anzberater,
                'anzratsuchendeanmeld0' => $anzratsuchendeanmeld0,
                'anzratsuchendeanmeld1' => $anzratsuchendeanmeld1,
                'anzratsuchendeerstb' => $anzratsuchendeerstb,
                'anzratsuchendearch' => $anzratsuchendearch,
                'anzuserberatungsstellen' => count($backenduser->getUsergroup()),
                'alleRatsuchendentotal' => $sumalleaktuell,
                'statsgesamtratsuchende' => $statsgesamtratsuchende,
                'statsgesamtfertigberaten' => $statsgesamtfertigberaten,
                'statsgesamtarchiviert' => $statsgesamtarchiviert,
                'neuanmeldungen7tage' => $neuanmeldungen7tage,
                'diesesjahr' => date('y'),
                'letztesjahr' => idate('y') - 1,
                'niqbidselected' => $niqbidselected,
                'beratungsstelle' => $thisberatungsstelle,
                'niqbid' => $thisniqbid,
                'letzteanmeldungen' => $letzteanmeldungen,
                'allebundeslaender' => $allebundeslaender,
                'bundeslandselected' => $bundeslandselected,
                'doppelteplzarray' => $doppelteplzberatungsstelle,
                'staatenarr' => $staatenarr,
                'berufearr' => $berufearr,
                'filterbundesland' => $filterbundesland ?? '',
                'filterstaat' => $staatselected,
                'filterberuf' => $berufselected ?? '',
                'ausgabearray' => $ausgabearray ?? '',
                'anzgesamt' => $anzgesamt ?? '',
                'abschlussartanmeldungen' => $abschlussartanmeldungen ?? '',
                'abschlussartberatungabgeschl' => $abschlussartberatungabgeschl ?? '',
                'arrabschlussart' => $arrabschlussart ?? '',
                'herkunftanmeldungen' => $herkunftanmeldungen ?? '',
                'herkunftberatungabgeschl' => $herkunftberatungabgeschl ?? '',
                'berufeanmeldungen' => $berufeanmeldungen ?? '',
                'berufeberatungabgeschl' => $berufeberatungabgeschl ?? '',
                'arrgeschlecht' => $arrgeschlecht ?? '',
                'geschlechtartanmeldungen' => $geschlechtartanmeldungen ?? '',
                'geschlechtberatungabgeschl' => $geschlechtberatungabgeschl ?? '',
                'lebensalteranmeldungen' => $lebensalteranmeldungen ?? '',
                'lebensalterberatungabgeschl' => $lebensalterberatungabgeschl ?? ''
                
            ]
            );
    }
   
}
    