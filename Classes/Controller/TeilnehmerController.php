<?php
namespace Ud\Iqtp13db\Controller;
use \Datetime;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/***
 *
 * This file is part of the "IQ Webapp Anerkennungsberatung" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2021 Uli Dohmen <edv@whkt.de>, WHKT
 *
 ***/

/**
 * TeilnehmerController
 */
class TeilnehmerController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

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
    		$this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('abschlussart1');
    		$this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('abschlussart1', 'array');

    		$this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('abschlussart2');
    		$this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('abschlussart2', 'array');

    		$this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('einwilligungdatenanAAmedium');
    	    $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('einwilligungdatenanAAmedium', 'array');

    	    $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('einwAnerkstellemedium');
    	    $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('einwAnerkstellemedium', 'array');

    	    $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('einwPersonmedium');
    	    $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('einwPersonmedium', 'array');
    	}

    	if ($this->arguments->hasArgument('tnseite2')) {
    		$this->arguments->getArgument('tnseite2')->getPropertyMappingConfiguration()->allowProperties('abschlussart1');
    		$this->arguments->getArgument('tnseite2')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('abschlussart1', 'array');

    		$this->arguments->getArgument('tnseite2')->getPropertyMappingConfiguration()->allowProperties('abschlussart2');
    		$this->arguments->getArgument('tnseite2')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('abschlussart2', 'array');
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
            $this->forward('listerstberatung', 'Beratung', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'niqerfassung') {
            $this->forward('listniqerfassung', 'Beratung', 'Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'archiv') {
        	$this->forward('listarchiv', 'Beratung', 'Iqtp13db');
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
     * @return void
     */
    public function statusAction()
    {
        // Seite "Übersicht"

    	$valArray = $this->request->getArguments();
    	// DebuggerUtility::var_dump($valArray);

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
    		$erstberatung[$m] = $this->beratungRepository->count4StatusErstberatung("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr);
    		$beratungfertig[$m] = $this->beratungRepository->count4StatusBeratungfertig("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr);
    		$niqerfasst[$m] =  $this->teilnehmerRepository->count4Statusniqerfasst("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr);

    		$days4beratung[$m] =  $this->beratungRepository->days4Beratungfertig("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr);
    		$days4wartezeit[$m] =  $this->beratungRepository->days4Wartezeit("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr);
    	}
    	for($m = 1; $m <= $diesermonat; $m++) {
    		$angemeldeteTN[$m] = $this->teilnehmerRepository->count4Status("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr);
    		$qfolgekontakte[$m] = $this->folgekontaktRepository->count4Status("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr);
    		$erstberatung[$m] = $this->beratungRepository->count4StatusErstberatung("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr);
    		$beratungfertig[$m] = $this->beratungRepository->count4StatusBeratungfertig("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr);
    		$niqerfasst[$m] = $this->teilnehmerRepository->count4Statusniqerfasst("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr);

    		$days4beratung[$m] =  $this->beratungRepository->days4Beratungfertig("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr);
    		$days4wartezeit[$m] = $this->beratungRepository->days4Wartezeit("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr);
    	}

    	/*
    	 * durchschnittl. Tage Beratung abgeschl. und durchschnittl. Tage Wartezeit	berechnen
    	 */
    	$anz4avgmonthb = 0;
    	$anz4avgmonthw = 0;
    	for($n = 1; $n <= 12; $n++) {
    		$diffdaysb = 0;
    		for($k = 0; $k < count($days4beratung[$n]); $k++) {
    			$dat1 = new Datetime($days4beratung[$n][$k]->getDatum());
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
    		    if($days4wartezeit[$n][$k]->getTeilnehmer() != null) {
        			$dat1 = new DateTime();
        			$dat1->setTimestamp($days4wartezeit[$n][$k]->getTeilnehmer()->getVerificationDate());
        			$dat2 = new Datetime($days4wartezeit[$n][$k]->getDatum());
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
    	//DebuggerUtility::var_dump($angemeldeteTN);
    	//die;

    	$aktuelleanmeldungen = count($this->teilnehmerRepository->findAllOrder4List("crdate", 'DESC'));
    	$aktuellerstberatungen = count($this->beratungRepository->findAllOrder4List(2, "crdate", 'DESC'));
    	$aktuellberatungenfertig = count($beratungen = $this->beratungRepository->findAllOrder4List(3, "crdate", 'DESC'));
    	$archivierttotal = count($this->beratungRepository->findAllOrder4List(4, "crdate", 'DESC'));

    	$historie = $this->historieRepository->findAllDesc();

    	$this->view->assign('monatsnamen', $monatsnamen);
    	$this->view->assign('aktmonat', $diesermonat-1);

		$this->view->assign('angemeldeteTN', $angemeldeteTN);
		$this->view->assign('SUMangemeldeteTN', array_sum($angemeldeteTN));

		$this->view->assign('qfolgekontakte', $qfolgekontakte);
		$this->view->assign('SUMqfolgekontakte',  array_sum($qfolgekontakte));

		$this->view->assign('erstberatung', $erstberatung);
		$this->view->assign('SUMerstberatung',  array_sum($erstberatung));

		$this->view->assign('beratungfertig', $beratungfertig);
		$this->view->assign('SUMberatungfertig',  array_sum($beratungfertig));

		$this->view->assign('niqerfasst', $niqerfasst);
		$this->view->assign('SUMniqerfasst',  array_sum($niqerfasst));

		$this->view->assign('totalavgmonthb', $totalavgmonthb);
		$this->view->assign('SUMtotalavgmonthb',  $anz4avgmonthb > 0 ? array_sum($totalavgmonthb)/$anz4avgmonthb : 0);
		
		$this->view->assign('totalavgmonthw', $totalavgmonthw);
		$this->view->assign('SUMtotalavgmonthw',  $anz4avgmonthw > 0 ? array_sum($totalavgmonthw)/$anz4avgmonthw : 0);

		$this->view->assign('aktuelleanmeldungen', $aktuelleanmeldungen);
		$this->view->assign('aktuellerstberatungen', $aktuellerstberatungen);
		$this->view->assign('aktuellberatungenfertig', $aktuellberatungenfertig);
		$this->view->assign('archivierttotal', $archivierttotal);

		$this->view->assign('historie', $historie);

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
    }

    /**
     * action listangemeldet
     *
     * @return void
     */
    public function listangemeldetAction()
    {
    	$valArray = $this->request->getArguments();
    	//DebuggerUtility::var_dump($valArray);

    	if(empty($valArray['orderby'])) {
    		$orderby = 'crdate';
    		$GLOBALS['TSFE']->fe_user->setKey('ses', 'listangemeldetorder', 'DESC');
    		$order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listangemeldetorder');
    	} else {
    		$orderby = $valArray['orderby'];
    		$order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listangemeldetorder');
    		if(!isset($valArray['@widget_0'])) {
    		    $order = $order == 'DESC' ? 'ASC' : 'DESC';
    		    $GLOBALS['TSFE']->fe_user->setKey('ses', 'listangemeldetorder', $order);
    		}
    	}

    	$teilnehmers = $this->setfilter(0, $valArray, $orderby, $order);

    	for($j=0; $j < count($teilnehmers); $j++) {
    	    $anz = $this->teilnehmerRepository->findDublette4Angemeldet($teilnehmers[$j]->getNachname(), $teilnehmers[$j]->getVorname());
    	    if($anz > 1) $teilnehmers[$j]->setDublette(TRUE);
    	}
    	//DebuggerUtility::var_dump($anz);

    	$this->view->assign('teilnehmers', $teilnehmers);
    	$this->view->assign('calleraction', 'listangemeldet');
    	$this->view->assign('callercontroller', 'Teilnehmer');
    }

    /**
     * action listdeleted
     *
     * @return void
     */
    public function listdeletedAction()
    {
    	$valArray = $this->request->getArguments();

    	if(empty($valArray['orderby'])) {
    		$orderby = 'crdate';
    		$GLOBALS['TSFE']->fe_user->setKey('ses', 'listdeletedorder', 'DESC');
    		$order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listdeletedorder');
    	} else {
    	    $orderby = $valArray['orderby'];
    	    $order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listdeletedorder');
    	    if(!isset($valArray['@widget_0'])) {
    	        $order = $order == 'DESC' ? 'ASC' : 'DESC';
    	        $GLOBALS['TSFE']->fe_user->setKey('ses', 'listdeletedorder', $order);
    	    }
    	}

    	$teilnehmers = $this->setfilter(1, $valArray, $orderby, $order);

    	for($j=0; $j < count($teilnehmers); $j++) {
    	    $anz = $this->teilnehmerRepository->findDublette4Deleted($teilnehmers[$j]->getNachname(), $teilnehmers[$j]->getVorname());
    		if($anz > 1) $teilnehmers[$j]->setDublette(TRUE);
    	}
    	$this->view->assign('teilnehmers', $teilnehmers);
    	$this->view->assign('calleraction', 'listdeleted');
    	$this->view->assign('callercontroller', 'Teilnehmer');
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

    	$historie = $this->historieRepository->findByTeilnehmerOrdered($teilnehmer->getUid());
    	$this->view->assign('historie', $historie);

    	$dokumente = $this->dokumentRepository->findByTeilnehmer($teilnehmer);

    	$this->view->assign('dokumente', $dokumente);
    	$valArray = $this->request->getArguments();
    	$this->view->assign('calleraction', $valArray['calleraction']);
    	$this->view->assign('callercontroller', $valArray['callercontroller']);
    }

    /**
     * action new
     *
     * @return void
     */
    public function newAction()
    {
        $valArray = $this->request->getArguments();
        
        $this->view->assign('calleraction', $valArray['calleraction']);
        $this->view->assign('callercontroller', $valArray['callercontroller']);

    }

    /**
     * action create
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function createAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
    	//$this->addFlashMessage('Teilnehmer wurde erstellt.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
    	$this->teilnehmerRepository->add($teilnehmer);

    	// Daten sofort in die Datenbank schreiben
    	$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
    	$persistenceManager->persistAll();

    	$valArray = $this->request->getArguments();
    	$this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('teilnehmer' => $teilnehmer));
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

    	$dokumente = $this->dokumentRepository->findByTeilnehmer($teilnehmer);

    	$this->view->assign('dokumente', $dokumente);

    	$valArray = $this->request->getArguments();
    	$this->view->assign('calleraction', $valArray['calleraction']);
    	$this->view->assign('callercontroller', $valArray['callercontroller']);
    }

    /**
     * action update
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function updateAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
    	$username = $GLOBALS['TSFE']->fe_user->user['username'];
    	$berater = $this->beraterRepository->findOneByKuerzel($username);
    	if($berater == NULL) {
    		$berater = $this->beraterRepository->findOneByKuerzel('admin');
    	}

		$this->createHistory($teilnehmer, $berater, "niqchiffre");
		$this->createHistory($teilnehmer, $berater, "schonberaten");
		$this->createHistory($teilnehmer, $berater, "schonberatenvon");
		$this->createHistory($teilnehmer, $berater, "nachname");
		$this->createHistory($teilnehmer, $berater, "vorname");
		$this->createHistory($teilnehmer, $berater, "plz");
		$this->createHistory($teilnehmer, $berater, "ort");
		$this->createHistory($teilnehmer, $berater, "email");
		$this->createHistory($teilnehmer, $berater, "telefon");
		$this->createHistory($teilnehmer, $berater, "lebensalter");
		$this->createHistory($teilnehmer, $berater, "geburtsland");
		$this->createHistory($teilnehmer, $berater, "geschlecht");
		$this->createHistory($teilnehmer, $berater, "ersteStaatsangehoerigkeit");
		$this->createHistory($teilnehmer, $berater, "zweiteStaatsangehoerigkeit");
		$this->createHistory($teilnehmer, $berater, "einreisejahr");
		$this->createHistory($teilnehmer, $berater, "wohnsitzDeutschland");
		$this->createHistory($teilnehmer, $berater, "wohnsitzNeinIn");
		$this->createHistory($teilnehmer, $berater, "ortskraftafghanistan");
		$this->createHistory($teilnehmer, $berater, "deutschkenntnisse");
		$this->createHistory($teilnehmer, $berater, "zertifikatdeutsch");
		$this->createHistory($teilnehmer, $berater, "zertifikatSprachniveau");
		$this->createHistory($teilnehmer, $berater, "abschlussart1");
		$this->createHistory($teilnehmer, $berater, "abschlussart2");
		$this->createHistory($teilnehmer, $berater, "erwerbsland1");
		$this->createHistory($teilnehmer, $berater, "dauerBerufsausbildung1");
		$this->createHistory($teilnehmer, $berater, "abschlussjahr1");
		$this->createHistory($teilnehmer, $berater, "ausbildungsinstitution1");
		$this->createHistory($teilnehmer, $berater, "ausbildungsort1");
		$this->createHistory($teilnehmer, $berater, "abschluss1");
		$this->createHistory($teilnehmer, $berater, "berufserfahrung1");
		$this->createHistory($teilnehmer, $berater, "ausbildungsfremdeberufserfahrung1");
		$this->createHistory($teilnehmer, $berater, "deutscherReferenzberuf1");
		$this->createHistory($teilnehmer, $berater, "wunschberuf1");
		$this->createHistory($teilnehmer, $berater, "erwerbsland2");
		$this->createHistory($teilnehmer, $berater, "dauerBerufsausbildung2");
		$this->createHistory($teilnehmer, $berater, "abschlussjahr2");
		$this->createHistory($teilnehmer, $berater, "ausbildungsinstitution2");
		$this->createHistory($teilnehmer, $berater, "ausbildungsort2");
		$this->createHistory($teilnehmer, $berater, "abschluss2");
		$this->createHistory($teilnehmer, $berater, "berufserfahrung2");
		$this->createHistory($teilnehmer, $berater, "ausbildungsfremdeberufserfahrung2");
		$this->createHistory($teilnehmer, $berater, "deutscherReferenzberuf2");
		$this->createHistory($teilnehmer, $berater, "wunschberuf2");
		$this->createHistory($teilnehmer, $berater, "erwerbsstatus");
		$this->createHistory($teilnehmer, $berater, "leistungsbezugjanein");
		$this->createHistory($teilnehmer, $berater, "leistungsbezug");
		$this->createHistory($teilnehmer, $berater, "einwilligungdatenanAA");
		$this->createHistory($teilnehmer, $berater, "einwilligungdatenanAAdatum");
		$this->createHistory($teilnehmer, $berater, "einwilligungdatenanAAmedium");
		$this->createHistory($teilnehmer, $berater, "name_beraterAA");
		$this->createHistory($teilnehmer, $berater, "kontakt_beraterAA");
		$this->createHistory($teilnehmer, $berater, "kundennummerAA");
		$this->createHistory($teilnehmer, $berater, "einwAnerkstelle");
		$this->createHistory($teilnehmer, $berater, "einwAnerkstelledatum");
		$this->createHistory($teilnehmer, $berater, "einwAnerkstellemedium");
		$this->createHistory($teilnehmer, $berater, "einwAnerkstellename");
		$this->createHistory($teilnehmer, $berater, "einwAnerkstellekontakt");
		$this->createHistory($teilnehmer, $berater, "einwPerson");
		$this->createHistory($teilnehmer, $berater, "einwPersondatum");
		$this->createHistory($teilnehmer, $berater, "einwPersonmedium");
		$this->createHistory($teilnehmer, $berater, "einwPersonname");
		$this->createHistory($teilnehmer, $berater, "einwPersonkontakt");
		$this->createHistory($teilnehmer, $berater, "aufenthaltsstatus");
		$this->createHistory($teilnehmer, $berater, "aufenthaltsstatusfreitext");
		$this->createHistory($teilnehmer, $berater, "fruehererAntrag");
		$this->createHistory($teilnehmer, $berater, "fruehererAntragReferenzberuf");
		$this->createHistory($teilnehmer, $berater, "fruehererAntragInstitution");
		$this->createHistory($teilnehmer, $berater, "bescheidfruehererAnerkennungsantrag");
		$this->createHistory($teilnehmer, $berater, "nameBeratungsstelle");
		$this->createHistory($teilnehmer, $berater, "notizen");

		// Daten sofort in die Datenbank schreiben
		$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
		$persistenceManager->persistAll();

    	$bstatus = $this->checkberatungsstatus($teilnehmer);
    	$teilnehmer->setBeratungsstatus($bstatus);
    	$this->teilnehmerRepository->update($teilnehmer);

    	// Daten sofort in die Datenbank schreiben
    	$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
    	$persistenceManager->persistAll();

    	$valArray = $this->request->getArguments();
    	$this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, null);
    }

    /**
     * createHistory
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @param \Ud\Iqtp13db\Domain\Model\Berater $berater
     * @param string $property
     * @return void
     */
    public function createHistory(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer, \Ud\Iqtp13db\Domain\Model\Berater $berater, $property)
    {
    	if($teilnehmer->_isDirty($property)) {
    		$history = new \Ud\Iqtp13db\Domain\Model\Historie();

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
    	$teilnehmer->setHidden(1);

    	$this->teilnehmerRepository->update($teilnehmer);

    	// Daten sofort in die Datenbank schreiben
    	$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
    	$persistenceManager->persistAll();

    	$this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, null);
    	//$this->redirect('listangemeldet');
    }

    /**
     * action undelete
     *
     *  @param int $tnuid
     * @return void
     */
    public function undeleteAction()
    {
        if ($this->request->hasArgument('tnuid')) {
            $tnuid = $this->request->getArgument('tnuid');
            
            $teilnehmer = $this->teilnehmerRepository->findHiddenByUid($tnuid);
            
            $teilnehmer->setHidden(0);
            
            $this->teilnehmerRepository->update($teilnehmer);
            
            // Daten sofort in die Datenbank schreiben
            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
        } else {
            $this->addFlashMessage('Fehler in undeleteAction, tnuid nicht vorhanden.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        }
        
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
                'baseurl' => $this->request->getBaseUri(),
                'registrationpageuid' => $this->settings['registrationpageuid'],
                'askconsent' => '1'
            );
            $this->sendTemplateEmail(array($recipient), array($bcc), array($sender), $subject, $templateName, $variables, false);
            
            $this->addFlashMessage('Einwilligungsanforderung versendet.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
            $this->redirect('listangemeldet', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
        }
    }

    /**
     * Check Beratungsstatus
     *
     * Beratungsstatus: 0 = angemeldet, 1 = Anmeldung bestätigt, 2 = Erstberatung Start, 3 = Erstberatung abgeschlossen, 4 = NIQ erfasst
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return int
     */
    public function checkberatungsstatus(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer) {
    	$beratung = $this->beratungRepository->findByTeilnehmer($teilnehmer->getUid());
    	$beratung = $beratung[0];
    	if($beratung != NULL) {
    		if($teilnehmer->getVerificationDate() == 0 && $beratung->getDatum() == '' && $beratung->getErstberatungabgeschlossen() == '' && $teilnehmer->getNiqchiffre() == '') {
    			$beratungsstatus = 0;
    		} elseif($teilnehmer->getVerificationDate() > 0 && $beratung->getDatum() == '' && $beratung->getErstberatungabgeschlossen() == '' && $teilnehmer->getNiqchiffre() == '') {
    			$beratungsstatus = 1;
    		} elseif($teilnehmer->getVerificationDate() > 0 && $beratung->getDatum() != '' && $beratung->getErstberatungabgeschlossen() == '' && $teilnehmer->getNiqchiffre() == '') {
    			$beratungsstatus = 2;
    		} elseif($teilnehmer->getVerificationDate() > 0 && $beratung->getDatum() != '' && $beratung->getErstberatungabgeschlossen() != '' && $teilnehmer->getNiqchiffre() == '') {
    			$beratungsstatus = 3;
    		} elseif($teilnehmer->getVerificationDate() > 0 && $beratung->getDatum() != '' && $beratung->getErstberatungabgeschlossen() != '' && $teilnehmer->getNiqchiffre() != '') {
    			$beratungsstatus = 4;
    		} else {
    		 	if($teilnehmer->getVerificationDate() > 0) {
    				$beratungsstatus = 1;
    			} else {
    				$beratungsstatus = 0;
    			}
    		}
    	} else {
    		if($teilnehmer->getVerificationDate() > 0) {
    			$beratungsstatus = 1;
    		} else {
    			$beratungsstatus = 0;
    		}
    	}

    	return $beratungsstatus;
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
            $fname = $valArray['name'];
            $fort = $valArray['ort'];
            $fberuf = $valArray['beruf'];
            $fland = $valArray['land'];

        	$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_iqtp13db_domain_model_teilnehmer');

        	$arraytoexport = $valArray['chktoexport'];

        	if(is_array($arraytoexport)) {
				$strlisttoexport = implode(",", $arraytoexport);

				$rows = $queryBuilder
						->select('niqchiffre', 'nachname', 'vorname', 'plz', 'ort', 'email', 'telefon', 'lebensalter', 'geschlecht', 'erste_staatsangehoerigkeit', 'zweite_staatsangehoerigkeit',
								'einreisejahr', 'wohnsitz_deutschland', 'wohnsitz_nein_in', 'ortskraftafghanistan', 'zertifikat_sprachniveau', 'erwerbsland1', 'erwerbsland2',
								'abschluss1', 'abschluss2', 'deutscher_referenzberuf1', 'deutscher_referenzberuf2', 'bescheidfrueherer_anerkennungsantrag', 'name_beratungsstelle')
						->from('tx_iqtp13db_domain_model_teilnehmer')
						->where(
						    $queryBuilder->expr()->in('tx_iqtp13db_domain_model_teilnehmer.uid', $queryBuilder->createNamedParameter(GeneralUtility::intExplode(',', $strlisttoexport, true), Connection::PARAM_INT_ARRAY))
						 )
						->execute()
						->fetchAll();
        	} else {
        		$rows = $queryBuilder
        		->select('niqchiffre', 'b.erstberatungabgeschlossen', 'b.beratungsart', 'c.kuerzel', 'nachname', 'vorname', 'plz', 'ort', 'email', 'telefon', 'lebensalter', 'geschlecht',
						'erste_staatsangehoerigkeit', 'zweite_staatsangehoerigkeit', 'einreisejahr', 'wohnsitz_deutschland', 'wohnsitz_nein_in', 'ortskraftafghanistan', 'zertifikat_sprachniveau',
						'erwerbsland1', 'erwerbsland2', 'abschluss1', 'abschluss2', 'deutscher_referenzberuf1', 'deutscher_referenzberuf2', 'bescheidfrueherer_anerkennungsantrag', 'b.beratungzu', 'name_beratungsstelle')
						->from('tx_iqtp13db_domain_model_teilnehmer')
						->leftJoin('tx_iqtp13db_domain_model_teilnehmer', 'tx_iqtp13db_domain_model_beratung', 'b',	$queryBuilder->expr()->eq('b.teilnehmer', $queryBuilder->quoteIdentifier('tx_iqtp13db_domain_model_teilnehmer.uid')))
						->leftJoin('b', 'tx_iqtp13db_domain_model_berater', 'c', $queryBuilder->expr()->eq('c.uid', $queryBuilder->quoteIdentifier('b.berater')))
						->execute()
						->fetchAll();
        	}

            $filename = 'export_' . date('Y-m-d_H-i', time()) . '.csv';
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename=' . $filename);
            $fp = fopen('php://output', 'w');

            // output header row (if at least one row exists)
            if (count($rows) > 0) {
                fputcsv($fp, array_keys($rows[0]));
            }

            for($i=0; $i < count($rows); $i++) {
                fputcsv($fp, $rows[$i]);
            }
            fclose($fp);
            exit;
        } else {

        	//$teilnehmers = $this->teilnehmerRepository->findAll();
        	$orderby = 'crdate';
        	$order = 'ASC';
        	$teilnehmers = $this->setfilter(0, $valArray, $orderby, $order);

        	for($j=0; $j < count($teilnehmers); $j++) {
        	    $anz = $this->teilnehmerRepository->findDublette4Angemeldet($teilnehmers[$j]->getNachname(), $teilnehmers[$j]->getVorname());
        		if($anz > 1) $teilnehmers[$j]->setDublette(TRUE);
        	}

        	$this->view->assign('teilnehmers', $teilnehmers);
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

    	$this->view->assign('tnseite1', $tnseite1);
    	$this->view->assign('settings', $this->settings);
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
    	    
    	    $this->redirect('anmeldseite2');
    	} else {
    	    $this->cancelregistration(null);
    	}
    	
    }

    /**
     * action anmeldseite2
     *
     * @param \Ud\Iqtp13db\Domain\Model\TNSeite2 $tnseite2
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
    	$GLOBALS['TSFE']->fe_user->setKey('ses', 'tnseite2', serialize($tnseite2));

    	$valArray = $this->request->getArguments();
    	//DebuggerUtility::var_dump($valArray);
    	//die;
    	if (isset($valArray['btnzurueck'])) {
    		$this->redirect('anmeldseite1');
    	} elseif(isset($valArray['btnweiter'])) {
    	    $this->redirect('anmeldseite3');
    	} else {
    	    $this->cancelregistration(null);
    	}
    }

    /**
     * action anmeldseite3
     *
     * @param \Ud\Iqtp13db\Domain\Model\TNSeite3 $tnseite3
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

    	$GLOBALS['TSFE']->fe_user->setKey('ses', 'tnseite3', serialize($tnseite3));

    	$valArray = $this->request->getArguments();
    	if (isset($valArray['btnzurueck'])) {
    		$this->redirect('anmeldseite2');
    	} elseif(isset($valArray['btnweiter'])) {
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
    	$storage = $this->getTP13Storage($newFilePath);
    	$foldersize = $this->getFolderSize($storage->getConfiguration()['basePath'].$newFilePath);
    	if(!is_numeric($foldersize)) $foldersize = 0;
    	$dokumente = $this->dokumentRepository->findByTeilnehmer($teilnehmer);
    	$this->view->assign('heute', time());
    	$this->view->assign('teilnehmer', $teilnehmer);
    	$this->view->assign('dokumente', $dokumente);
    	$this->view->assign('foldersize', 100-(intval(($foldersize/30000)*100)));
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
    		$this->redirect('anmeldseite3');
    	} elseif(isset($valArray['btnAbsenden'])) {
    	    $tfolder = $this->createFolder($teilnehmer);
    	    $teilnehmer->setBeratungsstatus(0);
    	    $this->teilnehmerRepository->update($teilnehmer);

    	    $bcc = $this->settings['bccmail'];
    	    $sender = $this->settings['sender'];
    	    if($bcc == '' || $sender == '') {
    	        $this->addFlashMessage('Fehler 102.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
    	        $this->redirect('anmeldungcomplete', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
    	    } else {
    	        $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('tnseite1', null);
    	        $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('tnseite2', null);
    	        $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('tnseite3', null);
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
    	            'baseurl' => $this->request->getBaseUri(),
    	            'registrationpageuid' => $this->settings['registrationpageuid'],
    	            'askconsent' => '0'
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

    	//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($askconsent);
    	//die;
    	if($teilnehmer) {
    		// it's a valid verificationCode
    		$teilnehmer->setVerificationDate(new \DateTime);
    		$teilnehmer->setVerificationIp($_SERVER['REMOTE_ADDR']);
    		$this->teilnehmerRepository->update($teilnehmer);

    		if($askconsent == 0) $this->sendconfirmedMail($teilnehmer);


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
            $storage = $this->getTP13Storage($filePath);
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

        //DebuggerUtility::var_dump(count($dokumente));
        //die;
        $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('tnseite1', null);
        $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('tnseite2', null);
        $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('tnseite3', null);
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
    	$typo3VersionArray = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionStringToArray(\TYPO3\CMS\Core\Utility\VersionNumberUtility::getCurrentTypo3Version());
    	$this->typo3VersionMain = $typo3VersionArray['version_main'];
    	if ($this->typo3VersionMain > 9) {
    	    $message->setBody()->html($emailBody);    	    
    	} else {
    	    $message->setBody($emailBody, 'text/html');
    	}
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
    	$tnseite2 = unserialize($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnseite2'));
    	$tnseite3 = unserialize($GLOBALS['TSFE']->fe_user->getKey('ses', 'tnseite3'));
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
    	    $teilnehmer->setOrtskraftafghanistan($tnseite1->getOrtskraftafghanistan() == true ? 1 : 0);
    	}
    	if($tnseite2) {
    	    $teilnehmer->setDeutschkenntnisse($tnseite2->getDeutschkenntnisse());
    	    $teilnehmer->setZertifikatSprachniveau($tnseite2->getZertifikatSprachniveau());
    	    $teilnehmer->setZertifikatdeutsch($tnseite2->getZertifikatdeutsch());
    	    $teilnehmer->setAbschlussart1($tnseite2->getAbschlussart1());
    	    $teilnehmer->setAbschlussart2($tnseite2->getAbschlussart2());
    	    $teilnehmer->setErwerbsland1($tnseite2->getErwerbsland1());
    	    $teilnehmer->setDauerBerufsausbildung1($tnseite2->getDauerBerufsausbildung1());
    	    $teilnehmer->setAbschlussjahr1($tnseite2->getAbschlussjahr1());
    	    $teilnehmer->setAusbildungsinstitution1($tnseite2->getAusbildungsinstitution1());
    	    $teilnehmer->setAusbildungsort1($tnseite2->getAusbildungsort1());
    	    $teilnehmer->setAbschluss1($tnseite2->getAbschluss1());
    	    $teilnehmer->setBerufserfahrung1($tnseite2->getBerufserfahrung1());
    	    $teilnehmer->setAusbildungsfremdeberufserfahrung1($tnseite2->getAusbildungsfremdeberufserfahrung1());
    	    $teilnehmer->setDeutscherReferenzberuf1($tnseite2->getDeutscherReferenzberuf1());
    	    $teilnehmer->setErwerbsland2($tnseite2->getErwerbsland2());
    	    $teilnehmer->setDauerBerufsausbildung2($tnseite2->getDauerBerufsausbildung2());
    	    $teilnehmer->setAbschlussjahr2($tnseite2->getAbschlussjahr2());
    	    $teilnehmer->setAusbildungsinstitution2($tnseite2->getAusbildungsinstitution2());
    	    $teilnehmer->setAusbildungsort2($tnseite2->getAusbildungsort2());
    	    $teilnehmer->setAbschluss2($tnseite2->getAbschluss2());
    	    $teilnehmer->setBerufserfahrung2($tnseite2->getBerufserfahrung2());
    	    $teilnehmer->setAusbildungsfremdeberufserfahrung2($tnseite2->getAusbildungsfremdeberufserfahrung2());
    	    $teilnehmer->setDeutscherReferenzberuf2($tnseite2->getDeutscherReferenzberuf2());

    	}
    	if($tnseite3) {
    	    $teilnehmer->setErwerbsstatus($tnseite3->getErwerbsstatus());
    	    $teilnehmer->setLeistungsbezugjanein($tnseite3->getLeistungsbezugjanein());
    	    $teilnehmer->setLeistungsbezug($tnseite3->getLeistungsbezug());
    	    $teilnehmer->setEinwilligungdatenanaa($tnseite3->getEinwilligungdatenanaa());
    	    if($tnseite3->getEinwilligungdatenanaa() == 1) {
    	        $thisdate = new DateTime();
    	        $teilnehmer->setEinwilligungdatenanaadatum($thisdate->format('d.m.Y'));
    	        $teilnehmer->setEinwilligungdatenanaamedium(explode(',', 4));
    	    } else {
    	        $teilnehmer->setEinwilligungdatenanaadatum('');
    	        $teilnehmer->setEinwilligungdatenanaamedium(array());
    	    }
    	    $teilnehmer->setNameBeraterAA($tnseite3->getNameBeraterAA());
    	    $teilnehmer->setKontaktBeraterAA($tnseite3->getKontaktBeraterAA());
    	    $teilnehmer->setKundennummerAA($tnseite3->getKundennummerAA());
    	    $teilnehmer->setFruehererAntrag($tnseite3->getFruehererAntrag());
    	    $teilnehmer->setFruehererAntragReferenzberuf($tnseite3->getFruehererAntragReferenzberuf());
    	    $teilnehmer->setFruehererAntragInstitution($tnseite3->getFruehererAntragInstitution());
    	    $teilnehmer->setBescheidfruehererAnerkennungsantrag($tnseite3->getBescheidfruehererAnerkennungsantrag());
    	    $teilnehmer->setNameBeratungsstelle($tnseite3->getNameBeratungsstelle());
    	    $teilnehmer->setNotizen ( $tnseite3->getNotizen () );
    	}

		return $teilnehmer;
	}

	function setfilter(int $type, array $valArray, $orderby, $order) {
	    // FILTER
	    if ($valArray['filteraus']) {
	        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fname', NULL);
	        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fort', NULL);
	        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fberuf', NULL);
	        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fland', NULL);
	    }
	    if ($valArray['filteran']) {
	        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fname', $valArray['name']);
	        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fort', $valArray['ort']);
	        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fberuf', $valArray['beruf']);
	        $GLOBALS['TSFE']->fe_user->setKey('ses', 'fland', $valArray['land']);
	    }
	    $fname = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fname');
	    $fort = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fort');
	    $fberuf = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fberuf');
	    $fland = $GLOBALS['TSFE']->fe_user->getKey('ses', 'fland');
	    if ($fname == '' && $fort == '' && $fberuf == '' && $fland == '') {
	        if($type == 0) {
	            $teilnehmers = $this->teilnehmerRepository->findAllOrder4List($orderby, $order);
	        } elseif($type == 1) {
	            $teilnehmers = $this->teilnehmerRepository->findhidden4list($orderby, $order);
	        } else {

	        }
	    } else {
	        $teilnehmers = $this->teilnehmerRepository->searchTeilnehmer($fname, $fort, $fberuf, $fland, $type);
	        $this->view->assign('filtername', $fname);
	        $this->view->assign('filterort', $fort);
	        $this->view->assign('filterberuf', $fberuf);
	        $this->view->assign('filterland', $fland);
	        $this->view->assign('filteron', 1);
	    }
	    // FILTER bis hier
	    return $teilnehmers;
	}

	public function createFolder($teilnehmer)
	{
	    $targetFolder = '';

	    $pfad = 'Beratene/' . $teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid(). '/';

	    $storage = $this->getTP13Storage($pfad);

	    if (!$storage->hasFolder($pfad)) {
	        $targetFolder = $storage->createFolder($pfad);
	    }

	    return $targetFolder;
	}

	function getTP13Storage($pfad) {
		$storageRepository = $this->objectManager->get ( 'TYPO3\\CMS\\Core\\Resource\\StorageRepository' );
		// Speicher 'tp13data' muss im Typo3-Backend auf der Root-Seite als "Dateispeicher" angelegt sein!
		// wenn der Speicher mal nicht verfügbar war (temporär), muss er im Backend im Bereich "Dateispeicher" manuell wieder "online" geschaltet werden mit der Checkbox "ist online?" in den Eigenschaften des jeweiligen Dateispeichers
		$storages = $storageRepository->findAll ();
		foreach ( $storages as $s ) {
			$storageObject = $s;
			$storageRecord = $storageObject->getStorageRecord ();
			if ($storageRecord ['name'] == 'tp13data') {
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

}
