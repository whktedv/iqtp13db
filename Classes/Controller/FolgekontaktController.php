<?php
namespace Ud\Iqtp13db\Controller;

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
 * FolgekontaktController
 */
class FolgekontaktController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
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
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @return void
     */
    public function newAction(\Ud\Iqtp13db\Domain\Model\Beratung $beratung)
    {
    	$username = $GLOBALS['TSFE']->fe_user->user['username'];
    	
    	$berater = $this->beraterRepository->findOneByKuerzel($username);
    	if($berater == NULL) {
    		$berater = $this->beraterRepository->findOneByKuerzel('admin');
    	} 
    	$teilnehmer = $beratung->getTeilnehmer();
    	    	
    	$alleberater = $this->beraterRepository->findAll();
    	$this->view->assign('alleberater', $alleberater);
    	$this->view->assign('berater', $berater);
        $this->view->assign('teilnehmer', $teilnehmer);
        
        $valArray = $this->request->getArguments();
        $this->view->assign('calleraction', $valArray['calleraction']);
        $this->view->assign('callercontroller', $valArray['callercontroller']);
    }
    

     /**
     * action create
     *
     * @param \Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt
     * @return void
     */
    public function createAction(\Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt)
    {  		
        //$this->addFlashMessage('Folgekontakt wurde erstellt.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->folgekontaktRepository->add($folgekontakt);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
                
        $this->view->assign('calleraction', 'listarchiv');
        $this->view->assign('callercontroller', 'Beratung');
        
        $valArray = $this->request->getArguments();
        $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, null);
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
   		$berater = $this->beraterRepository->findOneByKuerzel('admin');
   		$this->view->assign('berater', $berater);
    	$alleberater = $this->beraterRepository->findAll();    	
    	$this->view->assign('alleberater', $alleberater);
        $this->view->assign('folgekontakt', $folgekontakt);
        
        $valArray = $this->request->getArguments();
        $this->view->assign('calleraction', $valArray['calleraction']);
        $this->view->assign('callercontroller', $valArray['callercontroller']);
    }

    /**
     * action update
     *
     * @param \Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt
     * @return void
     */
    public function updateAction(\Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt)
    {
    	
        //$this->addFlashMessage('Folgekontakt aktualisiert.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->folgekontaktRepository->update($folgekontakt);
         
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        $valArray = $this->request->getArguments();
        $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, null);
    }

    /**
     * action delete
     *
     * @param \Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt
     * @return void
     */
    public function deleteAction(\Ud\Iqtp13db\Domain\Model\Folgekontakt $folgekontakt)
    {
        //$this->addFlashMessage('Folgekontakt gelöscht.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->folgekontaktRepository->remove($folgekontakt);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        $valArray = $this->request->getArguments();
        $this->view->assign('calleraction', $valArray['calleraction']);
        $this->view->assign('callercontroller', $valArray['callercontroller']);
        
        $valArray = $this->request->getArguments();
        $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, null);
        
    }

    
}
