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
use Ud\Iqtp13db\Domain\Repository\HistorieRepository;
use Ud\Iqtp13db\Domain\Repository\BeraterRepository;
use Ud\Iqtp13db\Domain\Repository\AbschlussRepository;
use TYPO3\CMS\Core\Resource\StorageRepository;

require_once(Environment::getPublicPath() . '/' . 'typo3conf/ext/iqtp13db/Resources/Public/PHP/xlsxwriter.class.php');

/***
 *
 * This file is part of the "IQ Webapp Anerkennungserstberatung" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2022 Uli Dohmen <edv@whkt.de>, WHKT
 *
 ***/

/**
 * TeilnehmerController
 */
class TeilnehmerController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    
    protected $generalhelper, $niqinterface, $niqapiurl, $allusergroups, $usergroup, $niqbid, $groupbccmail;
    
    protected $userGroupRepository;
    protected $teilnehmerRepository;
    protected $folgekontaktRepository;
    protected $dokumentRepository;
    protected $historieRepository;
    protected $beraterRepository;
    protected $abschlussRepository;
    protected $storageRepository;
    
    public function __construct(UserGroupRepository $userGroupRepository, TeilnehmerRepository $teilnehmerRepository, FolgekontaktRepository $folgekontaktRepository, DokumentRepository $dokumentRepository, HistorieRepository $historieRepository, BeraterRepository $beraterRepository, AbschlussRepository $abschlussRepository, StorageRepository $storageRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
        $this->teilnehmerRepository = $teilnehmerRepository;
        $this->folgekontaktRepository = $folgekontaktRepository;
        $this->dokumentRepository = $dokumentRepository;
        $this->historieRepository = $historieRepository;
        $this->beraterRepository = $beraterRepository;
        $this->abschlussRepository = $abschlussRepository;
        $this->storageRepository = $storageRepository;
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
        if ($this->arguments->hasArgument('tnseite3')) {
            $this->arguments->getArgument('tnseite3')->getPropertyMappingConfiguration()->allowProperties('wieberaten');
            $this->arguments->getArgument('tnseite3')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('wieberaten', 'array');
        }
        
        /* Propertymapping bis hier */
        
        $this->allusergroups = $this->userGroupRepository->findAllGroups($this->settings['beraterstoragepid']);
        
        $this->generalhelper = new \Ud\Iqtp13db\Helper\Generalhelper();
        // **** NIQ deaktiviert **** $this->niqinterface = new \Ud\Iqtp13db\Helper\NiqInterface();
        // **** NIQ deaktiviert **** $this->niqapiurl = $this->settings['niqapiurl'];
        
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
            $this->forward('status', 'Teilnehmer', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'angemeldet') {
            $this->forward('listangemeldet', 'Teilnehmer', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'erstberatung') {
            $this->forward('listerstberatung', 'Teilnehmer', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'archiv') {
            $this->forward('listarchiv', 'Teilnehmer', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'export') {
            $this->forward('export', 'Teilnehmer', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'berater') {
            $this->forward('list', 'Berater', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'deleted') {
            $this->forward('listdeleted', 'Teilnehmer', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'anmeldung') {
            if($datum >= $wartungvon->getTimestamp() AND $datum <= $wartungbis->getTimestamp())
            {
                $this->forward('wartung', 'Teilnehmer', 'Iqtp13db');
            }
            else
            {
                $this->forward('startseite', 'Teilnehmer', 'Iqtp13db');
            }
        }
    }
    
    /*************************************************************************/
    /******************************* Backend *******************************/
    /*************************************************************************/
    
    /**
     * action status
     *
     * @param int $currentPage
     * @return void
     */
    public function statusAction(int $currentPage = 1)
    {
        // Seite "Übersicht"
        //DebuggerUtility::var_dump($this->settings);
        
        $valArray = $this->request->getArguments();
        
        $heute = date('Y-m-d');
        $diesesjahr = date('Y');
        $diesermonat = idate('m');
        $letztesjahr = idate('Y') - 1;
        
        for($i=1;$i<13;$i++) {
            $monatsnamen[$i] = date("M", mktime(0, 0, 0, $i, 1, $diesesjahr));
        }
        
        for($m = $diesermonat + 1; $m < 13; $m++) {
            $angemeldeteTN[$m] = $this->teilnehmerRepository->count4Status("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr, $this->niqbid);
            $qfolgekontakte[$m] = $this->folgekontaktRepository->count4Status("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr, $this->niqbid);
            $erstberatung[$m] = $this->teilnehmerRepository->count4StatusErstberatung("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr, $this->niqbid);
            $beratungfertig[$m] = $this->teilnehmerRepository->count4StatusBeratungfertig("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr, $this->niqbid);
            $niqerfasst[$m] =  $this->teilnehmerRepository->count4Statusniqerfasst("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr, $this->niqbid);
            
            $days4beratung[$m] =  $this->teilnehmerRepository->days4Beratungfertig("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr, $this->niqbid);
            $days4wartezeit[$m] =  $this->teilnehmerRepository->days4Wartezeit("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr, $this->niqbid);
        }
        for($m = 1; $m <= $diesermonat; $m++) {
            $angemeldeteTN[$m] = $this->teilnehmerRepository->count4Status("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr, $this->niqbid);
            $qfolgekontakte[$m] = $this->folgekontaktRepository->count4Status("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr, $this->niqbid);
            $erstberatung[$m] = $this->teilnehmerRepository->count4StatusErstberatung("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr, $this->niqbid);
            $beratungfertig[$m] = $this->teilnehmerRepository->count4StatusBeratungfertig("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr, $this->niqbid);
            $niqerfasst[$m] = $this->teilnehmerRepository->count4Statusniqerfasst("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr, $this->niqbid);
            
            $days4beratung[$m] =  $this->teilnehmerRepository->days4Beratungfertig("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr, $this->niqbid);
            $days4wartezeit[$m] = $this->teilnehmerRepository->days4Wartezeit("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr, $this->niqbid);
        }
        /*
         * durchschnittl. Tage Beratung abgeschl. und durchschnittl. Tage Wartezeit	berechnen
         */
        $anz4avgmonthb = 0;
        $anz4avgmonthw = 0;
        for($n = 1; $n <= 12; $n++) {
            $diffdaysb = 0;
            for($k = 0; $k < count($days4beratung[$n]); $k++) {
                $dat1 = new Datetime($days4beratung[$n][$k]->getBeratungdatum());
                $dat2 = new Datetime($days4beratung[$n][$k]->getErstberatungabgeschlossen());
                $diffdaysb += date_diff($dat1, $dat2)->format('%a');
            }
            
            if(count($days4beratung[$n]) > 0) {
                $totalavgmonthb[$n] = floatval($diffdaysb)/floatval(count($days4beratung[$n]));
                $anz4avgmonthb++;
            } else {
                $totalavgmonthb[$n] = '-';
            }
            
            $diffdaysw = 0;
            for($k = 0; $k < count($days4wartezeit[$n]); $k++) {
                if($days4wartezeit[$n][$k] != null) {
                    $dat1 = new DateTime();
                    $dat1->setTimestamp($days4wartezeit[$n][$k]->getVerificationDate());
                    $dat2 = new Datetime($days4wartezeit[$n][$k]->getBeratungdatum());
                    $diffdaysw += date_diff($dat1, $dat2)->format('%a');
                }
            }
            
            if(count($days4wartezeit[$n]) > 0) {
                $totalavgmonthw[$n] = floatval($diffdaysw)/floatval(count($days4wartezeit[$n]));
                $anz4avgmonthw++;
            } else {
                $totalavgmonthw[$n] = '-';
            }
        }
        
        ksort($angemeldeteTN);
        ksort($qfolgekontakte);
        ksort($erstberatung);
        ksort($beratungfertig);
        ksort($niqerfasst);
        ksort($days4beratung);
        ksort($days4wartezeit);
        
        $aktuelleanmeldungen = count($this->teilnehmerRepository->findAllOrder4Status(0, $this->niqbid)) + count($this->teilnehmerRepository->findAllOrder4Status(1, $this->niqbid));
        $aktuellerstberatungen = count($this->teilnehmerRepository->findAllOrder4Status(2, $this->niqbid));
        $aktuellberatungenfertig = count($teilnehmers = $this->teilnehmerRepository->findAllOrder4Status(3, $this->niqbid));
        $archivierttotal = count($this->teilnehmerRepository->findAllOrder4Status(4, $this->niqbid));
        
        // keine Berater vorhanden?
        $alleberater = $this->beraterRepository->findAllBerater($this->settings['beraterstoragepid']);
        if(count($alleberater) == 0) {
            $this->addFlashMessage('Es sind noch keine Berater:innen vorhanden. Bitte im Menü Berater*innen anlegen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        }
        
        $historie = $this->historieRepository->findAllDesc($this->niqbid);
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
        $paginator = new QueryResultPaginator($historie, $currentPage, 25);
        $pagination = new SimplePagination($paginator);
        
        // **** NIQ deaktiviert **** $niqdbstatus = $this->niqinterface->check_curl($this->niqapiurl) ? "<span style='color: green;'>erreichbar</span>" : "<span style='color: red;'>nicht erreichbar!</span>";
        $niqdbstatus = '';
        
        // ******************** EXPORT Statistik ****************************
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
        $rows[5] = $niqerfasst;
        array_unshift($rows[5], "davon NIQ erfasst");
        $rows[6] = $totalavgmonthw;
        array_unshift($rows[6], "durchschn. Tage Wartezeit");
        $rows[7] = $totalavgmonthb;
        array_unshift($rows[7], "durchschn. Tage Beratungsdauer");
        
        if (isset($valArray['statsexport']) && $valArray['statsexport'] == 'Statistik exportieren') {
            
            $filename = 'stats_' . date('Y-m-d_H-i', time()) . '.csv';
            header('Content-Encoding: UTF-8');
            header('Content-type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment;filename=' . $filename);
            echo "\xEF\xBB\xBF";
            $fp = fopen('php://output', 'w');
            
            for($i=0; $i < count($rows); $i++) {
                fputcsv($fp, $rows[$i]);
            }
            fclose($fp);
            exit;
        }
        // ******************** EXPORT Statistik bis hier ****************************
        
        $this->view->assignMultiple(
            [
                'monatsnamen'=> $monatsnamen,
                'aktmonat'=> $diesermonat-1,
                'angemeldeteTN'=> $angemeldeteTN,
                'SUMangemeldeteTN'=> array_sum($angemeldeteTN),
                'qfolgekontakte'=> $qfolgekontakte,
                'SUMqfolgekontakte'=>  array_sum($qfolgekontakte),
                'erstberatung'=> $erstberatung,
                'SUMerstberatung'=>  array_sum($erstberatung),
                'beratungfertig'=> $beratungfertig,
                'SUMberatungfertig'=>  array_sum($beratungfertig),
                'niqerfasst'=> $niqerfasst,
                'SUMniqerfasst'=>  array_sum($niqerfasst),
                'totalavgmonthb'=> $totalavgmonthb,
                'SUMtotalavgmonthb'=>  $anz4avgmonthb > 0 ? array_sum($totalavgmonthb)/$anz4avgmonthb : 0,
                'totalavgmonthw'=> $totalavgmonthw,
                'SUMtotalavgmonthw'=>  $anz4avgmonthw > 0 ? array_sum($totalavgmonthw)/$anz4avgmonthw : 0,
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
                'niqdbstatus' => $niqdbstatus
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
        //DebuggerUtility::var_dump($GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus'));
        //die;
        
        $valArray = $this->request->getArguments();
        if(!empty($valArray['callerpage'])) $currentPage = $valArray['callerpage'];
        
        if(empty($valArray['orderby'])) {
            // ANMERKUNG: Nach Telefonat mit T. Schiller Standardsortierung per Bestätigungsdatum (verificationDate)
            $orderby = 'verification_date';
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'listangemeldetorder', 'DESC');
            $order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listangemeldetorder');
        } else {
            $orderby = $valArray['orderby'];
            $order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listangemeldetorder');
        }
        
        if(isset($valArray['changeorder']) && $valArray['changeorder'] == 1) {
            $order = $order == 'DESC' ? 'ASC' : 'DESC';
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'listangemeldetorder', $order);
        }
        
        $teilnehmer = $this->setfilter(0, $valArray, $orderby, $order, 0);
        
        // Wegen Bug in Paginator, der nicht mit Custom SQL Queryresults funktioniert, werden hier alle gefilterten Einträge auf einer Seite dargestellt. Queryresultpaginator hat dann keine Auswahl an Datensätzen, sondern alle.
        $anzperpag = $GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus') == '1' ? 250 : 25;
        
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
        $paginator = new QueryResultPaginator($teilnehmer, $currentPage, $anzperpag);
        $pagination = new SimplePagination($paginator);
        
        $teilnehmerpag = $paginator->getPaginatedItems();
              
        for($j=0; $j < count($teilnehmerpag); $j++) {
            $anz = $this->teilnehmerRepository->findDublette4Angemeldet($teilnehmerpag[$j]->getNachname(), $teilnehmerpag[$j]->getVorname(), $this->niqbid);
            if($anz > 1) $teilnehmerpag[$j]->setDublette(TRUE);
            $abschluesse[$j] = $this->abschlussRepository->findByTeilnehmer($teilnehmerpag[$j]);
        }
        
        $wohnsitzstaaten = $this->settings['staaten'];
        unset($wohnsitzstaaten[201]);
        
        $this->view->assignMultiple(
            [
                'anzgesamt' => count($teilnehmer),
                'abschluesse' => $abschluesse,
                'calleraction' => 'listangemeldet',
                'callercontroller' => 'Teilnehmer',
                'callerpage' => $currentPage,
                'paginator' => $paginator,
                'pagination' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
                'orderby' => $orderby,
                'wohnsitzstaaten' => $wohnsitzstaaten
            ]
            );
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
        if(!empty($valArray['callerpage'])) $currentPage = $valArray['callerpage'];
        
        if(empty($valArray['orderby'])) {
            $orderby = 'beratungdatum';
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'listerstberatungorder', 'DESC');
            $order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listerstberatungorder');
        } else {
            $orderby = $valArray['orderby'];
            $order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listerstberatungorder');
        }
        
        if(isset($valArray['changeorder']) && $valArray['changeorder'] == 1) {
            $order = $order == 'DESC' ? 'ASC' : 'DESC';
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'listerstberatungorder', $order);
        }
        
        $teilnehmer = $this->setfilter(3, $valArray, $orderby, $order, 0);
        
        // Wegen Bug in Paginator, der nicht mit Custom SQL Queryresults funktioniert, werden hier alle gefilterten Einträge auf einer Seite dargestellt. Queryresultpaginator hat dann keine Auswahl an Datensätzen, sondern alle.
        $anzperpag = $GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus') == '1' ? 250 : 25;
        
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
        $paginator = new QueryResultPaginator($teilnehmer, $currentPage, $anzperpag);
        $pagination = new SimplePagination($paginator);
        
        $teilnehmerpag = $paginator->getPaginatedItems();
        
        $anzfolgekontakte = array();
        $abschluesse = array();
        $niqstatusberatung = '';
        $niqwasfehlt = '';
        
        foreach ($teilnehmerpag as $key => $tn) {
            $anzfolgekontakte[$key] = count($this->folgekontaktRepository->findByTeilnehmer($tn->getUid()));
            
            $abschluesse[$key] = $this->abschlussRepository->findByTeilnehmer($tn);
            // **** NIQ deaktiviert **** $niqstat = $this->niqinterface->niqstatus($tn, $abschluesse[$key]);
            $niqstat = '';
            
            if($niqstat == 0) {
                $niqstatusberatung[$key] = 'rot';
            } elseif($niqstat == 2) {
                $niqstatusberatung[$key] = 'gelb';
            } elseif($niqstat == 1) {
                $niqstatusberatung[$key] = 'gruen';
            } else {
                $niqstatusberatung[$key] = '';
            }
            
            // **** NIQ deaktiviert **** if($niqstat == 0 || $niqstat == 2) $niqwasfehlt[$key] = $this->niqinterface->niqwasfehlt($tn, $abschluesse[$key]);
            
        }
        $folgekontakte = $this->folgekontaktRepository->findAll4List($this->niqbid);
        
        //DebuggerUtility::var_dump($anzfolgekontakte);
        //die;
        
        $berufeliste = $this->settings['berufe'];
        $wohnsitzstaaten = $this->settings['staaten'];
        unset($wohnsitzstaaten[201]);
        
        $this->view->assignMultiple(
            [
                'anzgesamt' => count($teilnehmer),
                'anzfolgekontakte' => $anzfolgekontakte,
                'niqstatuus' => $niqstatusberatung,
                'niqwasfehlt' => $niqwasfehlt,
                'folgekontakte' => $folgekontakte,
                'abschluesse' => $abschluesse,
                'calleraction' => 'listerstberatung',
                'callercontroller' => 'Teilnehmer',
                'callerpage' => $currentPage,
                'paginator' => $paginator,
                'pagination' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
                'orderby' => $orderby,
                'wohnsitzstaaten' => $wohnsitzstaaten,
                'berufe' => $berufeliste
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
        
        
        if(!empty($valArray['callerpage'])) $currentPage = $valArray['callerpage'];
        
        
        if(empty($valArray['orderby'])) {
            $orderby = 'erstberatungabgeschlossen';
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'listarchivorder', 'DESC');
            $order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listarchivorder');
        } else {
            $orderby = $valArray['orderby'];
            $order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listarchivorder');
        }
        
        if(isset($valArray['changeorder']) && $valArray['changeorder'] == 1) {
            $order = $order == 'DESC' ? 'ASC' : 'DESC';
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'listarchivorder', $order);
        }
        
        $teilnehmer = $this->setfilter(4, $valArray, $orderby, $order, 0);
        
        // Wegen Bug in Paginator, der nicht mit Custom SQL Queryresults funktioniert, werden hier alle gefilterten Einträge auf einer Seite dargestellt. Queryresultpaginator hat dann keine Auswahl an Datensätzen, sondern alle.
        $anzperpag = $GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus') == '1' ? 250 : 25;
        
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
        $paginator = new QueryResultPaginator($teilnehmer, $currentPage, $anzperpag);
        $pagination = new SimplePagination($paginator);
        
        $teilnehmerpag = $paginator->getPaginatedItems();
        
        $anzfolgekontakte = '';
        $abschluesse = array();
        $niqstatusberatung = '';
        $niqwasfehlt = '';
        foreach ($teilnehmerpag as $key => $tn) {
            $anzfolgekontakte[$key] = count($this->folgekontaktRepository->findByTeilnehmer($tn->getUid()));
            
            $abschluesse[$key] = $this->abschlussRepository->findByTeilnehmer($tn);
            // **** NIQ deaktiviert ****  $niqstat = $this->niqinterface->niqstatus($tn, $abschluesse[$key]);
            if($niqstat == 0) {
                $niqstatusberatung[$key] = 'rot';
            } elseif($niqstat == 2) {
                $niqstatusberatung[$key] = 'gelb';
            } elseif($niqstat == 1) {
                $niqstatusberatung[$key] = 'gruen';
            } else {
                $niqstatusberatung[$key] = '';
            }
            
            // **** NIQ deaktiviert **** if($niqstat == 0 || $niqstat == 2) $niqwasfehlt[$key] = $this->niqinterface->niqwasfehlt($tn, $abschluesse[$key]);
        }
        $folgekontakte = $this->folgekontaktRepository->findAll4List($this->niqbid);
        
        $berufeliste = $this->settings['berufe'];
        $wohnsitzstaaten = $this->settings['staaten'];
        unset($wohnsitzstaaten[201]);
        
        $this->view->assignMultiple(
            [
                'anzgesamt' => count($teilnehmer),
                'anzfolgekontakte' => $anzfolgekontakte,
                'niqstatuus' => $niqstatusberatung,
                'niqwasfehlt' => $niqwasfehlt,
                'folgekontakte' => $folgekontakte,
                'abschluesse' => $abschluesse,
                'calleraction' => 'listarchiv',
                'callercontroller' => 'Teilnehmer',
                'callerpage' => $currentPage,
                'paginator' => $paginator,
                'pagination' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
                'orderby' => $orderby,
                'wohnsitzstaaten' => $wohnsitzstaaten,
                'berufe' => $berufeliste
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
        if(!empty($valArray['callerpage'])) $currentPage = $valArray['callerpage'];
        
        if(empty($valArray['orderby'])) {
            $orderby = 'crdate';
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'listdeletedorder', 'DESC');
            $order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listdeletedorder');
        } else {
            $orderby = $valArray['orderby'];
            $order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listdeletedorder');
        }
        
        if(isset($valArray['changeorder']) && $valArray['changeorder'] == 1) {
            $order = $order == 'DESC' ? 'ASC' : 'DESC';
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'listdeletedorder', $order);
        }
        
        $teilnehmer = $this->setfilter(999, $valArray, $orderby, $order, 1);
        
        // Wegen Bug in Paginator, der nicht mit Custom SQL Queryresults funktioniert, werden hier alle gefilterten Einträge auf einer Seite dargestellt. Queryresultpaginator hat dann keine Auswahl an Datensätzen, sondern alle.
        $anzperpag = $GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus') == '1' ? 250 : 25;
        
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
        $paginator = new QueryResultPaginator($teilnehmer, $currentPage, $anzperpag);
        $pagination = new SimplePagination($paginator);
        
        $teilnehmerpag = $paginator->getPaginatedItems();
        
        $abschluesse = array();
        for($j=0; $j < count($teilnehmerpag); $j++) {
            $anz = $this->teilnehmerRepository->findDublette4Deleted($teilnehmerpag[$j]->getNachname(), $teilnehmerpag[$j]->getVorname(), $this->niqbid);
            if($anz > 1) $teilnehmerpag[$j]->setDublette(TRUE);
            $abschluesse[$j] = $this->abschlussRepository->findByTeilnehmer($teilnehmerpag[$j]);
        }
        
        $wohnsitzstaaten = $this->settings['staaten'];
        unset($wohnsitzstaaten[201]);
        
        $this->view->assignMultiple(
            [
                'anzgesamt' => count($teilnehmer),
                'abschluesse' => $abschluesse,
                'calleraction' => 'listdeleted',
                'callercontroller' => 'Teilnehmer',
                'callerpage' => $currentPage,
                'paginator' => $paginator,
                'pagination' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
                'orderby' => $orderby,
                'wohnsitzstaaten' => $wohnsitzstaaten
            ]
            );
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
        
        $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
        $historie = $this->historieRepository->findByTeilnehmerOrdered($teilnehmer->getUid());
        $dokumente = $this->dokumentRepository->findByTeilnehmer($teilnehmer);
        $dokumentpfad = $this->generalhelper->sanitizeFileFolderName($teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid(). '/');
        
        //DebuggerUtility::var_dump($teilnehmer->getBeratungdatum());
        
        $this->view->assignMultiple(
            [
                'dokumente' => $dokumente,
                'dokumentpfad' => $dokumentpfad,
                'calleraction' => $valArray['calleraction'],
                'callercontroller' => $valArray['callercontroller'],
                'callerpage' => $valArray['callerpage'],
                'historie' => $historie,
                'teilnehmer' => $teilnehmer,
                'abschluesse' => $abschluesse,
                'showabschluesse' => $valArray['showabschluesse'],
                'showdokumente' => $valArray['showdokumente']
            ]
            );
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
        
        //DebuggerUtility::var_dump($alleberater);
        //die;
        
        $staatsangehoerigkeitstaaten = $this->settings['staaten'];
        $wohnsitzstaaten = $this->settings['staaten'];
        unset($wohnsitzstaaten[201]);
        
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
        
        $this->view->assignMultiple(
            [
                'altervonbis' => $altervonbis,
                'calleraction' => $valArray['calleraction'],
                'callercontroller' => $valArray['callercontroller'],
                'callerpage' => $valArray['callerpage'],
                'staatsangehoerigkeitstaaten' => $staatsangehoerigkeitstaaten,
                'abschluss' => $abschluss,
                'wohnsitzstaaten' => $wohnsitzstaaten,
                'alleberater' => $alleberater,
                'berater' => $this->user,
                'settings' => $this->settings,
                'jahre' => $jahre
            ]
            );
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
        
        $teilnehmer->setNiqidberatungsstelle($this->niqbid);
        
        $this->teilnehmerRepository->add($teilnehmer);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        $tfolder = $this->generalhelper->createFolder($teilnehmer, $this->settings['standardniqidberatungsstelle'], $this->allusergroups, $this->storageRepository->findAll());
        
        $valArray = $this->request->getArguments();
        $this->redirect('edit', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer, 'showabschluesse' => '1', 'calleraction' => $valArray['calleraction'], 'callercontroller' => $valArray['callercontroller'], 'callerpage' => $valArray['callerpage']));
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
        
        //DebuggerUtility::var_dump($valArray);
        
        $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
                
        $alleberater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->user['usergroup']);
        
        $dokumente = $this->dokumentRepository->findByTeilnehmer($teilnehmer);
        $dokumentpfad = $this->generalhelper->sanitizeFileFolderName($teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid(). '/');
        
        $staatsangehoerigkeitstaaten = $this->settings['staaten'];
        $wohnsitzstaaten = $this->settings['staaten'];
        unset($wohnsitzstaaten[201]);
        
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
        $this->view->assignMultiple(
            [
                'altervonbis' => $altervonbis,
                'calleraction' => $valArray['calleraction'],
                'callercontroller' => $valArray['callercontroller'],
                'callerpage' => $valArray['callerpage'],
                'staatsangehoerigkeitstaaten' => $staatsangehoerigkeitstaaten,
                'abschluesse' => $abschluesse,
                'alleberater' => $alleberater,
                'berater' => $this->user,
                'settings' => $this->settings,
                'wohnsitzstaaten' => $wohnsitzstaaten,
                'teilnehmer' => $teilnehmer,
                'dokumente' => $dokumente,
                'dokumentpfad' => $dokumentpfad,
                'abschlusshinzu' => $abschlusshinzu,
                'jahre' => $jahre,
                'showabschluesse' => $valArray['showabschluesse'],
                'showdokumente' => $valArray['showdokumente']
            ]
            );
    }
    
    /**
     * action update
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function updateAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();
        
        //DebuggerUtility::var_dump($valArray);
        //die;
        
        if(is_numeric($teilnehmer->getLebensalter())) {
            if($teilnehmer->getLebensalter() > 0 && ($teilnehmer->getLebensalter() < 15 || $teilnehmer->getLebensalter() > 80)) {
                $this->addFlashMessage("Datensatz NICHT gespeichert. Lebensalter muss zwischen 15 und 80 oder k.A. sein.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']));
            }
        }
        
        if($teilnehmer->getVerificationDate() == 0 && ($this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen()) || $this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum()))) {
            $this->addFlashMessage("Datensatz NICHT gespeichert. Vor Eintragung von -Datum Erstberatung- oder -Erstberatung abgeschlossen- muss die Anmeldung bestätigt werden!", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']));
        }
        
        if($this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen()) && !$this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum())) {
            $this->addFlashMessage("Datensatz NICHT gespeichert. -Datum Erstberatung– muss eingetragen sein, wenn -Erstberatung abgeschlossen- ausgefüllt ist.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']));            
        }
        
        $this->createHistory($teilnehmer, "niqchiffre");
        $this->createHistory($teilnehmer, "schonberaten");
        $this->createHistory($teilnehmer, "schonberatenvon");
        $this->createHistory($teilnehmer, "nachname");
        $this->createHistory($teilnehmer, "vorname");
        $this->createHistory($teilnehmer, "plz");
        $this->createHistory($teilnehmer, "ort");
        $this->createHistory($teilnehmer, "email");
        $this->createHistory($teilnehmer, "telefon");
        $this->createHistory($teilnehmer, "lebensalter");
        $this->createHistory($teilnehmer, "geburtsland");
        $this->createHistory($teilnehmer, "geschlecht");
        $this->createHistory($teilnehmer, "ersteStaatsangehoerigkeit");
        $this->createHistory($teilnehmer, "zweiteStaatsangehoerigkeit");
        $this->createHistory($teilnehmer, "einreisejahr");
        $this->createHistory($teilnehmer, "wohnsitzDeutschland");
        $this->createHistory($teilnehmer, "wohnsitzNeinIn");
        $this->createHistory($teilnehmer, "sonstigerstatus");
        $this->createHistory($teilnehmer, "deutschkenntnisse");
        $this->createHistory($teilnehmer, "zertifikatdeutsch");
        $this->createHistory($teilnehmer, "zertifikatSprachniveau");
        
        // TODO: ggf. hier Daten aus der Tabelle Abschluss einfügen
        $this->createHistory($teilnehmer, "erwerbsstatus");
        $this->createHistory($teilnehmer, "leistungsbezugjanein");
        $this->createHistory($teilnehmer, "leistungsbezug");
        $this->createHistory($teilnehmer, "name_beraterAA");
        $this->createHistory($teilnehmer, "kontakt_beraterAA");
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
        $this->createHistory($teilnehmer, "aufenthaltsstatus");
        $this->createHistory($teilnehmer, "aufenthaltsstatusfreitext");
        $this->createHistory($teilnehmer, "nameBeratungsstelle");
        $this->createHistory($teilnehmer, "wieberaten");
        $this->createHistory($teilnehmer, "notizen");
        $this->createHistory($teilnehmer, "anerkennungszuschussbeantragt");
        $this->createHistory($teilnehmer, "kooperationgruppe");
        $this->createHistory($teilnehmer, "beratungdatum");
        $this->createHistory($teilnehmer, "berater");
        $this->createHistory($teilnehmer, "beratungsart");
        $this->createHistory($teilnehmer, "beratungsartfreitext");
        $this->createHistory($teilnehmer, "beratungsort");
        $this->createHistory($teilnehmer, "beratungsdauer");
        $this->createHistory($teilnehmer, "beratungzu");
        $this->createHistory($teilnehmer, "anerkennendestellen");
        $this->createHistory($teilnehmer, "anerkennungsberatung");
        $this->createHistory($teilnehmer, "anerkennungsberatungfreitext");
        $this->createHistory($teilnehmer, "qualifizierungsberatung");
        $this->createHistory($teilnehmer, "qualifizierungsberatungfreitext");
        $this->createHistory($teilnehmer, "beratungnotizen");
        $this->createHistory($teilnehmer, "erstberatungabgeschlossen");
        
        $bstatus = $this->checkberatungsstatus($teilnehmer);
        if($bstatus == 999) {
            $this->addFlashMessage("Fehler in Update-Routine -> beratungsstatus = 999. Bitte Admin informieren.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        }
        
        $teilnehmer->setNiqidberatungsstelle($this->niqbid);
        $teilnehmer->setBeratungsstatus($bstatus);
        $this->teilnehmerRepository->update($teilnehmer);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']));
    }

/**
 * action delete
 *
 * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
 * @return void
 */
public function deleteAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
{
    $valArray = $this->request->getArguments();
    
    if($teilnehmer->getNiqchiffre() == '') {
        $teilnehmer->setHidden(1);
        
        $this->teilnehmerRepository->update($teilnehmer);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
    } else {
        $this->addFlashMessage('Bereits in NIQ übertragene Datensätze können nicht gelöscht werden.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
    }
    $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']));
}

/**
 * action undelete
 *
 * @param int $tnuid
 * @return void
 */
public function undeleteAction($tnuid)
{
    $teilnehmer = $this->teilnehmerRepository->findHiddenByUid($tnuid);
    $teilnehmer->setHidden(0);
    
    $this->teilnehmerRepository->update($teilnehmer);
    
    // Daten sofort in die Datenbank schreiben
    $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
    $persistenceManager->persistAll();
    
    $this->redirect('listdeleted');
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
    
    $bcc = $this->groupbccmail;
    $sender = $this->settings['sender'];
    if($bcc == '' || $sender == '') {
        $this->addFlashMessage('Fehler 101 in askconsent.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->redirect('listangemeldet', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
    } else {
        $recipient = $teilnehmer->getEmail();
        if($recipient == '') {
            $this->addFlashMessage('Keine E-Mail-Adresse eingetragen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect('listangemeldet', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
        }
        $templateName = 'Mailtoconfirm';
        $confirmmailtext1 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmmailtext1', 'Iqtp13db');
        $confirmlinktext = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmlinktext', 'Iqtp13db');
        $confirmmailtext2 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmmailtext2', 'Iqtp13db');
        $subject = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmsubject', 'Iqtp13db');
        
        $variables = array(
            'teilnehmer' => $teilnehmer,
            'confirmmailtext1' => $confirmmailtext1,
            'confirmlinktext' => $confirmlinktext,
            'confirmmailtext2' => $confirmmailtext2,
            'startseitelink' => $this->settings['startseitelink'],
            'logolink' => $this->settings['logolink'],
            'registrationpageuid' => $this->settings['registrationpageuid'],
            'askconsent' => '1',
            'baseurl' => $this->request->getBaseUri()
        );
        
        //DebuggerUtility::var_dump($variables);
        //die;
        
        $this->sendTemplateEmail(array($recipient), array($bcc), array($sender), $subject, $templateName, $variables, false);
        
        $this->addFlashMessage('Einwilligungsanforderung versendet.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        
        $this->redirect('listangemeldet', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer, 'callerpage' => $valArray['callerpage']));
    }
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
            $this->redirect('show', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
        }
    }
    
    $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
    $dokumente = $this->dokumentRepository->findByTeilnehmer($teilnehmer);
    
    $thisdate = new DateTime();
    $zeitstempel = $thisdate->format('d.m.Y - H:i:s');
    
    $this->view->assign('teilnehmer', $teilnehmer);
    $this->view->assign('abschluesse', $abschluesse);
    $this->view->assign('dokumente', $dokumente);
    
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
    
    $pfad = $this->generalhelper->createFolder($teilnehmer, $this->settings['standardniqidberatungsstelle'], $this->allusergroups, $this->storageRepository->findAll());
    $filename = 'DB-' .$this->generalhelper->sanitizeFileFolderName($teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid()). '.pdf';
    $storage = $this->generalhelper->getTP13Storage( $this->storageRepository->findAll());
    
    $niqbid = $this->niqbid;
    $beratungsstellenfolder = $niqbid == '' ? 'Beratene' : $niqbid;
    $fullpath = $storage->getConfiguration()['basePath']. '/' .$beratungsstellenfolder. '/' .$pfad->getName().'/'. $filename;
    
    $mpdf->Output($fullpath, 'F');
    
  
    // ******* Als Dokument speichern, damit aus Webapp abrufbar *******
    $dokument = new \Ud\Iqtp13db\Domain\Model\Dokument();
    
    $dokument->setBeschreibung("DATENBLATT");
    $dokument->setName($filename);
    $dokument->setPfad($beratungsstellenfolder. '/' .$pfad->getName().'/');
    $dokument->setTeilnehmer($teilnehmer);
    
    $this->dokumentRepository->add($dokument);
    
    //Daten sofort in die Datenbank schreiben
    $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
    $persistenceManager->persistAll();
    //********************************************************************
    
    
    $this->addFlashMessage('Datenblatt wurde in '.$pfad->getIdentifier().' erstellt.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
    
    $this->redirect('show', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer, 'callerpage' => $valArray['callerpage'], 'showdokumente' => '1'));
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
    
    //DebuggerUtility::var_dump($valArray);
    //die;
    
    //$filtervondate = new DateTime($valArray['filtervon'].' 00:00');
    
    $filtervon = isset($valArray['filtervon']) ? $valArray['filtervon'] : '01.01.1970';
    $filterbis = isset($valArray['filtervon']) ? $valArray['filterbis'] : '31.12.2099';
    
    $arrjanein = array(0 => 'keine Angabe', 1 => 'ja', 2 => 'nein');
    $arrerwerbsstatus = $this->settings['erwerbsstatus'];
    $arrleistungsbezug = $this->settings['leistungsbezug'];
    $arrstaaten = $this->settings['staaten'];
    $arraufenthaltsstatus = $this->settings['aufenthaltsstatus'];
    $arrberatungsart = $this->settings['beratungsart'];
    $arranerkennungsberatung = $this->settings['anerkennungsberatung'];
    $arrqualifizierungsberatung = $this->settings['qualifizierungsberatung'];
    $arrberatungsstelle = $this->settings['beratungsstelle'];
    //$arrberatungzu = $this->settings['beratungzu'];
    $arrberufe = $this->settings['berufe'];
    $arrabschlussart =  array('-1' => 'keine Angabe', '1' => 'Ausbildungsabschluss', '2' => 'Universitätsabschluss');
    $arrantragstellungerfolgt = $this->settings['antragstellungerfolgt'];
    
    $orderby = 'crdate';
    $order = 'ASC';
    $fberatungsstatus = isset($valArray['filterberatungsstatus']) ? $valArray['filterberatungsstatus'] : '';
    
    if($fberatungsstatus == 1 || $fberatungsstatus == NULL) {
        $teilnehmers = array();
    } elseif($fberatungsstatus == 13) {
        $teilnehmers = $this->teilnehmerRepository->search4exportTeilnehmer(4, 0, $filtervon, $filterbis, $this->niqbid);
    } elseif($fberatungsstatus == 12) {
        $teilnehmers = $this->teilnehmerRepository->search4exportTeilnehmer(2, 0, $filtervon, $filterbis, $this->niqbid);
    } elseif($fberatungsstatus == 11) {
        $teilnehmers = $this->teilnehmerRepository->search4exportTeilnehmer(1, 0, $filtervon, $filterbis, $this->niqbid);        
    } else {
        $teilnehmers = $this->teilnehmerRepository->search4exportTeilnehmer(0, 1, $filtervon, $filterbis, $this->niqbid);
    }
    
    foreach ($teilnehmers as $akey => $atn) {
        $anzfolgekontakte[$akey] = count($this->folgekontaktRepository->findByTeilnehmer($atn->getUid()));
        $abschluesse[$akey] = $this->abschlussRepository->findByTeilnehmer($atn);
    }
    
    // ******************** EXPORT ****************************
    if (isset($valArray['export']) && $fberatungsstatus != '') {
        
        //$x = 0;
        foreach($teilnehmers as $x => $tn) {
            $props = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getGettablePropertyNames($tn);
            
            $berater = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'berater');
            
            foreach ($props as $prop) {
                $rows[$x]['verificationDate'] = date('d.m.Y H:i:s', \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'verificationDate'));
                $rows[$x]['Nachname'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'nachname');
                $rows[$x]['Vorname'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'vorname');
                $rows[$x]['PLZ'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'plz');
                $rows[$x]['Ort'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'ort');
                $rows[$x]['Email'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'email');
                $rows[$x]['Telefon'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'telefon');
                $rows[$x]['erwerbsstatus'] = $arrerwerbsstatus[\TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'erwerbsstatus')];
                $rows[$x]['Leistungsbezugjanein'] = $arrjanein[\TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'leistungsbezugjanein')];
                $rows[$x]['Leistungsbezug'] = $arrleistungsbezug[\TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'leistungsbezug')];
                $rows[$x]['Geburtsland'] = $arrstaaten[\TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'geburtsland')];
                $rows[$x]['aufenthaltsstatus'] = $arraufenthaltsstatus[\TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'aufenthaltsstatus')];
                $geschlecht = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'geschlecht');
                if($geschlecht == 1) $geschlecht = 'w';
                if($geschlecht == 2) $geschlecht = 'm';
                if($geschlecht == 3) $geschlecht = 'd';
                $rows[$x]['Geschlecht'] = $geschlecht;
            }
            
            if($berater != NULL) {
                $rows[$x]['Beraterin'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($berater, 'username');
            }
            
            foreach (\TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'beratungsart') as $atn) $rows[$x]['beratungsart'] .= $arrberatungsart[$atn]." ";
            foreach (\TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'anerkennungsberatung') as $atn) $rows[$x]['anerkennungsberatung'] .= $arranerkennungsberatung[$atn]." ";
            foreach (\TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'qualifizierungsberatung') as $atn) $rows[$x]['qualifizierungsberatung'] .= $arrqualifizierungsberatung[$atn]." ";
            $rows[$x]['nameberatungsstelle'] = $arrberatungsstelle[\TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'name_beratungsstelle')];
            $rows[$x]['beratungzuschulabschluss'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'beratungzu');
            
            $rows[$x]['AnzFolgekontakte'] = $anzfolgekontakte[$x];
            
            foreach($abschluesse[$x] as $y => $abschluss) {
                $aprops = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getGettablePropertyNames($abschluss);
                
                $rows[$x]['Abschluss'.$y.' Referenzberufzugewiesen'] = $arrberufe[\TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'referenzberufzugewiesen')];
                foreach (\TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'abschlussart') as $atn) $rows[$x]['Abschluss'.$y.' Abschlussart'] .= $arrabschlussart[$atn]." ";
                $rows[$x]['Abschluss'.$y.' Erwerbsland'] = $arrstaaten[\TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'erwerbsland')];
                $rows[$x]['Abschluss'.$y.' Antragstellungerfolgt'] = $arrantragstellungerfolgt[\TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($abschluss, 'antragstellungerfolgt')];
                
            }
            //$x++;
        }
        
        $bezbstatus = $this->settings['filterberatungsstatus'][$fberatungsstatus];
        
        // XLSX
        $filename = 'export_'.$bezbstatus.'_'.date('Y-m-d_H-i', time()).'.xlsx';
        $header = [
            'Bestätigungsdatum' => 'string',
            'Nachname' => 'string',
            'Vorname' => 'string',
            'PLZ' => 'string',
            'Ort' => 'string',
            'E-Mail' => 'string',
            'Telefon' => 'string',
            'Erwerbsstatus' => 'string',
            'Leistungsbezug ja/nein' => 'string',
            'Leistungsbezug' => 'string',
            'Geburtsland' => 'string',
            'Aufenthaltsstatus' => 'string',
            'Geschlecht' => 'string',
            'Berater:in' => 'string',
            'Beratungsart' => 'string',
            'Anerkennungsberatung' => 'string',
            'Qualifizierungsberatung' => 'string',
            'Beratungsstelle' => 'string',
            'Beratung zu Schulabschluss' => 'string',
            'Anz. Folgekontakte' => 'string',
            'Abschluss1 Referenzberuf zugewiesen' => 'string',
            'Abschluss1 Abschlussart' => 'string',
            'Abschluss1 Erwerbsland' => 'string',
            'Abschluss1 Antragstellung erfolgt' => 'string',
            'Abschluss2 Referenzberuf zugewiesen' => 'string',
            'Abschluss2 Abschlussart' => 'string',
            'Abschluss2 Erwerbsland' => 'string',
            'Abschluss2 Antragstellung erfolgt' => 'string',
            'Abschluss3 Referenzberuf zugewiesen' => 'string',
            'Abschluss3 Abschlussart' => 'string',
            'Abschluss3 Erwerbsland' => 'string',
            'Abschluss3 Antragstellung erfolgt' => 'string',
            'Abschluss4 Referenzberuf zugewiesen' => 'string',
            'Abschluss4 Abschlussart' => 'string',
            'Abschluss4 Erwerbsland' => 'string',
            'Abschluss4 Antragstellung erfolgt' => 'string'
            
        ];
        
        $writer = new \XLSXWriter();
        $writer->setAuthor('IQ Webapp');
        $writer->writeSheet($rows, 'Blatt1', $header);  // with headers
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $writer->writeToStdOut();
        exit;
        
    } elseif(isset($valArray['export']) && $valArray['export'] && $fberatungsstatus == '') {
        
        $this->addFlashMessage("Bitte Status für Export auswählen.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->view->assignMultiple(
            [
                'anzgesamt' => count($teilnehmers),
                'calleraction' => 'export',
                'callercontroller' => 'Teilnehmer',
                'callerpage' => $currentPage,
                'filterberatungsstatus' => $fberatungsstatus,
                'filteron' => $GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus')
            ]
            );
    } else {
        $filtervon = isset($valArray['filtervon']) ? $valArray['filtervon'] : '';
        $filterbis = isset($valArray['filterbis']) ? $valArray['filterbis'] : '';
        $this->view->assignMultiple(
            [
                'anzgesamt' => count($teilnehmers),
                'calleraction' => 'export',
                'callercontroller' => 'Teilnehmer',
                'callerpage' => $currentPage,
                'filterberatungsstatus' => $fberatungsstatus,
                'filteron' => $GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus'),
                'filtervon' => $filtervon,
                'filterbis' => $filterbis
            ]
            );
    }
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
        //$berater = new \Ud\Iqtp13db\Domain\Model\Berater($this->user['username']);
        $berater = $this->beraterRepository->findAllBerater($this->settings['beraterstoragepid']);
        foreach($berater as $thisberater) {
            if($this->user['username'] == $thisberater->getUsername()) $history->setBerater($thisberater);
        }
        //DebuggerUtility::var_dump($berater);
        //die;
        
        $history->setTeilnehmer($teilnehmer);
        $history->setProperty($property);
        $history->setOldvalue($teilnehmer->_getCleanProperty($property));
        $history->setNewvalue($teilnehmer->_getProperty($property));
        
        
        $this->historieRepository->add($history);
    }
}

/*************************************************************************/
/******************************* ANMELDUNG *******************************/
/*************************************************************************/

/**
 * action startseite
 *
 * @return void
 */
public function startseiteAction()
{
    $valArray = $this->request->getArguments();
    $beratungsstellenname = $valArray['beratung'] ?? '';
    if($beratungsstellenname != '') {
        foreach ($this->allusergroups as $group) {
            if(strtolower($group->getTitle()) == $beratungsstellenname) {
                $this->view->assign('beratungsstelle', $group->getNiqbid());
                $GLOBALS['TSFE']->fe_user->setKey('ses', 'beratungsstellenid', $group->getNiqbid());
            }
        }
    } else {
        $this->view->assign('beratungsstelle', $GLOBALS['TSFE']->fe_user->getKey('ses', 'beratungsstellenid'));
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
    
    $staatsangehoerigkeitstaaten = $this->settings['staaten'];
    $wohnsitzstaaten = $this->settings['staaten'];
    unset($wohnsitzstaaten[201]);
    
    $altervonbis[-1000] = '-';
    $altervonbis[-1] = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('ka', 'iqtp13db');
    for ($i = 15; $i <= 80; $i++) {
        $altervonbis[$i] = $i;
    }
    
    $this->view->assignMultiple(
        [
            'altervonbis' => $altervonbis,
            'staatsangehoerigkeitstaaten' => $staatsangehoerigkeitstaaten,
            'wohnsitzstaaten' => $wohnsitzstaaten,
            'tnseite1' => $tnseite1,
            'settings' => $this->settings,
            'beratungsstelle' => $GLOBALS['TSFE']->fe_user->getKey('ses', 'beratungsstellenid')
        ]
        );
}

/**
 * action anmeldseite1redirect
 *
 * @param \Ud\Iqtp13db\Domain\Model\TNSeite1 $tnseite1
 * @TYPO3\CMS\Extbase\Annotation\Validate("Ud\Iqtp13db\Domain\Validator\TNSeite1Validator", param="tnseite1")
 * @return void
 */
public function anmeldseite1redirectAction(\Ud\Iqtp13db\Domain\Model\TNSeite1 $tnseite1 = NULL)
{
    if($tnseite1 == NULL) {
        $this->redirect('anmeldseite1', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer));
    } else {
        $valArray = $this->request->getArguments();
        if(isset($valArray['btnweiter'])) {
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'tnseite1', serialize($tnseite1));
            
            if ($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnuid') == NULL) {
                $teilnehmer = $this->getTeilnehmerFromSession();
                $teilnehmer->setBeratungsstatus(99);
                $this->teilnehmerRepository->add($teilnehmer);
                
                // Daten sofort in die Datenbank schreiben
                $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
                $persistenceManager->persistAll();
                $GLOBALS['TSFE']->fe_user->setKey('ses', 'tnuid', $teilnehmer->getUid());
            } else {
                $teilnehmer = $this->teilnehmerRepository->findByUid($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnuid'));
                $teilnehmer = $this->getTeilnehmerFromSession($teilnehmer);
                $teilnehmer->setBeratungsstatus(99);
            }
            
            // Hier entscheidet sich, welcher Beratungsstelle der Ratsuchende zugewiesen wird.
            // Wenn durch Link angegeben, dann nimm diese, sonst ermittel aus Generalhelper
            if($GLOBALS['TSFE']->fe_user->getKey('ses', 'beratungsstellenid') != '') {
                $niqbid = $GLOBALS['TSFE']->fe_user->getKey('ses', 'beratungsstellenid');
            } else {
                $niqbid = $this->generalhelper->getNiqberatungsstellenid($teilnehmer, $this->allusergroups, $this->settings['standardniqidberatungsstelle']);
                $GLOBALS['TSFE']->fe_user->setKey('ses', 'beratungsstellenid', $niqbid);
            }
            $teilnehmer->setNiqidberatungsstelle($niqbid);
            $this->teilnehmerRepository->update($teilnehmer);
            
            // Daten sofort in die Datenbank schreiben
            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
            
            $this->redirect('anmeldseite2', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
        } else {
            $this->cancelregistration(null);
        }
    }
}

/**
 * action initdeleteFileWebapp
 *
 * @param void
 */
public function initializeanmeldseite2Action()
{
    $arguments = $this->request->getArguments();
    if($this->teilnehmerRepository->countByUid($arguments['teilnehmer']) == 0) {
        $this->forward('anmeldseite2', 'Teilnehmer', null, null);
        die;
    }
}
/**
 * action anmeldseite2
 *
 * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
 * @param \Ud\Iqtp13db\Domain\Model\Abschluss $abschluss
 * @return void
 */
public function anmeldseite2Action(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer, \Ud\Iqtp13db\Domain\Model\Abschluss $abschluss = NULL)
{
    if($this->teilnehmerRepository->countByUid($teilnehmer) != 0) {
        $abschluesse = new \Ud\Iqtp13db\Domain\Model\Abschluss();
        $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
        if(count($abschluesse) == 0) {
            $abschluss = new \Ud\Iqtp13db\Domain\Model\Abschluss();
            $abschluss->setTeilnehmer($teilnehmer);
            $this->abschlussRepository->add($abschluss);
            // Daten sofort in die Datenbank schreiben
            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
        } else {
            if($abschluss == NULL) $abschluss = $this->abschlussRepository->findOneByTeilnehmer($teilnehmer);
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
                'selectedabschluss' => $abschluss,
                'selecteduid' => $abschluss->getUid(),
                'beratungsstelle' => $GLOBALS['TSFE']->fe_user->getKey('ses', 'beratungsstellenid'),
                'abschlussjahre', $abschlussjahre
            ]
            );
    } else {
        $this->forward('startseite', 'Teilnehmer', 'Iqtp13db');
    }
}

/**
 * action anmeldseite2redirect
 *
 * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
 * @return void
 */
public function anmeldseite2redirectAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
{
    $this->cancelregistration(null);
}

/**
 * action anmeldseite3
 *
 * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
 * @return void
 */
public function anmeldseite3Action(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
{
    foreach ($this->allusergroups as $group) {
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
        }
    }
    
    if($this->teilnehmerRepository->countByUid($teilnehmer) != 0) {
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
    
    if($this->teilnehmerRepository->countByUid($teilnehmer) != 0) {
        $niqbid = $teilnehmer->getNiqidberatungsstelle();
        $beratungsstellenfolder = $niqbid == '10143' ? 'Beratene' : $niqbid;
        $newFilePath = $beratungsstellenfolder.'/' . $teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid(). '/';
        $storage = $this->generalhelper->getTP13Storage($this->storageRepository->findAll());
        $foldersize = $this->generalhelper->getFolderSize($storage->getConfiguration()['basePath'].$newFilePath);
        if(!is_numeric($foldersize)) $foldersize = 0;
        $dokumente = $this->dokumentRepository->findByTeilnehmer($teilnehmer);
        $abschluesse = new \Ud\Iqtp13db\Domain\Model\Abschluss();
        $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
        
        $this->view->assignMultiple(
            [
                'settings' => $this->settings,
                'abschluesse' => $abschluesse,
                'heute' => time(),
                'teilnehmer' => $teilnehmer,
                'dokumente' => $dokumente,
                'foldersize' =>  100-(intval(($foldersize/30000)*100))
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
            $tfolder = $this->generalhelper->createFolder($teilnehmer, $this->settings['standardniqidberatungsstelle'], $this->allusergroups, $this->storageRepository->findAll());
            if($teilnehmer->getVerificationDate() == 0) $teilnehmer->setBeratungsstatus(0);
            
            // Sonstiger Status in Feld "Gruppe" eintragen
            $sonst = $teilnehmer->getSonstigerstatus()[0] == '1' ? 'Ortskraft Afghanistan' : '';
            $sonst = $teilnehmer->getSonstigerstatus()[0] == '2' ? 'Geflüchtet aus der Ukraine' : '';
            $teilnehmer->setKooperationgruppe($sonst);
            
            $this->teilnehmerRepository->update($teilnehmer);
            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
            
            $bcc = $this->generalhelper->getGeneralmailBeratungsstelle($teilnehmer->getNiqidberatungsstelle(), $this->allusergroups, $this->settings['standardbccmail']);
            $sender = $this->settings['sender'];
            if($bcc == '' || $sender == '') {
                $this->addFlashMessage('Fehler 101.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                $this->redirect('anmeldungcomplete', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
            } else {
                $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('tnseite1', null);
                $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('tnuid', null);
                
                $recipient = $teilnehmer->getEmail();
                $templateName = 'Mailtoconfirm';
                $confirmmailtext1 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmmailtext1', 'Iqtp13db');
                $confirmlinktext = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmlinktext', 'Iqtp13db');
                $confirmmailtext2 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmmailtext2', 'Iqtp13db');
                $subject = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmsubject', 'Iqtp13db');
                
                $variables = array(
                    'teilnehmer' => $teilnehmer,
                    'confirmmailtext1' => $confirmmailtext1,
                    'confirmlinktext' => $confirmlinktext,
                    'confirmmailtext2' => $confirmmailtext2,
                    'startseitelink' => $this->settings['startseitelink'],
                    'logolink' => $this->settings['logolink'],
                    'registrationpageuid' => $this->settings['registrationpageuid'],
                    'askconsent' => '0',
                    'baseurl' => $this->request->getBaseUri()
                );
                $this->sendTemplateEmail(array($recipient), array($bcc), array($sender), $subject, $templateName, $variables, false);
                
                $this->redirect(null, null, null, null, $this->settings['redirectValidationInitiated']);
            }
        } else {
            $this->cancelregistration($teilnehmer->getUid());
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
        $teilnehmer = $this->teilnehmerRepository->findByVerificationCode($this->request->getArgument('code'));
    }
    
    if($this->request->hasArgument('askconsent')) {
        $askconsent = $this->request->getArgument('askconsent');
    }
    
    if($teilnehmer) {
        $teilnehmer->setBeratungsstatus(1);
        $teilnehmer->setVerificationDate(new \DateTime);
        $teilnehmer->setVerificationIp($_SERVER['REMOTE_ADDR']);
        $this->teilnehmerRepository->update($teilnehmer);
        
        // ANMERKUNG: Nach Telefonat mit T. Schiller auskommentiert, da auch per Button aus Backend die Bestätigung gesendet werden soll: if($askconsent == 0)
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
 * cancelregistration
 *
 * @return void
 */
public function cancelregistration($tnuid)
{
    if($tnuid != null) {
        
        $teilnehmer = $this->teilnehmerRepository->findByUid($tnuid);
        
        $niqbid = $teilnehmer->getNiqidberatungsstelle();
        $beratungsstellenfolder = $niqbid == '10143' ? 'Beratene' : $niqbid;
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
    
    /*
     $uriBuilder = $this->controllerContext->getUriBuilder();
     $uriBuilder->reset();
     $uriBuilder->setTargetPageUid($this->settings['startseite']);
     $this->redirectToUri($uriBuilder->build());
     */
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
    $niqbid = $teilnehmer->getNiqidberatungsstelle();
    $bcc = $this->generalhelper->getGeneralmailBeratungsstelle($niqbid, $this->allusergroups, $this->settings['standardbccmail']);
    $sender = $this->settings['sender'];
    $subject = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('subject', 'Iqtp13db');
    $templateName = 'Mail';
    $anrede = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('anredemail', 'Iqtp13db');
    $mailtext = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mailtext', 'Iqtp13db');
    $mailtext = str_replace("WARTEZEITWOCHEN", $this->settings['wartezeitwochen'], $mailtext);
    $variables = array(
        'anrede' => $anrede . $teilnehmer->getVorname(). ' ' . $teilnehmer->getNachname() . ',',
        'mailtext' => $mailtext,
        'startseitelink' => $this->settings['startseitelink'],
        'logolink' => $this->settings['logolink'],
        'baseurl' => $this->request->getBaseUri()
    );
    $this->sendTemplateEmail(array($recipient), array($bcc), array($sender), $subject, $templateName, $variables, true);
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
    $templateRootPath = end($extbaseFrameworkConfiguration['view']['templateRootPaths']);
    $templatePathAndFilename = $templateRootPath . 'Teilnehmer/' . $templateName . '.html';
    
    $emailView->setTemplatePathAndFilename($templatePathAndFilename);
    $emailView->assignMultiple($variables);
    $emailBody = $emailView->render();
    
    $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Mail\MailMessage::class);
    $message->to(new \Symfony\Component\Mime\Address($recipient[0]))->from(new \Symfony\Component\Mime\Address($sender[0]));
    $message->subject($subject);
    if($templateName != 'Mailtoconfirm') $message->bcc(new \Symfony\Component\Mime\Address($bcc[0]));
    
    if($this->settings['mailattacheinwilligung'] != '') {
        $publicRootPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($this->settings['mailattacheinwilligung']);
        if($publicRootPath != '' && $addattachment) {
            $message->attach(\Swift_Attachment::fromPath($publicRootPath));
        }
    }
    
    // HTML Email
    $message->html($emailBody);
    $message->send();
    
    return $message->isSent();
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
        $teilnehmer->setZertifikatdeutsch($tnseite1->getZertifikatdeutsch());
    }
    
    return $teilnehmer;
}

/**
 * action checkniqconnection
 *
 * @return void
 */
public function checkniqconnectionAction()
{
    // **** NIQ deaktiviert **** $retval = $this->niqinterface->check_curl($this->niqapiurl);
    
    if($retval) {
        $this->addFlashMessage('NIQ Verbindung verfügbar.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
    } else {
        $this->addFlashMessage('NIQ Verbindung nicht erreichbar!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
    }
    $this->redirect('listerstberatung');
}

/**
 * action sendtoniq
 *
 * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
 * @return void
 */
public function sendtoniqAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
{
    $valArray = $this->request->getArguments();
    
    // NIQ verfügbar?
    $retval = $this->niqinterface->check_curl($this->niqapiurl);
    if(!$retval) {
        $this->addFlashMessage('NIQ Verbindung nicht erreichbar!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']), null);
    } else {
        $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
        $folgekontakte = $this->folgekontaktRepository->findByTeilnehmer($teilnehmer);
        
        if($teilnehmer->getNiqidberatungsstelle() != '0') {
            $niqidbstelle = $teilnehmer->getNiqidberatungsstelle();
        } else {
            $niqidbstelle = $this->niqbid;
        }
        $returnarray = $this->niqinterface->uploadtoNIQ($teilnehmer, $abschluesse, $folgekontakte, $niqidbstelle, $this->niqapiurl);
        
        $retteilnehmer = $returnarray[0];
        $retstring = $returnarray[1];
        
        if($retteilnehmer instanceof \Ud\Iqtp13db\Domain\Model\Teilnehmer) {
            $this->teilnehmerRepository->update($retteilnehmer);
            // Daten sofort in die Datenbank schreiben
            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
            
            $this->addFlashMessage($retstring, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        } else {
            $this->addFlashMessage($retstring, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        }
        
        $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']), null);
    }
}


/********** HILFSFUNKTIONEN **********/

/**
 * Check Beratungsstatus
 *
 * Beratungsstatus: 0 = angemeldet, 1 = Anmeldung bestätigt, 2 = Erstberatung Start, 3 = Erstberatung abgeschlossen, 4 = NIQ erfasst
 * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
 * @return int
 */
public function checkberatungsstatus(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer) {
    if($teilnehmer != NULL) {
        if($teilnehmer->getVerificationDate() == 0) {
            return 0;
        } else {
            if($teilnehmer->getNiqchiffre() != '') return 4;
            
            if($teilnehmer->getVerificationDate() > 0 && !$this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum()) && !$this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen())) return 1;
            
            if($teilnehmer->getVerificationDate() > 0 && $this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum()) && !$this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen())) return 2;
            
            if($teilnehmer->getVerificationDate() > 0 && $this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum()) && $this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen())) return 3;
        }
    }
    return 999;
}

/**
 * Set Filter
 *
 */
function setfilter(int $type, array $valArray, $orderby, $order, $deleted) {
    // FILTER
    if (isset($valArray['filteraus'])) {
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fname', NULL);
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fort', NULL);
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fberuf', NULL);
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fland', NULL);
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fgruppe', NULL);
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'filtermodus', NULL);
    }
    if (isset($valArray['filteran'])) {
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fname', $valArray['name']);
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fort', $valArray['ort']);
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fberuf', $valArray['beruf']);
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fland', $valArray['land']);
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fgruppe', $valArray['gruppe']);
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'filtermodus', '1');
    }
    
    $fname = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fname');
    $fort = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fort');
    $fberuf = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fberuf');
    $fland = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fland');
    $fgruppe = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fgruppe');
    
    if($fland == -1000 || $fland == NULL) $fland = '';
    
    if ($fname == '' && $fort == '' && $fberuf == '' && $fland == '' && $fgruppe == '') {
        if($deleted == 1) {
            $teilnehmers = $this->teilnehmerRepository->findhidden4list($orderby, $order, $this->niqbid);
        } else {
            $teilnehmers = $this->teilnehmerRepository->findAllOrder4List($type, $orderby, $order, $this->niqbid);
        }
    } else {
        $teilnehmers = $this->teilnehmerRepository->searchTeilnehmer($type, $fname, $fort, $fland, $fgruppe, $deleted, $this->niqbid, $fberuf, $this->settings['berufe'], $orderby, $order);
        
        //DebuggerUtility::var_dump($GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus'));
        
        $this->view->assign('filtername', $fname);
        $this->view->assign('filterort', $fort);
        $this->view->assign('filterberuf', $fberuf);
        $this->view->assign('filterland', $fland);
        $this->view->assign('filtergruppe', $fgruppe);
        $this->view->assign('filteron', $GLOBALS['TSFE']->fe_user->getKey('ses', 'filtermodus'));
    }
    
    // FILTER bis hier
    return $teilnehmers;
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
