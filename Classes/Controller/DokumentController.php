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
 * DokumentController
 */
class DokumentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * dokumentRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\DokumentRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $dokumentRepository = NULL;

    /**
     * storageRepository
     *
     * @var \TYPO3\CMS\Core\Resource\StorageRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $storageRepository;

    /**
     * teilnehmerRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\TeilnehmerRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $teilnehmerRepository = NULL;
    
    /**
     * action init
     *
     * @return void
     */
    public function initializeAction()
    {

    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $dokuments = $this->dokumentRepository->findAll();
        $this->view->assign('dokuments', $dokuments);
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
        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($_FILES);
        //die;
        
		if ($_FILES['tx_iqtp13db_iqtp13dbwebapp']['tmp_name']['file'] == '') {
		    $this->addFlashMessage('Error in saveFileWebapp: maximum filesize exceeded or permission error', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
		} else {
            $this->saveFileTeilnehmer($teilnehmer, $_FILES['tx_iqtp13db_iqtp13dbwebapp']);                     
		}
		$this->redirect('anmeldungcomplete', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer));
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
    	$newFilePath = 'Beratene/' . $teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid(). '/';    	
    	$tmpName = $this->sanitizeFileName($files['name']['file']);
    	
    	$storage = $this->getTP13Storage($newFilePath);
    	$fullpath = $storage->getConfiguration()['basePath'].$newFilePath.$tmpName;
    	
    	if($this->getFolderSize($storage->getConfiguration()['basePath'].$newFilePath) > 30000) {
    	    $this->addFlashMessage('Maximum total filesize of 30 MB exceeded, please reduce filesize. Maximale Dateigröße aller Dateien zusammen ist 30 MB. Bitte Dateigröße verringern.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
    	} else {
    	    if ($files['name']['file'] && !file_exists($fullpath)) {
    	        $dokument = $this->savefile($newFilePath, $files);
    	        $dokument->setTeilnehmer($teilnehmer);
    	        $this->dokumentRepository->update($dokument);
    	        //Daten sofort in die Datenbank schreiben
    	        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
    	        $persistenceManager->persistAll();
    	           	        
    	        $this->teilnehmerRepository->update($teilnehmer);
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
        $delfilepath = 'Beratene/' . $teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid() . '/' . $dokument->getName();
        $storage = $this->getTP13Storage($newFilePath);
        $fullpath = $storage->getConfiguration()['basePath'].$delfilepath;
        
        if (file_exists($fullpath)) {
            if ($this->deletefile($dokument, $delfilepath)) {   
                
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
        
        //TODO: should validate the file type! This is not included here
        $tmpName = $this->sanitizeFileName($arrfiles['name']['file']);
        $tmpFile = $arrfiles['tmp_name']['file'];
        
        $storage = $this->getTP13Storage($pfad);
      
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
        
        $storage = $this->getTP13Storage($delfilepath);
        
        $delfile = $storage->getFile($delfilepath);
                
        $erg = $storage->deleteFile($delfile);
        
        return $erg;
    }
    
    function getTP13Storage($pfad) {
        //$storageRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
        
        // Speicher 'tp13data' muss im Typo3-Backend auf der Root-Seite als "Dateispeicher" angelegt sein!
        // wenn der Speicher mal nicht verfügbar war (temporär), muss er im Backend im Bereich "Dateispeicher" 
        // manuell wieder "online" geschaltet werden mit der Checkbox "ist online?" 
        // in den Eigenschaften des jeweiligen Dateispeichers
        
        $storages = $this->storageRepository->findAll();
        foreach ($storages as $s) {
            $storageObject = $s;
            $storageRecord = $storageObject->getStorageRecord();
            if ($storageRecord['name'] == 'tp13data') {
                $storage = $s;
                break;
            }
        }
        
        return $storage;
    }
	    
    function getFolderSize($folderpath) {        
        $io = popen ( '/usr/bin/du -sk ' . $folderpath, 'r' );
        $size = fgets ( $io, 4096);
        $size = substr ( $size, 0, strpos ( $size, "\t" ) );
        pclose ( $io );
        
        return $size;
    }
    
    function sanitizeFileName($fileName)
    {
        // Replace spaces with hyphens
        $fileName = preg_replace('/\s/', '-', $fileName);
        
        $fileName = preg_replace("/[^a-zA-Z0-9\-\/\.]/", "", $fileName);
        
        return $fileName;
    }
}
