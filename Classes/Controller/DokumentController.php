<?php
namespace Ud\Iqtp13db\Controller;

/***
 *
 * This file is part of the "IQ TP13 Datenbank Anerkennungserstberatung NRW" Extension for TYPO3 CMS.
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
     * beratungRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\BeratungRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $beratungRepository = NULL;

    /**
     * schulungRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\SchulungRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $schulungRepository = NULL;

    /**
     * storageRepository
     *
     * @var TYPO3\CMS\Core\Resource\StorageRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $storageRepository = NULL;

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
     * action saveFileBeratung
     *
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function saveFileBeratungAction(\Ud\Iqtp13db\Domain\Model\Beratung $beratung, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        
        $this->saveFileBeratung($beratung, $teilnehmer, $_FILES['tx_iqtp13db_iqtp13dbadmin']);
        $this->redirect('show', 'Beratung', null, array('beratung' => $beratung, 'teilnehmer' => $teilnehmer));
    }

    /**
     * action deleteFileBeratung
     *
     * @param \Ud\Iqtp13db\Domain\Model\Dokument $dokument
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function deleteFileBeratungAction(\Ud\Iqtp13db\Domain\Model\Dokument $dokument, \Ud\Iqtp13db\Domain\Model\Beratung $beratung, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $this->deleteFileBeratung($dokument, $beratung, $teilnehmer);
        $this->redirect('show', 'Beratung', null, array('beratung' => $beratung, 'teilnehmer' => $teilnehmer));
    }

    /**
     * action saveFileSchulung
     *
     * @param \Ud\Iqtp13db\Domain\Model\Schulung $schulung
     * @return void
     */
    public function saveFileSchulungAction(\Ud\Iqtp13db\Domain\Model\Schulung $schulung)
    {
        $newFilePath = '/Schulungen/' . $schulung->getDatum()->format('dmY') . '_' . $schulung->getInstitution();
        if ($_FILES['tx_iqtp13db_iqtp13dbadmin']['name']['formdoc'][0]) {
            $dokument = $this->savefile($newFilePath, $_FILES);
            $dokument->setSchulung($schulung);
            $this->dokumentRepository->update($dokument);
            
            //Daten sofort in die Datenbank schreiben
            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
            $anzdokumente = count($this->dokumentRepository->findBySchulung($schulung->getUid()));
            $schulung->setAnzDokumente($anzdokumente);
            $this->schulungRepository->update($schulung);
        }
        $this->redirect('show', 'Schulung', null, array('schulung' => $schulung));
    }

    /**
     * action deleteFileSchulung
     *
     * @param \Ud\Iqtp13db\Domain\Model\Dokument $dokument
     * @param \Ud\Iqtp13db\Domain\Model\Schulung $schulung
     * @return void
     */
    public function deleteFileSchulungAction(\Ud\Iqtp13db\Domain\Model\Dokument $dokument, \Ud\Iqtp13db\Domain\Model\Schulung $schulung)
    {
        $delfilepath = '/Schulungen/' . $schulung->getDatum()->format('dmY') . '_' . $schulung->getInstitution() . '/' . $dokument->getName();
        if ($this->deletefile($dokument, $delfilepath)) {
            $anzdokumente = count($this->dokumentRepository->findBySchulung($schulung->getUid()));
            $schulung->setAnzDokumente($anzdokumente);
            $this->schulungRepository->update($schulung);
            $this->addFlashMessage('Dokument wurde gelöscht.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        } else {
            $this->addFlashMessage('Dokument konnte nicht gelöscht werden!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        }
        $this->redirect('show', 'Schulung', null, array('schulung' => $schulung));
    }

    /**
     * action saveFileBeratungExtern
     *
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function saveFileBeratungExternAction(\Ud\Iqtp13db\Domain\Model\Beratung $beratung, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {        
		if ($_FILES['tx_iqtp13db_iqtp13dbwebapp']['tmp_name']['file'] == '') {
		    $this->addFlashMessage('Error in saveFileBeratungExtern: maximum filesize exceeded or permission error', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
		} else {
            $this->saveFileBeratung($beratung, $teilnehmer, $_FILES['tx_iqtp13db_iqtp13dbwebapp']);                     
		}
		$this->redirect('anmeldungcomplete', 'Beratung', null, array('beratung' => $beratung, 'teilnehmer' => $teilnehmer));
    }

    /**
     * action deleteFileBeratungExtern
     *
     * @param \Ud\Iqtp13db\Domain\Model\Dokument $dokument
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function deleteFileBeratungExternAction(\Ud\Iqtp13db\Domain\Model\Dokument $dokument, \Ud\Iqtp13db\Domain\Model\Beratung $beratung, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $this->deleteFileBeratung($dokument, $beratung, $teilnehmer);
        $this->forward('anmeldungcomplete', 'Beratung', null, null);
    }

    /**
     * saveFileBeratung
     *
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @param array $files
     * @return void
     */
    public function saveFileBeratung(\Ud\Iqtp13db\Domain\Model\Beratung $beratung, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer, $files)
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
    	        $dokument->setBeratung($beratung);
    	        $this->dokumentRepository->update($dokument);
    	        //Daten sofort in die Datenbank schreiben
    	        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
    	        $persistenceManager->persistAll();
    	        
    	        $anzdokumente = count($this->dokumentRepository->findByBeratung($beratung->getUid()));
    	        $beratung->setAnzDokumente($anzdokumente);
    	        
    	        $this->beratungRepository->update($beratung);
    	    }
    	}    	
        
    }

    /**
     * deleteFileBeratung
     *
     * @param \Ud\Iqtp13db\Domain\Model\Dokument $dokument
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function deleteFileBeratung(\Ud\Iqtp13db\Domain\Model\Dokument $dokument, \Ud\Iqtp13db\Domain\Model\Beratung $beratung, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    { 
        $delfilepath = 'Beratene/' . $teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid() . '/' . $dokument->getName();
        $storage = $this->getTP13Storage($newFilePath);
        $fullpath = $storage->getConfiguration()['basePath'].$delfilepath;
        //
        if (file_exists($fullpath)) {
            if ($this->deletefile($dokument, $delfilepath)) {   
                
                $anzdokumente = count($this->dokumentRepository->findByBeratung($beratung->getUid()));
                
                $beratung->setAnzDokumente($anzdokumente);
                $this->beratungRepository->update($beratung);
                
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
        $storageRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
        
        // Speicher 'tp13data' muss im Typo3-Backend auf der Root-Seite als "Dateispeicher" angelegt sein!
        // wenn der Speicher mal nicht verfügbar war (temporär), muss er im Backend im Bereich "Dateispeicher" 
        // manuell wieder "online" geschaltet werden mit der Checkbox "ist online?" 
        // in den Eigenschaften des jeweiligen Dateispeichers
        
        $storages = $storageRepository->findAll();
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
