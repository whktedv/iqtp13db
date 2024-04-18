<?php
namespace Ud\Iqtp13db\Controller;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Core\Core\Environment;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Http\ForwardResponse;

use Ud\Iqtp13db\Domain\Repository\UserGroupRepository;
use Ud\Iqtp13db\Domain\Repository\TeilnehmerRepository;
use Ud\Iqtp13db\Domain\Repository\DokumentRepository;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\InaccessibleFolder;
use TYPO3\CMS\Core\Resource\Search\FileSearchDemand;

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
    
    protected $userGroupRepository;
    protected $teilnehmerRepository;
    protected $dokumentRepository;
    protected $storageRepository;
    
    public function __construct(UserGroupRepository $userGroupRepository, TeilnehmerRepository $teilnehmerRepository, DokumentRepository $dokumentRepository, StorageRepository $storageRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
        $this->teilnehmerRepository = $teilnehmerRepository;
        $this->dokumentRepository = $dokumentRepository;
        $this->storageRepository = $storageRepository;
    }
        
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
     * action saveFileBackend
     *
     * @param \Ud\Iqtp13db\Domain\Model\Dokument $dokument
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function saveFileBackendAction(\Ud\Iqtp13db\Domain\Model\Dokument $dokument, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();
        
        if ($_FILES['tx_iqtp13db_iqtp13dbadmin']['tmp_name']['file'] == '') {
            $this->addFlashMessage('Error in saveFileWebapp: maximum filesize exceeded or permission error', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        } else {            
            $this->saveFileTeilnehmer($dokument, $teilnehmer, $_FILES['tx_iqtp13db_iqtp13dbadmin']);
        }
        
        $this->redirect($valArray['thisaction'], 'Backend', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'] ?? 'edit', 'callercontroller' => $valArray['callercontroller'] ?? 'Backend', 'callerpage' => $valArray['callerpage'] ?? '1', 'showdokumente' => '1'));
    }
    
    /**
     * action updateBackendAction
     *
     * @param \Ud\Iqtp13db\Domain\Model\Dokument $dokument
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function updateBackendAction(\Ud\Iqtp13db\Domain\Model\Dokument $dokument, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();

        $this->dokumentRepository->update($dokument);
        //Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        $this->redirect($valArray['thisaction'], 'Backend', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'] ?? 'edit', 'callercontroller' => $valArray['callercontroller'] ?? 'Backend', 'callerpage' => $valArray['callerpage'] ?? '1', 'showdokumente' => '1'));
    }
        
    /**
     * action deleteFileBackend
     *
     * @param \Ud\Iqtp13db\Domain\Model\Dokument $dokument
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function deleteFileBackendAction(\Ud\Iqtp13db\Domain\Model\Dokument $dokument, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer) : ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        $retval = $this->deleteFileTeilnehmer($dokument, $teilnehmer);
        return (new ForwardResponse($valArray['thisaction']))->withControllerName('Backend')->withArguments(['teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'] ?? 'edit', 'callercontroller' => $valArray['callercontroller'] ?? 'Backend', 'callerpage' => $valArray['callerpage'] ?? '1', 'showdokumente' => $retval]) ;
    }
    
    /**
     * action openfile
     *
     * @param \Ud\Iqtp13db\Domain\Model\Dokument $dokument
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function openfileAction(\Ud\Iqtp13db\Domain\Model\Dokument $dokument, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $storage = $this->generalhelper->getTP13Storage($this->storageRepository->findAll());
        $beratenepath = $dokument->getPfad();
        $tmpName = $dokument->getName();
        
        if($storage->getConfiguration()['pathType'] == 'relative') {
            if($folder->getStorage()->hasFileInFolder($tmpName, $folder)) {
                $folder = $storage->getFolder($beratenepath);
                $targetfile = $folder->getStorage()->getFileInFolder($tmpName, $folder);
            } else {
                $this->addFlashMessage('Datei wurde nicht gefunden. ', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                $this->redirect($valArray['thisaction'], 'Backend', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'] ?? 'edit', 'callercontroller' => $valArray['callercontroller'] ?? 'Backend', 'callerpage' => $valArray['callerpage'] ?? '1', 'showdokumente' => '1'));
            }
        } else {
            $targetfile = $storage->getFile($beratenepath . $tmpName);
        }
        
        $queryParameterArray = ['eID' => 'dumpFile', 't' => 'f'];
        $queryParameterArray['f'] = $targetfile->getUid();
        $queryParameterArray['token'] = GeneralUtility::hmac(implode('|', $queryParameterArray), 'resourceStorageDumpFile');
        $publicUrl = GeneralUtility::locationHeaderUrl(PathUtility::getAbsoluteWebPath(Environment::getPublicPath() . '/index.php'));
        $publicUrl .= '?' . http_build_query($queryParameterArray, '', '&', PHP_QUERY_RFC3986);
        
        $this->redirectToURI($publicUrl, $delay=0, $statusCode=303);
    }
    
    
    /**
     * action saveFileWebapp
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function saveFileWebappAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();
        if($_FILES == NULL) {
            $this->addFlashMessage('Error in saveFileWebapp: File does not meet policy.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        } else {
            if ($_FILES['tx_iqtp13db_iqtp13dbwebapp']['tmp_name']['file'] == '') {
                $this->addFlashMessage('Error in saveFileWebapp: permission error or maximum filesize exceeded.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            } elseif (filesize($_FILES['tx_iqtp13db_iqtp13dbwebapp']['tmp_name']['file']) > 10485760) {
                $this->addFlashMessage('Error in saveFileWebapp: Maximum filesize exceeded (10 MB). Please reduce filesize.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            } else {
                $dokument = new \Ud\Iqtp13db\Domain\Model\Dokument();
                $dokument->setBeschreibung($valArray['beschreibung']);
                $this->saveFileTeilnehmer($dokument, $teilnehmer, $_FILES['tx_iqtp13db_iqtp13dbwebapp']);
            }
            
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
        
        if(is_string($arguments['dokument'])) $dcuid = $arguments['dokument'];
        else $dcuid = $arguments['dokument']->getUid();
        
        $thisdok = $this->dokumentRepository->findByUid($dcuid);
        
        if($thisdok == null) {
            $this->redirect('anmeldungcomplete', 'Teilnehmer', null, array('teilnehmer' => $arguments['teilnehmer']));
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
        $retval = $this->deleteFileTeilnehmer($dokument, $teilnehmer);
        return (new ForwardResponse('anmeldungcomplete'))->withControllerName('Teilnehmer');
    }
   
    /**
     * saveFileTeilnehmer
     *
     * @param \Ud\Iqtp13db\Domain\Model\Dokument $dokument
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @param array $files
     * @return void
     */
    public function saveFileTeilnehmer(\Ud\Iqtp13db\Domain\Model\Dokument $dokument, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer, $files)
    {        
        $storage = $this->generalhelper->getTP13Storage( $this->storageRepository->findAll());
        $pfad = $this->generalhelper->createFolder($teilnehmer, $this->storageRepository->findAll());
        $beratenepath = ltrim($pfad->getIdentifier(), '/');
        
        $tmpName = $this->generalhelper->sanitizeFileFolderName($files['name']['file']);
        $fullpath = $storage->getConfiguration()['basePath'] . $beratenepath . $tmpName;
        
        if($this->generalhelper->getFolderSize($storage->getConfiguration()['basePath'] . $beratenepath) > 40000) {
    	    $this->addFlashMessage('Maximum total filesize of 40 MB exceeded, please reduce filesize. Maximale Dateigröße aller Dateien zusammen ist 40 MB. Bitte Dateigröße verringern.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
    	} else {
    	    if ($files['name']['file']) {
    	        
    	        $dokument = $this->savefile($dokument->getBeschreibung(), $beratenepath, $files);
    	        
    	        if($dokument == null) {
    	            $this->addFlashMessage('File already uploaded. Datei wurde schon hochgeladen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
    	        } else {
    	            $dokument->setTeilnehmer($teilnehmer);
    	            $this->dokumentRepository->update($dokument);
    	            //Daten sofort in die Datenbank schreiben
    	            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
    	            $persistenceManager->persistAll();
    	            
    	            $this->teilnehmerRepository->update($teilnehmer);
    	        }    	        
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
     * @return boolean
     */
    public function deleteFileTeilnehmer(\Ud\Iqtp13db\Domain\Model\Dokument $dokument, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    { 
        $storage = $this->generalhelper->getTP13Storage($this->storageRepository->findAll());
        
        $fullpath = $storage->getConfiguration()['basePath'].'/'.$dokument->getPfad().$dokument->getName();
        
        if (file_exists($fullpath)) {
            if ($this->deletefile($dokument)) {   
                
                $anzdokumente = count($this->dokumentRepository->findByTeilnehmer($teilnehmer->getUid()));
                $this->teilnehmerRepository->update($teilnehmer);
                
                $this->addFlashMessage('Dokument wurde gelöscht.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
                return true;
            } else {
                $this->addFlashMessage('Dokument konnte nicht gelöscht werden!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                return false;
            }
        } else {
            $this->addFlashMessage('Datei mit ID '.$dokument->getUid().' nicht gefunden. Pfad: '.$fullpath, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            return false;
        }
    }

    /**
     * adds file
     *
     * @param string $beschreibung
     * @param string $pathtofile
     * @param array $arrfiles
     * @return \Ud\Iqtp13db\Domain\Model\Dokument
     */
    public function savefile($beschreibung, $pfad, $arrfiles)
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

        $searchDemand = FileSearchDemand::createForSearchTerm($tmpName);
        $files = $targetFolder->searchFiles($searchDemand);
        
        if(count($files) != 0) {
            return null;
        } else {
            $movedNewFile = $storage->addFile($tmpFile, $targetFolder, $tmpName, \TYPO3\CMS\Core\Resource\DuplicationBehavior::REPLACE);
            
            $reducedfile = $this->reduce_filesize($arrfiles, $tmpName, $storage->getConfiguration()['basePath'] . "/" .$pfad);
            
            $dokument->setBeschreibung($beschreibung);
            if($reducedfile) {            
                $movedNewFile->delete();
                $dokument->setName($reducedfile);
            } else {
                $dokument->setName($movedNewFile->getName());
            }
            
            $dokument->setPfad($pfad);
            
            $this->dokumentRepository->add($dokument);
            
            //Daten sofort in die Datenbank schreiben
            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
            
            return $dokument;
        }
    }

    /**
     * delete file
     *
     * @param \Ud\Iqtp13db\Domain\Model\Dokument $dokument
     * @return boolean
     */
    public function deletefile(\Ud\Iqtp13db\Domain\Model\Dokument $dokument)
    {                
        $this->dokumentRepository->remove($dokument);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        $storage = $this->generalhelper->getTP13Storage( $this->storageRepository->findAll());
        $delfile = $storage->getFile('/'.$dokument->getPfad().$dokument->getName());
        $erg = $storage->deleteFile($delfile);
        
        return $erg;
    }

   
    /**
     * 
     * Reduce file size on upload of file
     *
     **/    
    function reduce_filesize($filearr, $filename, $pfad) {
        
        if (is_array($filearr) && $filearr['size']['file'] > 1000000 && file_exists($pfad.$filename)) // bei Dateigrößen über 1 MB 
        {
            $fileName = $filearr['tmp_name']['file'];
            $fileExt = pathinfo($filearr['name']['file'], PATHINFO_EXTENSION);
            $fileNamewoExt = pathinfo($filearr['name']['file'], PATHINFO_FILENAME); 
            $percent = 40;
            
            $timestamp = time();
            
            $new_file_name = $fileNamewoExt . '_' . $timestamp . '_r.' . $fileExt;
                        
            //switch ($filearr['type']['file'])
            switch (exif_imagetype($pfad.$filename))
            {
                //case 'image/jpeg':
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg($pfad.$filename);
                    
                    if($image) {
                        $original_width = imagesx($image);
                        $original_height = imagesy($image);
                        $newwidth = $original_width * ($percent / 100);
                        $newheight = $original_height * ($percent / 100);
                        $new_image = imagecreatetruecolor($newwidth, $newheight);
                        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $newwidth, $newheight, $original_width, $original_height);
                        
                        $retval = imagejpeg($new_image, $pfad . $new_file_name, 90);
                        imagedestroy($image);
                        imagedestroy($new_image);
                        return $new_file_name;
                    } else {
                        return false;                        
                    }
                    break;
                
                //case 'image/gif':
                case IMAGETYPE_GIF:
                    $image = imagecreatefromgif($pfad.$filename);
                    
                    if($image) {
                        $original_width = imagesx($image);
                        $original_height = imagesy($image);
                        $newwidth = $original_width * ($percent / 100);
                        $newheight = $original_height * ($percent / 100);
                        $new_image = imagecreatetruecolor($newwidth, $newheight);
                        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $newwidth, $newheight, $original_width, $original_height);
                        
                        $retval = imagegif($new_image, $pfad . $new_file_name);
                        imagedestroy($image);
                        imagedestroy($new_image);
                        return $new_file_name;
                    } else {
                        return false;
                    }                    
                    break;
                
                //case 'image/png':
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($pfad.$filename);
                    
                    if($image) {
                        $original_width = imagesx($image);
                        $original_height = imagesy($image);
                        $newwidth = $original_width * ($percent / 100);
                        $newheight = $original_height * ($percent / 100);
                        $new_image = imagecreatetruecolor($newwidth, $newheight);
                        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $newwidth, $newheight, $original_width, $original_height);
                        
                        $retval = imagepng($new_image, $pfad . $new_file_name);
                        imagedestroy($image);
                        imagedestroy($new_image);
                        return $new_file_name;
                    } else {
                        return false;
                    }                    
                    break;
                //case 'application/pdf':
                default:                    
                    break;
            }
            
        }
        return FALSE;
    }
    
    /**
     *
     * Check if file was already uploaded to server
     *
     **/
    function file_already_uploaded($pfad) {
        
    }
    
    
}
