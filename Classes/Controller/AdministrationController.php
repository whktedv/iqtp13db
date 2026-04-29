<?php
namespace Ud\Iqtp13db\Controller;

use TYPO3\CMS\Core\Core\Environment;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

use Ud\Iqtp13db\Domain\Repository\UserGroupRepository;
use Ud\Iqtp13db\Domain\Repository\TeilnehmerRepository;
use Ud\Iqtp13db\Domain\Repository\FolgekontaktRepository;
use Ud\Iqtp13db\Domain\Repository\DokumentRepository;
use Ud\Iqtp13db\Domain\Repository\BeraterRepository;
use Ud\Iqtp13db\Domain\Repository\AbschlussRepository;
use TYPO3\CMS\Core\Resource\StorageRepository;
use Ud\Iqtp13db\Domain\Repository\BerufeRepository;
use Ud\Iqtp13db\Domain\Repository\StaatenRepository;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

require_once(Environment::getPublicPath() . '/' . 'typo3conf/ext/iqtp13db/Resources/Private/Libraries/xlsxwriter.class.php');

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
    
    protected $user, $generalhelper, $niqapiurl, $usergroup, $niqbid, $groupbccmail;
    
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
    
    private function getConnectionPool(): ConnectionPool
    {
        return GeneralUtility::makeInstance(ConnectionPool::class);
    }

    /**
     * action init
     *
     */
    public function initializeAction(): void
    {
       
        $this->generalhelper = new \Ud\Iqtp13db\Helper\Generalhelper();
        
        $this->user=null;
        $context = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class);
        if($context->getPropertyFromAspect('frontend.user', 'isLoggedIn')){
            $this->user = $this->request->getAttribute('frontend.user');
        } else {
            $this->user = NULL;
        }
        
        if($this->user != NULL) {
            $standardniqidberatungsstelle = $this->settings['standardniqidberatungsstelle'];
            $standardbccmail = $this->settings['standardbccmail'];
            
            $this->usergroup = $this->userGroupRepository->findByIdentifier($this->user->user['usergroup']);
            
            if($this->usergroup != NULL) {
                $userniqidbstelle = $this->usergroup->getNiqbid() ?? $standardniqidberatungsstelle;
                $userbccmail = $this->usergroup->getGeneralmail();
            }
            
            $sesniqbid = $this->user->getKey('ses', 'currentusergroup') ?? '';
            $this->niqbid = $sesniqbid != '' ? $sesniqbid : $userniqidbstelle;    
            
            $this->groupbccmail = $userbccmail == '' ? $standardbccmail : $userbccmail;
        } else {
            $this->groupbccmail = $this->settings['standardbccmail'];
        }
        
    }
    
    /**
     * action adminuebersicht
     *
     * @return void
     */
    public function adminuebersichtAction(): ResponseInterface
    {       
        $valArray = $this->request->getArguments();
        $emptystatusarray = array(1 => 0,2 => 0,3 => 0,4 => 0,5 => 0,6 => 0,7 => 0,8 => 0,9 => 0,10 => 0,11 => 0, 12 => 0);
        $jahrselected = $valArray['jahrauswahl'] ?? 0;
        $bundeslandselected = $valArray['bundeslandauswahl'] ?? '%';
        $staatselected = $valArray['filterstaat'] ?? '%';
        $filterbstelle = $valArray['filterbstelle'] ?? '%';
        
        $backenduser = $this->beraterRepository->findByUid($this->user->user['uid']);
        if(isset($valArray['remove'])) {
            $thisberatungsstelle = $backenduser->getUsergroup()[0]->getTitle();
            $thisniqbid = $backenduser->getUsergroup()[0]->getNiqbid();
            $niqbidgruppeselected = $thisniqbid;
            $this->user->setKey('ses', 'currentusergroup', $niqbidgruppeselected);
            $this->niqbid = $this->user->getKey('ses', 'currentusergroup');
        }elseif(isset($valArray['bstellen']) && $valArray['bstellen'] != '') {
            $niqbidgruppeselected = $valArray['bstellen'];
            $this->user->setKey('ses', 'currentusergroup', $niqbidgruppeselected);
            $this->niqbid = $this->user->getKey('ses', 'currentusergroup');
        }

        $thisgroup = $this->userGroupRepository->findBeratungsstellebyNiqbid($this->settings['beraterstoragepid'], $this->niqbid);
        $thisberatungsstelle = $thisgroup[0]->getTitle();
        $allebundeslaender = $this->userGroupRepository->findAllBundeslaender();
        $alleberatungsstellen = $this->userGroupRepository->findAllBeratungsstellen($this->settings['beraterstoragepid']);
        $alleberatungsstellensortiert = $this->userGroupRepository->findAllBeratungsstellenABC($this->settings['beraterstoragepid']);
        // keine Berater vorhanden?
        $alleberater = $this->beraterRepository->findAllBerater($this->settings['beraterstoragepid']);
        if(count($alleberater) == 0) {
            $this->addFlashMessage('Es sind noch keine Berater:innen vorhanden. Bitte im Menü Berater*innen anlegen.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
        }

        $monatsnamen = $this->generalhelper->getMonthNames($jahrselected);        
        $jahrarray = array();
        for($j=2023;$j<=date('Y');$j++){
            $jahrarray[$j] = $j;
        }
        $jahrarray[99] = "- alle seit 01/2023 -";
        

        // ------------ Daten für Übersicht-Statistik aus Cache-Tabelle lesen ----------
        $qb2 = $this->getConnectionPool()->getQueryBuilderForTable('tx_iqtp13db_domain_model_statistik_cache');
        $rows2 = $qb2
            ->select('niqbid', 'metric', 'wert_json', 'generated_at')
            ->from('tx_iqtp13db_domain_model_statistik_cache')
            ->where($qb2->expr()->like('niqbid', $qb2->createNamedParameter($filterbstelle)))
            ->andWhere($qb2->expr()->eq('bezugsjahr', $qb2->createNamedParameter(999)))
            ->andWhere($qb2->expr()->like('bundesland', $qb2->createNamedParameter($bundeslandselected)))
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($rows2 as $row) {
            if($row['metric'] == 'aktuelleanmeldungenunbestaetigt') {
                $aktuelleanmeldungenunbestaetigt[] = json_decode($row['wert_json'], true);
                $anzratsuchendeanmeld0[$row['niqbid']]= json_decode($row['wert_json'], true);
            }
            if($row['metric'] == 'aktuelleanmeldungenbestaetigt') {
                $aktuelleanmeldungenbestaetigt[] = json_decode($row['wert_json'], true);
                $anzratsuchendeanmeld1[$row['niqbid']] = json_decode($row['wert_json'], true);
            }
            if($row['metric'] == 'aktuelleanmeldungen') $aktuelleanmeldungen[] = json_decode($row['wert_json'], true);
            if($row['metric'] == 'aktuellerstberatungen') {
                $aktuellerstberatungen[] = json_decode($row['wert_json'], true);
                $anzratsuchendeerstb[$row['niqbid']] = json_decode($row['wert_json'], true);
            }
            if($row['metric'] == 'aktuellberatungenfertig') {
                $aktuellberatungenfertig[] = json_decode($row['wert_json'], true);
                $anzratsuchendearch[$row['niqbid']] = json_decode($row['wert_json'], true);
            }
            if($row['metric'] == 'archivierttotal') {
                $archivierttotal[] = json_decode($row['wert_json'], true);
                $alleberatungsstellencurrstats[$row['niqbid']]['archivierttotal'] = json_decode($row['wert_json'], true);
            }
            if($row['metric'] == 'neuanmeldungen7tage') $neuanmeldungen7tage[] = json_decode($row['wert_json'], true);
        }
        $aktuelleanmeldungenunbestaetigt = array_sum($aktuelleanmeldungenunbestaetigt);
        $aktuelleanmeldungenbestaetigt = array_sum($aktuelleanmeldungenbestaetigt);        
        $aktuelleanmeldungen = array_sum($aktuelleanmeldungen);
        $aktuellerstberatungen = array_sum($aktuellerstberatungen);
        $aktuellberatungenfertig = array_sum($aktuellberatungenfertig);
        $archivierttotal = array_sum($archivierttotal);
        if(count($neuanmeldungen7tage) == 1) {
            $neuanmeldungen7tage = $neuanmeldungen7tage[0];
        } else {
            $resultdaysarr = array();
            foreach($neuanmeldungen7tage as $arrwithdays) {   
                $i = 0;                
                
                foreach($arrwithdays as $singleday) {                    
                    $resultdaysarr[$i]['tag'] = $singleday['tag'];
                    $newvalue = ($resultdaysarr[$i]['wert'] ?? 0) + $singleday['wert'];
                    $resultdaysarr[$i]['wert'] = $newvalue;
                    $i++;
                }
            } 
            $neuanmeldungen7tage = $resultdaysarr;
        } 
        // --------------------------------------------   
        $sumalleaktuell = $aktuelleanmeldungen + $aktuellerstberatungen + $aktuellberatungenfertig + $archivierttotal;

        if($staatselected == '%') {
            // ----------- Daten für Jahres-Statistik aus Cache-Tabelle auslesen: ---------    
            $qb = $this->getConnectionPool()->getQueryBuilderForTable('tx_iqtp13db_domain_model_statistik_cache');
            $rows = $qb
                ->select('niqbid', 'metric', 'wert_json', 'generated_at')
                ->from('tx_iqtp13db_domain_model_statistik_cache')
                ->where($qb->expr()->like('niqbid', $qb->createNamedParameter($filterbstelle)))
                ->andWhere($qb->expr()->eq('bezugsjahr', $qb->createNamedParameter($jahrselected)))
                ->andWhere($qb->expr()->like('bundesland', $qb->createNamedParameter($bundeslandselected)))
                ->executeQuery()
                ->fetchAllAssociative();
            $anzwartezeiten = 0;
            $anzberatungszeiten = 0;
            foreach ($rows as $row) {
                $stand = date('d.m.Y H:i', (int)$row['generated_at']);
                if($row['metric'] == 'angemeldeteTN') $angemeldeteTN[] = json_decode($row['wert_json'], true);
                if($row['metric'] == 'erstberatung')  $erstberatung[] = json_decode($row['wert_json'], true);
                if($row['metric'] == 'beratungfertig') $beratungfertig[] = json_decode($row['wert_json'], true);
                if($row['metric'] == 'qfolgekontakte') $qfolgekontakte[] = json_decode($row['wert_json'], true);
                if($row['metric'] == 'days4wartezeit') {
                    $days4wartezeit[] = json_decode($row['wert_json'], true);
                    $anzwartezeiten++;
                }
                if($row['metric'] == 'days4beratung') {
                    $days4beratung[] = json_decode($row['wert_json'], true);
                    $anzberatungszeiten++;
                }
            }        
            $angemeldeteTN = array_map(fn(...$values) => array_sum($values), ...$angemeldeteTN);
            $erstberatung = array_map(fn(...$values) => array_sum($values), ...$erstberatung);
            $beratungfertig = array_map(fn(...$values) => array_sum($values), ...$beratungfertig);
            $qfolgekontakte = array_map(fn(...$values) => array_sum($values), ...$qfolgekontakte);
            $days4wartezeit = array_map(fn(...$values) => array_sum($values), ...$days4wartezeit);
            foreach($days4wartezeit as $key => $d4w) {
                $newvalue = $d4w/$anzwartezeiten;
                $days4wartezeit[$key] = $newvalue;
            }
            $days4beratung = array_map(fn(...$values) => array_sum($values), ...$days4beratung);
            foreach($days4beratung as $key => $d4w) {
                $newvalue = $d4w/$anzberatungszeiten;
                $days4beratung[$key] = $newvalue;
            }
            // --------------------------------------------
        } else {            
            $angemeldeteTN = $emptystatusarray;
            $erstberatung = $emptystatusarray;
            $beratungfertig = $emptystatusarray;
            $niqerfasst = $emptystatusarray;
            $qfolgekontakte =  $emptystatusarray;
            $days4wartezeit = $emptystatusarray;
            $days4beratung = $emptystatusarray;

            $ergarrayangemeldete = $this->teilnehmerRepository->countTNby($filterbstelle, $bundeslandselected, 1, $jahrselected, $staatselected);
            foreach($ergarrayangemeldete as $erg) $angemeldeteTN[$erg['monat']] = $erg['anzahl'];
            $ergarrayerstberatung = $this->teilnehmerRepository->countTNby($filterbstelle, $bundeslandselected, 2, $jahrselected, $staatselected);
            foreach($ergarrayerstberatung as $erg) $erstberatung[$erg['monat']] = $erg['anzahl'];
            $ergarrayberatungfertig = $this->teilnehmerRepository->countTNby($filterbstelle, $bundeslandselected, 3, $jahrselected, $staatselected);
            foreach($ergarrayberatungfertig as $erg) $beratungfertig[$erg['monat']] = $erg['anzahl'];
            $ergarrayniqerfasst = $this->teilnehmerRepository->countTNby($filterbstelle, $bundeslandselected, 4, $jahrselected, $staatselected);
            foreach($ergarrayniqerfasst as $erg) $niqerfasst[$erg['monat']] = $erg['anzahl'];
            $ergarrayfolgekontakte = $this->folgekontaktRepository->countFKby($filterbstelle, $bundeslandselected, $jahrselected, $staatselected);
            foreach($ergarrayfolgekontakte as $erg) $qfolgekontakte[$erg['monat']] = $erg['anzahl'];
            $ergarraywartezeitanmeldung = $this->teilnehmerRepository->calcwaitingdays($filterbstelle, $bundeslandselected,'anmeldung', $jahrselected, $staatselected);
            foreach($ergarraywartezeitanmeldung as $erg) $days4wartezeit[$erg['monat']] = $erg['wert'];
            $ergarraywartezeitberatung = $this->teilnehmerRepository->calcwaitingdays($filterbstelle, $bundeslandselected,'beratung', $jahrselected, $staatselected);
            foreach($ergarraywartezeitberatung as $erg) $days4beratung[$erg['monat']] = $erg['wert'];

            ksort($angemeldeteTN);
            ksort($qfolgekontakte);
            ksort($erstberatung);
            ksort($beratungfertig);
            ksort($niqerfasst);
            ksort($days4wartezeit);
            ksort($days4beratung);
        }
                
        $anzberater = array();
        foreach ($alleberatungsstellen as $bst) {
            $anzberater[$bst->getNiqbid()] = 0;
            foreach ($alleberater as $brtr) {
                foreach ($brtr->getUsergroup() as $onegrp) {
                    if($onegrp->getUid() == $bst->getUid()) {
                        $anzberater[$bst->getNiqbid()]++;
                    }
                }   
            }
        }
        
        $statsgesamtratsuchende = $this->teilnehmerRepository->count4Status("01.1.1970", "31.12.".date('Y'), '%', 1, '%')[0]['anzahl'];
        $statsgesamtfertigberaten = $this->teilnehmerRepository->count4Status("01.1.1970", "31.12.".date('Y'), '%', 3, '%')[0]['anzahl'];
        $statsgesamtarchiviert = $this->teilnehmerRepository->count4Status("01.1.1970", "31.12.".date('Y'), '%', 5, '%')[0]['anzahl'];
        
        $letzteanmeldungen = $this->teilnehmerRepository->findLast4Admin();
        
        // *********** Stats Bundesland/Beruf/Staatsangehörigkeit *************
        
        $berufe = $this->berufeRepository->findAllOrdered('de');
        foreach($berufe as $beruf) {
            $berufearr[$beruf->getBerufid()] = $beruf->getTitel();
        }
        $staaten = $this->staatenRepository->findByLangisocode('de');
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }
        foreach($alleberatungsstellen as $bst) {
            $bstellenarr[$bst->getNiqbid()] = $bst->getTitle();
        }
        
        if($jahrselected != 0) {
            $arrabschlussart1 = $this->settings['abschlussart'];  
            $arrabschlussart2 = array("" => 'nichts eingetragen', '1,2' => 'Alte Angabe: sowohl Uni, als auch Ausbildungsabschluss', '-1,1' => 'Alte Angabe: k.A. und Ausbildungsabschluss', '-1,2' => 'Alte Angabe: k.A. und Universitätsabschluss', '-1,1,2'  => 'Eintrag fehlerhaft');
            $arrabschlussart = array_merge($arrabschlussart1, $arrabschlussart2);

            $abschlussartanmeldungen = $this->teilnehmerRepository->showAbschlussart($filterbstelle, 0, $jahrselected, $bundeslandselected, $staatselected);
            $abschlussartberatungabgeschl = $this->teilnehmerRepository->showAbschlussart($filterbstelle, 4, $jahrselected, $bundeslandselected, $staatselected);
            
            $herkunftanmeldungen = array();
            $herkunftberatungabgeschl = array();
            if($staatselected == '%') {
                $herkunftanmeldungen = $this->teilnehmerRepository->showHerkunft($filterbstelle, 0, $jahrselected, $bundeslandselected);
                $herkunftberatungabgeschl = $this->teilnehmerRepository->showHerkunft($filterbstelle, 4, $jahrselected, $bundeslandselected);
            }
            
            $berufeanmeldungen = $this->teilnehmerRepository->showAbschluesseBerufe($filterbstelle, 0, $jahrselected, $bundeslandselected, $staatselected);
            $berufeberatungabgeschl = $this->teilnehmerRepository->showAbschluesseBerufe($filterbstelle, 4, $jahrselected, $bundeslandselected, $staatselected);
            
            $brancheanmeldungen = $this->teilnehmerRepository->showAbschluesseBranchen($filterbstelle, 0, $jahrselected, $bundeslandselected, $staatselected);
            $brancheberatungabgeschl = $this->teilnehmerRepository->showAbschluesseBranchen($filterbstelle, 4, $jahrselected, $bundeslandselected, $staatselected);
            
            $geschlechtartanmeldungen = $this->teilnehmerRepository->showGeschlecht($filterbstelle, 0, $jahrselected, $bundeslandselected, $staatselected);
            $geschlechtberatungabgeschl = $this->teilnehmerRepository->showGeschlecht($filterbstelle, 4, $jahrselected, $bundeslandselected, $staatselected);
            
            $arrgeschlecht =  array('0' => 'nichts eingetragen', '-1' => 'keine Angabe', '1' => 'weiblich', '2' => 'männlich', '3' => 'divers');
            
            $lebensalteranmeldungen = $this->teilnehmerRepository->showAlter($filterbstelle, 0, $jahrselected, $bundeslandselected, $staatselected);
            $lebensalterberatungabgeschl = $this->teilnehmerRepository->showAlter($filterbstelle, 4, $jahrselected, $bundeslandselected, $staatselected);
            
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
        
        // ******************** EXPORT Statistik ****************************
        if (isset($valArray['statsexport']) && $jahrselected != 0) {
            
            // XLSX
            $filename = 'adminstatistik_'.date('Y-m-d_H-i-s', time()).'.xlsx';
            $writer = new \XLSXWriter();
            $writer->setAuthor('IQ Webapp');
            
            // Statistik Jahr allgemein
            $rows[0] = $monatsnamen;
            array_unshift($rows[0], " ");
            $rows[1] = $angemeldeteTN;
            array_unshift($rows[1], "Anmeldungen");
            $rows[2] = $erstberatung;
            array_unshift($rows[2], "Erstberatungen");
            $rows[3] = $qfolgekontakte;
            array_unshift($rows[3], "Folgekontakte");
            $rows[4] = $beratungfertig;
            array_unshift($rows[4], "Beratungen fertig");
            $rows[5] = $days4wartezeit;
            array_unshift($rows[5], "durchschn. Tage Wartezeit");
            $rows[6] = $days4beratung;
            array_unshift($rows[6], "durchschn. Tage Beratungsdauer");
            
            $headerblatt1 = [
                'Statistik '.($jahrselected != 0 ? $jahrselected : 'letzte 12 Monate') => 'string',
                'Bundesland: '.($bundeslandselected == '%' ? 'Alle' : $bundeslandselected)  => 'string',
                'Staatsangehörigkeit: '.($staatselected == '%' ? 'Alle' : $staatenarr[$staatselected]) => 'string',
                'Beratungsstelle: '.($filterbstelle == '%' ? 'Alle' : $bstellenarr[$filterbstelle]) => 'string',               
            ];
            
            // Abschlussart
            $rowsabschla[0] = array("Abschlussart alle Anmeldungen ".$jahrselected, "Anzahl");                        
            $i=1;
            foreach($abschlussartanmeldungen as $abschlussart) {
                $rowsabschla[$i] = array($arrabschlussart[$abschlussart['abschlussart']], $abschlussart['anz']);
                $i++;
            }           
            $rowsabschla[$i] = array(" ", " ");
            $i++;
            $rowsabschla[$i] = array("Abschlussart alle Beratungen ".$jahrselected, "Anzahl");
            $i++;
            foreach($abschlussartberatungabgeschl as $abschlussart) {
                $rowsabschla[$i] = array($arrabschlussart[$abschlussart['abschlussart']], $abschlussart['anz']);
                $i++;
            }
            
            // Herkunft
            $rowsherkunft[0] = array("Herkunft alle Anmeldungen ".$jahrselected, "Anzahl");
            $i=1;
            foreach($herkunftanmeldungen as $herkunft) {
                $rowsherkunft[$i] = array($herkunft['titel'], $herkunft['anz']);
                $i++;
            }
            $rowsherkunft[$i] = array(" ", " ");
            $i++;
            $rowsherkunft[$i] = array("Herkunft alle Beratungen ".$jahrselected, "Anzahl");
            $i++;
            foreach($herkunftberatungabgeschl as $herkunft) {
                $rowsherkunft[$i] = array($herkunft['titel'], $herkunft['anz']);
                $i++;
            }
            
            // Berufe
            $rowsberufe[0] = array("Berufe/Abschlüsse alle Anmeldungen ".$jahrselected, "Anzahl");
            $i=1;
            foreach($berufeanmeldungen as $beruf) {
                $beruftitel = $beruf['titel'] == '' ? 'kein Beruf/Abschluss eingetragen' : $beruf['titel'];
                $rowsberufe[$i] = array($beruftitel, $beruf['anz']);
                $i++;
            }
            $rowsberufe[$i] = array(" ", " ");
            $i++;
            $rowsberufe[$i] = array("Berufe/Abschlüsse alle Beratungen ".$jahrselected, "Anzahl");
            $i++;
            foreach($berufeberatungabgeschl as $beruf) {
                $beruftitel = $beruf['titel'] == '' ? 'kein Beruf/Abschluss eingetragen' : $beruf['titel'];
                $rowsberufe[$i] = array($beruftitel, $beruf['anz']);
                $i++;
            }
            
            // Branchen
            $rowsbranchen[0] = array("Branchen alle Anmeldungen ".$jahrselected, "Anzahl");
            $i=1;
            foreach($brancheanmeldungen as $branche) {
                $branchetitel = $branche['titel'] == '' ? 'kein Beruf/Abschluss eingetragen' : $branche['titel'];
                $rowsbranchen[$i] = array($branchetitel, $branche['anz']);
                $i++;
            }
            $rowsbranchen[$i] = array(" ", " ");
            $i++;
            $rowsbranchen[$i] = array("Branchen alle Beratungen ".$jahrselected, "Anzahl");
            $i++;
            foreach($berufeberatungabgeschl as $branche) {
                $branchetitel = $branche['titel'] == '' ? 'kein Beruf/Abschluss eingetragen' : $branche['titel'];
                $rowsbranchen[$i] = array($branchetitel, $branche['anz']);
                $i++;
            }
            
            // Geschlecht
            $rowsgeschlecht[0] = array("Geschlecht alle Anmeldungen ".$jahrselected, "Anzahl");
            $i=1;
            foreach($geschlechtartanmeldungen as $geschlecht) {
                $rowsgeschlecht[$i] = array($arrgeschlecht[$geschlecht['geschlecht']], $geschlecht['anz']);
                $i++;
            }
            $rowsgeschlecht[$i] = array(" ", " ");
            $i++;
            $rowsgeschlecht[$i] = array("Geschlecht alle Beratungen ".$jahrselected, "Anzahl");
            $i++;
            foreach($geschlechtberatungabgeschl as $geschlecht) {
                $rowsgeschlecht[$i] = array($arrgeschlecht[$geschlecht['geschlecht']], $geschlecht['anz']);
                $i++;
            }
            
            // Lebensalter
            $rowsalter[0] = array("Lebensalter alle Anmeldungen ".$jahrselected, "Anzahl");
            $i=1;
            foreach($lebensalteranmeldungen as $alter) {
                if($alter['lebensalter'] == '-1000') $keyalter = 'keine Angabe';
                elseif($alter['lebensalter'] == '-1') $keyalter = 'nichts eingetragen';
                else $keyalter = $alter['lebensalter'];
                $rowsalter[$i] = array($keyalter , $alter['anz']);
                $i++;
            }
            $rowsalter[$i] = array(" ", " ");
            $i++;
            $rowsalter[$i] = array("Lebensalter alle Beratungen ".$jahrselected, "Anzahl");
            $i++;
            foreach($lebensalterberatungabgeschl as $alter) {
                if($alter['lebensalter'] == '-1000') $keyalter = 'keine Angabe';
                elseif($alter['lebensalter'] == '-1') $keyalter = 'nichts eingetragen';
                else $keyalter = $alter['lebensalter'];
                $rowsalter[$i] = array($keyalter, $alter['anz']);
                $i++;
            }
            
            $writer->writeSheet($rows, 'Statistik', $headerblatt1);
            $writer->writeSheet($rowsabschla, 'Abschlussart');
            $writer->writeSheet($rowsherkunft, 'Herkunft');
            $writer->writeSheet($rowsberufe, 'Berufe');
            $writer->writeSheet($rowsbranchen, 'Branchen');
            $writer->writeSheet($rowsgeschlecht, 'Geschlecht');
            $writer->writeSheet($rowsalter, 'Lebensalter');
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer->writeToStdOut();
            exit;
        } elseif(isset($valArray['statsexport']) && $jahrselected == 0) {
            $this->addFlashMessage("Bitte Jahr auswählen!", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::WARNING);
        }
        
        // ******************** EXPORT Statistik bis hier ****************************
        
        // *********** find TN by UID ****************
        if(isset($valArray['zeigeTN'])) {
            if (filter_var($valArray['uideingabe'], FILTER_VALIDATE_EMAIL)) {
                $searchtn = $this->teilnehmerRepository->findOneByEmail($valArray['uideingabe']);
            } else {
                $searchtn = $this->teilnehmerRepository->findOneByUid($valArray['uideingabe']);
            }
            if($searchtn != null){
                $tndaten = array();
                $tndaten['uid'] = $valArray['uideingabe'];
                $tnbstellefromrepo = $this->userGroupRepository->findBeratungsstellebyNiqbid($this->settings['beraterstoragepid'], $searchtn->getNiqidberatungsstelle());        
                $tndaten['beratungsstelle'] = $tnbstellefromrepo[0]->getTitle();                
                switch($searchtn->getBeratungsstatus()) {
                    case 0:
                        $tndaten['beratungsstatus'] = 'unbestätigt angemeldet';
                        break;
                    case 1:
                        $tndaten['beratungsstatus'] = 'bestätigt angemeldet';
                        break;
                    case 2:
                        $tndaten['beratungsstatus'] = 'Beratung begonnen';
                        break;
                    case 3:
                        $tndaten['beratungsstatus'] = 'Beratung abgeschlossen';
                        break;
                    case 4:
                        $tndaten['beratungsstatus'] = 'archiviert';
                        break;
                    case 99:
                        $tndaten['beratungsstatus'] = 'Anmeldung nicht abgeschlossen!';
                        break;
                    default:
                        $tndaten['beratungsstatus'] = '';
                }
                $tndaten['nachname'] = $searchtn->getNachname();
                $tndaten['vorname'] = $searchtn->getVorname();
                $tndaten['email'] = $searchtn->getEmail();
                $tndaten['anmeldedatum'] = date('m/d/Y', $searchtn->getVerificationDate());
                $tndaten['erstberatungabgeschlossen'] = $searchtn->getErstberatungabgeschlossen();                         
            } else {                                
                $this->addFlashMessage("UID bzw. E-Mail-Adresse unbekannt.", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::WARNING);
            }
        }        
        $this->view->assignMultiple(
            [   
                'stand' => $stand,
                'monatsnamen'=> $monatsnamen,
                'aktmonat'=> $jahrselected == 0 ? idate('m')-1 : '',
                'jahrauswahl' => $jahrarray,
                'jahrselected' => $jahrselected,
                'angemeldeteTN'=> $angemeldeteTN ?? $emptystatusarray,
                'SUMangemeldeteTN'=> array_sum($angemeldeteTN ?? $emptystatusarray),
                'qfolgekontakte'=> $qfolgekontakte ?? $emptystatusarray,
                'SUMqfolgekontakte'=> array_sum($qfolgekontakte ?? $emptystatusarray),
                'erstberatung'=> $erstberatung ?? $emptystatusarray,
                'SUMerstberatung'=> array_sum($erstberatung ?? $emptystatusarray),
                'beratungfertig'=> $beratungfertig ?? $emptystatusarray,
                'SUMberatungfertig'=> array_sum($beratungfertig ?? $emptystatusarray),
                'totalavgmonthb'=> $days4beratung ?? $emptystatusarray,
                'SUMtotalavgmonthb'=> array_sum($days4beratung ?? $emptystatusarray)/count($days4beratung ?? $emptystatusarray),
                'totalavgmonthw'=> $days4wartezeit ?? $emptystatusarray,
                'SUMtotalavgmonthw'=> array_sum($days4wartezeit ?? $emptystatusarray)/count($days4beratung ?? $emptystatusarray),
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
                'beratungsstelle' => $thisberatungsstelle,
                'niqbid' => $this->niqbid,
                'letzteanmeldungen' => $letzteanmeldungen,
                'allebundeslaender' => $allebundeslaender,
                'bundeslandselected' => $bundeslandselected,
                'doppelteplzarray' => $doppelteplzberatungsstelle,
                'staatenarr' => $staatenarr,
                'berufearr' => $berufearr,
                'filterbundesland' => $filterbundesland ?? '',
                'filterstaat' => $staatselected,
                'filterberuf' => $berufselected ?? '',
                'filterniqbid' => $filterbstelle ?? '',
                'ausgabearray' => $ausgabearray ?? '',
                'anzgesamt' => $anzgesamt ?? '',
                'abschlussartanmeldungen' => $abschlussartanmeldungen ?? '',
                'abschlussartberatungabgeschl' => $abschlussartberatungabgeschl ?? '',
                'arrabschlussart' => $arrabschlussart ?? '',
                'herkunftanmeldungen' => $herkunftanmeldungen ?? '',
                'herkunftberatungabgeschl' => $herkunftberatungabgeschl ?? '',
                'berufeanmeldungen' => $berufeanmeldungen ?? '',
                'berufeberatungabgeschl' => $berufeberatungabgeschl ?? '',
                'brancheanmeldungen' => $brancheanmeldungen ?? '',
                'brancheberatungabgeschl' => $brancheberatungabgeschl ?? '',
                'arrgeschlecht' => $arrgeschlecht ?? '',
                'geschlechtartanmeldungen' => $geschlechtartanmeldungen ?? '',
                'geschlechtberatungabgeschl' => $geschlechtberatungabgeschl ?? '',
                'lebensalteranmeldungen' => $lebensalteranmeldungen ?? '',
                'lebensalterberatungabgeschl' => $lebensalterberatungabgeschl ?? '',
                'tndaten' => $tndaten ?? ''
            ]
            );
        return $this->htmlResponse();
    }
   
    protected function getTNbyUID($uid) {
        

    }

}
    