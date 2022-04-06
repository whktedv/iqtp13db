<?php
namespace Ud\Iqtp13db\Controller;
use \Datetime;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

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
 * FolgekontaktController
 */
class FolgekontaktController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
   
    protected $niqinterface, $niqbid, $usergroup;
    
    /**
     * folgekontaktRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\FolgekontaktRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $folgekontaktRepository;
    
    /**
     * teilnehmerRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\TeilnehmerRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $teilnehmerRepository;

    /**
     * beraterRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\BeraterRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $beraterRepository;      
        
    /**
     * abschlussRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\AbschlussRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $abschlussRepository;	
    
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
        $this->user=null;
        $context = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class);
        if($context->getPropertyFromAspect('frontend.user', 'isLoggedIn')){
            $this->user=$GLOBALS['TSFE']->fe_user->user;
        }
        
        //DebuggerUtility::var_dump($this->user);
        if($this->user != NULL) {
            $standardniqidberatungsstelle = $this->settings['standardniqidberatungsstelle'];
            $this->usergroup = $this->frontendUserGroupRepository->findByIdentifier($this->user['usergroup']);
            $userniqidbstelle = $this->usergroup->getDescription();
            $this->niqbid = $userniqidbstelle == '' ? $standardniqidberatungsstelle : $userniqidbstelle;
        }
                
        $this->niqinterface = new \Ud\Iqtp13db\Helper\NiqInterface();
    }
    
    /**
     * action show
     *
     * @param \Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt
     * @return void
     */
    public function showAction(\Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt)
    {
    	$this->view->assign('folgekontakt', $folgekontakt);
    	
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
        $valArray = $this->request->getArguments();
        
        if($teilnehmer->getNiqchiffre() != '' && $this->niqinterface->check_curl() == FALSE) {
            $this->addFlashMessage("Bearbeiten von bereits übertragenen Datensätzen vorübergehend nicht möglich, da NIQ-Datenbank nicht erreichbar.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']));
        } else {
            $alleberater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->user['usergroup']);
        	
        	$this->view->assign('alleberater', $alleberater);
        	$this->view->assign('berater', $this->user);
            $this->view->assign('teilnehmer', $teilnehmer);
            
            $this->view->assign('calleraction', $valArray['calleraction']);
            $this->view->assign('callercontroller', $valArray['callercontroller']);
            $this->view->assign('callerpage', $valArray['callerpage']);
            $this->view->assign('settings', $this->settings);
        }
    }
    

     /**
     * action create
     *
     * @param \Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt
     * @return void
     */
    public function createAction(\Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt)
    {  		
        $valArray = $this->request->getArguments();
        $teilnehmer = $this->teilnehmerRepository->findByUid($valArray['folgekontakt']['teilnehmer']);
        //DebuggerUtility::var_dump($teilnehmer);
        //die;
        
        if($teilnehmer->getNiqchiffre() != '' && $this->niqinterface->check_curl() == FALSE) {
            $this->addFlashMessage("Bearbeiten von bereits übertragenen Datensätzen vorübergehend nicht möglich, da NIQ-Datenbank nicht erreichbar.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']));
        } else {
            $teilnehmer = $folgekontakt->getTeilnehmer();
            
            $letzterfolgekontakt = $this->folgekontaktRepository->findLastByTNuid($teilnehmer->getUid());
            
            $beratungtimestamp = DateTime::createFromFormat("Y-m-d", $teilnehmer->getBeratungdatum());
            $folgekontakttimestamp = DateTime::createFromFormat("d.m.Y", $folgekontakt->getDatum());  
            $letzterfolgekontakttimestamp = $letzterfolgekontakt != NULL ? DateTime::createFromFormat("d.m.Y", $letzterfolgekontakt->getDatum()) : DateTime::createFromFormat("d.m.Y", '01.01.1970');
            
            if($folgekontakttimestamp->getTimestamp() <= $beratungtimestamp->getTimestamp() || $folgekontakttimestamp->getTimestamp() <= $letzterfolgekontakttimestamp->getTimestamp()) {
                $this->addFlashMessage('Datum Folgekontakt muss nach letztem Folgekontakt und Datum Erstberatung sein.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                
                $this->redirect('new', 'Folgekontakt', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'],'callercontroller' => $valArray['callercontroller'], 'callerpage' => $valArray['callerpage']));
            } else {
                $this->folgekontaktRepository->add($folgekontakt);
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
                    
//                    DebuggerUtility::var_dump($returnarray[0]);
 //                   die;
                    $retteilnehmer = $this->teilnehmerRepository->findByUid($returnarray[0]->getUid());
                    $retstring = $returnarray[1];
                    
                    $this->teilnehmerRepository->update($retteilnehmer);
                    
                    $this->addFlashMessage($retstring, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
                }
                
                // Daten sofort in die Datenbank schreiben
                $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
                $persistenceManager->persistAll();
                
                $this->view->assign('calleraction', 'listarchiv');
                $this->view->assign('callercontroller', 'Teilnehmer');
            }
               
            $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, null);
        }
    }
	
	 /**
     * action edit
     *
     * @param \Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("folgekontakt")
     * @return void
     */
    public function editAction(\Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt)
    {
        $valArray = $this->request->getArguments();
        $teilnehmer = $folgekontakt->getTeilnehmer();
        
        if($teilnehmer->getNiqchiffre() != '' && $this->niqinterface->check_curl() == FALSE) {
            $this->addFlashMessage("Bearbeiten von bereits übertragenen Datensätzen vorübergehend nicht möglich, da NIQ-Datenbank nicht erreichbar.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']));
        } else {
       		$this->view->assign('berater', $this->user);
       		$alleberater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->user['usergroup']);
       		
        	$this->view->assign('alleberater', $alleberater);
            $this->view->assign('folgekontakt', $folgekontakt);
            $this->view->assign('teilnehmer', $teilnehmer);
            
            $this->view->assign('callerpage', $valArray['callerpage']);
            $this->view->assign('calleraction', $valArray['calleraction']);
            $this->view->assign('callercontroller', $valArray['callercontroller']);
            $this->view->assign('settings', $this->settings);
        }
    }

    /**
     * action update
     *
     * @param \Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt
     * @return void
     */
    public function updateAction(\Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt)
    {
        $valArray = $this->request->getArguments();
        $teilnehmer = $folgekontakt->getTeilnehmer();
        
        if($teilnehmer->getNiqchiffre() != '' && $this->niqinterface->check_curl() == FALSE) {
            $this->addFlashMessage("Bearbeiten von bereits übertragenen Datensätzen vorübergehend nicht möglich, da NIQ-Datenbank nicht erreichbar.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']));
        } else {
            $this->folgekontaktRepository->update($folgekontakt);
             
            // Daten sofort in die Datenbank schreiben
            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
            
            $teilnehmer = $folgekontakt->getTeilnehmer();
            
            if($teilnehmer->getNiqchiffre() != '') {
                $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
                $folgekontakte = $this->folgekontaktRepository->findByTeilnehmer($teilnehmer);
                if($teilnehmer->getNiqidberatungsstelle() != '0') {
                    $niqidbstelle = $teilnehmer->getNiqidberatungsstelle();
                } else {
                    $niqidbstelle = $this->niqbid;
                }
                $returnarray = $this->niqinterface->uploadtoNIQ($teilnehmer, $abschluesse, $folgekontakte, $niqidbstelle);
                
                $retteilnehmer = $this->teilnehmerRepository->findByUid($returnarray[0]->getUid());
                $retstring = $returnarray[1];
                
                $this->teilnehmerRepository->update($retteilnehmer);
                
                $this->addFlashMessage($retstring, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
            }
            
            // Daten sofort in die Datenbank schreiben
            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
            
            $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']));
        }
    }

    /**
     * action delete
     *
     * @param \Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt
     * @return void
     */
    public function deleteAction(\Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt)
    {
        $valArray = $this->request->getArguments();
        $teilnehmer = $folgekontakt->getTeilnehmer();
        
        if($teilnehmer->getNiqchiffre() != '' && $this->niqinterface->check_curl() == FALSE) {
            $this->addFlashMessage("Bearbeiten von bereits übertragenen Datensätzen vorübergehend nicht möglich, da NIQ-Datenbank nicht erreichbar.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']));
        } else {
            $this->folgekontaktRepository->remove($folgekontakt);
            
            // Daten sofort in die Datenbank schreiben
            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
            
            $teilnehmer = $folgekontakt->getTeilnehmer();
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
                
                $this->teilnehmerRepository->update($retteilnehmer);
                
                $this->addFlashMessage($retstring, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
            }
            // Daten sofort in die Datenbank schreiben
            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();

            $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']));
        }
        
    }

    
}
