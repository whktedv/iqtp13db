<?php
namespace Ud\Iqtp13db\Controller;
use \Datetime;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use Psr\Http\Message\ResponseInterface;

use Ud\Iqtp13db\Domain\Repository\UserGroupRepository;
use Ud\Iqtp13db\Domain\Repository\TeilnehmerRepository;
use Ud\Iqtp13db\Domain\Repository\FolgekontaktRepository;
use Ud\Iqtp13db\Domain\Repository\BeraterRepository;
use Ud\Iqtp13db\Domain\Repository\AbschlussRepository;

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
    
    protected $generalhelper, $usergroup;
    
    protected $userGroupRepository;
    protected $teilnehmerRepository;
    protected $folgekontaktRepository;
    protected $beraterRepository;
    protected $abschlussRepository;
    
    public function __construct(UserGroupRepository $userGroupRepository, TeilnehmerRepository $teilnehmerRepository, FolgekontaktRepository $folgekontaktRepository, BeraterRepository $beraterRepository, AbschlussRepository $abschlussRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
        $this->teilnehmerRepository = $teilnehmerRepository;
        $this->folgekontaktRepository = $folgekontaktRepository;
        $this->beraterRepository = $beraterRepository;
        $this->abschlussRepository = $abschlussRepository;
    }
    
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
        
        if($this->user != NULL) {
            $this->usergroup = $this->userGroupRepository->findByIdentifier($this->user['usergroup']);
        }
    }
    
    /**
     * action show
     *
     * @param \Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("folgekontakt")
     * @return void
     */
    public function showAction(\Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt): ResponseInterface
    {
        $this->view->assign('folgekontakt', $folgekontakt);
        
        $teilnehmer = $folgekontakt->getTeilnehmer();
        $this->view->assign('teilnehmer', $teilnehmer);
        
        $valArray = $this->request->getArguments();
        $this->view->assign('calleraction', $valArray['calleraction']);
        $this->view->assign('callercontroller', $valArray['callercontroller']);
        $this->view->assign('callerpage', $valArray['callerpage'] ?? '1');
        $this->view->assign('thisaction', $valArray['thisaction'] ?? '');
        return $this->htmlResponse();
    }
    
    
    /**
     * action initnew
     *
     * @return void
     */
    public function initializeNewAction() {
        $valArray = $this->request->getArguments();
        $thistn = $this->teilnehmerRepository->findByUid($valArray['teilnehmer']);
        if($thistn->getPlz() == '') $thistn->setPlz('0');
    }
    
    /**
     * action new
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function newAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        $alleberater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->user['usergroup']);
            
        $this->view->assign('alleberater', $alleberater);
        $this->view->assign('berater', $this->user);
        $this->view->assign('teilnehmer', $teilnehmer);
        $this->view->assign('thisaction', $valArray['thisaction'] ?? '');
        $this->view->assign('calleraction', $valArray['calleraction']);
        $this->view->assign('callercontroller', $valArray['callercontroller']);
        $this->view->assign('callerpage', $valArray['callerpage'] ?? '1');
        $this->view->assign('settings', $this->settings);
        $this->view->assign('datum', date("Y-m-d"));
        return $this->htmlResponse();
    }
    
    
    /**
     * action create
     *
     * @param \Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("folgekontakt")
     * @return void
     */
    public function createAction(\Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt): ResponseInterface
    {
        
        $valArray = $this->request->getArguments();
        $teilnehmer = $this->teilnehmerRepository->findByUid($valArray['folgekontakt']['teilnehmer']);
        
        $letzterfolgekontakt = $this->folgekontaktRepository->findLastByTNuid($teilnehmer->getUid());
        $beratungtimestamp = DateTime::createFromFormat("Y-m-d", $teilnehmer->getBeratungdatum());
        
        if($beratungtimestamp == FALSE) {
            $this->addFlashMessage('Noch kein Beratungsdatum eingetragen, Folgekontakt nur nach erfolgter Erstberatung möglich.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            return $this->redirect('new', 'Folgekontakt', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'],'callercontroller' => $valArray['callercontroller'], 'callerpage' => $valArray['callerpage']));
        }
        
        if($folgekontakt->getDatum() == '') {
            $this->addFlashMessage('Bitte Datum eingeben.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            return $this->redirect('new', 'Folgekontakt', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'],'callercontroller' => $valArray['callercontroller'], 'callerpage' => $valArray['callerpage']));
        }
        
        $folgekontakttimestamp = DateTime::createFromFormat("Y-m-d", $folgekontakt->getDatum());
        $letzterfolgekontakttimestamp = $letzterfolgekontakt != NULL ? DateTime::createFromFormat("Y-m-d", $letzterfolgekontakt->getDatum()) : DateTime::createFromFormat("d.m.Y", '01.01.1970');
        
        if($folgekontakttimestamp == FALSE) {
            $this->addFlashMessage('Falsches Format oder kein Datum eingeben.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            return $this->redirect('new', 'Folgekontakt', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'],'callercontroller' => $valArray['callercontroller'], 'callerpage' => $valArray['callerpage']));
        }
        
        if($folgekontakttimestamp->getTimestamp() < $beratungtimestamp->getTimestamp()) {
            $this->addFlashMessage('Datum Folgekontakt muss nach Datum Erstberatung sein.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            return $this->redirect('new', 'Folgekontakt', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'],'callercontroller' => $valArray['callercontroller'], 'callerpage' => $valArray['callerpage']));        
        } else {
            
            if($folgekontakttimestamp->getTimestamp() < $letzterfolgekontakttimestamp->getTimestamp()) {
                $this->addFlashMessage('Hinweis: Datum Folgekontakt ist vor letztem Folgekontakt.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::WARNING);
            }
            $this->folgekontaktRepository->add($folgekontakt);
            
            // Daten sofort in die Datenbank schreiben
            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
            $this->addFlashMessage('Folgekontakt für '.$teilnehmer->getNachname().', '.$teilnehmer->getVorname().' (UID: '.$teilnehmer->getUid().') erstellt.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
            
            return $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']));
        }
    }
    
     /**
     * action edit
     *
     * @param \Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("folgekontakt")
     * @return void
     */
    public function editAction(\Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        $teilnehmer = $folgekontakt->getTeilnehmer();
        
        $this->view->assign('berater', $this->user);
        $alleberater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->user['usergroup']);
        
        $this->view->assign('alleberater', $alleberater);
        $this->view->assign('folgekontakt', $folgekontakt);
        $this->view->assign('teilnehmer', $teilnehmer);
        $this->view->assign('thisaction', $valArray['thisaction'] ?? '');
        $this->view->assign('callerpage', $valArray['callerpage']  ?? '1');
        $this->view->assign('calleraction', $valArray['calleraction']);
        $this->view->assign('callercontroller', $valArray['callercontroller']);
        $this->view->assign('settings', $this->settings);
        return $this->htmlResponse();
    }
    
    /**
     * action update
     *
     * @param \Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("folgekontakt")
     * @return void
     */
    public function updateAction(\Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        $this->folgekontaktRepository->update($folgekontakt);
        $teilnehmer = $folgekontakt->getTeilnehmer();
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        $this->addFlashMessage('Folgekontakt für '.$teilnehmer->getNachname().', '.$teilnehmer->getVorname().' (UID: '.$teilnehmer->getUid().') aktualisiert.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
        return $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage'] ?? '1'));       
    }
    
    /**
     * action delete
     *
     * @param \Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("folgekontakt")
     * @return void
     */
    public function deleteAction(\Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        $this->folgekontaktRepository->remove($folgekontakt);
        $teilnehmer = $folgekontakt->getTeilnehmer();
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        $this->addFlashMessage('Folgekontakt für '.$teilnehmer->getNachname().', '.$teilnehmer->getVorname().' (UID: '.$teilnehmer->getUid().') gelöscht.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
        return $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage'] ?? '1'));
        
    }
    
    /**
     * action listall
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function listallAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {        
        $valArray = $this->request->getArguments();
        
        $folgekontakte = $this->folgekontaktRepository->findByTeilnehmer($teilnehmer->getUid());
        
        $this->view->assign('calleraction', $valArray['calleraction']);
        $this->view->assign('callercontroller', $valArray['callercontroller']);
        $this->view->assign('callerpage', $valArray['callerpage'] ?? '1');
        $this->view->assign('thisaction', $valArray['thisaction'] ?? '');
        $this->view->assign('folgekontakte', $folgekontakte);
        $this->view->assign('teilnehmer', $teilnehmer);
        return $this->htmlResponse();
    }
    
}
