<?php
namespace Ud\Iqtp13db\Controller;
use \Datetime;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;

use Psr\Http\Message\ResponseInterface;
use Ud\Iqtp13db\Domain\Repository\UserGroupRepository;
use Ud\Iqtp13db\Domain\Repository\TeilnehmerRepository;
use Ud\Iqtp13db\Domain\Repository\FolgekontaktRepository;
use Ud\Iqtp13db\Domain\Repository\DokumentRepository;
use Ud\Iqtp13db\Domain\Repository\HistorieRepository;
use Ud\Iqtp13db\Domain\Repository\BeraterRepository;
use Ud\Iqtp13db\Domain\Repository\AbschlussRepository;
use TYPO3\CMS\Core\Resource\StorageRepository;
use Ud\Iqtp13db\Domain\Repository\BerufeRepository;
use Ud\Iqtp13db\Domain\Repository\StaatenRepository;
use Ud\Iqtp13db\Domain\Repository\OrtRepository;
use Ud\Iqtp13db\Domain\Repository\BrancheRepository;

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
 * BackendController
 */
class BackendController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    
    protected $generalhelper, $usergroup, $niqbid;
    
    protected $userGroupRepository;
    protected $teilnehmerRepository;
    protected $folgekontaktRepository;
    protected $dokumentRepository;
    protected $historieRepository;
    protected $beraterRepository;
    protected $abschlussRepository;
    protected $storageRepository;
    protected $berufeRepository;
    protected $staatenRepository;
    protected $ortRepository;
    protected $brancheRepository;
    
    public function __construct(UserGroupRepository $userGroupRepository, TeilnehmerRepository $teilnehmerRepository, FolgekontaktRepository $folgekontaktRepository, DokumentRepository $dokumentRepository, HistorieRepository $historieRepository, BeraterRepository $beraterRepository, AbschlussRepository $abschlussRepository, StorageRepository $storageRepository, BerufeRepository $berufeRepository, StaatenRepository $staatenRepository, OrtRepository $ortRepository, BrancheRepository $brancheRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
        $this->teilnehmerRepository = $teilnehmerRepository;
        $this->folgekontaktRepository = $folgekontaktRepository;
        $this->dokumentRepository = $dokumentRepository;
        $this->historieRepository = $historieRepository;
        $this->beraterRepository = $beraterRepository;
        $this->abschlussRepository = $abschlussRepository;
        $this->storageRepository = $storageRepository;
        $this->berufeRepository = $berufeRepository;
        $this->staatenRepository = $staatenRepository;
        $this->ortRepository = $ortRepository;
        $this->brancheRepository = $brancheRepository;
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
             
        /* Propertymapping bis hier */
        
        $this->generalhelper = new \Ud\Iqtp13db\Helper\Generalhelper();
        
        $this->user=null;
        $context = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class);
        if($context->getPropertyFromAspect('frontend.user', 'isLoggedIn')){
            $this->user=$GLOBALS['TSFE']->fe_user->user;
        } else {
            $this->user = NULL;
        }
      
        if($this->user != NULL) {
            $standardniqidberatungsstelle = $this->settings['standardniqidberatungsstelle'];
            $standardbccmail = $this->settings['standardbccmail'];
            
            $ugroupsarray = explode(",",$this->user['usergroup']);
            $this->usergroup = $this->userGroupRepository->findByIdentifier(array_pop($ugroupsarray));
            
            if($this->usergroup != NULL) {
                $userniqidbstelle = $this->usergroup->getNiqbid();
            }
            $this->niqbid = $userniqidbstelle == '' ? $standardniqidberatungsstelle : $userniqidbstelle;
        } else {
            // FEHLER oder Frontend für Ratsuchende
        }
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
        
        if ($this->settings['modtyp'] == 'uebersicht') {
            $this->forward('status', 'Backend', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'angemeldet') {
            $this->forward('listangemeldet', 'Backend', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'erstberatung') {
            $this->forward('listerstberatung', 'Backend', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'archiv') {
            $this->forward('listarchiv', 'Backend', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'export') {
            $this->forward('export', 'Backend', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'berater') {
            $this->forward('list', 'Berater', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'deleted') {
            $this->forward('listdeleted', 'Backend', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'adminuebersicht') {
            $this->forward('adminuebersicht', 'Administration', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'einstellungen') {
            $this->forward('editsettings', 'Backend', 'Iqtp13db');
        }
     
    }
    
    /**
     * action status
     *
     * @param int $currentPage
     * @return void
     */
    public function statusAction(int $currentPage = 1)
    {
        $valArray = $this->request->getArguments();
       
        $jahrselected = $valArray['jahrauswahl'] ?? 0;
        
        //DebuggerUtility::var_dump($valArray);
               
        $monatsnamen = array();
        for($i=1;$i<=12;$i++) {
            $monatsnamen[$i] = date("M", mktime(0, 0, 0, $i, 1, date('Y')));
            if($jahrselected != 0) {
                $monatsnamen[$i] = $monatsnamen[$i]." ".$jahrselected;
            } else {
                if($i <= idate('m')) {
                    $monatsnamen[$i] = $monatsnamen[$i]." ".idate('Y');
                } else {
                    $monatsnamen[$i] = $monatsnamen[$i]." ".idate('Y') - 1;
                }
            }
        }
        
        $jahrarray = array();
        for($j=2023;$j<=date('Y');$j++){
            $jahrarray[$j] = $j;
        }
        
        if(isset($valArray['zeigebstelle'])) {
            $plzbstelle = $this->userGroupRepository->getBeratungsstelle4PLZ($valArray['plzeingabe'], $this->settings['beraterstoragepid']);
            $plzgroup = $plzbstelle[0];
        }
        
        // FK/Beratungen aus alter Förderphase in 2023
        $tnberatungenfk22 = $this->folgekontaktRepository->fk4StatusFK2022("01.01.2023", "31.12.2023", $this->niqbid);
        for($m = 1; $m < 13; $m++) $beratungfk22[$m] = 0;
        foreach($tnberatungenfk22 as $fk22) {
            $fkmonat = DateTime::createFromFormat('d.m.Y', $fk22->getDatum())->format('n');
            $beratungfk22[$fkmonat]++;
        }
        //
        
        $emptystatusarray = array(1 => 0,2 => 0,3 => 0,4 => 0,5 => 0,6 => 0,7 => 0,8 => 0,9 => 0,10 => 0,11 => 0, 12 => 0);
        $angemeldeteTN = $emptystatusarray;
        $erstberatung = $emptystatusarray;
        $beratungfertig = $emptystatusarray;
        $qfolgekontakte =  $emptystatusarray;
        $days4beratung = $emptystatusarray;
        $days4wartezeit = $emptystatusarray;
        
        $ergarrayangemeldete = $this->teilnehmerRepository->countTNby($this->niqbid,'%', 1, $jahrselected, '%');
        foreach($ergarrayangemeldete as $erg) $angemeldeteTN[$erg['monat']] = $erg['anzahl'];
        
        $ergarrayerstberatung = $this->teilnehmerRepository->countTNby($this->niqbid, '%', 2, $jahrselected, '%');
        foreach($ergarrayerstberatung as $erg) $erstberatung[$erg['monat']] = $erg['anzahl'];
        
        $ergarrayberatungfertig = $this->teilnehmerRepository->countTNby($this->niqbid, '%', 3, $jahrselected, '%');
        foreach($ergarrayberatungfertig as $erg) $beratungfertig[$erg['monat']] = $erg['anzahl'];
        
        $ergarrayfolgekontakte = $this->folgekontaktRepository->countFKby($this->niqbid, '%', $jahrselected, '%');
        foreach($ergarrayfolgekontakte as $erg) $qfolgekontakte[$erg['monat']] = $erg['anzahl'];
        
        $ergarraywartezeitanmeldung = $this->teilnehmerRepository->calcwaitingdays($this->niqbid, '%', 'anmeldung', $jahrselected, '%');
        foreach($ergarraywartezeitanmeldung as $erg) $days4wartezeit[$erg['monat']] = $erg['wert'];
        
        $ergarraywartezeitberatung = $this->teilnehmerRepository->calcwaitingdays($this->niqbid, '%', 'beratung', $jahrselected, '%');
        foreach($ergarraywartezeitberatung as $erg) $days4beratung[$erg['monat']] = $erg['wert'];
        
        ksort($angemeldeteTN);
        ksort($qfolgekontakte);
        ksort($erstberatung);
        ksort($beratungfk22);
        ksort($beratungfertig);
        ksort($days4beratung);
        ksort($days4wartezeit);
        
        $aktuelleanmeldungen = $this->teilnehmerRepository->countAllOrder4Status(0, $this->niqbid)[0]['anzahl'] + $this->teilnehmerRepository->countAllOrder4Status(1, $this->niqbid)[0]['anzahl'];
        $aktuellerstberatungen = $this->teilnehmerRepository->countAllOrder4Status(2, $this->niqbid)[0]['anzahl'];
        $aktuellberatungenfertig = $this->teilnehmerRepository->countAllOrder4Status(3, $this->niqbid)[0]['anzahl'];
        $archivierttotal = $this->teilnehmerRepository->countAllOrder4Status(4, $this->niqbid)[0]['anzahl'];
        
        // keine Berater vorhanden?
        $alleberater = $this->beraterRepository->findAllBerater($this->settings['beraterstoragepid']);
        if(count($alleberater) == 0) {
            $this->addFlashMessage('Es sind noch keine Berater:innen vorhanden. Bitte im Menü Berater*innen anlegen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        }
        
        $historie = $this->historieRepository->findAllDesc($this->niqbid);
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
        $paginator = new QueryResultPaginator($historie, $currentPage, 25);
        $pagination = new SimplePagination($paginator);
                
        $neuanmeldungen7tage = array();
        for($i = 7; $i >= 0; $i--) {
            $reftag = date("d.m.Y", strtotime( '-'.$i.' days' ));
            $neuanmeldungen7tage[$i]["tag"] = date("l, d.m.Y", strtotime( '-'.$i.' days' ));
            $neuanmeldungen7tage[$i]["wert"] = $this->teilnehmerRepository->count4Status($reftag, $reftag, $this->niqbid, 1)[0]['anzahl'];
        }
        
        // ******************** EXPORT Statistik ****************************
        if (isset($valArray['statsexport'])) {
            
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
            
            // XLSX
            $filename = 'statistik_'.date('Y-m-d_H-i-s', time()).'.xlsx';
            $headerblatt1 = [
                'Statistik '.($jahrselected != 0 ? $jahrselected : 'letzte 12 Monate') => 'string',
            ];
            
            $writer = new \XLSXWriter();
            $writer->setAuthor('IQ Webapp');
            
            $writer->writeSheet($rows, 'Statistik', $headerblatt1);
            // $writer->writeSheet($rowsfk, 'Folgekontakte', $headerblatt2);
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer->writeToStdOut();
            exit;
        }        
        
        // ******************** EXPORT Statistik bis hier ****************************
        
        $this->view->assignMultiple(
            [
                'beratungfk22'=> $beratungfk22,
                'SUMberatungfk22'=> count($tnberatungenfk22),
                'monatsnamen'=> $monatsnamen,
                'jahrauswahl' => $jahrarray,
                'jahrselected' => $jahrselected,
                'aktmonat'=> $jahrselected == 0 ? idate('m')-1 : '',
                'angemeldeteTN'=> $angemeldeteTN,
                'SUMangemeldeteTN'=> array_sum($angemeldeteTN),
                'qfolgekontakte'=> $qfolgekontakte,
                'SUMqfolgekontakte'=> array_sum($qfolgekontakte),
                'erstberatung'=> $erstberatung,
                'SUMerstberatung'=> array_sum($erstberatung),
                'beratungfertig'=> $beratungfertig,
                'SUMberatungfertig'=> array_sum($beratungfertig),
                'totalavgmonthb'=> $days4beratung,
                'SUMtotalavgmonthb'=> array_sum($days4beratung)/count($days4beratung),
                'totalavgmonthw'=> $days4wartezeit,
                'SUMtotalavgmonthw'=> array_sum($days4wartezeit)/count($days4beratung),
                'aktuelleanmeldungen'=> $aktuelleanmeldungen,
                'aktuellerstberatungen'=> $aktuellerstberatungen,
                'aktuellberatungenfertig'=> $aktuellberatungenfertig,
                'archivierttotal'=> $archivierttotal,
                'paginator' => $paginator,
                'pagination' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
                'historie' => $historie,
                'beratungsstelle' => $this->usergroup->getTitle(),
                'niqbid' => $this->niqbid,
                'username' => $this->user['username'],
                'neuanmeldungen7tage' => $neuanmeldungen7tage,
                'bstellevonplz' => $plzgroup ?? ''
            ]
            );
    }
    
    /**
     * action listangemeldet
     *
     * @param int $currentPage
     * @return void
     */
    public function listangemeldetAction(int $currentPage = 1)
    {
        $valArray = $this->request->getArguments();
        
        if(($valArray['allemodule'] ?? '') == '1') {
            $this->redirect('showsearchresult', 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1', 'searchparams' => $valArray));
        }
        // zuletzt bearbeiteten User zurücksetzen
        if(isset($valArray['tn'])) {
            $editedteilnehmer = $this->teilnehmerRepository->findByUid($valArray['tn']);
            $tnedituser = $editedteilnehmer->getEdituser();
            if($this->user['uid'] == $tnedituser) {
                $editedteilnehmer->setEdituser(0);
                $editedteilnehmer->setEdittstamp(0);
                $this->teilnehmerRepository->update($editedteilnehmer);
                // Daten sofort in die Datenbank schreiben
                $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
                $persistenceManager->persistAll();
            }
        }
                
        if(!empty($valArray['callerpage'])) $currentPage = $valArray['callerpage'];
        
        if(empty($valArray['orderby'])) {            
            $orderby = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listangemeldetorderby') ?? 'verificationDate';
            $order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listangemeldetorder') ?? 'DESC';
        } else {
            $orderby = $valArray['orderby'];
            $order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listangemeldetorder');
        }        
        if(isset($valArray['changeorder']) && $valArray['changeorder'] == 1) {
            $orderby = $valArray['orderby'];
            $order = $order == 'DESC' ? 'ASC' : 'DESC';
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'listangemeldetorderby', $orderby);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'listangemeldetorder', $order);
        }
        
        $teilnehmer = $this->setfilter(0, $valArray, $orderby, $order, 0, 9999);
        
        // Wegen Bug in Paginator, der nicht mit Custom SQL Queryresults funktioniert, werden hier alle gefilterten Einträge auf einer Seite dargestellt. Queryresultpaginator hat dann keine Auswahl an Datensätzen, sondern alle.
        $anzperpag = $GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus') == '1' ? 20 : 20;
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
                
        if($GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus') == '1' && is_array($teilnehmer)) {
            $paginator = new ArrayPaginator($teilnehmer, $currentPage, $anzperpag);
        } else {
            $paginator = new QueryResultPaginator($teilnehmer, $currentPage, $anzperpag);
        }
        
        $pagination = new SimplePagination($paginator);
        
        $teilnehmerpag = $paginator->getPaginatedItems();
        
        $plzberatungsstelle4tn = array();
        $abschluesse = array();
        for($j=0; $j < count($teilnehmerpag); $j++) {
            $anz = $this->teilnehmerRepository->findDublette4Angemeldet($teilnehmerpag[$j]->getNachname(), $teilnehmerpag[$j]->getVorname(), $this->niqbid);
            if($anz > 1) $teilnehmerpag[$j]->setDublette(TRUE);
            
            $abschluesse[$j] = $this->abschlussRepository->findByTeilnehmer($teilnehmerpag[$j]);
            
            $plzberatungsstelle = array();
            $plzberatungsstelle = $this->userGroupRepository->getBeratungsstelle4PLZ($teilnehmerpag[$j]->getPlz(), $this->settings['beraterstoragepid']);
            $plzberatungsstelle4tn[$j] = count($plzberatungsstelle) > 0 ? $plzberatungsstelle[0]->getNiqbid() : '';
        }
        
        $staaten = $this->staatenRepository->findByLangisocode('de');
        unset($staaten[201]);
        
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }
        
        $orderchar = $order == 'ASC' ? "↓" : "↑";        
        $alleberater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->usergroup);
        
        $this->view->assignMultiple(
            [
                'anzgesamt' => count($teilnehmer),
                'abschluesse' => $abschluesse,
                'calleraction' => 'listangemeldet',
                'callercontroller' => 'Backend',
                'callerpage' => $currentPage,
                'paginator' => $paginator,
                'pagination' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
                'orderby' => $orderby,
                'orderchar' => $orderchar,
                'staatenarr' => $staatenarr,
                'plzberatungsstelle4tn' => $plzberatungsstelle4tn,
                'beratungsstelle' => $this->usergroup->getTitle(),
                'niqbid' => $this->niqbid,
                'alleberater' => $alleberater
            ]);
    }
    
    /**
     * action listerstberatung
     *
     * @param int $currentPage
     * @return void
     */
    public function listerstberatungAction(int $currentPage = 1)
    {
        $valArray = $this->request->getArguments();
        if(($valArray['allemodule'] ?? '') == '1') {
            $this->redirect('showsearchresult', 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1', 'searchparams' => $valArray));
        }
        // zuletzt bearbeiteten User zurücksetzen
        if(isset($valArray['tn'])) {
            $editedteilnehmer = $this->teilnehmerRepository->findByUid($valArray['tn']);
            $tnedituser = $editedteilnehmer->getEdituser();
            if($this->user['uid'] == $tnedituser) {
                $editedteilnehmer->setEdituser(0);
                $editedteilnehmer->setEdittstamp(0);
                $this->teilnehmerRepository->update($editedteilnehmer);
                // Daten sofort in die Datenbank schreiben
                $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
                $persistenceManager->persistAll();
            }
        }
        
        if(!empty($valArray['callerpage'])) $currentPage = $valArray['callerpage'];
        
        if(empty($valArray['orderby'])) {
            $orderby = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listerstberatungorderby') ?? 'verificationDate';
            $order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listerstberatungorder') ?? 'DESC';
        } else {
            $orderby = $valArray['orderby'];
            $order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listerstberatungorder');
        }
        if(isset($valArray['changeorder']) && $valArray['changeorder'] == 1) {
            $orderby = $valArray['orderby'];
            $order = $order == 'DESC' ? 'ASC' : 'DESC';
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'listerstberatungorderby', $orderby);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'listerstberatungorder', $order);
        }
        
        $teilnehmer = $this->setfilter(3, $valArray, $orderby, $order, 0, 9999);
        
        // Wegen Bug in Paginator, der nicht mit Custom SQL Queryresults funktioniert, werden hier alle gefilterten Einträge auf einer Seite dargestellt. Queryresultpaginator hat dann keine Auswahl an Datensätzen, sondern alle.
        $anzperpag = $GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus') == '1' ? 20 : 20;
        
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
        if($GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus') == '1' && is_array($teilnehmer)) {
            $paginator = new ArrayPaginator($teilnehmer, $currentPage, $anzperpag);
        } else {
            $paginator = new QueryResultPaginator($teilnehmer, $currentPage, $anzperpag);
        }
        $pagination = new SimplePagination($paginator);
        
        $teilnehmerpag = $paginator->getPaginatedItems();
        
        $anzfolgekontakte = array();
        $summeberatungsdauer = array();
        $abschluesse = array();
        
        $folgekontakte = $this->folgekontaktRepository->findAll4List($this->niqbid);
        $berufeliste = $this->berufeRepository->findAllOrdered('de');
        
        foreach ($teilnehmerpag as $key => $tn) {
            $fk4tn = $this->folgekontaktRepository->findByTeilnehmer($tn->getUid());
            $anzfolgekontakte[$key] = count($fk4tn);
            $summebdauerfk = 0;
            foreach($fk4tn as $singlefk) $summebdauerfk = $summebdauerfk + floatval(str_replace(',','.',$singlefk->getBeratungsdauer()));
            $summeberatungsdauer[$key] = str_replace('.',',',floatval(str_replace(',','.',$tn->getBeratungsdauer())) + $summebdauerfk);
            
            $abschluesse[$key] = $this->abschlussRepository->findByTeilnehmer($tn);
        }
        
        
        $staaten = $this->staatenRepository->findByLangisocode('de');
        unset($staaten[201]);
        
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }
        
        $orderchar = $order == 'ASC' ? "↓" : "↑";
        $alleberater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->usergroup);
        
        $this->view->assignMultiple(
            [
                'anzgesamt' => count($teilnehmer),
                'abschluesse' => $abschluesse,
                'anzfolgekontakte' => $anzfolgekontakte,
                'folgekontakte' => $folgekontakte,
                'summeberatungsdauer' => $summeberatungsdauer,
                'calleraction' => 'listerstberatung',
                'callercontroller' => 'Backend',
                'callerpage' => $currentPage,
                'paginator' => $paginator,
                'pagination' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
                'orderby' => $orderby,
                'orderchar' => $orderchar,
                'staatenarr' => $staatenarr,
                'berufe' => $berufeliste,
                'beratungsstelle' => $this->usergroup->getTitle(),
                'niqbid' => $this->niqbid,
                'alleberater' => $alleberater
            ]
            );
    }
    
    /**
     * action listarchiv
     *
     * @param int $currentPage
     * @return void
     */
    public function listarchivAction(int $currentPage = 1)
    {
        $valArray = $this->request->getArguments();
        if(($valArray['allemodule'] ?? '') == '1') {
            $this->redirect('showsearchresult', 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1', 'searchparams' => $valArray));
        }
        // zuletzt bearbeiteten User zurücksetzen
        if(isset($valArray['tn'])) {
            $editedteilnehmer = $this->teilnehmerRepository->findByUid($valArray['tn']);
            $tnedituser = $editedteilnehmer->getEdituser();
            if($this->user['uid'] == $tnedituser) {
                $editedteilnehmer->setEdituser(0);
                $editedteilnehmer->setEdittstamp(0);
                $this->teilnehmerRepository->update($editedteilnehmer);
                // Daten sofort in die Datenbank schreiben
                $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
                $persistenceManager->persistAll();
            }
        }
        
        if(!empty($valArray['callerpage'])) $currentPage = $valArray['callerpage'];
        
        if(empty($valArray['orderby'])) {
            $orderby = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listarchivorderby') ?? 'verificationDate';
            $order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listarchivorder') ?? 'DESC';
        } else {
            $orderby = $valArray['orderby'];
            $order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listarchivorder');
        }
        if(isset($valArray['changeorder']) && $valArray['changeorder'] == 1) {
            $orderby = $valArray['orderby'];
            $order = $order == 'DESC' ? 'ASC' : 'DESC';
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'listarchivorderby', $orderby);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'listarchivorder', $order);
        }
        
        $teilnehmer = $this->setfilter(4, $valArray, $orderby, $order, 0, 9999);
        
        // Wegen Bug in Paginator, der nicht mit Custom SQL Queryresults funktioniert, werden hier alle gefilterten Einträge auf einer Seite dargestellt. Queryresultpaginator hat dann keine Auswahl an Datensätzen, sondern alle.
        $anzperpag = $GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus') == '1' ? 25 : 25;
        
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
        if($GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus') == '1' && is_array($teilnehmer)) {
            $paginator = new ArrayPaginator($teilnehmer, $currentPage, $anzperpag);
        } else {
            $paginator = new QueryResultPaginator($teilnehmer, $currentPage, $anzperpag);
        }
        $pagination = new SimplePagination($paginator);
        
        $teilnehmerpag = $paginator->getPaginatedItems();
        
        $anzfolgekontakte = array();
        $summeberatungsdauer = array();
        $abschluesse = array();
        foreach ($teilnehmerpag as $key => $tn) {
            $fk4tn = $this->folgekontaktRepository->findByTeilnehmer($tn->getUid());
            $anzfolgekontakte[$key] = count($fk4tn);
            
            $summebdauerfk = 0;
            foreach($fk4tn as $singlefk) $summebdauerfk = $summebdauerfk + floatval(str_replace(',','.',$singlefk->getBeratungsdauer()));
            $summeberatungsdauer[$key] = str_replace('.',',',floatval(str_replace(',','.',$tn->getBeratungsdauer())) + $summebdauerfk);
            
            $abschluesse[$key] = $this->abschlussRepository->findByTeilnehmer($tn);
        }
        $folgekontakte = $this->folgekontaktRepository->findAll4List($this->niqbid);
        
        $berufeliste = $this->berufeRepository->findAllOrdered('de');
        $staaten = $this->staatenRepository->findByLangisocode('de');
        unset($staaten[201]);
        
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }
                
        $orderchar = $order == 'ASC' ? "↓" : "↑";
        $alleberater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->usergroup);
        
        $this->view->assignMultiple(
            [
                'anzgesamt' => count($teilnehmer),
                'anzfolgekontakte' => $anzfolgekontakte,
                'folgekontakte' => $folgekontakte,
                'summeberatungsdauer' => $summeberatungsdauer,
                'abschluesse' => $abschluesse,
                'calleraction' => 'listarchiv',
                'callercontroller' => 'Backend',
                'callerpage' => $currentPage,
                'paginator' => $paginator,
                'pagination' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
                'orderby' => $orderby,
                'orderchar' => $orderchar,
                'staatenarr' => $staatenarr,
                'berufe' => $berufeliste,
                'beratungsstelle' => $this->usergroup->getTitle(),
                'niqbid' => $this->niqbid,
                'alleberater' => $alleberater
            ]
            );
    }
    
    /**
     * action listdeleted
     *
     * @param int $currentPage
     * @return void
     */
    public function listdeletedAction(int $currentPage = 1)
    {
        $valArray = $this->request->getArguments();
        if(($valArray['allemodule'] ?? '') == '1') {
            $this->redirect('showsearchresult', 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1', 'searchparams' => $valArray));
        }
        if(!empty($valArray['callerpage'])) $currentPage = $valArray['callerpage'];
        
        if(empty($valArray['orderby'])) {
            $orderby = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listdeletedorderby') ?? 'verificationDate';
            $order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listdeletedorder') ?? 'DESC';
        } else {
            $orderby = $valArray['orderby'];
            $order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listdeletedorder');
        }
        if(isset($valArray['changeorder']) && $valArray['changeorder'] == 1) {
            $orderby = $valArray['orderby'];
            $order = $order == 'DESC' ? 'ASC' : 'DESC';
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'listdeletedorderby', $orderby);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'listdeletedorder', $order);
        }
               
        $teilnehmer = $this->setfilter(999, $valArray, $orderby, $order, 1, 9999);
        
        // Wegen Bug in Paginator, der nicht mit Custom SQL Queryresults funktioniert, werden hier alle gefilterten Einträge auf einer Seite dargestellt. Queryresultpaginator hat dann keine Auswahl an Datensätzen, sondern alle.
        $anzperpag = $GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus') == '1' ? 25 : 25;
        
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
        if($GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus') == '1' && is_array($teilnehmer)) {
            $paginator = new ArrayPaginator($teilnehmer, $currentPage, $anzperpag);
        } else {
            $paginator = new QueryResultPaginator($teilnehmer, $currentPage, $anzperpag);
        }
        $pagination = new SimplePagination($paginator);
        
        $teilnehmerpag = $paginator->getPaginatedItems();
        
        $abschluesse = array();
        for($j=0; $j < count($teilnehmerpag); $j++) {
            $anz = $this->teilnehmerRepository->findDublette4Deleted($teilnehmerpag[$j]->getNachname(), $teilnehmerpag[$j]->getVorname(), $this->niqbid);
            if($anz > 1) $teilnehmerpag[$j]->setDublette(TRUE);
            $abschluesse[$j] = $this->abschlussRepository->findByTeilnehmer($teilnehmerpag[$j]);
        }
        
        $staaten = $this->staatenRepository->findByLangisocode('de');
        unset($staaten[201]);
        
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }
        
        $orderchar = $order == 'ASC' ? "↓" : "↑";
        
        $this->view->assignMultiple(
            [
                'anzgesamt' => count($teilnehmer),
                'abschluesse' => $abschluesse,
                'calleraction' => 'listdeleted',
                'callercontroller' => 'Backend',
                'callerpage' => $currentPage,
                'paginator' => $paginator,
                'pagination' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
                'orderby' => $orderby,
                'orderchar' => $orderchar,
                'staatenarr' => $staatenarr,
                'beratungsstelle' => $this->usergroup->getTitle(),
                'niqbid' => $this->niqbid
            ]
            );
    }
    
    /**
     * action showsearchresult
     * @param int $currentPage
     * @return void
     */
    public function showsearchresultAction(int $currentPage = 1)
    {        
        $valArray = $this->request->getArguments();
        
        if(isset($valArray['searchparams']) && $valArray['searchparams']['berater'] == '0' &&
            $valArray['searchparams']['beruf'] == '' &&
            $valArray['searchparams']['bescheid'] == '' &&
            $valArray['searchparams']['gruppe'] == '' &&
            $valArray['searchparams']['land'] == '-1000' &&
            $valArray['searchparams']['name'] == '' &&
            $valArray['searchparams']['ort'] == '' &&
            $valArray['searchparams']['uid'] == '') {
                $this->addFlashMessage("FEHLER: Bitte Suchkriterium angeben.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                $this->redirect($valArray['searchparameter']['action'] ?? 'listangemeldet', 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1'));
        }
        
        if(array_key_exists("searchparams", $valArray)) {
            $searchparams = $valArray['searchparams'];
        } else {
            if($valArray['action'] == 'showsearchresult') {
                if(!isset($valArray['filteran'])) {
                    // Filterfelder sind leer, weil z.B. Abschlüsse geöffnet wurden, dann ist die Suche nicht mehr aktiv und das aktive Standardmodul kann aufgerufen werden
                    $this->redirect('start');
                } else {
                    $searchparams['uid'] = $valArray['uid'];
                    $searchparams['name'] = $valArray['name'];
                    $searchparams['ort'] = $valArray['ort'];
                    $searchparams['beruf'] = $valArray['beruf'];
                    $searchparams['land'] = $valArray['land'];
                    $searchparams['berater'] = $valArray['berater'];
                    $searchparams['gruppe'] = $valArray['gruppe'];
                    $searchparams['bescheid'] = $valArray['bescheid'];
                    $searchparams['filteran'] = $valArray['filteran'];
                }
            }
        }
        
        $alleteilnehmer = $this->setfilter(999, $searchparams, "beratungsstatus", "DESC", -1, 50);

        $folgekontakte = $this->folgekontaktRepository->findAll4List($this->niqbid);
        
        $abschluesse = array();
        $anzfolgekontakte = array();
        
        foreach($alleteilnehmer as $key => $teilnehmer) {
            // Dublettenprüfung
            $anz = $this->teilnehmerRepository->findDublette4Angemeldet($teilnehmer->getNachname(),$teilnehmer->getVorname(), $this->niqbid);
            if($anz > 1) $alleteilnehmer[$key]->setDublette(TRUE);
            
            // Modul
            $beratungsstatus = $teilnehmer->getBeratungsstatus();
            if($teilnehmer->getHidden() == 1) $alleteilnehmer[$key]->setModul("Gelöscht");
            elseif($beratungsstatus == 0 || $beratungsstatus == 1) $alleteilnehmer[$key]->setModul("Angemeldet");
            elseif($beratungsstatus == 2 || $beratungsstatus == 3) $alleteilnehmer[$key]->setModul("Erstberatung");
            else $alleteilnehmer[$key]->setModul("Archiv");
            
            // Abschlüsse
            $abschluesse[$teilnehmer->getUid()] = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
            
            if($teilnehmer->getBeratungsstatus() > 1) {
                $fk4tn = $this->folgekontaktRepository->findByTeilnehmer($teilnehmer);
                $anzfolgekontakte[$teilnehmer->getUid()] = count($fk4tn);
                $summebdauerfk = 0;
                foreach($fk4tn as $singlefk) $summebdauerfk = $summebdauerfk + floatval(str_replace(',','.',$singlefk->getBeratungsdauer()));
                $summeberatungsdauer[$teilnehmer->getUid()] = str_replace('.',',',floatval(str_replace(',','.',$teilnehmer->getBeratungsdauer())) + $summebdauerfk);
            }
        }
        DebuggerUtility::var_dump($valArray);
        
        $berufeliste = $this->berufeRepository->findAllOrdered('de');
        $staaten = $this->staatenRepository->findByLangisocode('de');
        unset($staaten[201]);
        
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }
        
        $this->view->assignMultiple(
            [
                'anzgesamt' => count($alleteilnehmer),
                'abschluesse' => $abschluesse,
                'anzfolgekontakte' => $anzfolgekontakte,
                'folgekontakte' => $folgekontakte,
                'summeberatungsdauer' => $summeberatungsdauer ?? 0,
                'calleraction' => $valArray['searchparameter']['action'] ?? 'listangemeldet',
                'callercontroller' => 'Backend',
                'staatenarr' => $staatenarr,
                'beratungsstelle' => $this->usergroup->getTitle(),
                'niqbid' => $this->niqbid,
                'berufe' => $berufeliste,
                'searchparams' => $searchparams,
                'alleteilnehmer' => $alleteilnehmer
            ]
        );
    }
    
    /**
     * action export
     *
     * @param int $currentPage
     * @return void
     */
    public function exportAction(int $currentPage = 1)
    {
        $valArray = $this->request->getArguments();
        
        $current_quarter = ceil(date('n') / 3);
        $first_day_of_this_quarter = date('d.m.Y', strtotime(date('Y').'-'.(($current_quarter*3)-2).'-01'));
        $last_day_of_this_quarter = date('d.m.Y', strtotime(date('Y').'-'.($current_quarter*3).'-'.(date("t",strtotime(date('Y').'-'.($current_quarter*3).'-01')))));
        
        $today = date("d.m.Y");
        
        if(isset($valArray['filtervon'])) {
            $filtervon = $valArray['filtervon'];
        } else {
            $filtervon = $first_day_of_this_quarter;
        }
        
        if(isset($valArray['filterbis'])) {
            $filterbis = $valArray['filterbis'];
        } else {
            $filterbis = $today;
        }
        
        $bundeslandselected = $valArray['filterbundesland'] ?? $this->usergroup->getBundesland();
        $allebundeslaender = $this->userGroupRepository->findAllBundeslaender();
        $staatselected = $valArray['filterstaat'] ?? '%';
        $landkreisselected = $valArray['filterlandkreis'] ?? '%';
        $berufselected = $valArray['filterreferenzberuf'] ?? '%';
        
        $berater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->usergroup);
        foreach($berater as $currber) {
            $arrberater[$currber->getUid()] = $currber->getUsername();
        }
        
        $beraterselected = $valArray['filterberater'] ?? '%';
        
        $arrjanein = array(0 => '', 1 => 'ja', 2 => 'nein', 3 => 'keine Angabe');
        $arrerwerbsstatus = $this->settings['erwerbsstatus'];
        $arrleistungsbezug = $this->settings['leistungsbezug'];
        $staaten = $this->staatenRepository->findByLangisocode('de');
        unset($staaten[201]);
        foreach($staaten as $staat) {
            $arrstaaten[$staat->getStaatid()] = $staat->getTitel();
        }
        
        $brancheunterkat = $this->brancheRepository->findAllUnterkategorie('de');
        foreach($brancheunterkat as $branche) {
            $arrbranchen[$branche->getBrancheid()] = $branche->getTitel();
        }
                        
        $orte = $this->ortRepository->findByBundesland($bundeslandselected);        
        foreach($orte as $ort) {
            $arrorte[$ort->getPlz()] = $ort->getLandkreis();
        }
        
        $arrlandkreise = array();
        $arrlandkreise = $this->ortRepository->findLandkreiseByBundesland($bundeslandselected);
        
        //DebuggerUtility::var_dump($arrlandkreise);
        
        $arraufenthaltsstatus = $this->settings['aufenthaltsstatus'];
        $arrberatungsart = $this->settings['beratungsart'];
        $arrberatungsformfolgeberatung = $this->settings['beratungsformfolgeberatung'];
        $arranerkennungsberatung = $this->settings['anerkennungsberatung'];
        $arrqualifizierungsberatung = $this->settings['qualifizierungsberatung'];
        $arrberatungsstelle = $this->settings['beratungsstelle'];
                
        $arrzertifikatlevel = $this->settings['zertifikatlevel'];
        $berufeliste = $this->berufeRepository->findAllOrdered('de');
        foreach($berufeliste as $beruf) {
            $arrberufe[$beruf->getBerufid()] = $beruf->getTitel();
        }
        $arrabschlussart = $this->settings['abschlussart'];  
        //array('-1' => 'keine Angabe', '1' => 'Ausbildungsabschluss', '2' => 'Universitätsabschluss');
        $arrantragstellungerfolgt = $this->settings['antragstellungerfolgt'];
        
        $orderby = 'crdate';
        $order = 'ASC';
        $fberatungsstatus = isset($valArray['filterberatungsstatus']) ? $valArray['filterberatungsstatus'] : '';
        
        $del = 0;
        if($fberatungsstatus == 11) {
            $type = 1;
        } elseif($fberatungsstatus == 12) {
            $type = 2;
        } elseif($fberatungsstatus == 13) {
            $type = 3;
        } elseif($fberatungsstatus == 14) {
            $type = 0;
            $del = 1;
        } else {
            $type = 1;
        }
        
        $anzteilnehmers = 0;
        if($filtervon != '' && $filterbis != '') {
            $teilnehmers = $this->teilnehmerRepository->search4exportTeilnehmer($type, $del, $filtervon, $filterbis, $this->niqbid, $bundeslandselected, $staatselected, $beraterselected, $landkreisselected, $berufselected);
            $anzteilnehmers = count($teilnehmers);
        }
         
        // ******************** EXPORT ****************************
        if (isset($valArray['export']) && $fberatungsstatus != '') {
            
            if($anzteilnehmers == 0) {
                $this->addFlashMessage("Keine Einträge, bitte Suchparameter anpassen.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                $this->view->assignMultiple(
                    [
                        'anzgesamt' => $anzteilnehmers,
                        'calleraction' => 'export',
                        'callercontroller' => 'Backend',
                        'callerpage' => $currentPage,
                        'filterberatungsstatus' => $fberatungsstatus,
                        'filterbundesland' => $bundeslandselected,
                        'filterstaat' => $staatselected,
                        'filterberater' => $beraterselected,
                        'filterlandkreis' => $landkreisselected,
                        'filterreferenzberuf' => $berufselected,
                        'filteron' => $GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus')
                    ]
                    );
            } else {
                
                $rows = array();
                $rowsfk = array();
                $summedauerfk = array();
                $fkcnt = 0;
                foreach ($teilnehmers as $akey => $atn) {
                    $folgekontakte[$akey] = $this->folgekontaktRepository->findByTeilnehmer($atn->getUid());
                    $anzfolgekontakte[$akey] = count($folgekontakte[$akey]);
                    $abschluesse[$akey] = $this->abschlussRepository->findByTeilnehmer($atn);
                    $summedauerfk[$akey] = 0;
                    
                    foreach($folgekontakte[$akey] as $fk) {
                        $rowsfk[$fkcnt] = array();
                        $beraterfk = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($fk, 'berater');
                        $teilnehmerfk = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($fk, 'teilnehmer');
                        
                        $rowsfk[$fkcnt]['teilnehmeruid'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($teilnehmerfk, 'uid');
                        $rowsfk[$fkcnt]['teilnehmernachname'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($teilnehmerfk, 'nachname');
                        $rowsfk[$fkcnt]['teilnehmervorname'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($teilnehmerfk, 'vorname');
                        $rowsfk[$fkcnt]['datum'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($fk, 'datum');
                        if($beraterfk != NULL) $rowsfk[$fkcnt]['Beraterin'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($beraterfk, 'username');
                        else $rowsfk[$fkcnt]['beraterin'] = '-';
                        $rowsfk[$fkcnt]['notizen'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($fk, 'notizen');
                        $bform = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($fk, 'beratungsform');
                        $rowsfk[$fkcnt]['beratungsform'] = $bform == '-1000' ? '-' : $arrberatungsformfolgeberatung[$bform];
                        $rowsfk[$fkcnt]['beratungsdauer'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($fk, 'beratungsdauer');
                        $fkdauer = floatval(str_replace(',', '.', $rowsfk[$fkcnt]['beratungsdauer']));
                        $summedauerfk[$akey] += $fkdauer;
                        $fkcnt++;
                    }
                }
                
                foreach($teilnehmers as $x => $tn) {
                    
                    $berater = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'berater');
                    
                    $rows[$x] = array();
                    $rows[$x]['uid'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'uid');
                    $rows[$x]['verificationDate'] = date('d.m.Y H:i:s', \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'verificationDate'));
                    $rows[$x]['Nachname'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'nachname');
                    $rows[$x]['Vorname'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'vorname');
                    $rows[$x]['PLZ'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'plz');
                    $rows[$x]['Ort'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'ort');
                    $rows[$x]['Email'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'email');
                    $rows[$x]['Telefon'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'telefon');
                    $rows[$x]['Lebensalter'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'lebensalter');
                    
                    $tn1staatsangehoerigkeit = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'erste_staatsangehoerigkeit');
                    $rows[$x]['ErsteStaatsangehoerigkeit'] = $tn1staatsangehoerigkeit == '' ? '-' : $arrstaaten[$tn1staatsangehoerigkeit];
                    
                    $tn2staatsangehoerigkeit = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'zweite_staatsangehoerigkeit');
                    $rows[$x]['ZweiteStaatsangehoerigkeit'] = $tn2staatsangehoerigkeit == '' ? '-' : $arrstaaten[$tn2staatsangehoerigkeit];
                    
                    $wohnsitzdeutschland = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'wohnsitz_deutschland ');
                    if($wohnsitzdeutschland == 1) $wohnsitzdeutschland = 'ja';
                    if($wohnsitzdeutschland == 2) $wohnsitzdeutschland = 'nein';
                    if($wohnsitzdeutschland == -1) $wohnsitzdeutschland = 'k.a.';
                    $rows[$x]['WohnsitzDeutschland'] = $wohnsitzdeutschland ?? '';
                    
                    if($wohnsitzdeutschland == 'ja') {
                        $rows[$x]['Landkreis'] = (preg_match("/[0-9]{5}/", $rows[$x]['PLZ']) && array_key_exists($rows[$x]['PLZ'], $arrorte)) ? $arrorte[$rows[$x]['PLZ']] : '-';
                    } else {
                        $rows[$x]['Landkreis'] = '';
                    }
                    
                    $rows[$x]['Einreisejahr'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'einreisejahr');
                    
                    $wohnsitzneinin = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'wohnsitz_nein_in ');
                    $rows[$x]['WohnsitzNeinIn'] = $wohnsitzneinin == '' ? '-' : $arrstaaten[$wohnsitzneinin];
                    
                    $deutschkenntnisse = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'deutschkenntnisse ');
                    if($deutschkenntnisse == 1) $deutschkenntnisse = 'ja';
                    if($deutschkenntnisse == 2) $deutschkenntnisse = 'nein';
                    if($deutschkenntnisse == -1) $deutschkenntnisse = 'k.a.';
                    $rows[$x]['Deutschkenntnisse'] = $deutschkenntnisse ?? '';
                    
                    $zertifikatsprachniveau = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'zertifikat_sprachniveau ');
                    $rows[$x]['ZertifikatSprachniveau'] = $zertifikatsprachniveau == '' ? '-' : $arrzertifikatlevel[$zertifikatsprachniveau];
                    
                    $rows[$x]['WeitereSprachkenntnisse'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'weiteresprachkenntnisse');
                    
                    $rows[$x]['Sonstigerstatus'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'sonstigerstatus');
                    
                    $tnerwerbsstatus = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'erwerbsstatus');
                    $rows[$x]['erwerbsstatus'] = $tnerwerbsstatus == 0 ? '-' : $arrerwerbsstatus[$tnerwerbsstatus];
                    
                    $tnleistungsbezugjanein = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'leistungsbezugjanein');
                    $rows[$x]['Leistungsbezugjanein'] = $tnleistungsbezugjanein == 0 ? '-' : $arrjanein[$tnleistungsbezugjanein];
                    
                    $tnleistungsbezug = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'leistungsbezug');
                    $rows[$x]['Leistungsbezug'] = $tnleistungsbezug == '' ? '-' : $arrleistungsbezug[$tnleistungsbezug];
                    
                    $tngeburtsland = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'geburtsland');
                    $rows[$x]['Geburtsland'] = $tngeburtsland == '' ? '-' : $arrstaaten[$tngeburtsland];
                    
                    $tnaufenthaltsstatus = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'aufenthaltsstatus');
                    $rows[$x]['aufenthaltsstatus'] = $tnaufenthaltsstatus == 0 ? '-' : $arraufenthaltsstatus[$tnaufenthaltsstatus];
                    
                    $geschlecht = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'geschlecht');
                    if($geschlecht == 1) $geschlecht = 'w';
                    if($geschlecht == 2) $geschlecht = 'm';
                    if($geschlecht == 3) $geschlecht = 'd';
                    $rows[$x]['Geschlecht'] = $geschlecht;
                    
                    $rows[$x]['notizen'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'notizen');
                    
                    if($berater != NULL) {
                        $rows[$x]['Beraterin'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($berater, 'username');
                    } else {
                        $rows[$x]['Beraterin'] = '-';
                    }
                    
                    $stringberatungsart = '';
                    foreach (\TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'beratungsart') as $atn) $stringberatungsart .= $atn == '' ? '-;' : $arrberatungsart[$atn].";";
                    $rows[$x]['beratungsart'] = $stringberatungsart;
                    
                    $rows[$x]['beratungsort'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'beratungsort');
                    
                    $stringanerkennungsberatung = '';
                    foreach (\TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'anerkennungsberatung') as $atn) $stringanerkennungsberatung .= $atn == '' ? '-;' : $arranerkennungsberatung[$atn].";";
                    $rows[$x]['anerkennungsberatung'] = $stringanerkennungsberatung;
                    
                    $stringqualifizierungsberatung = '';
                    foreach (\TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'qualifizierungsberatung') as $atn) $stringqualifizierungsberatung .= $atn == '' ? '-;' : $arrqualifizierungsberatung[$atn].";";
                    $rows[$x]['qualifizierungsberatung'] = $stringqualifizierungsberatung;
                    
                    $tnnameberatungsstelle = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'name_beratungsstelle');
                    $rows[$x]['nameberatungsstelle'] = $tnnameberatungsstelle == '' ? '-' : $arrberatungsstelle[$tnnameberatungsstelle];
                    
                    $rows[$x]['beratungnotizen'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'beratungnotizen');
                    
                    $rows[$x]['beratungzuschulabschluss'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'beratungzu');
                    
                    $rows[$x]['AnzFolgekontakte'] = $anzfolgekontakte[$x];
                    $rows[$x]['sumDauerFolgekontakte'] = str_replace('.', ',', $summedauerfk[$x]);
                    
                    $rows[$x]['kooperationgruppe'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'kooperationgruppe');
                    $rows[$x]['beratungsdauer'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'beratungsdauer');
                    $rows[$x]['beratungdatum'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'beratungdatum');
                    $rows[$x]['erstberatungabgeschlossen'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'erstberatungabgeschlossen');
                    $einwilligunginfo = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'einwilligunginfo');
                    if($einwilligunginfo == 1) $rows[$x]['einwilligunginfo'] = 'ja';
                    else $rows[$x]['einwilligunginfo'] = 'nein';
                    
                    foreach($abschluesse[$x] as $y => $abschluss) {
                        $aprops = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getGettablePropertyNames($abschluss);
                        
                        $abreferenzberufzugewiesen = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'referenzberufzugewiesen');
                        $rows[$x]['Abschluss'.$y.' Referenzberufzugewiesen'] = $abreferenzberufzugewiesen == '' ? '-' : $arrberufe[$abreferenzberufzugewiesen];
                        $rows[$x]['Abschluss'.$y.' SonstigerBeruf'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'sonstigerberuf');
                        $rows[$x]['Abschluss'.$y.' NichtreglementierterBeruf'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'nregberuf');
                        
                        
                        //TODO: wenn alte Variante (beide Angaben möglich mit Komma getrennt), dann hier alte Variante ODER die Datenbank anpassen, sodass bei allen jeweils der höchste Abschluss angezeigt wird.
                        // Das Auslesen des Feldes "abschlussart" muss in allen Controllern und dem Model angepasst werden
                        //$rows[$x]['Abschluss'.$y.' Abschlussart'] = '';
                        //foreach (\TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'abschlussart') as $atn) $rows[$x]['Abschluss'.$y.' Abschlussart'] .= $atn == '' ? '' : $arrabschlussart[$atn]." ";
                        
                        $abschlussart = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'abschlussart');
                        $rows[$x]['Abschluss'.$y.' Abschlussart'] = $abschlussart == '' ? '-' : $arrabschlussart[$abschlussart];
                        
                        
                        
                        $abbranche = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'branche');
                        $rows[$x]['Abschluss'.$y.' Branche'] = $abbranche == '' ? '-' : $arrbranchen[$abbranche];
                        
                        $aberwerbsland = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'erwerbsland');
                        $rows[$x]['Abschluss'.$y.' Erwerbsland'] = $aberwerbsland == '' ? '-' : $arrstaaten[$aberwerbsland];
                        
                        $rows[$x]['Abschluss'.$y.' Abschlussjahr'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'abschlussjahr');
                        $rows[$x]['Abschluss'.$y.' Ausbildungsort'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'ausbildungsort');
                        $rows[$x]['Abschluss'.$y.' Abschluss'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'abschluss');
                        
                        $rows[$x]['Abschluss'.$y.' DauerBerufsausbildung'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'dauer_berufsausbildung');
                        $rows[$x]['Abschluss'.$y.' Ausbildungsinstitution'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'ausbildungsinstitution');
                        $rows[$x]['Abschluss'.$y.' Berufserfahrung'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'berufserfahrung');
                        $rows[$x]['Abschluss'.$y.' Wunschberuf'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'wunschberuf');
                        $rows[$x]['Abschluss'.$y.' DeutscherReferenzberuf'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'deutscher_referenzberuf');
                        
                        $abantragstellungerfolgt = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'antragstellungerfolgt');
                        $rows[$x]['Abschluss'.$y.' Antragstellungerfolgt'] = $abantragstellungerfolgt == 0 ? '-' : $arrantragstellungerfolgt[$abantragstellungerfolgt];
                        
                    }
                }
                
                $bezbstatus = $this->settings['filterberatungsstatus'][$fberatungsstatus];
                
                // XLSX
                $filename = 'export_'.$bezbstatus.'_'.date('Y-m-d_H-i', time()).'.xlsx';
                $headerblatt1 = [
                    'UID' => 'string',
                    'Bestätigungsdatum' => 'string',
                    'Nachname' => 'string',
                    'Vorname' => 'string',
                    'PLZ' => 'string',
                    'Ort' => 'string',
                    'E-Mail' => 'string',
                    'Telefon' => 'string',
                    'Lebensalter' => 'string',
                    'Erste Staatsangehoerigkeit' => 'string',
                    'Zweite Staatsangehoerigkeit' => 'string',
                    'WohnsitzDeutschland' => 'string',
                    'Landkreis' => 'string',
                    'Einreisejahr' => 'string',
                    'WohnsitzNeinIn' => 'string',
                    'Deutschkenntnisse' => 'string',
                    'ZertifikatSprachniveau' => 'string',
                    'Weitere Sprachkenntnisse' => 'string',
                    'SonstigerStatus' => 'string',
                    'Erwerbsstatus' => 'string',
                    'Leistungsbezug ja/nein' => 'string',
                    'Leistungsbezug' => 'string',
                    'Geburtsland' => 'string',
                    'Aufenthaltsstatus' => 'string',
                    'Geschlecht' => 'string',
                    'Notizen Ratsuchender' => 'string',
                    'Berater:in' => 'string',
                    'Beratungsart' => 'string',
                    'Beratungsort' => 'string',
                    'Anerkennungsberatung' => 'string',
                    'Qualifizierungsberatung' => 'string',
                    'Beratungsstelle' => 'string',
                    'Beratung Notizen' => 'string',
                    'Beratung zu Schulabschluss' => 'string',
                    'Anz. Folgekontakte' => 'string',
                    'Summe Dauer Folgekontakte' => 'string',
                    'Kooperationgruppe' => 'string',
                    'Beratungsdauer' => 'string',
                    'Beratungdatum' => 'string',
                    'Erstberatungabgeschlossen' => 'string',
                    'Einwilligung Infos' => 'string',
                    'Abschluss1 Referenzberuf zugewiesen' => 'string',
                    'Abschluss1 Referenzberuf - sonstiger Beruf' => 'string',
                    'Abschluss1 Referenzberuf - nicht reglementierter Beruf' => 'string',                    
                    'Abschluss1 Abschlussart' => 'string',
                    'Abschluss1 Branche' => 'string',
                    'Abschluss1 Erwerbsland' => 'string',
                    'Abschluss1 Abschlussjahr' => 'string',
                    'Abschluss1 Ausbildungsort' => 'string',
                    'Abschluss1 Abschluss' => 'string',
                    'Abschluss1 DauerBerufsausbildung' => 'string',
                    'Abschluss1 Ausbildungsinstitution' => 'string',
                    'Abschluss1 Berufserfahrung' => 'string',
                    'Abschluss1 Wunschberuf' => 'string',
                    'Abschluss1 Deutscher Referenzberuf' => 'string',
                    'Abschluss1 Antragstellung erfolgt' => 'string',
                    'Abschluss2 Referenzberuf zugewiesen' => 'string',
                    'Abschluss2 Referenzberuf - sonstiger Beruf' => 'string',
                    'Abschluss2 Referenzberuf - nicht reglementierter Beruf' => 'string',
                    'Abschluss2 Abschlussart' => 'string',
                    'Abschluss2 Branche' => 'string',
                    'Abschluss2 Erwerbsland' => 'string',
                    'Abschluss2 Abschlussjahr' => 'string',
                    'Abschluss2 Ausbildungsort' => 'string',
                    'Abschluss2 Abschluss' => 'string',
                    'Abschluss2 DauerBerufsausbildung' => 'string',
                    'Abschluss2 Ausbildungsinstitution' => 'string',
                    'Abschluss2 Berufserfahrung' => 'string',
                    'Abschluss2 Wunschberuf' => 'string',
                    'Abschluss2 Deutscher Referenzberuf' => 'string',
                    'Abschluss2 Antragstellung erfolgt' => 'string',
                    'Abschluss3 Referenzberuf zugewiesen' => 'string',
                    'Abschluss3 Referenzberuf - sonstiger Beruf' => 'string',
                    'Abschluss3 Referenzberuf - nicht reglementierter Beruf' => 'string',
                    'Abschluss3 Abschlussart' => 'string',
                    'Abschluss3 Branche' => 'string',
                    'Abschluss3 Erwerbsland' => 'string',
                    'Abschluss3 Abschlussjahr' => 'string',
                    'Abschluss3 Ausbildungsort' => 'string',
                    'Abschluss3 Abschluss' => 'string',
                    'Abschluss3 DauerBerufsausbildung' => 'string',
                    'Abschluss3 Ausbildungsinstitution' => 'string',
                    'Abschluss3 Berufserfahrung' => 'string',
                    'Abschluss3 Wunschberuf' => 'string',
                    'Abschluss3 Deutscher Referenzberuf' => 'string',
                    'Abschluss3 Antragstellung erfolgt' => 'string',
                    'Abschluss4 Referenzberuf zugewiesen' => 'string',
                    'Abschluss4 Referenzberuf - sonstiger Beruf' => 'string',
                    'Abschluss4 Referenzberuf - nicht reglementierter Beruf' => 'string',
                    'Abschluss4 Abschlussart' => 'string',
                    'Abschluss4 Branche' => 'string',
                    'Abschluss4 Erwerbsland' => 'string',
                    'Abschluss4 Abschlussjahr' => 'string',
                    'Abschluss4 Ausbildungsort' => 'string',
                    'Abschluss4 Abschluss' => 'string',
                    'Abschluss4 DauerBerufsausbildung' => 'string',
                    'Abschluss4 Ausbildungsinstitution' => 'string',
                    'Abschluss4 Berufserfahrung' => 'string',
                    'Abschluss4 Wunschberuf' => 'string',
                    'Abschluss4 Deutscher Referenzberuf' => 'string',
                    'Abschluss4 Antragstellung erfolgt' => 'string'
                ];
               
                $headerblatt2 = [
                    'Teilnehmer UID' => 'string',
                    'Nachname' => 'string',
                    'Vorname' => 'string',
                    'Datum' => 'string',
                    'Berater' => 'string',
                    'Notizen' => 'string',
                    'Beratungsform' => 'string',
                    'Beratungsdauer' => 'string',
                ];
                $writer = new \XLSXWriter();
                $writer->setAuthor('IQ Webapp');
    
                $writer->writeSheet($rows, 'Ratsuchende', $headerblatt1);
                $writer->writeSheet($rowsfk, 'Folgekontakte', $headerblatt2);
                
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="'.$filename.'"');
                header('Cache-Control: max-age=0');
                $writer->writeToStdOut();
                exit;
            }
        } elseif(isset($valArray['export']) && $valArray['export'] && $fberatungsstatus == '') {
            
            $this->addFlashMessage("Bitte Status für Export auswählen.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->view->assignMultiple(
                [
                    'anzgesamt' => count($teilnehmers),
                    'calleraction' => 'export',
                    'callercontroller' => 'Backend',
                    'callerpage' => $currentPage,
                    'filterberatungsstatus' => $fberatungsstatus,
                    'filterbundesland' => $bundeslandselected,
                    'filterstaat' => $staatselected,
                    'filterberater' => $beraterselected,
                    'filterlandkreis' => $landkreisselected,
                    'filterberuf' => $berufselected,
                    'filteron' => $GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus')
                ]
                );
        } else {
            foreach($staaten as $staat) {
                $staatenarr[$staat->getStaatid()] = $staat->getTitel();
            }
            
            //$filtervon = isset($valArray['filtervon']) ? $valArray['filtervon'] : '';
            //$filterbis = isset($valArray['filterbis']) ? $valArray['filterbis'] : '';
            $this->view->assignMultiple(
                [
                    'anzgesamt' => $anzteilnehmers,
                    'calleraction' => 'export',
                    'callercontroller' => 'Backend',
                    'callerpage' => $currentPage,
                    'filterberatungsstatus' => $fberatungsstatus,
                    'filteron' => $GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus'),
                    'filtervon' => $filtervon,
                    'filterbis' => $filterbis,
                    'beratungsstelle' => $this->usergroup->getTitle(),
                    'niqbid' => $this->niqbid,
                    'allebundeslaender' => $allebundeslaender,
                    'filterbundesland' => $bundeslandselected,
                    'filterstaat' => $staatselected,
                    'staatenarr' => $staatenarr,
                    'alleberater' => $arrberater ?? '',
                    'alleberufe' => $arrberufe,
                    'gewlandkreise' => $arrlandkreise,
                    'filterberater' => $beraterselected,
                    'filterlandkreis' => $landkreisselected,
                    'filterberuf' => $berufselected
                ]
                );
        }
    }
    
     /**
     * action initshow
     *
     * @return void
     */
    public function initializeShowAction() {
        $valArray = $this->request->getArguments();
        
        if(is_string($valArray['teilnehmer'])) $tnuid = $valArray['teilnehmer'];
        else $tnuid = $valArray['teilnehmer']->getUid();
        
        $thistn = $this->teilnehmerRepository->findByUid($tnuid);   
        
        if($thistn != null) {
            if($thistn->getPlz() == '') $thistn->setPlz('0');
            $tnanonym = $thistn->getAnonym();
            $anonymeberatung = $valArray['newanonymeberatung'] ?? '';
            if($anonymeberatung == '1' || $tnanonym == '1') {
                $this->addFlashMessage("Bitte beachten: Für anonyme Beratungen ist zur Wahrung des Datenschutzes kein Dokumentenupload möglich!", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            }            
        } else {
            // TN ist (nicht) mehr vorhanden (gelöscht z.B. durch Task)
            $this->addFlashMessage("FEHLER: Datensatz mit ID $tnuid nicht vorhanden.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect($valArray['calleraction'] ?? 'listangemeldet', $valArray['callercontroller'] ?? 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1'));
        }
    }
    
    /**
     * action show
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function showAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();
        $language = $this->request->getAttribute('language');
        $isocode= $language->getTwoLetterIsoCode();
                
        if(array_key_exists("searchparams", $valArray)) {
            $searchparams = $valArray['searchparams'];
        }
                
        $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
        $historie = $this->historieRepository->findByTeilnehmerOrdered($teilnehmer->getUid());
        $dokumente = $this->dokumentRepository->findByTeilnehmer($teilnehmer);
        $dokumentpfad = $this->generalhelper->sanitizeFileFolderName($teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid(). '/');
        
        $storage = $this->generalhelper->getTP13Storage($this->storageRepository->findAll());
        $folder = $storage->getConfiguration()['basePath'].'/';
        
        $filesizes = array();
        $filesizesum = 0;
        foreach($dokumente as $key => $dok) {
            $dokfs = $dok->getFilesize($folder) ?? 0;
            $filesizes[$key] = $dokfs == 0 ? 0 : $this->generalhelper->human_filesize($dokfs, 1);
            $filesizesum += $dokfs;
        }
        $speicherbelegung = intval(($filesizesum/31457280)*100);
        
        $berufeliste = $this->berufeRepository->findAll();
        $staaten = $this->staatenRepository->findByLangisocode($isocode);
        $abschlussartarr = $this->settings['abschlussart'];
        unset($abschlussartarr[2]);
        
        $backenduser = $this->beraterRepository->findByUid($this->user['uid']);
        
        $brancheunterkat = $this->brancheRepository->findAllUnterkategorie($isocode);
        
        $this->view->assignMultiple(
            [
                'dokumente' => $dokumente,
                'dokumentpfad' => $dokumentpfad,
                'calleraction' => $valArray['calleraction'] ?? 'listangemeldet',
                'callercontroller' => $valArray['callercontroller'] ?? 'Backend',
                'callerpage' => $valArray['callerpage'] ?? '1',
                'historie' => $historie,
                'teilnehmer' => $teilnehmer,
                'abschluesse' => $abschluesse,
                'showabschluesse' => $valArray['showabschluesse'] ?? '0',
                'showdokumente' => $valArray['showdokumente'] ?? '0',
                'niqbid' => $backenduser->getUsergroup()[0]->getNiqbid(),
                'staaten' => $staaten,
                'berufe' => $berufeliste,
                'filesizes' => $filesizes,
                'speicherbelegung' => $speicherbelegung,
                'searchparams' => $searchparams ?? '',
                'abschlussartarr' => $abschlussartarr,
                'brancheunterkat' => $brancheunterkat
            ]
            );
    }
    
    /**
     * action initnew
     *
     * @return void
     */
    public function initializeNewAction() {
        $valArray = $this->request->getArguments();
        
        $anonymeberatung = $valArray['newanonymeberatung'] ?? '';
        if($anonymeberatung == '1') {
            $this->addFlashMessage("Bitte beachten: Für anonyme Beratungen ist zur Wahrung des Datenschutzes kein Dokumentenupload möglich!", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        }
    }
    
    /**
     * action new
     *
     * @return void
     */
    public function newAction()
    {
        $valArray = $this->request->getArguments();
        
        $abschluss = new \Ud\Iqtp13db\Domain\Model\Abschluss();
        
        $alleberater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->user['usergroup']);
        
        $staaten = $this->staatenRepository->findByLangisocode('de');
        unset($staaten[201]);
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }
                
        $altervonbis[-1000] = '-';
        $altervonbis[-1] = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('ka', 'iqtp13db');
        for ($i = 15; $i <= 80; $i++) {
            $altervonbis[$i] = $i;
        }
        
        $group = $this->userGroupRepository->findByUid($this->user['usergroup']);
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
        
        $aktuellesJahr = (int)date("Y");
        $jahre = array();
        $jahre[-1] = 'k.A.';
        for($jahr = $aktuellesJahr; $jahr > $aktuellesJahr-60; $jahr--) {
            $jahre[$jahr] = (String)$jahr;
        }
     
        $uriBuilder = $this->controllerContext->getUriBuilder();
        $uriBuilder->reset();
        if($group->getEinwilligungserklaerungsseite() != 0) {
            $uriBuilder->setTargetPageUid($group->getEinwilligungserklaerungsseite());
        } else {
            $uriBuilder->setTargetPageUid($this->settings['datenschutzeinwilligungurluid']);
        }
        
        $urleinwilligung = $uriBuilder->build();
        
        $alleberatungsstellen = $this->userGroupRepository->findAllBeratungsstellen($this->settings['beraterstoragepid']);
                
        $backenduser = $this->beraterRepository->findByUid($this->user['uid']);
        $this->view->assignMultiple(
            [
                'alleberatungsstellen' => $alleberatungsstellen,
                'altervonbis' => $altervonbis,
                'calleraction' => $valArray['calleraction'] ?? 'listangemeldet',
                'callercontroller' => $valArray['callercontroller'] ?? 'Backend',
                'callerpage' => $valArray['callerpage'] ?? '1',
                'abschluss' => $abschluss,
                'staatenarr' => $staatenarr,
                'alleberater' => $alleberater,
                'berater' => $this->user,
                'settings' => $this->settings,
                'jahre' => $jahre,
                'urleinwilligung' => $urleinwilligung,
                'newnacherfassung' => $valArray['newnacherfassung'] ?? '0',
                'newanonymeberatung' => $valArray['newanonymeberatung'] ?? '0',
                'niqbid' => $backenduser->getUsergroup()[0]->getNiqbid()
            ]
            );
    }
    
    /**
     * action initcreate
     *
     * @return void
     */
    public function initializeCreateAction() {        
        $valArray = $this->request->getArguments();
        $beratungdatum = $valArray['teilnehmer']['beratungdatum'] ?? '';
        $erstberatungabgeschlossen = $valArray['teilnehmer']['erstberatungabgeschlossen'] ?? '';
        
        if($beratungdatum != '' && !$this->generalhelper->validateDateYmd($beratungdatum)) {
            $this->addFlashMessage("FEHLER: Datensatz NICHT gespeichert. 'Beratung Datum' ungültige Eingabe. Datum im Format JJJJ-MM-TT eintragen!", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect($valArray['calleraction'] ?? 'new', $valArray['callercontroller'] ?? 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1', 'newnacherfassung' => $valArray['newnacherfassung']));
        }
        if($erstberatungabgeschlossen != '' && !$this->generalhelper->validateDateYmd($erstberatungabgeschlossen)) {
            $this->addFlashMessage("FEHLER: Datensatz NICHT gespeichert. 'Erstberatung abgeschlossen' ungültige Eingabe. Datum im Format JJJJ-MM-TT eintragen!", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect($valArray['calleraction'] ?? 'new', $valArray['callercontroller'] ?? 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1', 'newnacherfassung' => $valArray['newnacherfassung']));
        }
    }
    
    /**
     * action create
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function createAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();
        
        if($teilnehmer->getVerificationDate() == 0 && $teilnehmer->getNacherfassung() == 0 && ($this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen()) || $this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum()))) {
            $this->addFlashMessage("HINWEIS: Bitte unmittelbar nach Eintragung Einwilligung anfordern!", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        }
        
        if($valArray['newnacherfassung'] == '1' && $teilnehmer->getNacherfassung() == '') {
            $teilnehmer->setBeratungsstatus(99);
            $this->addFlashMessage("Datensatz NICHT gespeichert. Feld 'Nacherfassung' muss angekreuzt sein!", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);            
        } elseif($this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen()) && !$this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum())) {
            $teilnehmer->setBeratungsstatus(99);
            $this->addFlashMessage("Datensatz NICHT gespeichert. 'Datum Erstberatung' muss eingetragen sein, wenn 'Erstberatung abgeschlossen' ausgefüllt ist.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        } elseif($teilnehmer->getNacherfassung() == 1 && (!$this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum()) || !$this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen()))) {
            $teilnehmer->setBeratungsstatus(99);
            $this->addFlashMessage("Datensatz NICHT gespeichert. Bei Nacherfassungen müssen -Datum Erstberatung– und -Erstberatung abgeschlossen- ausgefüllt sein.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        } else {
            $teilnehmer->setBeratungsstatus(0);
        }
        if($teilnehmer->getAnonym() == 1) {
            if($teilnehmer->getErstberatungabgeschlossen() != '') {
                $teilnehmer->setBeratungsstatus(3);
            } else {
                $teilnehmer->setBeratungsstatus(2);
            }            
            $teilnehmer->setVerificationDate(new DateTime('now'));
            $teilnehmer->setVerificationIp($_SERVER['REMOTE_ADDR']);
        }
        if($teilnehmer->getNacherfassung() == 1) {
            $teilnehmer->setBeratungsstatus(4);
            $teilnehmer->setVerificationDate(new DateTime('now'));
            $teilnehmer->setVerificationIp($_SERVER['REMOTE_ADDR']);
        }        
        $teilnehmer->setNiqidberatungsstelle($this->niqbid);
        if($teilnehmer->getBerater() == 0) $teilnehmer->setBerater($this->beraterRepository->findByUid($this->user['uid']));
        $teilnehmer->setCrdate(time());
        $this->teilnehmerRepository->add($teilnehmer);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        // 07.06.2023 auskommentiert, weil ggf. nicht notwendig: $tfolder = $this->generalhelper->createFolder($teilnehmer, $this->storageRepository->findAll());
        
        $this->redirect('edit', 'Backend', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'], 'callercontroller' => $valArray['callercontroller'], 'callerpage' => $valArray['callerpage'], 'newnacherfassung' => $valArray['newnacherfassung']));
    }
    
    /**
     * action initedit
     *
     * @return void
     */
    public function initializeEditAction() {
        $valArray = $this->request->getArguments();
        
        if(array_key_exists('teilnehmer', $valArray)) {
            if(is_string($valArray['teilnehmer'])) {
                $tnuid = $valArray['teilnehmer'];
                //else $tnuid = $valArray['teilnehmer']['__identity'];
    
                $thistn = $this->teilnehmerRepository->findByUid($tnuid);
                if($tnuid != null) $tnanonym = $thistn->getAnonym();
                else $tnanonym = 0;
                $anonymeberatung = $valArray['newanonymeberatung'] ?? '';
                
                if($anonymeberatung == '1' || $tnanonym == '1') {
                    $this->addFlashMessage("Bitte beachten: Für anonyme Beratungen ist zur Wahrung des Datenschutzes kein Dokumentenupload möglich!", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
                }
            }
        }
    }
    
    /**
     * action edit
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @param \Ud\Iqtp13db\Domain\Model\Abschluss $abschluss
     * @param String $selectboxabschluss
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function editAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer, \Ud\Iqtp13db\Domain\Model\Abschluss $abschluss = NULL)
    {
        $valArray = $this->request->getArguments();
        $language = $this->request->getAttribute('language');
        $isocode= $language->getTwoLetterIsoCode();        
        
        if(array_key_exists("searchparams", $valArray)) {
            $searchparams = $valArray['searchparams'];
        }
        
        $edituserfield = '';
        
        if($teilnehmer->getEdittstamp() == 0 || $teilnehmer->getEdituser() == $this->user['uid'] || (time() - $teilnehmer->getEdittstamp()) > 10) {
            $teilnehmer->setEdittstamp(time());
            $teilnehmer->setEdituser($this->user['uid']);
            $this->teilnehmerRepository->update($teilnehmer);
            
            // Daten sofort in die Datenbank schreiben
            $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
        } else {
            $editberater = $this->beraterRepository->findByUid($teilnehmer->getEdituser());
            $edituserfield = $editberater->getUsername();
            $edittstampfield = date("G:i:s", $teilnehmer->getEdittstamp());
        }
        
        $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
        
        $alleberater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->user['usergroup']);
        
        $dokumente = $this->dokumentRepository->findByTeilnehmer($teilnehmer);
        $dokumentpfad = $this->generalhelper->sanitizeFileFolderName($teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid(). '/');
        
        $storage = $this->generalhelper->getTP13Storage($this->storageRepository->findAll());
        $folder = $storage->getConfiguration()['basePath'].'/';
        
        $filesizes = array();
        $filesizesum = 0;
        foreach($dokumente as $key => $dok) {
            $dokfs = $dok->getFilesize($folder) ?? 0;
            $filesizes[$key] = $dokfs == 0 ? 0 : $this->generalhelper->human_filesize($dokfs, 1);
            $filesizesum += $dokfs;
        }
        $speicherbelegung = intval(($filesizesum/31457280)*100);
        
        $berufe = $this->berufeRepository->findAllOrdered($isocode);
        $staaten = $this->staatenRepository->findByLangisocode($isocode);
        unset($staaten[201]);
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }        
        
        $altervonbis[-1000] = '-';
        $altervonbis[-1] = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('ka', 'iqtp13db');
        for ($i = 15; $i <= 80; $i++) {
            $altervonbis[$i] = $i;
        }
        
        $group = $this->userGroupRepository->findByUid($this->user['usergroup']);
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
        
        $aktuellesJahr = (int)date("Y");
        $jahre = array();
        $jahre[-1] = 'k.A.';
        for($jahr = $aktuellesJahr; $jahr > $aktuellesJahr-60; $jahr--) {
            $jahre[$jahr] = (String)$jahr;
        }
        
        $abschlusshinzu = isset($valArray['abschlusshinzu']) ? $valArray['abschlusshinzu'] : '';
        
        $alleberatungsstellen = $this->userGroupRepository->findAllBeratungsstellen($this->settings['beraterstoragepid']);
        
        if($group->getEinwilligungserklaerungsseite() != '') {
            $uriBuilder = $this->controllerContext->getUriBuilder();
            $uriBuilder->reset();
            $uriBuilder->setTargetPageUid($group->getEinwilligungserklaerungsseite());
            $urleinwilligung = $uriBuilder->build();
        } else {
            $urleinwilligung = $this->settings['datenschutzeinwilligungurl'];
        }
        
        $nacherfassung = $valArray['newnacherfassung'] ?? '0';
        if($teilnehmer->getNacherfassung() == 1) {
            $nacherfassung = 1;
        }
        $abschlussartarr = $this->settings['abschlussart'];
        unset($abschlussartarr[2]);
        
        $backenduser = $this->beraterRepository->findByUid($this->user['uid']);
        $brancheunterkat = $this->brancheRepository->findAllUnterkategorie($isocode);
        $this->view->assignMultiple(
            [
                'alleberatungsstellen' => $alleberatungsstellen,
                'altervonbis' => $altervonbis,
                'calleraction' => $valArray['calleraction'] ?? 'listangemeldet',
                'callercontroller' => $valArray['callercontroller'] ?? 'Backend',
                'callerpage' => $valArray['callerpage'] ?? '1',
                'abschluesse' => $abschluesse,
                'alleberater' => $alleberater,
                'settings' => $this->settings,
                'staatenarr' => $staatenarr,
                'berufe' => $berufe,
                'staaten' => $staaten,
                'teilnehmer' => $teilnehmer,
                'dokumente' => $dokumente,
                'dokumentpfad' => $dokumentpfad,
                'filesizes' => $filesizes,
                'speicherbelegung' => $speicherbelegung,
                'abschlusshinzu' => $abschlusshinzu,
                'jahre' => $jahre,
                'showabschluesse' => $valArray['showabschluesse'] ?? '0',
                'showdokumente' => $valArray['showdokumente'] ?? '0',
                'edituserfield' => $edituserfield ?? '0',
                'edittstampfield' => $edittstampfield ?? '0',
                'urleinwilligung' => $urleinwilligung,
                'newnacherfassung' => $nacherfassung,
                'niqbid' => $teilnehmer->getNiqidberatungsstelle(),
                'searchparams' => $searchparams ?? '',
                'abschlussartarr' => $abschlussartarr,
                'brancheunterkat' => $brancheunterkat
            ]
            );
    }
    
    /**
     * action initupdate
     *
     * @return void
     */
    public function initializeUpdateAction() {
        
        $valArray = $this->request->getArguments();
       
        if(array_key_exists('teilnehmer', $valArray)) {
            $beratungdatum = $valArray['teilnehmer']['beratungdatum'] ?? '';
            $erstberatungabgeschlossen = $valArray['teilnehmer']['erstberatungabgeschlossen'] ?? '';
            
            $email = $valArray['teilnehmer']['email'] ?? '';
            $confirmemail = $valArray['teilnehmer']['confirmemail'] ?? '';
            
            if($email == '' || $confirmemail == '' || $email != $confirmemail) {
                $this->addFlashMessage("FEHLER: E-Mail-Adressen stimmen nicht überein!", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                $this->redirect('edit', 'Backend', null, array('teilnehmer' => $valArray['teilnehmer']['__identity'], 'callerpage' => $valArray['callerpage'] ?? '1', 'newnacherfassung' => $valArray['newnacherfassung']));
            }
            if($beratungdatum != '' && !$this->generalhelper->validateDateYmd($beratungdatum)) {
                $this->addFlashMessage("FEHLER: Datensatz NICHT gespeichert. 'Beratung Datum' ungültige Eingabe. Datum im Format JJJJ-MM-TT eintragen!", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                $this->redirect($valArray['calleraction'] ?? 'edit', $valArray['callercontroller'] ?? 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1', 'newnacherfassung' => $valArray['newnacherfassung']));
            }
            if($erstberatungabgeschlossen != '' && !$this->generalhelper->validateDateYmd($erstberatungabgeschlossen)) {
                $this->addFlashMessage("FEHLER: Datensatz NICHT gespeichert. 'Erstberatung abgeschlossen' ungültige Eingabe. Datum im Format JJJJ-MM-TT eintragen!", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                $this->redirect($valArray['calleraction'] ?? 'edit', $valArray['callercontroller'] ?? 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1', 'newnacherfassung' => $valArray['newnacherfassung']));
            }
        } else {
            $this->addFlashMessage("FEHLER in initializeUpdateAction.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']));
        }
    }
    
    /**
     * action update
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @Validate("Ud\Iqtp13db\Domain\Validator\TeilnehmerValidator", param="teilnehmer")
     * @return void
     */
    public function updateAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();
        if(array_key_exists("searchparams", $valArray)) {
            $searchparams = $valArray['searchparams'];
        }
        
        if(is_numeric($teilnehmer->getLebensalter())) {
            if($teilnehmer->getLebensalter() > 0 && ($teilnehmer->getLebensalter() < 15 || $teilnehmer->getLebensalter() > 80)) {
                $this->addFlashMessage("Datensatz NICHT gespeichert. Lebensalter muss zwischen 15 und 80 oder k.A. sein.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                $this->redirect($valArray['calleraction'] ?? 'edit', $valArray['callercontroller'] ?? 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1', 'newnacherfassung' => $valArray['newnacherfassung'], 'searchparams' => $searchparams));
            }
        }        
        $nacherfassung = $valArray['newnacherfassung'] ?? '0';
        if($nacherfassung == '1' && $teilnehmer->getNacherfassung() == '') {
            $this->addFlashMessage("Datensatz NICHT gespeichert. Feld 'Nacherfassung' muss angekreuzt sein!", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect('edit', 'Backend', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'], 'callercontroller' => $valArray['callercontroller'], 'callerpage' => $valArray['callerpage'] ?? '1', 'newnacherfassung' => $valArray['newnacherfassung'], 'searchparams' => $searchparams));
        }
        
        if($teilnehmer->getNacherfassung() == 1 && (!$this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum()) || !$this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen()))) {
            $this->addFlashMessage("Datensatz NICHT gespeichert. Bei Nacherfassungen müssen 'Datum Erstberatung' und 'Erstberatung abgeschlossen' ausgefüllt sein.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect('edit', 'Backend', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'], 'callercontroller' => $valArray['callercontroller'], 'callerpage' => $valArray['callerpage'] ?? '1', 'newnacherfassung' => $valArray['newnacherfassung'], 'searchparams' => $searchparams));
        }
        
        if($teilnehmer->getNacherfassung() != 1 && $teilnehmer->getVerificationDate() == 0 && ($this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen()) || $this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum()))) {
            $this->addFlashMessage("Datensatz NICHT gespeichert. Vor Eintragung von 'Datum Erstberatung' oder 'Erstberatung abgeschlossen' muss die Anmeldung bestätigt werden!", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect('edit', 'Backend', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'], 'callercontroller' => $valArray['callercontroller'], 'callerpage' => $valArray['callerpage'] ?? '1', 'newnacherfassung' => $valArray['newnacherfassung'], 'searchparams' => $searchparams));
        }
        
        if($this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen()) && !$this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum())) {
            $teilnehmer->setBeratungdatum($teilnehmer->getErstberatungabgeschlossen());
            
            $this->addFlashMessage("Datensatz gespeichert. Für 'Datum Erstberatung' wurde automatisch das Datum 'Erstberatung abgeschlossen' eingetragen.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            //$this->redirect($valArray['calleraction'] ?? 'edit', $valArray['callercontroller'] ?? 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1', 'newnacherfassung' => $valArray['newnacherfassung']));
        }

        // Stammdaten (im Fragebogen Seite 1)
        $this->createHistory($teilnehmer, "niqidberatungsstelle");
        $this->createHistory($teilnehmer, "einwilligung");
        $this->createHistory($teilnehmer, "schonberaten");
        $this->createHistory($teilnehmer, "schonberatenvon");
        $this->createHistory($teilnehmer, "nachname");
        $this->createHistory($teilnehmer, "vorname");
        $this->createHistory($teilnehmer, "plz");
        $this->createHistory($teilnehmer, "ort");
        $this->createHistory($teilnehmer, "email");
        $this->createHistory($teilnehmer, "confirmemail");
        $this->createHistory($teilnehmer, "telefon");
        $this->createHistory($teilnehmer, "lebensalter");
        $this->createHistory($teilnehmer, "geburtsland");
        $this->createHistory($teilnehmer, "geschlecht");
        $this->createHistory($teilnehmer, "ersteStaatsangehoerigkeit");
        $this->createHistory($teilnehmer, "zweiteStaatsangehoerigkeit");
        $this->createHistory($teilnehmer, "einreisejahr");
        $this->createHistory($teilnehmer, "wohnsitzDeutschland");
        $this->createHistory($teilnehmer, "wohnsitzNeinIn");
        $this->createHistory($teilnehmer, "aufenthaltsstatus");
        $this->createHistory($teilnehmer, "aufenthaltsstatusfreitext");
        $this->createHistory($teilnehmer, "sonstigerstatus");
        $this->createHistory($teilnehmer, "deutschkenntnisse");
        $this->createHistory($teilnehmer, "zertifikatSprachniveau");
        $this->createHistory($teilnehmer, "weiteresprachkenntnisse");

        // Stammdaten (im Fragebogen Seite 3)
        $this->createHistory($teilnehmer, "erwerbsstatus");
        $this->createHistory($teilnehmer, "leistungsbezugjanein");
        $this->createHistory($teilnehmer, "leistungsbezug");
        $this->createHistory($teilnehmer, "nameBeraterAA");
        $this->createHistory($teilnehmer, "kontaktBeraterAA");
        $this->createHistory($teilnehmer, "kundennummerAA");
        $this->createHistory($teilnehmer, "einwAnerkstelle");
        $this->createHistory($teilnehmer, "einwAnerkstelledatum");
        $this->createHistory($teilnehmer, "einwAnerkstellemedium");
        $this->createHistory($teilnehmer, "einwAnerkstellename");
        $this->createHistory($teilnehmer, "einwAnerkstellekontakt");
        $this->createHistory($teilnehmer, "einwPerson");
        $this->createHistory($teilnehmer, "einwPersondatum");
        $this->createHistory($teilnehmer, "einwPersonmedium");
        $this->createHistory($teilnehmer, "einwPersonname");
        $this->createHistory($teilnehmer, "einwPersonkontakt");
        $this->createHistory($teilnehmer, "nameBeratungsstelle");
        $this->createHistory($teilnehmer, "wieberaten");
        $this->createHistory($teilnehmer, "notizen");
        $this->createHistory($teilnehmer, "einwilligunginfo");

        // Beratungsdaten (nur Backend!)
        $this->createHistory($teilnehmer, "anerkennendestellen");
        $this->createHistory($teilnehmer, "beratungdatum");
        $this->createHistory($teilnehmer, "berater");
        $this->createHistory($teilnehmer, "beratungsart");
        $this->createHistory($teilnehmer, "beratungsartfreitext");
        $this->createHistory($teilnehmer, "beratungsort");
        $this->createHistory($teilnehmer, "beratungsdauer");
        $this->createHistory($teilnehmer, "anerkennungsberatung");
        $this->createHistory($teilnehmer, "anerkennungsberatungfreitext");
        $this->createHistory($teilnehmer, "qualifizierungsberatung");
        $this->createHistory($teilnehmer, "qualifizierungsberatungfreitext");
        $this->createHistory($teilnehmer, "beratungzu");
        $this->createHistory($teilnehmer, "anerkennungszuschussbeantragt");
        $this->createHistory($teilnehmer, "kooperationgruppe");
        $this->createHistory($teilnehmer, "beratungnotizen");
        $this->createHistory($teilnehmer, "erstberatungabgeschlossen");
        
        $bstatus = $this->checkberatungsstatus($teilnehmer);
        if($bstatus == 999) {
            $this->addFlashMessage("Fehler in Update-Routine -> beratungsstatus = 999. Bitte Admin informieren.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        }
        
        $teilnehmer->setBeratungsstatus($bstatus);
        
        if($teilnehmer->getNacherfassung() == 1) {
            $teilnehmer->setBeratungsstatus(4);
            if($teilnehmer->getVerificationDate() == 0) $teilnehmer->setVerificationDate(new DateTime('now'));
            if($teilnehmer->getVerificationIp() == '') $teilnehmer->setVerificationIp($_SERVER['REMOTE_ADDR']);
        }
        $this->teilnehmerRepository->update($teilnehmer);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        $this->redirect('edit', $valArray['callercontroller'] ?? 'Backend', null, array('teilnehmer'=> $teilnehmer, 'callerpage' => $valArray['callerpage'] ?? '1', 'calleraction' => $valArray['calleraction'] ?? 'listangemeldet', 'newnacherfassung' => $nacherfassung, 'searchparams' => $searchparams));
    }
    
    /**
     * action initdelete
     *
     * @return void
     */
    public function initializeDeleteAction() {
        $valArray = $this->request->getArguments();
        
        if(is_string($valArray['teilnehmer'])) $tnuid = $valArray['teilnehmer'];
        else $tnuid = $valArray['teilnehmer']->getUid();
        
        $thistn = $this->teilnehmerRepository->findByUid($tnuid);
        
        if($thistn == null) {
            // TN ist (nicht) mehr vorhanden (gelöscht z.B. durch Task)
            $this->addFlashMessage("FEHLER: Datensatz mit ID $tnuid nicht vorhanden.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect($valArray['calleraction'] ?? 'listangemeldet', $valArray['callercontroller'] ?? 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1'));
        }
    }
    
    /**
     * action delete
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function deleteAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();
        if(array_key_exists("searchparams", $valArray)) {
            $searchparams = $valArray['searchparams'];
        }
        
        if($teilnehmer->getNiqchiffre() == '') {
            $teilnehmer->setHidden(1);
            
            $this->teilnehmerRepository->update($teilnehmer);
            
            // Daten sofort in die Datenbank schreiben
            $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
        } else {
            $this->addFlashMessage('Bereits in NIQ übertragene Datensätze können nicht gelöscht werden.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        }
        $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage'] ?? '1', 'searchparams' => $searchparams ?? ''));
    }
    
    /**
     * action undelete
     *
     * @param int $tnuid
     * @return void
     */
    public function undeleteAction($tnuid)
    {
        $valArray = $this->request->getArguments();
        $searchparams  = array();
        if(array_key_exists("searchparams", $valArray)) {
            $searchparams = $valArray['searchparams'];
        }
        
        $teilnehmer = $this->teilnehmerRepository->findHiddenByUid($tnuid);
        $teilnehmer->setHidden(0);
        
        $this->teilnehmerRepository->update($teilnehmer);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage'], 'searchparams' => $searchparams));
    }
    
    
    /**
     * action takeover
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function takeoverAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();
        
        if(array_key_exists("searchparams", $valArray)) {
            $searchparams = $valArray['searchparams'];
        }
        
        $berater = $this->beraterRepository->findByUid($this->user['uid']);
        
        $teilnehmer->setBerater($berater);
        $this->teilnehmerRepository->update($teilnehmer);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage'] ?? '1', 'searchparams' => $searchparams ?? ''));
    }
    
    /**
     * action setBeratungsstellebyPLZ
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function setBeratungsstellebyPLZAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();
        
        $plzberatungsstelle = array();
        $plzberatungsstelle = $this->userGroupRepository->getBeratungsstelle4PLZ($teilnehmer->getPlz(), $this->settings['beraterstoragepid']);
        $bstid = count($plzberatungsstelle) > 0 ? $plzberatungsstelle[0]->getNiqbid() : '';
        
        if($bstid == '') {
            $this->addFlashMessage('Keine der PLZ zugehörige Beratungsstelle vorhanden.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        } else {
            if($bstid == $teilnehmer->getNiqidberatungsstelle()) {
                $this->addFlashMessage('Keine Änderung der Beratungsstelle, da die PLZ dieser Beratungsstelle zugewiesen ist.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            } else {
                $teilnehmer->setNiqidberatungsstelle($bstid);
                $teilnehmer->setBerater(null);
                
                $this->teilnehmerRepository->update($teilnehmer);
                
                // Daten sofort in die Datenbank schreiben
                $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
                $persistenceManager->persistAll();
                
                $this->addFlashMessage('Datensatz zu Beratungsstelle '.$plzberatungsstelle[0]->getTitle(). ' verschoben.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
            }
        }
        
        $this->redirect('listangemeldet', 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer, 'callerpage' => $valArray['callerpage'] ?? '1'));
    }
    
    /**
     * action askconsent
     * Einwilligungs-E-Mail aus dem Backend anfordern
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function askconsentAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();
        
        if(array_key_exists("searchparams", $valArray)) {
            $searchparams = $valArray['searchparams'];
        }
        
        $bcc = '';
        $sender = $this->settings['sender'];
        if($sender == '') {
            $this->addFlashMessage('Error 101 in askconsent.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect('listangemeldet', 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
        } else {
            $recipient = $teilnehmer->getEmail();
            if($recipient == '') {
                $this->addFlashMessage('Keine E-Mail-Adresse eingetragen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                $this->redirect('listangemeldet', 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
            }
            if($teilnehmer->getTstamp() < 1672527600) {
                $templateName = 'Mailtoconfirm2022';
            } else {
                $templateName = 'Mailtoconfirm';
            }
            $confirmmailtext1 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmmailtext1', 'Iqtp13db');
            $confirmmailtext1 = str_replace("VORNAMENACHNAME", $teilnehmer->getVorname().' '.$teilnehmer->getNachname(), $confirmmailtext1);
            $confirmlinktext = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmlinktext', 'Iqtp13db');
            $confirmmailtext2 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmmailtext2', 'Iqtp13db');
            $subject = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmsubject', 'Iqtp13db');
            
            $zugewieseneberatungsstelle = $this->userGroupRepository->findBeratungsstellebyNiqbid($this->settings['beraterstoragepid'], $teilnehmer->getNiqidberatungsstelle());
            $datenberatungsstelle = $zugewieseneberatungsstelle != NULL ? $zugewieseneberatungsstelle[0]->getDescription() : '';
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
                'askconsent' => '1',
                'baseurl' => $this->request->getBaseUri()
            );
            
            $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
            $this->generalhelper->sendTemplateEmail(array($recipient), array($bcc), array($sender), $subject, $templateName, $variables, $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView'), $this->controllerContext->getUriBuilder(), $extbaseFrameworkConfiguration);
            
            $this->addFlashMessage('Einwilligungsanforderung versendet.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
            
            $this->redirect('listangemeldet', 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer, 'callerpage' => $valArray['callerpage'] ?? '1', 'searchparams' => $searchparams ?? ''));
        }
    }     
        
    /**
     * action sendtoarchiv
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function sendtoarchivAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();
        
        if($teilnehmer->getVerificationDate() == 0) {
            $this->addFlashMessage('FEHLER: Einwilligung noch nicht eingeholt.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        } else {
            $teilnehmer->setBeratungsstatus(4);
            
            $this->teilnehmerRepository->update($teilnehmer);
            // Daten sofort in die Datenbank schreiben
            $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
            
            $this->addFlashMessage('Archiviert.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        }
        
        $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage'] ?? '1'), null);
    }
    
    /**
     * action savedatenblattpdf
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function savedatenblattpdfAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();
        
        if(array_key_exists("searchparams", $valArray)) {
            $searchparams = $valArray['searchparams'];
        }
        
        // MPDF per composer einbinden - wenn nicht vorhanden, dann s.u.
        $mpdfComposer = \TYPO3\CMS\Core\Core\Environment::getConfigPath() . '/ext/vendor/autoload.php';
        if (file_exists($mpdfComposer)) {
            require_once($mpdfComposer);
        } else {
            // MPDF nicht per composer eingebunden, dann prüfe, ob extension web2pdf installiert ist und binde MPDF aus der ext web2pdf ein
            $mpdfAutoload = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('web2pdf') . 'Resources/Private/Libraries/vendor/autoload.php';
            if (file_exists($mpdfAutoload)) {
                require_once($mpdfAutoload);
            } else {
                // PDF erstellen nicht möglich
                $this->addFlashMessage('Datenblatt kann nicht erstellt werden, da MPDF nicht installiert. Bitte Admin kontaktieren.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                $this->redirect('show', 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
            }
        }
        
        $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
        $dokumente = $this->dokumentRepository->findByTeilnehmer($teilnehmer);
        
        $thisdate = new DateTime();
        $zeitstempel = $thisdate->format('d.m.Y - H:i:s');
        $zeitstempel4filename = $thisdate->format('dmY-His');
        $berufeliste = $this->berufeRepository->findAllOrdered('de');
        $staaten = $this->staatenRepository->findByLangisocode('de');
               
        $this->view->assign('teilnehmer', $teilnehmer);
        $this->view->assign('abschluesse', $abschluesse);
        $this->view->assign('dokumente', $dokumente);
        $this->view->assign('berufe', $berufeliste);
        $this->view->assign('staaten', $staaten);
        
        $htmlcode = $this->view->render();
        
        $default_mpdfconfig = [
            'mode' => 'c',
            'format' => 'A4',
            'default_font_size' => 0,
            'default_font' => '',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'margin_header' => 9,
            'margin_footer' => 9,
            'orientation' => 'P',
        ];
        
        $mpdf = new \Mpdf\Mpdf($default_mpdfconfig);
        
        $stylesheet = file_get_contents(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('iqtp13db').'/Resources/Public/CSS/customtp13db.css');
        
        $mpdf->SetHeader('Datenblatt vom '.$zeitstempel.'||IQ Webapp');
        $mpdf->SetFooter('|{PAGENO}|');
        
        $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($htmlcode,\Mpdf\HTMLParserMode::HTML_BODY);
        
        $pfad = $this->generalhelper->createFolder($teilnehmer, $this->storageRepository->findAll());
        $filename = 'DB-' .$this->generalhelper->sanitizeFileFolderName($teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid()). '_' . $zeitstempel4filename. '.pdf';
        $storage = $this->generalhelper->getTP13Storage( $this->storageRepository->findAll());
        
        $niqbid = $this->niqbid;
        $beratungsstellenfolder = $niqbid == '' ? 'Beratene' : $niqbid;
        $fullpath = $storage->getConfiguration()['basePath']. '/' .$beratungsstellenfolder. '/' .$pfad->getName().'/'. $filename;
        
        $mpdf->Output($fullpath, 'F');
        
        // ******* Als Dokument speichern, damit aus Webapp abrufbar *******
        $dbexists = $this->dokumentRepository->findByName($filename);
        
        if(count($dbexists) == 0) {
            $dokument = new \Ud\Iqtp13db\Domain\Model\Dokument();
            
            $dokument->setBeschreibung("DATENBLATT vom ".$zeitstempel);
            $dokument->setName($filename);
            $dokument->setPfad($beratungsstellenfolder. '/' .$pfad->getName().'/');
            $dokument->setTeilnehmer($teilnehmer);
            
            $this->dokumentRepository->add($dokument);
            
            //Daten sofort in die Datenbank schreiben
            $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
            
            $this->addFlashMessage('Datenblatt wurde in '.$pfad->getIdentifier().' erstellt.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
            
        } else {
            $this->addFlashMessage('Datenblatt mit diesem Zeitstempel schon vorhanden.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        }
        //********************************************************************
        
        $this->redirect('show', 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer, 'callerpage' => $valArray['callerpage'] ?? '1', 'showdokumente' => '1', 'searchparams' => $searchparams ?? ''));
    }
    
    /**
     * action saveAVpdf
     *
     * @return void
     */
    public function saveAVpdfAction()
    {
        $valArray = $this->request->getArguments();
        
        // MPDF per composer einbinden - wenn nicht vorhanden, dann s.u.
        $mpdfComposer = \TYPO3\CMS\Core\Core\Environment::getConfigPath() . '/ext/vendor/autoload.php';
        if (file_exists($mpdfComposer)) {
            //require_once __DIR__ . '/vendor/autoload.php';
            require_once($mpdfComposer);
        } else {
            // MPDF nicht per composer eingebunden, dann prüfe, ob extension web2pdf installiert ist und binde MPDF aus der ext web2pdf ein
            $mpdfAutoload = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('web2pdf') . 'Resources/Private/Libraries/vendor/autoload.php';
            if (file_exists($mpdfAutoload)) {
                require_once($mpdfAutoload);
            } else {
                // PDF erstellen nicht möglich
                $this->addFlashMessage('AV kann nicht erstellt werden, da MPDF nicht installiert. Bitte Admin kontaktieren.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                $this->redirect('show', 'Backend', 'Iqtp13db', null);
            }
        }
        
        $thisdate = new DateTime();
        $zeitstempel = $thisdate->format('d.m.Y - H:i:s');        
        $zeitstempel4filename = $thisdate->format('dmY-His');
        
        $default_mpdfconfig = [
            'mode' => 'c',
            'format' => 'A4',
            'default_font_size' => 10.5,
            'default_font' => 'Arial',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'margin_header' => 9,
            'margin_footer' => 9,
            'orientation' => 'P',
        ];
        
        $mpdf = new \Mpdf\Mpdf($default_mpdfconfig);
        
        $sourcefile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('iqtp13db') . 'Resources/Public/' . '/IQWebapp_Auftragsverarbeitung.pdf'; // absolute path to pdf file
        $mpdf->setSourceFile($sourcefile);
        
        $mpdf->SetHeader('AV generiert am '.$zeitstempel.'||IQ Webapp zur Anerkennungs- und Qualifizierungsberatung');

        $tplIdx = $mpdf->importPage(1);
        $mpdf->useTemplate($tplIdx, 10, 10, 200);
        
        $mpdf->SetTextColor(0, 0, 0);
        $mpdf->SetXY(34, 187);
        $mpdf->WriteHTML('<b>'.$this->usergroup->getAvadresse().'</b>');
        
        $mpdf->AddPage();
        $mpdf->setSourceFile($sourcefile);
        $tplIdx = $mpdf->importPage(2);
        $mpdf->useImportedPage($tplIdx, 0, 0, 210);
        $mpdf->AddPage();
        $mpdf->setSourceFile($sourcefile);
        $tplIdx = $mpdf->importPage(3);
        $mpdf->useImportedPage($tplIdx, 0, 0, 210);
        $mpdf->AddPage();
        $mpdf->setSourceFile($sourcefile);
        $tplIdx = $mpdf->importPage(4);
        $mpdf->useImportedPage($tplIdx, 0, 0, 210);
        $mpdf->AddPage();
        $mpdf->setSourceFile($sourcefile);
        $tplIdx = $mpdf->importPage(5);
        $mpdf->useImportedPage($tplIdx, 0, 0, 210);
        $mpdf->AddPage();
        $mpdf->setSourceFile($sourcefile);
        $tplIdx = $mpdf->importPage(6);
        $mpdf->useImportedPage($tplIdx, 0, 0, 210);
        $mpdf->AddPage();
        $mpdf->setSourceFile($sourcefile);
        $tplIdx = $mpdf->importPage(7);
        $mpdf->useImportedPage($tplIdx, 0, 0, 210);
        
        $mpdf->SetTextColor(0, 0, 0);
        $mpdf->SetXY(55, 22);
        $mpdf->WriteHTML('<b>'.$thisdate->format('d.m.Y').'</b>');
        
        $niqbid = $this->niqbid;
        $filename = 'AV-IQWebapp_WHKT_' . $niqbid . '_' . $zeitstempel4filename. '.pdf';
        $storage = $this->generalhelper->getTP13Storage($this->storageRepository->findAll());
        
        $beratungsstellenfolder = $niqbid == '' ? 'Beratene' : $niqbid;
        $fullpath = $storage->getConfiguration()['basePath']. '/' .$beratungsstellenfolder. '/' . $filename;
        
        $mpdf->Output($fullpath, \Mpdf\Output\Destination::DOWNLOAD);
        $mpdf->Output($fullpath, \Mpdf\Output\Destination::FILE);
        
        die;        
    }
    
    /**
     * action editsettings
     *
     * @return void
     */
    public function editsettingsAction() {
        $valArray = $this->request->getArguments();
        
        $berater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->user['usergroup']);
        
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : 1;
        $paginator = new QueryResultPaginator($berater, $currentPage, 25);
        $pagination = new SimplePagination($paginator);
        
        $this->view->assignMultiple(
            [
                'callerpage' => $currentPage,
                'paginator' => $paginator,
                'pagination' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
                'berater' => $berater,
                'thisuser' => $this->user,
                'niqbid' => $this->usergroup->getNiqbid(),
                'custominfotextstart' => $this->usergroup->getCustominfotextstart() ?? '',
                'custominfotextmail'=> $this->usergroup->getCustominfotextmail() ?? ''
            ]
        );
    }
    
    /**
     * action updatesettings
     *
     * @return void
     */
    public function updatesettingsAction() {
        $valArray = $this->request->getArguments();

        $this->usergroup->setCustominfotextstart($valArray['custominfotextstart']);
        $this->usergroup->setCustominfotextmail($valArray['custominfotextmail']);
        
        $this->userGroupRepository->update($this->usergroup);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        $this->addFlashMessage("Einstellungen gespeichert.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        
        $this->forward('editsettings', 'Backend', 'Iqtp13db');
    }
    
    /*************************************************************************/
    /********** NO ACTION FUNCTIONS - TODO: in Hilfsklasse auslagern **********/
    /*************************************************************************/
    
    /**
     * Set Filter
     */
    function setfilter(int $type, array $searchparams, $orderby, $order, $deleted, $limit) {
        // FILTER
        $beraterdiesergruppe = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->usergroup);
        
        if (isset($searchparams['filteran'])) {
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fuid', $searchparams['uid'] ?? '');
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fname', $searchparams['name'] ?? '');
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fort', $searchparams['ort'] ?? '');
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fberuf', $searchparams['beruf'] ?? '');
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fland', $searchparams['land'] ?? '');
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fberater', $searchparams['berater'] ?? '');            
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fberatername', $searchparams['berater'] ?? '');            
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fgruppe', $searchparams['gruppe'] ?? '');
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fbescheid', $searchparams['bescheid'] ?? ''); // antragstellungvorher
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'filtermodus', '1');
        } 
        $filtermodus = $searchparams['filtermodus'] ?? '1';
        if($filtermodus == '0') 
        {
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fuid', NULL);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fname', NULL);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fort', NULL);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fberuf', NULL);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fland', NULL);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fberater', NULL);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fgruppe', NULL);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'fbescheid', NULL); // antragstellungvorher
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'filtermodus', NULL);          
        }
        
        $f['uid'] = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fuid');
        $f['name'] = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fname');
        $f['ort'] = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fort');
        $f['beruf'] = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fberuf');
        $f['land'] = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fland');
        $f['berater'] = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fberater');
        $f['gruppe'] = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fgruppe');
        $f['bescheid'] = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fbescheid'); // antragstellungvorher
        
        if($f['land'] == '-1000' || $f['land'] == NULL) $f['land'] = '';
        if($f['berater'] == 0 || $f['berater'] == NULL) $f['berater'] = '';
        if($f['uid'] == '' && $f['name'] == '' && $f['ort'] == '' && $f['beruf'] == '' && $f['land'] == '' && $f['berater'] == '' && $f['gruppe'] == '' && $f['bescheid'] == '') {
            if($deleted == 1) {
                $teilnehmers = $this->teilnehmerRepository->findhidden4list($orderby, $order, $this->niqbid);
            } else {
                $teilnehmers = $this->teilnehmerRepository->findAllOrder4List($type, $orderby, $order, $this->niqbid);
            }
        } else {
            $berufearr = $this->berufeRepository->findAllOrdered('de');
            if($limit > 0) {
                $teilnehmers = $this->teilnehmerRepository->searchTeilnehmer($type, $f, $deleted, $this->niqbid, $berufearr, $orderby, $order, $this->usergroup, $limit);
            } else {
                $teilnehmers = $this->teilnehmerRepository->searchTeilnehmer($type, $f, $deleted, $this->niqbid, $berufearr, $orderby, $order, $this->usergroup, $limit);
            }            
            //if($this->user['username'] == 'admin') DebuggerUtility::var_dump($teilnehmers);
            $this->view->assign('filteruid', $f['uid']);
            $this->view->assign('filtername', $f['name']);
            $this->view->assign('filterort', $f['ort']);
            $this->view->assign('filterberuf', $f['beruf']);
            $this->view->assign('filterland', $f['land']);
            if($f['land'] != '') {
                $land = $this->staatenRepository->findStaatname($f['land']);                
                $this->view->assign('filterlandname', $land[0]->getTitel());
            }
            $this->view->assign('filterberater', $f['berater']);
            if($f['berater'] != '') {
                $berater = $this->beraterRepository->findByUid($f['berater']);
                $this->view->assign('filterberatername', $berater->getUsername());
            }
            $this->view->assign('filtergruppe', $f['gruppe']);
            $this->view->assign('filterbescheid', $f['bescheid']); // antragstellungvorher
            $this->view->assign('filteron', $GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus'));
        }
        
        // FILTER bis hier
        return $teilnehmers;
    }
    
    /**
     * createHistory
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @param string $property
     * @return void
     */
    public function createHistory(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer, $property)
    {
        if($teilnehmer->_isDirty($property)) {
            $history = new \Ud\Iqtp13db\Domain\Model\Historie();
            $berater = $this->beraterRepository->findAllBerater($this->settings['beraterstoragepid']);
            foreach($berater as $thisberater) {
                if($this->user['username'] == $thisberater->getUsername()) $history->setBerater($thisberater);
            }
            
            $history->setTeilnehmer($teilnehmer);
            $history->setProperty($property);
            
            $oldvalue = $teilnehmer->_getCleanProperty($property) ?? '';
            $newvalue = $teilnehmer->_getProperty($property) ?? '';
            
            $staaten = $this->staatenRepository->findByLangisocode('de');
            foreach($staaten as $staat) {
                $staatenarr[$staat->getStaatid()] = $staat->getTitel();
            }
            
            if($property == 'geburtsland' || $property == 'ersteStaatsangehoerigkeit' || $property == 'zweiteStaatsangehoerigkeit' || $property == 'wohnsitzNeinIn') $newvalue = $staatenarr[$newvalue];
            if($property == 'geschlecht') {
                if($newvalue == 2) $newvalue = 'männlich';
                if($newvalue == 1) $newvalue = 'weiblich';
                if($newvalue == 3) $newvalue = 'divers';
            }
            if($property == 'wohnsitzDeutschland' || $property == 'deutschkenntnisse' || $property == 'leistungsbezugjanein' || $property == 'einwAnerkstelle' || $property == 'einwPerson') {
                if($newvalue == 1) $newvalue = 'ja';
                if($newvalue == 2) $newvalue = 'nein';
            }
            if($property == 'aufenthaltsstatus') $newvalue = $this->settings['aufenthaltsstatus'][$newvalue];
            if($property == 'zertifikatSprachniveau') $newvalue = $this->settings['zertifikatlevel'][$newvalue];
            if($property == 'erwerbsstatus') $newvalue = $this->settings['erwerbsstatus'][$newvalue];
            if($property == 'leistungsbezug') $newvalue = $this->settings['leistungsbezug'][$newvalue];
            
            if($property == 'nameBeratungsstelle') $newvalue = $this->settings['beratungsstelle'][$newvalue];
            
            if($property == 'berater') {
                if($newvalue == 0) {
                    $newvalue = '-';
                } else {
                    $berater = $this->beraterRepository->findOneByUid($newvalue);                    
                    $newvalue = $berater ? $berater->getUsername() : '?';
                }
            }            
                 
            if($oldvalue == '-1000') $oldvalue = '-';
            if($oldvalue == '-1') $oldvalue = 'k.A.';
            
            if($newvalue == '-1000') $newvalue = '-';
            if($newvalue == '-1') $newvalue = 'k.A.';
                            
            $history->setOldvalue($oldvalue);
            $history->setNewvalue($newvalue);
            
            $this->historieRepository->add($history);
        }
    }
         
    
    /**
     * Check Beratungsstatus
     *
     * Beratungsstatus: 0 = angemeldet, 1 = Anmeldung bestätigt, 2 = Erstberatung Start, 3 = Erstberatung abgeschlossen, 4 = Archiviert (NIQ erfasst), 99 = Anmeldung nicht abgesendet
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer     
     * @return int
     */
    public function checkberatungsstatus(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer) {
        if($teilnehmer != NULL) {
            if($teilnehmer->getVerificationDate() == 0) {
                return 0;
            } else {
                if($teilnehmer->getBeratungsstatus() == 4) return 4;
                
                if($teilnehmer->getVerificationDate() > 0 && !$this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum()) && !$this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen())) return 1;
                
                if($teilnehmer->getVerificationDate() > 0 && $this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum()) && !$this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen())) return 2;
                
                if($teilnehmer->getVerificationDate() > 0 && $this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum()) && $this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen())) return 3;
            }
        }
        return 999;
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
