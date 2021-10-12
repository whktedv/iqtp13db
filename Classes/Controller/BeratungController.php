<?php
namespace Ud\Iqtp13db\Controller;
use \Datetime;

/***
 *
 * This file is part of the "IQ Webapp Anerkennungsberatung" Extension for TYPO3 CMS.
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

	/**
	 * folgekontaktRepository
	 *
	 * @var \Ud\Iqtp13db\Domain\Repository\FolgekontaktRepository
	 * @TYPO3\CMS\Extbase\Annotation\Inject
	 */
	protected $folgekontaktRepository = NULL;
	
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
	 * beraterRepository
	 *
	 * @var \Ud\Iqtp13db\Domain\Repository\BeraterRepository
	 * @TYPO3\CMS\Extbase\Annotation\Inject
	 */
	protected $beraterRepository = NULL;
	
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
        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->arguments['beratung']);
        //die;
        
    	/*
    	 * PropertyMapping für die multiple ankreuzbaren Checkboxen. 
    	 * Annehmen eines String-Arrays, das im Setter und Getter des Models je per implode/explode wieder in Strings bzw. Array (of Strings) konvertiert wird
    	 */ 
        if ($this->arguments->hasArgument('beratung')) {
            $this->arguments->getArgument('beratung')->getPropertyMappingConfiguration()->allowProperties('beratungsart');
            $this->arguments->getArgument('beratung')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('beratungsart', 'array');

            $this->arguments->getArgument('beratung')->getPropertyMappingConfiguration()->allowProperties('beratungzu');
            $this->arguments->getArgument('beratung')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('beratungzu', 'array');
            
            $this->arguments->getArgument('beratung')->getPropertyMappingConfiguration()->allowProperties('anerkennungsberatung');
            $this->arguments->getArgument('beratung')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('anerkennungsberatung', 'array');

            $this->arguments->getArgument('beratung')->getPropertyMappingConfiguration()->allowProperties('qualifizierungsberatung');
            $this->arguments->getArgument('beratung')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('qualifizierungsberatung', 'array');
        }
        
    }
    
	
	/**
	 * action listerstberatung
	 *
	 * @return void
	 */
	public function listerstberatungAction()
	{
		$valArray = $this->request->getArguments();
		
		if(empty($valArray['orderby'])) {
			$orderby = 'crdate';
			$GLOBALS['TSFE']->fe_user->setKey('ses', 'listerstberatungorder', 'DESC');
			$order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listerstberatungorder');
		} else {
			$orderby = $valArray['orderby'];
			$order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listerstberatungorder');
			if(!isset($valArray['@widget_0'])) {
    			$order = $order == 'DESC' ? 'ASC' : 'DESC';
    			$GLOBALS['TSFE']->fe_user->setKey('ses', 'listerstberatungorder', $order);
			}
		}
		
		$beratungen = $this->setfilter(2, $valArray, $orderby, $order);
			
		foreach ($beratungen as $key => $bn) {
			$tn = $bn->getTeilnehmer();
			$anzfolgekontakte[$key] = count($this->folgekontaktRepository->findByTeilnehmer($tn->getUid()));
			$folgekontakte[$key] = $this->folgekontaktRepository->findByTeilnehmer($tn->getUid());
		}
		
		$this->view->assign('anzfolgekontakte', $anzfolgekontakte);
		$this->view->assign('folgekontakte', $folgekontakte);
		$this->view->assign('beratungen', $beratungen);
		$this->view->assign('anztn', count($beratungen));
		$this->view->assign('calleraction', 'listerstberatung');
		$this->view->assign('callercontroller', 'Beratung');
		
	}
	
	/**
	 * action listniqerfassung
	 *
	 * @return void
	 */
	public function listniqerfassungAction()
	{
		$valArray = $this->request->getArguments();
		
		if(empty($valArray['orderby'])) {
			$orderby = 'crdate';
			$GLOBALS['TSFE']->fe_user->setKey('ses', 'listniqerfassungorder', 'DESC');
			$order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listniqerfassungorder');
		} else {
			$orderby = $valArray['orderby'];
			$order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listniqerfassungorder');
			if(!isset($valArray['@widget_0'])) {
    			$order = $order == 'DESC' ? 'ASC' : 'DESC';
    			$GLOBALS['TSFE']->fe_user->setKey('ses', 'listniqerfassungorder', $order);
			}
		}
		
		$beratungen = $this->setfilter(3, $valArray, $orderby, $order);

		foreach ($beratungen as $key => $bn) {
			$tn = $bn->getTeilnehmer();
			$anzfolgekontakte[$key] = count($this->folgekontaktRepository->findByTeilnehmer($tn->getUid()));
		}
		
		$folgekontakte = $this->folgekontaktRepository->findAll();
		
		$this->view->assign('anzfolgekontakte', $anzfolgekontakte);
		$this->view->assign('folgekontakte', $folgekontakte);		
		$this->view->assign('beratungen', $beratungen);
		$this->view->assign('calleraction', 'listniqerfassung');
		$this->view->assign('callercontroller', 'Beratung');
		
	}
	
	/**
	 * action listarchiv
	 *
	 * @return void
	 */
	public function listarchivAction()
	{
		$valArray = $this->request->getArguments();
		
		if(empty($valArray['orderby'])) {
			$orderby = 'crdate';
			$GLOBALS['TSFE']->fe_user->setKey('ses', 'listarchivorder', 'DESC');
			$order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listarchivorder');
		} else {
			$orderby = $valArray['orderby'];
			$order = $GLOBALS['TSFE']->fe_user->getKey('ses', 'listarchivorder');
			if(!isset($valArray['@widget_0'])) {
    			$order = $order == 'DESC' ? 'ASC' : 'DESC';
    			$GLOBALS['TSFE']->fe_user->setKey('ses', 'listarchivorder', $order);
			}
		}
		
		$beratungen = $this->setfilter(4, $valArray, $orderby, $order);
		
		foreach ($beratungen as $key => $bn) {
			$tn = $bn->getTeilnehmer();
			$anzfolgekontakte[$key] = count($this->folgekontaktRepository->findByTeilnehmer($tn->getUid()));
		}
		
		$folgekontakte = $this->folgekontaktRepository->findAll();
		
		$this->view->assign('anzfolgekontakte', $anzfolgekontakte);
		$this->view->assign('folgekontakte', $folgekontakte);
		$this->view->assign('beratungen', $beratungen);
		$this->view->assign('calleraction', 'listarchiv');
		$this->view->assign('callercontroller', 'Beratung');
		
	}
	 
    /**
     * action show
     *
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @return void
     */
    public function showAction(\Ud\Iqtp13db\Domain\Model\Beratung $beratung)
    {        
        $berater = $this->beraterRepository->findAll();
        $this->view->assign('berater', $berater);
        $this->view->assign('beratung', $beratung);
        
        $teilnehmer = $beratung->getTeilnehmer();        
        $historie = $this->historieRepository->findByTeilnehmerOrdered($teilnehmer->getUid());        
        
        $folgekontakte = $this->folgekontaktRepository->findByTeilnehmer($teilnehmer->getUid());
        
        $this->view->assign('teilnehmer', $teilnehmer);
        $this->view->assign('historie', $historie);
        $this->view->assign('folgekontakte', $folgekontakte);
        
        $valArray = $this->request->getArguments();
        $this->view->assign('calleraction', $valArray['calleraction']);
        $this->view->assign('callercontroller', $valArray['callercontroller']);
    }
	
    /**
     * action new
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function newAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
    	$username = $GLOBALS['TSFE']->fe_user->user['username'];
    	
    	$berater = $this->beraterRepository->findOneByKuerzel($username);
    	if($berater == NULL) {
    		$berater = $this->beraterRepository->findOneByKuerzel('admin');
    	}
    	$this->view->assign('berater', $berater);
    	 
    	$alleberater = $this->beraterRepository->findAll();
    	
    	if(count($alleberater) == 0) {
    	    // keine Berater vorhanden
    	    $this->addFlashMessage('Es sind noch keine Berater:innen vorhanden. Bitte im Menü Berater*innen anlegen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
    	    $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, null);
    	} else {
        	$this->view->assign('alleberater', $alleberater);
            $this->view->assign('teilnehmer', $teilnehmer);
            
            $valArray = $this->request->getArguments();
            $this->view->assign('calleraction', $valArray['calleraction']);
            $this->view->assign('callercontroller', $valArray['callercontroller']);
    	}
    }

	 /**
     * action create
     *
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @return void
     */
    public function createAction(\Ud\Iqtp13db\Domain\Model\Beratung $beratung)
    {  		    	
    	$teilnehmer = $beratung->getTeilnehmer();
    	
    	if(($beratung->getErstberatungabgeschlossen() != '' && (!$this->validateDate($beratung->getErstberatungabgeschlossen()))) || !$this->validateDate($beratung->getDatum())){
    	    $this->addFlashMessage('Syntaxfehler in Datum! Bitte "Datum Erstberatung" oder "Erstberatung abgeschlossen" prüfen und Erstberatung für '.$teilnehmer->getNachname().', '.$teilnehmer->getVorname().' erneut eingeben.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
    	    $valArray = $this->request->getArguments();
    	    $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, null);
    	}
    	    
    	$tn = $this->beratungRepository->findByTeilnehmer($teilnehmer);
    	//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->arguments['beratung']);
    	//die;
    	if(count($tn) > 0) {
    		// Erstberatung für TN schon vorhanden
    		$this->addFlashMessage('Erstberatung von '.$teilnehmer->getNachname().', '.$teilnehmer->getVorname().' schon vorhanden.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
    		$this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, null);
    		
    	} else {
    		//$this->addFlashMessage('Erstberatung von '.$teilnehmer->getNachname().', '.$teilnehmer->getVorname().' wurde erstellt.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
    		$this->beratungRepository->add($beratung);
    		
    		// Daten sofort in die Datenbank schreiben
    		$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
    		$persistenceManager->persistAll();
    		
    		$teilnehmer = $beratung->getTeilnehmer();
    		$bstatus = $this->checkberatungsstatus($teilnehmer);
    		$teilnehmer->setBeratungsstatus($bstatus);
    		$this->teilnehmerRepository->update($teilnehmer);
    		
    		$valArray = $this->request->getArguments();
    		$this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, null);
    		
    	}    	
    }
	
	 /**
     * action edit
     *
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("beratung")
     * @return void
     */
    public function editAction(\Ud\Iqtp13db\Domain\Model\Beratung $beratung)
    {
    	$username = $GLOBALS['TSFE']->fe_user->user['username'];
    	
    	$berater = $this->beraterRepository->findOneByKuerzel($username);
    	if($berater == NULL) {
    		$berater = $this->beraterRepository->findOneByKuerzel('admin');
    	}
    	$this->view->assign('berater', $berater);
    	 
    	$alleberater = $this->beraterRepository->findAll();
    	$this->view->assign('alleberater', $alleberater);

    	$this->view->assign('beratung', $beratung);
    	
    	$valArray = $this->request->getArguments();
    	$this->view->assign('calleraction', $valArray['calleraction']);
    	$this->view->assign('callercontroller', $valArray['callercontroller']);
    	
    }

    /**
     * action update
     *
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @return void
     */
    public function updateAction(\Ud\Iqtp13db\Domain\Model\Beratung $beratung)
    {
        
    	$teilnehmer = $beratung->getTeilnehmer();
    	
    	if($beratung->getErstberatungabgeschlossen() != '' && (!$this->validateDate($beratung->getErstberatungabgeschlossen()))){
    	    $this->addFlashMessage('Syntaxfehler in Datumseintrag. Bitte alle Datum-Felder prüfen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
    	    $valArray = $this->request->getArguments();
    	    $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, null);
    	}
    	
    	if($beratung->getDatum() == '') {
    		//$this->addFlashMessage('Erstberatung von '.$teilnehmer->getNachname().', '.$teilnehmer->getVorname().' gelöscht.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
    		$this->beratungRepository->remove($beratung);

    		$teilnehmer->setBeratungsstatus(1);
    	} else {
    		//$this->addFlashMessage('Erstberatung von '.$teilnehmer->getNachname().', '.$teilnehmer->getVorname().' aktualisiert.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
    		$this->beratungRepository->update($beratung);   

    		$bstatus = $this->checkberatungsstatus($teilnehmer);
    		$teilnehmer->setBeratungsstatus($bstatus);
    	}

    	$this->teilnehmerRepository->update($teilnehmer);
    	 
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();

        $valArray = $this->request->getArguments();
        $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, null);
    }

    /**
     * action delete
     *
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @return void
     */
    public function deleteAction(\Ud\Iqtp13db\Domain\Model\Beratung $beratung)
    {
    	$teilnehmer = $beratung->getTeilnehmer();
    	
        //$this->addFlashMessage('Erstberatung von '.$teilnehmer->getNachname().', '.$teilnehmer->getVorname().' gelöscht.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->beratungRepository->remove($beratung);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();

        $valArray = $this->request->getArguments();
        $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('beratung' => $beratung));
    }

    /**
     * Check Beratungsstatus
     *
     * Beratungsstatus: 0 = angemeldet, 
     * 					1 = Anmeldung bestätigt, 
     * 					2 = Erstberatung Start, 
     * 					3 = Erstberatung abgeschlossen, 
     * 					4 = NIQ erfasst
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return int
     */
    
    public function checkberatungsstatus(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer) {
    	
    	$beratung = $this->beratungRepository->findByTeilnehmer($teilnehmer->getUid());
    	$beratung = $beratung[0];    	
    	 
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
    		$beratungsstatus = 0;
    	}
    	 
    	return $beratungsstatus;
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
            $beratungen = $this->beratungRepository->findAllOrder4List($type, $orderby, $order);
        } else {
            $beratungen = $this->beratungRepository->searchBeratungen($type, $fname, $fort, $fberuf, $fland);
            $this->view->assign('filtername', $fname);
            $this->view->assign('filterort', $fort);
            $this->view->assign('filterberuf', $fberuf);
            $this->view->assign('filterland', $fland);
            $this->view->assign('filteron', 1);
        }
        // FILTER bis hier
        return $beratungen;
    }

    
    function validateDate($date, $format = 'd.m.Y')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
