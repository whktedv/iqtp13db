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
    	$pfad = $this->createFolder($teilnehmer);
        $storage = $this->getTP13Storage();
        $beratenepath = 'Beratene/' .$pfad->getName().'/';
    	$tmpName = $this->sanitizeFileFolderName($files['name']['file']);
        $fullpath = $storage->getConfiguration()['basePath'] . $beratenepath . $tmpName;
    	
    	if($this->getFolderSize($storage->getConfiguration()['basePath'] . $beratenepath) > 30000) {
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
    	$pfad = $this->createFolder($teilnehmer);
        $storage = $this->getTP13Storage();
        $beratenepath = 'Beratene/' .$pfad->getName().'/';
    	$tmpName = $dokument->getName();
        $fullpath = $storage->getConfiguration()['basePath'] .$beratenepath . $tmpName;
        
        //$pfad = $this->createFolder($teilnehmer);
        //$storage = $this->getTP13Storage();
        //$fullpath = $storage->getConfiguration()['basePath'] .'Beratene/' .$pfad->getName().'/'. $dokument->getName();
        
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
        
        //TODO: should validate the file type! This is not included here
        $tmpName = $this->sanitizeFileFolderName($arrfiles['name']['file']);
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
        //$storageRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
        
        // Speicher 'iqwebappdata' muss im Typo3-Backend auf der Root-Seite als "Dateispeicher" angelegt sein!
        // wenn der Speicher mal nicht verfügbar war (temporär), muss er im Backend im Bereich "Dateispeicher" 
        // manuell wieder "online" geschaltet werden mit der Checkbox "ist online?" 
        // in den Eigenschaften des jeweiligen Dateispeichers
        
        $storages = $this->storageRepository->findAll();
        foreach ($storages as $s) {
            $storageObject = $s;
            $storageRecord = $storageObject->getStorageRecord();
            if ($storageRecord['name'] == 'iqwebappdata') {
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
   
}
