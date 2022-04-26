<?php
namespace Ud\Iqtp13db\Controller;

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
 * DokumentController
 */
class DokumentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    protected $generalhelper, $allusergroups;
    
    /**
     * dokumentRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\DokumentRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $dokumentRepository = NULL;

    /**
     * teilnehmerRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\TeilnehmerRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $teilnehmerRepository = NULL;
    
    /**
     * userGroupRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\UserGroupRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $userGroupRepository = NULL;

    /**
     * storageRepository
     *
     * @var \TYPO3\CMS\Core\Resource\StorageRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $storageRepository;
    
    /**
     * action init
     *
     * @param void
     */
    public function initializeAction()
    {
        $this->generalhelper = new \Ud\Iqtp13db\Helper\Generalhelper();
        $this->allusergroups = $this->userGroupRepository->findAllGroups($this->settings['beraterstoragepid']);
    }
    
    /**
     * action saveFileTeilnehmer
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function saveFileTeilnehmerAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {        
        $this->saveFileTeilnehmer($teilnehmer, $_FILES['tx_iqtp13db_iqtp13dbadmin']);
        $this->redirect('show', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer));
    }

    /**
     * action deleteFileTeilnehmer
     *
     * @param \Ud\Iqtp13db\Domain\Model\Dokument $dokument
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function deleteFileAction(\Ud\Iqtp13db\Domain\Model\Dokument $dokument, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $this->deleteFile($dokument, $teilnehmer);
        $this->redirect('show', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer));
    }

    /**
     * action saveFileWebapp
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function saveFileWebappAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {           
		if ($_FILES['tx_iqtp13db_iqtp13dbwebapp']['tmp_name']['file'] == '') {
		    $this->addFlashMessage('Error in saveFileWebapp: maximum filesize exceeded or permission error', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
		} else {
            $this->saveFileTeilnehmer($teilnehmer, $_FILES['tx_iqtp13db_iqtp13dbwebapp']);                     
		}
		$this->redirect('anmeldungcomplete', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer));
    }
    
    /**
     * action initdeleteFileWebapp
     *
     * @param void
     */
    public function initializedeleteFileWebappAction()
    {
        $arguments = $this->request->getArguments();
        if($this->dokumentRepository->countByUid($arguments['dokument']) == 0) {
            //DebuggerUtility::var_dump($arguments);
            $this->forward('anmeldungcomplete', 'Teilnehmer', null, null);
            die;
        }
    }
    /**
     * action deleteFileWebapp
     *
     * @param \Ud\Iqtp13db\Domain\Model\Dokument $dokument
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function deleteFileWebappAction(\Ud\Iqtp13db\Domain\Model\Dokument $dokument, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $this->deleteFileTeilnehmer($dokument, $teilnehmer);
        $this->forward('anmeldungcomplete', 'Teilnehmer', null, null);
    }

    /**
     * saveFileTeilnehmer
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @param array $files
     * @return void
     */
    public function saveFileTeilnehmer(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer, $files)
    {        
        $storage = $this->generalhelper->getTP13Storage( $this->storageRepository->findAll());
        $pfad = $this->generalhelper->createFolder($teilnehmer, $this->settings['standardniqidberatungsstelle'], $this->allusergroups, $this->storageRepository->findAll());
        $beratenepath = ltrim($pfad->getIdentifier(), '/');

        $tmpName = $this->generalhelper->sanitizeFileFolderName($files['name']['file']);
        $fullpath = $storage->getConfiguration()['basePath'] . $beratenepath . $tmpName;
    	
        if($this->generalhelper->getFolderSize($storage->getConfiguration()['basePath'] . $beratenepath) > 30000) {
    	    $this->addFlashMessage('Maximum total filesize of 30 MB exceeded, please reduce filesize. Maximale Dateigröße aller Dateien zusammen ist 30 MB. Bitte Dateigröße verringern.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
    	} else {
    	    if ($files['name']['file'] && !file_exists($fullpath)) {
    	            
    	        $dokument = $this->savefile($beratenepath, $files);
    	        $dokument->setTeilnehmer($teilnehmer);
    	        $this->dokumentRepository->update($dokument);
    	        //Daten sofort in die Datenbank schreiben
    	        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
    	        $persistenceManager->persistAll();
    	           	        
    	        $this->teilnehmerRepository->update($teilnehmer);
    	    } else {
    	    	// Fehler
    	    }
    	}
    }

    /**
     * deleteFileTeilnehmer
     *
     * @param \Ud\Iqtp13db\Domain\Model\Dokument $dokument
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function deleteFileTeilnehmer(\Ud\Iqtp13db\Domain\Model\Dokument $dokument, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    { 
        $storage = $this->generalhelper->getTP13Storage($this->storageRepository->findAll());
        $pfad = $this->generalhelper->createFolder($teilnehmer, $this->settings['standardniqidberatungsstelle'], $this->allusergroups, $this->storageRepository->findAll());
        $beratenepath = ltrim($pfad->getIdentifier(), '/');
        
    	$tmpName = $dokument->getName();
        $fullpath = $storage->getConfiguration()['basePath'] .$beratenepath . $tmpName;
                
        if (file_exists($fullpath)) {
            if ($this->deletefile($dokument, $beratenepath . $tmpName)) {   
                
                $anzdokumente = count($this->dokumentRepository->findByTeilnehmer($teilnehmer->getUid()));
                $this->teilnehmerRepository->update($teilnehmer);
                
                $this->addFlashMessage('Dokument wurde gelöscht.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
            } else {
                $this->addFlashMessage('Dokument konnte nicht gelöscht werden!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            }
        }      
    }

    /**
     * adds file
     *
     * @param string $pathtofile
     * @param array $arrfiles
     * @return \Ud\Iqtp13db\Domain\Model\Dokument
     */
    public function savefile($pfad, $arrfiles)
    {          
        $dokument = new \Ud\Iqtp13db\Domain\Model\Dokument();
        
        $tmpName = $this->generalhelper->sanitizeFileFolderName($arrfiles['name']['file']);
        $tmpFile = $arrfiles['tmp_name']['file'];
        
        $storage = $this->generalhelper->getTP13Storage( $this->storageRepository->findAll());
      
        if (!$storage->hasFolder($pfad)) {
            $targetFolder = $storage->createFolder($pfad);
        } else {
            $targetFolder = $storage->getFolder($pfad);            
        }
	
        $movedNewFile = $storage->addFile($tmpFile, $targetFolder, $tmpName);
        
        $dokument->setName($movedNewFile->getName());            
        $dokument->setPfad($pfad);
        
        $this->dokumentRepository->add($dokument);
        
        //Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        return $dokument;
    }

    /**
     * delete file
     *
     * @param \Ud\Iqtp13db\Domain\Model\Dokument $dokument
     * @param string $delfilepath
     * @return boolean
     */
    public function deletefile(\Ud\Iqtp13db\Domain\Model\Dokument $dokument, $delfilepath)
    {                
        $this->dokumentRepository->remove($dokument);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        $storage = $this->generalhelper->getTP13Storage( $this->storageRepository->findAll());
        $delfile = $storage->getFile($delfilepath);
        $erg = $storage->deleteFile($delfile);
        
        return $erg;
    }

}
