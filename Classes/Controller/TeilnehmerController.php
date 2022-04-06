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
    
    protected $niqinterface, $niqbid, $usergroup;
    
    /**
     * teilnehmerRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\TeilnehmerRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $teilnehmerRepository = NULL;

       /**
     * folgekontaktRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\FolgekontaktRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $folgekontaktRepository = NULL;
    
    /**
     * dokumentRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\DokumentRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $dokumentRepository = NULL;
    
    /**
     * historieRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\HistorieRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $historieRepository = NULL;
    
    /**
     * beraterRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\BeraterRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $beraterRepository = NULL;
    
    /**
     * abschlussRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\AbschlussRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $abschlussRepository = NULL;
        
    /**
     * frontendUserGroupRepository
     *
     * @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserGroupRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $frontendUserGroupRepository;
    
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
    		
    		$this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('einwilligungdatenanAAmedium');
    	    $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('einwilligungdatenanAAmedium', 'array');
    	    
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

    	
    	$this->niqinterface = new \Ud\Iqtp13db\Helper\NiqInterface();
    	
    	$this->user=null;
    	$context = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class);
    	if($context->getPropertyFromAspect('frontend.user', 'isLoggedIn')){
    	    $this->user=$GLOBALS['TSFE']->fe_user->user;
    	}
    	
    	//DebuggerUtility::var_dump($this->user);
    	if($this->user != NULL) {
    	    $standardniqidberatungsstelle = $this->settings['standardniqidberatungsstelle'];
    	    $this->usergroup = $this->frontendUserGroupRepository->findByIdentifier($this->user['usergroup']);
    	    $userniqidbstelle = $this->usergroup->getDescription(); // Beratungsstellen-ID im "Beschreibung"-Feld der Benutzergruppe
    	    $this->niqbid = $userniqidbstelle == '' ? $standardniqidberatungsstelle : $userniqidbstelle;    	    
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
                $this->forward('anmeldseite1', 'Teilnehmer', 'Iqtp13db');            
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
        
    	$valArray = $this->request->getArguments();
    	   
    	$heute = date('Y-m-d');
    	$diesesjahr = date('Y');
    	$diesermonat = idate('m');
    	$letztesjahr = idate('Y') - 1;
    	
    	for($i=1;$i<13;$i++) {
    		$monatsnamen[$i] = date("M", mktime(0, 0, 0, $i, 1, $diesesjahr));
    	}
    	
    	for($m = $diesermonat + 1; $m < 13; $m++) {
    		$angemeldeteTN[$m] = $this->teilnehmerRepository->count4Status("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr);
    		$qfolgekontakte[$m] = $this->folgekontaktRepository->count4Status("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr);
    		$erstberatung[$m] = $this->teilnehmerRepository->count4StatusErstberatung("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr);
    		$beratungfertig[$m] = $this->teilnehmerRepository->count4StatusBeratungfertig("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr);
    		$niqerfasst[$m] =  $this->teilnehmerRepository->count4Statusniqerfasst("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr);
    		
    		$days4beratung[$m] =  $this->teilnehmerRepository->days4Beratungfertig("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr);
    		$days4wartezeit[$m] =  $this->teilnehmerRepository->days4Wartezeit("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr);
    	}
    	for($m = 1; $m <= $diesermonat; $m++) {
    		$angemeldeteTN[$m] = $this->teilnehmerRepository->count4Status("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr);
    		$qfolgekontakte[$m] = $this->folgekontaktRepository->count4Status("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr);
    		$erstberatung[$m] = $this->teilnehmerRepository->count4StatusErstberatung("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr);
    		$beratungfertig[$m] = $this->teilnehmerRepository->count4StatusBeratungfertig("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr);
    		$niqerfasst[$m] = $this->teilnehmerRepository->count4Statusniqerfasst("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr);
    		
    		$days4beratung[$m] =  $this->teilnehmerRepository->days4Beratungfertig("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr);
    		$days4wartezeit[$m] = $this->teilnehmerRepository->days4Wartezeit("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr);    		
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
    	
    	$aktuelleanmeldungen = count($this->teilnehmerRepository->findAllOrder4Status(0)) + count($this->teilnehmerRepository->findAllOrder4Status(1));
    	$aktuellerstberatungen = count($this->teilnehmerRepository->findAllOrder4Status(2));
    	$aktuellberatungenfertig = count($teilnehmers = $this->teilnehmerRepository->findAllOrder4Status(3));
    	$archivierttotal = count($this->teilnehmerRepository->findAllOrder4Status(4));
    	
    	// keine Berater vorhanden?    	
    	$alleberater = $this->beraterRepository->findAllBerater($this->settings['beraterstoragepid']);
    	if(count($alleberater) == 0) {
    	    $this->addFlashMessage('Es sind noch keine Berater:innen vorhanden. Bitte im Menü Berater*innen anlegen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
    	} 

    	$historie = $this->historieRepository->findAllDesc();	
    	$currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
    	$paginator = new QueryResultPaginator($historie, $currentPage, 25);
    	$pagination = new SimplePagination($paginator);
    	
    	$niqdbstatus = $this->niqinterface->check_curl() ? "<span style='color: green;'>erreichbar</span>" : "<span style='color: red;'>nicht erreichbar!</span>";
    	
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
		
		if ($valArray['statsexport'] == 'Statistik exportieren') {
			 
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
    	
    	if($valArray['changeorder'] == 1) {
    	    $order = $order == 'DESC' ? 'ASC' : 'DESC';
    	    $GLOBALS['TSFE']->fe_user->setKey('ses', 'listangemeldetorder', $order);
    	}
		
    	$teilnehmer = $this->setfilter(0, $valArray, $orderby, $order, 0);
    	
    	$currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
    	$paginator = new QueryResultPaginator($teilnehmer, $currentPage, 25);
    	$pagination = new SimplePagination($paginator);
    	
    	$teilnehmerpag = $paginator->getPaginatedItems();
    	
    	for($j=0; $j < count($teilnehmerpag); $j++) {
    	    $anz = $this->teilnehmerRepository->findDublette4Angemeldet($teilnehmerpag[$j]->getNachname(), $teilnehmerpag[$j]->getVorname());
    	    if($anz > 1) $teilnehmerpag[$j]->setDublette(TRUE);
    	    $abschluesse[$j] = $this->abschlussRepository->findByTeilnehmer($teilnehmerpag[$j]);
    	    //DebuggerUtility::var_dump($teilnehmerpag[$j]->getBeratungdatum()." ".$this->validateDateYmd($teilnehmerpag[$j]->getBeratungdatum()));
    	}
    	
    	$wohnsitzstaaten = $this->settings['staaten'];
    	unset($wohnsitzstaaten[201]);
    	
    	//DebuggerUtility::var_dump($abschluesse);
    	
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
        
        if($valArray['changeorder'] == 1) {
            $order = $order == 'DESC' ? 'ASC' : 'DESC';
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'listerstberatungorder', $order);
        }
        
        $teilnehmer = $this->setfilter(3, $valArray, $orderby, $order, 0);
        
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
        $paginator = new QueryResultPaginator($teilnehmer, $currentPage, 20);
        $pagination = new SimplePagination($paginator);
        
        $teilnehmerpag = $paginator->getPaginatedItems();
        
        foreach ($teilnehmerpag as $key => $tn) {
            $anzfolgekontakte[$key] = count($this->folgekontaktRepository->findByTeilnehmer($tn->getUid()));
            
            $abschluesse[$key] = $this->abschlussRepository->findByTeilnehmer($tn);
            $niqstat = $this->niqinterface->niqstatus($tn, $abschluesse[$key]);
            if($niqstat == 0) {
                $niqstatusberatung[$key] = 'rot';                
            } elseif($niqstat == 2) {
                $niqstatusberatung[$key] = 'gelb';
            } elseif($niqstat == 1) {
                $niqstatusberatung[$key] = 'gruen';
            } else {
                $niqstatusberatung[$key] = '';
            }
            
            if($niqstat == 0 || $niqstat == 2) $niqwasfehlt[$key] = $this->niqinterface->niqwasfehlt($tn, $abschluesse[$key]);
        }
        $folgekontakte = $this->folgekontaktRepository->findAll();
        
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
        
        if($valArray['changeorder'] == 1) {            
            $order = $order == 'DESC' ? 'ASC' : 'DESC';
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'listarchivorder', $order);       
        }
        
        $teilnehmer = $this->setfilter(4, $valArray, $orderby, $order, 0);
            
        
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
        $paginator = new QueryResultPaginator($teilnehmer, $currentPage, 20);
        $pagination = new SimplePagination($paginator);
        
        $teilnehmerpag = $paginator->getPaginatedItems();
        
        foreach ($teilnehmerpag as $key => $tn) {
            $anzfolgekontakte[$key] = count($this->folgekontaktRepository->findByTeilnehmer($tn->getUid()));
            
            $abschluesse[$key] = $this->abschlussRepository->findByTeilnehmer($tn);
            $niqstat = $this->niqinterface->niqstatus($tn, $abschluesse[$key]);
            if($niqstat == 0) {
                $niqstatusberatung[$key] = 'rot';
            } elseif($niqstat == 2) {
                $niqstatusberatung[$key] = 'gelb';
            } elseif($niqstat == 1) {
                $niqstatusberatung[$key] = 'gruen';
            } else {
                $niqstatusberatung[$key] = '';
            }
            
            if($niqstat == 0 || $niqstat == 2) $niqwasfehlt[$key] = $this->niqinterface->niqwasfehlt($tn, $abschluesse[$key]);
        }
        $folgekontakte = $this->folgekontaktRepository->findAll();
        
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
    	
    	if($valArray['changeorder'] == 1) {
    	    $order = $order == 'DESC' ? 'ASC' : 'DESC';
    	    $GLOBALS['TSFE']->fe_user->setKey('ses', 'listdeletedorder', $order);
    	}
    	
    	$teilnehmer = $this->setfilter(0, $valArray, $orderby, $order, 1);
    	
    	$currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
    	$paginator = new QueryResultPaginator($teilnehmer, $currentPage, 25);
    	$pagination = new SimplePagination($paginator);
    	
    	$teilnehmerpag = $paginator->getPaginatedItems();
    	
    	for($j=0; $j < count($teilnehmerpag); $j++) {
    	    $anz = $this->teilnehmerRepository->findDublette4Deleted($teilnehmerpag[$j]->getNachname(), $teilnehmerpag[$j]->getVorname());
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
    	
    	$this->view->assignMultiple(
    	    [
    	        'dokumente' => $dokumente,
    	        'calleraction' => $valArray['calleraction'],
    	        'callercontroller' => $valArray['callercontroller'],
    	        'callerpage' => $valArray['callerpage'],
    	        'historie' => $historie,
    	        'teilnehmer' => $teilnehmer,
    	        'abschluesse' => $abschluesse
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
                'calleraction' => $valArray['calleraction'],
                'callercontroller' => $valArray['callercontroller'],
                'callerpage' => $valArray['callerpage'],
                'staatsangehoerigkeitstaaten' => $staatsangehoerigkeitstaaten,
                'abschluss' => $abschluss,
                'wohnsitzstaaten' => $wohnsitzstaaten,
                'alleberater' => $alleberater,
                'berater' => $this->user,
                'settings' => $this->settings
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
        $abschluss = new \Ud\Iqtp13db\Domain\Model\Abschluss();
        
        if(is_array($valArray['abschluss']['abschlussart'])) {
            $abschluss->setAbschlussart($valArray['abschluss']['abschlussart']);
        } else {
            $abschlussart = array($valArray['abschluss']['abschlussart']);
            $abschluss->setAbschlussart($abschlussart);
        }
        
    	$abschluss->setErwerbsland($valArray['abschluss']['erwerbsland']);
    	$abschluss->setDauerBerufsausbildung($valArray['abschluss']['dauerBerufsausbildung']);
    	$abschluss->setAbschlussjahr($valArray['abschluss']['abschlussjahr']);
    	$abschluss->setAusbildungsinstitution($valArray['abschluss']['ausbildungsinstitution']);
    	$abschluss->setAusbildungsort($valArray['abschluss']['ausbildungsort']);
    	$abschluss->setAbschluss($valArray['abschluss']['abschluss']);
    	$abschluss->setBerufserfahrung($valArray['abschluss']['berufserfahrung']);
    	$abschluss->setDeutscherReferenzberuf($valArray['abschluss']['deutscherReferenzberuf']);
    	$abschluss->setReferenzberufzugewiesen($valArray['abschluss']['referenzberufzugewiesen']);
    	$abschluss->setSonstigerberuf($valArray['abschluss']['sonstigerberuf']);
    	$abschluss->setNregberuf($valArray['abschluss']['nregberuf']);    	
    	$abschluss->setWunschberuf($valArray['abschluss']['wunschberuf']);
    	$abschluss->setAntragstellungvorher($valArray['abschluss']['antragstellungvorher']);
    	$abschluss->setAntragstellunggwpvorher($valArray['abschluss']['antragstellunggwpvorher']);
    	$abschluss->setAntragstellungzabvorher($valArray['abschluss']['antragstellungzabvorher']);
    	$abschluss->setAntragstellungerfolgt($valArray['abschluss']['antragstellungerfolgt']);
    	$abschluss->setAntragstellunggwpdatum($valArray['abschluss']['antragstellunggwpdatum']);
    	$abschluss->setAntragstellunggwpergebnis($valArray['abschluss']['antragstellunggwpergebnis']);
    	$abschluss->setAntragstellungzabdatum($valArray['abschluss']['antragstellungzabdatum']);
    	$abschluss->setAntragstellungzabergebnis($valArray['abschluss']['antragstellungzabergebnis']);
    	$abschluss->setNiquebertragung($valArray['abschluss']['niquebertragung']);
	    $abschluss->setTeilnehmer($teilnehmer);
	    
	    $this->teilnehmerRepository->add($teilnehmer);	    
    	$this->abschlussRepository->add($abschluss);
    	
    	// Daten sofort in die Datenbank schreiben
    	$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
    	$persistenceManager->persistAll();
    	
    	$tfolder = $this->createFolder($teilnehmer);
    	
    	$valArray = $this->request->getArguments();    	
    	$this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('teilnehmer' => $teilnehmer, 'callerpage' => $valArray['callerpage']));
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
        
        if($teilnehmer->getNiqchiffre() != '' && $this->niqinterface->check_curl() == FALSE) {
            $this->addFlashMessage("Bearbeiten von bereits übertragenen Datensätzen vorübergehend nicht möglich, da NIQ-Datenbank nicht erreichbar.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']));
        } else {
            
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
                if($valArray['selectboxabschluss'] != '') {
                    $abschluss=$this->abschlussRepository->findByUid($valArray['selectboxabschluss']);
                    $selectboxsubmit = '1';
                } elseif($abschluss == NULL) {
                    $abschluss = $this->abschlussRepository->findOneByTeilnehmer($teilnehmer);
                }
            }
            
            $alleberater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->user['usergroup']);
            
            $dokumente = $this->dokumentRepository->findByTeilnehmer($teilnehmer);
                
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
                    'selectedabschluss' => $abschluss,
                    'selectboxsubmit' => $selectboxsubmit,
                    'selecteduid' => $abschluss->getUid(),
                    'weitererabschluss' => $valArray['weitererabschluss']
                ]
                );
        }
    }

    /**
     * action update
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @param \Ud\Iqtp13db\Domain\Model\Abschluss $abschluss
     * @return void
     */
    public function updateAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer, \Ud\Iqtp13db\Domain\Model\Abschluss $abschluss = NULL)
    {
        $valArray = $this->request->getArguments();
        //DebuggerUtility::var_dump($valArray);
        //die;
        if($teilnehmer->getNiqchiffre() != '' && $this->niqinterface->check_curl() == FALSE) {
            $this->addFlashMessage("Datensatz NICHT gespeichert. Bearbeiten von bereits übertragenen Datensätzen vorübergehend nicht möglich, da NIQ-Datenbank nicht erreichbar.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']));
        } else {
            
            if(is_numeric($teilnehmer->getLebensalter())) {
                if($teilnehmer->getLebensalter() > 0 && ($teilnehmer->getLebensalter() < 15 || $teilnehmer->getLebensalter() > 80)) {
                    $this->addFlashMessage("Datensatz NICHT gespeichert. Lebensalter muss zwischen 15 und 80 oder k.A. sein.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                    $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']));                    
                }
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
    
    // TODO: Abschlüsse nicht mehr bei Teilnehmer, ggf. hier aus der Tabelle Abschluss neu einfügen in die Historie
    		$this->createHistory($teilnehmer, "erwerbsstatus");
    		$this->createHistory($teilnehmer, "leistungsbezugjanein");
    		$this->createHistory($teilnehmer, "leistungsbezug");
    		$this->createHistory($teilnehmer, "einwilligungdatenanAA");
    		$this->createHistory($teilnehmer, "einwilligungdatenanAAdatum");
    		$this->createHistory($teilnehmer, "einwilligungdatenanAAmedium");
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
    
    // TODO: historie für Beratung-Felder
    
    		// Daten sofort in die Datenbank schreiben
    		$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
    		$persistenceManager->persistAll();
    		
        	$bstatus = $this->checkberatungsstatus($teilnehmer);
        	if($bstatus == 999) {
        	    $this->addFlashMessage("Fehler in Update-Routine -> beratungsstatus = 999. Bitte Admin informieren.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        	}
        	
        	$teilnehmer->setNiqidberatungsstelle($this->niqbid);
        	$teilnehmer->setBeratungsstatus($bstatus);
        	$this->teilnehmerRepository->update($teilnehmer);
        	
        	if($abschluss != NULL && $valArray['selectboxsubmit'] != '1') {
    
            	$savedabschluss = $this->abschlussRepository->findByUid($valArray['selectboxabschluss']);
            	$savedabschluss->setAbschlussart($abschluss->getAbschlussart());
            	$savedabschluss->setErwerbsland($abschluss->getErwerbsland());
            	$savedabschluss->setDauerBerufsausbildung($abschluss->getDauerBerufsausbildung());
            	$savedabschluss->setAbschlussjahr($abschluss->getAbschlussjahr());
            	$savedabschluss->setAusbildungsinstitution($abschluss->getAusbildungsinstitution());
            	$savedabschluss->setAusbildungsort($abschluss->getAusbildungsort());
            	$savedabschluss->setAbschluss($abschluss->getAbschluss());
            	$savedabschluss->setBerufserfahrung($abschluss->getBerufserfahrung());
            	$savedabschluss->setDeutscherReferenzberuf($abschluss->getDeutscherReferenzberuf());
            	$savedabschluss->setReferenzberufzugewiesen($abschluss->getReferenzberufzugewiesen());
            	$savedabschluss->setSonstigerberuf($abschluss->getSonstigerberuf());
            	$savedabschluss->setNregberuf($abschluss->getNregberuf());
            	$savedabschluss->setWunschberuf($abschluss->getWunschberuf());
            	$savedabschluss->setAntragstellungvorher($abschluss->getAntragstellungvorher());
            	$savedabschluss->setAntragstellunggwpvorher($abschluss->getAntragstellunggwpvorher());
            	$savedabschluss->setAntragstellungzabvorher($abschluss->getAntragstellungzabvorher());        	
            	$savedabschluss->setAntragstellungerfolgt($abschluss->getAntragstellungerfolgt());
            	$savedabschluss->setAntragstellunggwpdatum($abschluss->getAntragstellunggwpdatum());
            	$savedabschluss->setAntragstellunggwpergebnis($abschluss->getAntragstellunggwpergebnis());
            	$savedabschluss->setAntragstellungzabdatum($abschluss->getAntragstellungzabdatum());
            	$savedabschluss->setAntragstellungzabergebnis($abschluss->getAntragstellungzabergebnis());
            	$savedabschluss->setNiquebertragung($abschluss->getNiquebertragung());
    
            	$this->abschlussRepository->update($savedabschluss);    	    
        	}
        	
        	// Daten sofort in die Datenbank schreiben
        	$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        	$persistenceManager->persistAll();
        	    	
        	if($teilnehmer->getNiqchiffre() != '') {
        	    $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
        	    $folgekontakte = $this->folgekontaktRepository->findByTeilnehmer($teilnehmer);
        	    if($teilnehmer->getNiqidberatungsstelle() != '0') {
        	        $niqidbstelle = $teilnehmer->getNiqidberatungsstelle();
        	    } else {
        	        $niqidbstelle = $this->niqbid;
        	    }
        	    $returnarray = $this->niqinterface->uploadtoNIQ($teilnehmer, $abschluesse, $folgekontakte, $niqidbstelle);
        	    
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
        	    
        	}
    
        	if (isset($valArray['btnweitererabschluss'])) {
        	    $newabschluss = new \Ud\Iqtp13db\Domain\Model\Abschluss();
        	    $newabschluss->setTeilnehmer($teilnehmer);
        	    $this->abschlussRepository->add($newabschluss);
        	    // Daten sofort in die Datenbank schreiben
        	    $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        	    $persistenceManager->persistAll();
        	    $this->redirect('edit', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer, 'abschluss' => $newabschluss, 'weitererabschluss' => '1', 'callerpage' => $valArray['callerpage']));
        	} elseif(isset($valArray['btndelete'])) {    	    
        	    $this->abschlussRepository->remove($savedabschluss);
        	    $this->redirect('edit', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer, 'callerpage' => $valArray['callerpage']));
        	} elseif($valArray['selectboxsubmit'] == '1') {
        	    $this->redirect('edit', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer, 'selectboxabschluss' => $valArray['selectboxabschluss'], 'callerpage' => $valArray['callerpage']));
        	} else {
        	    $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']));
        	}
        }
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
    		$berater = new \Ud\Iqtp13db\Domain\Model\Berater($this->user['username']);
    		
    		$history->setTeilnehmer($teilnehmer);
    		$history->setProperty($property);
    		$history->setOldvalue($teilnehmer->_getCleanProperty($property));
    		$history->setNewvalue($teilnehmer->_getProperty($property));
    		$history->setBerater($berater);

    		$this->historieRepository->add($history);
    	}    	
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
        
        $bcc = $this->settings['bccmail'];
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
        require_once \TYPO3\CMS\Core\Core\Environment::getConfigPath() . '/ext/vendor/autoload.php';
        
        $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
        $dokumente = $this->dokumentRepository->findByTeilnehmer($teilnehmer);
        
        $thisdate = new DateTime();
        $zeitstempel = $thisdate->format('d.m.Y - H:i:s');
        
        $this->view->assign('teilnehmer', $teilnehmer);
        $this->view->assign('abschluesse', $abschluesse);
        $this->view->assign('dokumente', $dokumente);
        
        $htmlcode = $this->view->render();
        
        $mpdf = new \Mpdf\Mpdf();
        
        $stylesheet = file_get_contents(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('iqtp13db').'/Resources/Public/CSS/customtp13db.css');
        
        $mpdf->SetHeader('Datenblatt vom '.$zeitstempel.'||IQ Webapp');
        $mpdf->SetFooter('|{PAGENO}|');
        
        $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($htmlcode,\Mpdf\HTMLParserMode::HTML_BODY);
        
        $pfad = $this->createFolder($teilnehmer);
        $filename = 'DB-' .$this->sanitizeFileFolderName($teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid()). '.pdf';
        $storage = $this->getTP13Storage();
        $fullpath = $storage->getConfiguration()['basePath'] .'Beratene/' .$pfad->getName().'/'. $filename;
        
         
        $mpdf->Output($fullpath, 'F');
        
        $this->addFlashMessage('Datenblatt wurde in '.$pfad->getIdentifier().' erstellt.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        
        $this->redirect('show', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
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
            
        $orderby = 'crdate';
        $order = 'ASC';
        $fberatungsstatus = $valArray['filterberatungsstatus'];
        
        if($fberatungsstatus == 12) {
            $teilnehmers = $this->setfilter(4, $valArray, $orderby, $order, 0);
        } elseif($fberatungsstatus == 13) {
            $teilnehmers = $this->setfilter(1, $valArray, $orderby, $order, 0);
        } else {
            $teilnehmers = $this->setfilter(0, $valArray, $orderby, $order, 1);
        }
            
        // ******************** EXPORT ****************************
        if ($valArray['export'] == 'Daten exportieren') {
           
            $x = 0;
            foreach($teilnehmers as $tn) {
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
                    $rows[$x]['Leistungsbezugjanein'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'leistungsbezugjanein');
                    $rows[$x]['Geburtsland'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'geburtsland');
                    $rows[$x]['Geschlecht'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($tn, 'geschlecht');
                }
                $rows[$x]['Beraterin'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($berater, 'username');
                $x++;
            }
// TODO: Referenzberuf ausgeben, Leistungsbezug, Geburtsland und Geschlecht entschlüsseln

            // XLSX
            $filename = 'export_' . date('Y-m-d_H-i', time()) . '.xlsx';
            $header = [ 
                'Bestätigungsdatum' => 'string',
                'Nachname' => 'string',
                'Vorname' => 'string',
                'PLZ' => 'string',
                'Ort' => 'string',
                'E-Mail' => 'string',
                'Telefon' => 'string',
                'Leistungsbezug ja/nein' => 'string',
                'Geburtsland' => 'string',
                'Geschlecht' => 'string',
                'Berater:in' => 'string'
            ];
            
            $writer = new \XLSXWriter();
            $writer->setAuthor('IQ Webapp');
            $writer->writeSheet($rows, 'Blatt1', $header);  // with headers
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer->writeToStdOut();
            exit;
          
        } else {

            $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
            $paginator = new QueryResultPaginator($teilnehmers, $currentPage, 25);
            $pagination = new SimplePagination($paginator);
            
            $teilnehmerpag = $paginator->getPaginatedItems();
            
        	for($j=0; $j < count($teilnehmers); $j++) {
        	    $anz = $this->teilnehmerRepository->findDublette4Angemeldet($teilnehmers[$j]->getNachname(), $teilnehmers[$j]->getVorname());
        		if($anz > 1) $teilnehmers[$j]->setDublette(TRUE);
        		$abschluesse[$j] = $this->abschlussRepository->findByTeilnehmer($teilnehmerpag[$j]);
        	}
            
        	$this->view->assignMultiple(
        	    [
        	        'anzgesamt' => count($teilnehmers),
        	        'abschluesse' => $abschluesse,
        	        'calleraction' => 'export',
        	        'callercontroller' => 'Teilnehmer',
        	        'callerpage' => $currentPage,
        	        'paginator' => $paginator,
        	        'pagination' => $pagination,
        	        'pages' => range(1, $pagination->getLastPageNumber()),
        	        'filterberatungsstatus' => $fberatungsstatus
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
    
    
    /*************************************************************************/
    /******************************* ANMELDUNG *******************************/
    /*************************************************************************/
    
    /**
     * action anmeldseite1
     *
     * @param \Ud\Iqtp13db\Domain\Model\TNSeite1 $tnseite1
     * @return void
     */
    public function anmeldseite1Action(\Ud\Iqtp13db\Domain\Model\TNSeite1 $tnseite1 = NULL)
    {
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
    	        'settings' => $this->settings
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
    public function anmeldseite1redirectAction(\Ud\Iqtp13db\Domain\Model\TNSeite1 $tnseite1)
    {
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
    	        $this->teilnehmerRepository->update($teilnehmer);
    	    }
    	    $this->redirect('anmeldseite2', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));    	    
    	} else {
    	    $this->cancelregistration(null);
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
        
        $this->view->assignMultiple(
            [
                'settings' => $this->settings,
                'abschluesse' => $abschluesse,
                'teilnehmer' => $teilnehmer,
                'selectedabschluss' => $abschluss,
                'selecteduid' => $abschluss->getUid()
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
    	$this->view->assign('settings', $this->settings);
    	$this->view->assign('teilnehmer', $teilnehmer);
    }
    
    /**
     * action anmeldseite3redirect
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function anmeldseite3redirectAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
    	$valArray = $this->request->getArguments();
    	$thisdate = new DateTime();
    	
    	if($teilnehmer->getEinwilligungdatenanaa() == 1) {
    	    $teilnehmer->setEinwilligungdatenanaadatum($thisdate->format('d.m.Y'));
    	    $teilnehmer->setEinwilligungdatenanaamedium(explode(',', 4));
    	} else {
    	    $teilnehmer->setEinwilligungdatenanaadatum('');
    	    $teilnehmer->setEinwilligungdatenanaamedium(array());
    	}
    	if($teilnehmer->getEinwperson() == 1) {
    	    $teilnehmer->setEinwpersondatum($thisdate->format('d.m.Y'));
    	    $teilnehmer->setEinwpersonmedium(explode(',', 4));
    	} else {
    	    $teilnehmer->setEinwpersondatum('');
    	    $teilnehmer->setEinwpersonmedium(array());
    	}
    	
    	if (isset($valArray['btnzurueck'])) {
    	    $teilnehmer->setNiqidberatungsstelle($this->settings['standardniqidberatungsstelle']);
    	    $this->teilnehmerRepository->update($teilnehmer);
    	    $this->redirect('anmeldseite2', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer)); 
    	} elseif(isset($valArray['btnweiter'])) {
    	    $teilnehmer->setNiqidberatungsstelle($this->settings['standardniqidberatungsstelle']);
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
    
    /**
     * action anmeldungcomplete
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function anmeldungcompleteAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
    	$valArray = $this->request->getArguments();
    	
    	$newFilePath = 'Beratene/' . $teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid(). '/';
    	$storage = $this->getTP13Storage();
    	$foldersize = $this->getFolderSize($storage->getConfiguration()['basePath'].$newFilePath);
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
    }
    
    /**
     * action anmeldungcompleteredirect
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function anmeldungcompleteredirectAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
    	$valArray = $this->request->getArguments();
    	 
    	if (isset($valArray['btnzurueck'])) {
    	    $this->redirect('anmeldseite3', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer)); 
    	} elseif(isset($valArray['btnAbsenden'])) {
    	    $tfolder = $this->createFolder($teilnehmer);
    	    if($teilnehmer->getVerificationDate() == 0) $teilnehmer->setBeratungsstatus(0);
    	        	    
//TODO: das ist so nur provisorisch umgesetzt!
    	    // Sonstiger Status in Gruppe eintragen
    	    
   	        $sonst = $teilnehmer->getSonstigerstatus()[0] == '1' ? 'Ortskraft Afghanistan' : '';
   	        $sonst = $teilnehmer->getSonstigerstatus()[0] == '2' ? 'Geflüchtet aus der Ukraine' : '';
    	    $teilnehmer->setKooperationgruppe($sonst);
    	    //DebuggerUtility::var_dump($sonst);
    	    //die;
    	    
    	    $this->teilnehmerRepository->update($teilnehmer);
    	    $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
    	    $persistenceManager->persistAll();
    	    
    	    $bcc = $this->settings['bccmail'];
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
    	        
    	        $this->redirect(null, null, null, null, $this->settings['redirectValidationInitiated']); // TODO: url aus id hier einfügen
    	    }
    	} else {
    	    $this->cancelregistration($teilnehmer->getUid());
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
    		// it's a valid verificationCode
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
            
            $filePath = 'Beratene/' . $teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid(). '/';
            $storage = $this->getTP13Storage();
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
        
        $uriBuilder = $this->controllerContext->getUriBuilder();
        $uriBuilder->reset();
        $uriBuilder->setTargetPageUid($this->settings['startseite']);
        $this->redirectToUri($uriBuilder->build());
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
    	$bcc = $this->settings['bccmail'];    	
    	$sender = $this->settings['sender'];
    	$subject = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('subject', 'Iqtp13db');
    	$templateName = 'Mail';
    	$anrede = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('anredemail', 'Iqtp13db');
    	$mailtext = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mailtext', 'Iqtp13db');    	
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
    	$extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('iqtp13db');
    	$templateRootPath = $extPath."Resources/Private/Templates/";
    
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
	    $retval = $this->niqinterface->check_curl();
	    
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
	    
	    $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
	    $folgekontakte = $this->folgekontaktRepository->findByTeilnehmer($teilnehmer);
	    
	    if($teilnehmer->getNiqidberatungsstelle() != '0') {
	        $niqidbstelle = $teilnehmer->getNiqidberatungsstelle();
	    } else {
	        $niqidbstelle = $this->niqbid;
	    }
	    $returnarray = $this->niqinterface->uploadtoNIQ($teilnehmer, $abschluesse, $folgekontakte, $niqidbstelle);
	    
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
	    
	    $valArray = $this->request->getArguments();
	    $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']), null);
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
	            
	            if($teilnehmer->getVerificationDate() > 0 && !$this->validateDateYmd($teilnehmer->getBeratungdatum()) && !$this->validateDateYmd($teilnehmer->getErstberatungabgeschlossen())) return 1;
	            
	            if($teilnehmer->getVerificationDate() > 0 && $this->validateDateYmd($teilnehmer->getBeratungdatum()) && !$this->validateDateYmd($teilnehmer->getErstberatungabgeschlossen())) return 2;
	            
	            if($teilnehmer->getVerificationDate() > 0 && $this->validateDateYmd($teilnehmer->getBeratungdatum()) && $this->validateDateYmd($teilnehmer->getErstberatungabgeschlossen())) return 3;
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
	    if ($valArray['filteraus']) {
	        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fname', NULL);
	        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fort', NULL);
	        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fberuf', NULL);
	        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fland', NULL);
	        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fgruppe', NULL);
	    }
	    if ($valArray['filteran']) {
	        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fname', $valArray['name']);
	        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fort', $valArray['ort']);
	        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fberuf', $valArray['beruf']);
	        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fland', $valArray['land']);
	        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fgruppe', $valArray['gruppe']);
	    }
	    $fname = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fname');
	    $fort = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fort');
	    $fberuf = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fberuf');
	    $fland = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fland');
	    $fgruppe = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fgruppe');
	    
	    if($fland == -1000 || $fland == NULL) $fland = '';
	    
	    if ($fname == '' && $fort == '' && $fberuf == '' && $fland == '' && $fgruppe == '') {
	        if($deleted == 1) {
	            $teilnehmers = $this->teilnehmerRepository->findhidden4list($orderby, $order);
	        } else {
	            $teilnehmers = $this->teilnehmerRepository->findAllOrder4List($type, $orderby, $order);
	        }    
	    } else {
	        if($type != 0) {
    	        if($fberuf != '') {
    	            $berufearr = $this->settings['berufe'];
    	            foreach ($berufearr as $beruf => $bkey) {
    	                if (strpos(strtolower($bkey), strtolower($fberuf)) !== false) { $results[] = $beruf; }
    	            }
    	            
    	            if( empty($results) ) { $sberuf = ""; }
    	            else { $sberuf = "a.referenzberufzugewiesen IN (".implode(', ', $results).")"; }
    	        } else {
    	            $sberuf = "a.referenzberufzugewiesen LIKE '%'";
    	        }
	        } else {
	            $sberuf = "a.deutscher_referenzberuf LIKE '%$fberuf%'";
	        }

	        $teilnehmers = $this->teilnehmerRepository->searchTeilnehmer($type, $fname, $fort, $sberuf, $fland, $fgruppe, $deleted);
	        $this->view->assign('filtername', $fname);
	        $this->view->assign('filterort', $fort);
	        $this->view->assign('filterberuf', $fberuf);
	        $this->view->assign('filterland', $fland);
	        $this->view->assign('filtergruppe', $fgruppe);
	        $this->view->assign('filterberatungsstatus', $fstatus);
	        $this->view->assign('filteron', 1);
	    }
	    
        //DebuggerUtility::var_dump($teilnehmers);
	        
	    // FILTER bis hier
	    return $teilnehmers;
	}
		
	function sanitizeFileFolderName($name)
	{
	    // Remove special accented characters - ie. sí.
	    $fileName = strtr($name, array('Š' => 'S','Ž' => 'Z','š' => 's','ž' => 'z','Ÿ' => 'Y','À' => 'A','Á' => 'A','Â' => 'A','Ã' => 'A','Ä' => 'A','Å' => 'A','Ç' => 'C','È' => 'E','É' => 'E','Ê' => 'E','Ë' => 'E','Ì' => 'I','Í' => 'I','Î' => 'I','Ï' => 'I','Ñ' => 'N','Ò' => 'O','Ó' => 'O','Ô' => 'O','Õ' => 'O','Ö' => 'O','Ø' => 'O','Ù' => 'U','Ú' => 'U','Û' => 'U','Ü' => 'U','Ý' => 'Y','à' => 'a','á' => 'a','â' => 'a','ã' => 'a','ä' => 'a','å' => 'a','ç' => 'c','è' => 'e','é' => 'e','ê' => 'e','ë' => 'e','ì' => 'i','í' => 'i','î' => 'i','ï' => 'i','ñ' => 'n','ò' => 'o','ó' => 'o','ô' => 'o','õ' => 'o','ö' => 'o','ø' => 'o','ù' => 'u','ú' => 'u','û' => 'u','ü' => 'u','ý' => 'y','ÿ' => 'y'));
	    $fileName = strtr($fileName, array('Þ' => 'TH', 'þ' => 'th', 'Ð' => 'DH', 'ð' => 'dh', 'ß' => 'ss', 'Œ' => 'OE', 'œ' => 'oe', 'Æ' => 'AE', 'æ' => 'ae', 'µ' => 'u'));
	    $clean_name = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $fileName);
	    
	    return $clean_name;
	}
		
	public function createFolder($teilnehmer)
	{
	    $pfadname = $teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid(). '/';
	    $clean_path = 'Beratene/' . $this->sanitizeFileFolderName($pfadname);
	    $storage = $this->getTP13Storage();
	    
	    if (!$storage->hasFolder($clean_path)) {
	        $targetFolder = $storage->createFolder($clean_path);
	    } else {
	        $targetFolder = $storage->getFolder($clean_path);	        
	    }
	    
	    return $targetFolder;
	}
	
	function getTP13Storage() {
		$storageRepository = $this->objectManager->get ( 'TYPO3\\CMS\\Core\\Resource\\StorageRepository' );
		// Speicher 'iqwebappdata' muss im Typo3-Backend auf der Root-Seite als "Dateispeicher" angelegt sein!
		// wenn der Speicher mal nicht verfügbar war (temporär), muss er im Backend im Bereich "Dateispeicher" manuell wieder "online" geschaltet werden mit der Checkbox "ist online?" in den Eigenschaften des jeweiligen Dateispeichers
		$storages = $storageRepository->findAll ();
		foreach ( $storages as $s ) {
			$storageObject = $s;
			$storageRecord = $storageObject->getStorageRecord ();
			if ($storageRecord ['name'] == 'iqwebappdata') {
				$storage = $s;
				break;
			}
		}
		
		return $storage;
	}
	
	function getFolderSize($folderpath) {
		$io = popen ( '/usr/bin/du -sk ' . $folderpath, 'r' );
		$size = fgets ( $io, 4096 );
		$size = substr ( $size, 0, strpos ( $size, "\t" ) );
		pclose ( $io );
		return $size;
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
	
	/**
	 *
	 * @param $settingsarr
	 * @param $ergarrwert
	 */
	function getValu($settingsarr, $ergarrwert) {
		$ret = '';
		foreach ( $settingsarr as $key => $value ) {
			if ($ergarrwert == $value) {
				$ret = $key;
				break;
			}
		}
		return $ret;
	}
	
	function validateDate($date, $format = 'd.m.Y')
	{
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}
	
	function validateDateYmd($date, $format = 'Y-m-d')
	{
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}
	
	
}
