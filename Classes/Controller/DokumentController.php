<?php
namespace Ud\Iqtp13db\Controller;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Core\Core\Environment;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Core\Resource\ResourceFactory;

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
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function saveFileBackendAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        if ($_FILES['tx_iqtp13db_iqtp13dbadmin']['tmp_name']['file'] == '') {
            $this->addFlashMessage('Error in saveFileWebapp: maximum filesize exceeded or permission error', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
        } else {        
            $dokument = new \Ud\Iqtp13db\Domain\Model\Dokument();
            $dokument->setBeschreibung("");
            $dokument->setCrdate(time());
            $this->saveFileTeilnehmer($dokument, $teilnehmer, $_FILES['tx_iqtp13db_iqtp13dbadmin']);
        }
        
        return $this->redirect($valArray['thisaction'], 'Backend', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'] ?? 'edit', 'callercontroller' => $valArray['callercontroller'] ?? 'Backend', 'callerpage' => $valArray['callerpage'] ?? '1', 'showdokumente' => '1'));
    }
    
    /**
     * action savemultiFileBackend
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function savemultiFileBackendAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        $dateienbisher = $this->dokumentRepository->findByTeilnehmer($teilnehmer->getUid());
        $anzdateienbisher = count($dateienbisher);
        $files = $this->request->getArgument('file') ?? null;
        
        if($files == NULL) {
            $this->addFlashMessage('Error in saveFileBackend: maximum filesize exceeded or permission error', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
        } else {
            foreach ($files as $file) {
                $dokument = new \Ud\Iqtp13db\Domain\Model\Dokument();
                $dokument->setBeschreibung("");
                $dokument->setCrdate(time());
                $this->saveFileTeilnehmer($dokument, $teilnehmer, $file);
            }
        }
        
        return $this->redirect($valArray['thisaction'], 'Backend', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'] ?? 'edit', 'callercontroller' => $valArray['callercontroller'] ?? 'Backend', 'callerpage' => $valArray['callerpage'] ?? '1', 'showdokumente' => '1'));
    }
    
    /**
     * action updateBackendAction
     * 
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function updateBackendAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        if(array_key_exists('Dokument', $valArray)) {
            $dokid = $valArray['Dokument']['__identity'];
            $thisdok = $this->dokumentRepository->findByUid($dokid);
            $thisdok->setBeschreibung($valArray['Dokument']['beschreibung']);
            
            $this->dokumentRepository->update($thisdok);
            //Daten sofort in die Datenbank schreiben
            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
        }
         
        return $this->redirect($valArray['thisaction'], 'Backend', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'] ?? 'edit', 'callercontroller' => $valArray['callercontroller'] ?? 'Backend', 'callerpage' => $valArray['callerpage'] ?? '1', 'showdokumente' => '1'));
    }
    
    
    /**
     * action deleteFileBackend
     *
     * @param \Ud\Iqtp13db\Domain\Model\Dokument $dokument
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("dokument")
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
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
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("dokument")
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function openfileAction(\Ud\Iqtp13db\Domain\Model\Dokument $dokument, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $storage = $this->generalhelper->getTP13Storage($this->storageRepository->findAll());
        $beratenepath = $dokument->getPfad();
        $tmpName = $dokument->getName();
        
        $targetfile = $storage->getFile($beratenepath . $tmpName);
        
        $queryParameterArray = ['eID' => 'dumpFile', 't' => 'f'];
        $queryParameterArray['f'] = $targetfile->getUid();
        $queryParameterArray['token'] = GeneralUtility::hmac(implode('|', $queryParameterArray), 'resourceStorageDumpFile');
        $publicUrl = GeneralUtility::locationHeaderUrl(PathUtility::getAbsoluteWebPath(Environment::getPublicPath() . '/index.php'));
        $publicUrl .= '?' . http_build_query($queryParameterArray, '', '&', PHP_QUERY_RFC3986);
        
        return $this->redirectToURI($publicUrl, $delay=0, $statusCode=303);
        
    }
    
    /**
     * action saveFileWebapp
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function saveFileWebappAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        if($_FILES == NULL) {
            $this->addFlashMessage('Error in saveFileWebapp: File does not meet policy.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
        } else {
            if ($_FILES['tx_iqtp13db_iqtp13dbwebapp']['tmp_name']['file'] == '') {
                $this->addFlashMessage('Error in saveFileWebapp: permission error or maximum filesize exceeded.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            } elseif (filesize($_FILES['tx_iqtp13db_iqtp13dbwebapp']['tmp_name']['file']) > 10485760) {
                $this->addFlashMessage('Error in saveFileWebapp: Maximum filesize exceeded (10 MB). Please reduce filesize.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            } else {
                $dokument = new \Ud\Iqtp13db\Domain\Model\Dokument();
                $dokument->setBeschreibung($valArray['beschreibung'] ?? '');
                $dokument->setTnfreigabe(1);
                $dokument->setCrdate(time());
                $this->saveFileTeilnehmer($dokument, $teilnehmer, $_FILES['tx_iqtp13db_iqtp13dbwebapp']);
            }
            
        }
        return $this->redirect('anmeldungcomplete', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer));
    }
    
    
    /**
     * action savemultiFileWebapp
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function savemultiFileWebappAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        $dateienbisher = $this->dokumentRepository->findByTeilnehmer($teilnehmer->getUid());
        $anzdateienbisher = count($dateienbisher);
        if($this->request->hasArgument('file')){
            $files = $this->request->getArgument('file');
            
            if($files == NULL) {
                $this->addFlashMessage('Error in saveFileWebapp: File does not meet policy.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            } else {
                foreach ($files as $file) {
                    $filesize = $file['size'] ?? 0;
                    if ($file['tmp_name'] == '') {
                        $this->addFlashMessage('Error: permission error or maximum filesize exceeded.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
                        break;
                    } elseif ($filesize > 10485760) {
                        $this->addFlashMessage('Error: Maximum filesize exceeded (10 MB). Please reduce filesize.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
                        break;
                    } else {
                        $fileType = $file['type'];
                        // TODO: Dateityp überprüfen
                        
                        $dokument = new \Ud\Iqtp13db\Domain\Model\Dokument();
                        $dokument->setBeschreibung($valArray['beschreibung'] ?? '');                                                
                        $dokument->setTnfreigabe(1);
                        $dokument->setCrdate(time());
                        $this->saveFileTeilnehmer($dokument, $teilnehmer, $file);
                        $this->addFlashMessage('Upload erfolgreich.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
                    }
                }
                
            }            
        }   
        return $this->redirect($valArray['calleraction'], 'Teilnehmer', null, array('teilnehmer' => $teilnehmer));
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
    public function deleteFileWebappAction(\Ud\Iqtp13db\Domain\Model\Dokument $dokument, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        $retval = $this->deleteFileTeilnehmer($dokument, $teilnehmer);
        return $this->redirect($valArray['calleraction'], 'Teilnehmer', null, array('teilnehmer' => $teilnehmer));
        //return (new ForwardResponse($valArray['calleraction']))->withControllerName('Teilnehmer');
    }
   
    /**
     * saveFileTeilnehmer
     *
     * @param \Ud\Iqtp13db\Domain\Model\Dokument $dokument
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @param array $file
     * @return void
     */
    public function saveFileTeilnehmer(\Ud\Iqtp13db\Domain\Model\Dokument $dokument, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer, $file)
    {        
        $storage = $this->generalhelper->getTP13Storage( $this->storageRepository->findAll());
        $pfad = $this->generalhelper->createFolder($teilnehmer, $this->storageRepository->findAll());
        $beratenepath = ltrim($pfad->getIdentifier(), '/');
        
        $tmpName = $this->generalhelper->sanitizeFileFolderName($file['name']);
        $fullpath = $storage->getConfiguration()['basePath'] . $beratenepath . $tmpName;
        
        if($this->generalhelper->getFolderSize($storage->getConfiguration()['basePath'] . $beratenepath) > 40000) {
    	    $this->addFlashMessage('Maximum total filesize of 40 MB exceeded, please reduce filesize. Maximale Dateigröße aller Dateien zusammen ist 40 MB. Bitte Dateigröße verringern.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
    	} else {
    	    if ($file) {

    	        $dokument = $this->savefile($dokument->getBeschreibung(), $beratenepath, $file, $dokument->getTnfreigabe());
    	        
    	        if($dokument == null) {
    	            $this->addFlashMessage('File already uploaded. Datei wurde schon hochgeladen.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
    	        } else {
    	            $dokument->setTeilnehmer($teilnehmer);
    	            $dokument->setCrdate(time());
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
        
        if($this->dokumentRepository->findDublette($dokument->getName(), $teilnehmer->getUid())) {
            $this->addFlashMessage('Fehler: D-1. Datei mit ID '.$dokument->getUid().' konnte nicht gelöscht werden!', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            return false;
        }
        
        $storage = $this->generalhelper->getTP13Storage($this->storageRepository->findAll());
        
        $fullpath = $storage->getConfiguration()['basePath'].'/'.$dokument->getPfad().$dokument->getName();
        
        if (file_exists($fullpath)) {
            if ($this->deletefile($dokument)) {   
                
                $anzdokumente = count($this->dokumentRepository->findByTeilnehmer($teilnehmer->getUid()));
                $this->teilnehmerRepository->update($teilnehmer);
                
                $this->addFlashMessage('Dokument wurde gelöscht.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
                return true;
            } else {
                $this->addFlashMessage('Dokument konnte nicht gelöscht werden!', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
                return false;
            }
        } else {
            $this->addFlashMessage('Datei mit ID '.$dokument->getUid().' nicht gefunden. Pfad: '.$fullpath, '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            return false;
        }
    }
    
    /**
     * action openfileextern
     *
     * @param \Ud\Iqtp13db\Domain\Model\Dokument $dokument
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function openfileexternAction(\Ud\Iqtp13db\Domain\Model\Dokument $dokument, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $storage = $this->generalhelper->getTP13Storage($this->storageRepository->findAll());
        $beratenepath = $dokument->getPfad();
        $tmpName = $dokument->getName();
        
        $targetfile = $storage->getFile($beratenepath . $tmpName);
        
        $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
        
        try {
            $file = $resourceFactory->getFileObject($targetfile->getUid());
            
            // Sicherheitsprüfung
            if (!$file->exists()) {
                throw new \Exception('Datei nicht gefunden');
            }
            
            // Response-Header setzen
            $response = $this->responseFactory->createResponse()
            ->withHeader('Content-Type', $file->getMimeType())
            ->withHeader('Content-Disposition', 'attachment; filename="' . $file->getName() . '"')
            ->withHeader('Content-Length', (string)$file->getSize());
            
            // Datei-Inhalt zum Response hinzufügen
            $response->getBody()->write($file->getContents());
            
            return $response;
            
        } catch (\Exception $e) {
            // Fehlerbehandlung
            return $this->responseFactory->createResponse(404)
            ->withHeader('Content-Type', 'text/plain')
            ->withBody($this->streamFactory->createStream('Datei nicht gefunden'));
        }
      
    }
    
    /**
     * action deletefileextern
     *
     * @param \Ud\Iqtp13db\Domain\Model\Dokument $dokument
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function deletefileexternAction(\Ud\Iqtp13db\Domain\Model\Dokument $dokument, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $retval = $this->deleteFileTeilnehmer($dokument, $teilnehmer);
        return $this->redirect('editexternmenu', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
    }
    
    /**
     * action uploadfileextern
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function uploadfileexternAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        if($_FILES == NULL) {
            $this->addFlashMessage('Error in saveFileWebapp: File does not meet policy.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
        } else {
            if ($_FILES['tx_iqtp13db_iqtp13dbwebapp']['tmp_name']['file'] == '') {
                $this->addFlashMessage('Error in saveFileWebapp: permission error or maximum filesize exceeded.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            } elseif (filesize($_FILES['tx_iqtp13db_iqtp13dbwebapp']['tmp_name']['file']) > 10485760) {
                $this->addFlashMessage('Error in saveFileWebapp: Maximum filesize exceeded (10 MB). Please reduce filesize.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            } else {
                $dokument = new \Ud\Iqtp13db\Domain\Model\Dokument();
                $dokument->setBeschreibung($valArray['beschreibung']);
                $dokument->setTnfreigabe(1);
                $this->saveFileTeilnehmer($dokument, $teilnehmer, $_FILES['tx_iqtp13db_iqtp13dbwebapp']);
            }
            
        }
        return $this->redirect('editexternmenu', 'Teilnehmer', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
    }
    
    /**
     * adds file
     *
     * @param string $beschreibung
     * @param string $pathtofile
     * @param array $file
     * @param string $tnfreigabe 
     * @return \Ud\Iqtp13db\Domain\Model\Dokument
     */
    public function savefile($beschreibung, $pfad, $file, $tnfreigabe)
    {          
        $dokument = new \Ud\Iqtp13db\Domain\Model\Dokument();
        
        $tmpName = $this->generalhelper->sanitizeFileFolderName($file['name']);
        $tmpFile = $file['tmp_name'];
                
        $storage = $this->generalhelper->getTP13Storage($this->storageRepository->findAll());
        
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
            
            $reducedfile = $this->reduce_filesize($file, $tmpName, $storage->getConfiguration()['basePath'] . "/" .$pfad);
            
            $dokument->setBeschreibung($beschreibung);
            $dokument->setTnfreigabe($tnfreigabe);
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
        
        $storage = $this->generalhelper->getTP13Storage($this->storageRepository->findAll());
        $delfile = $storage->getFile('/'.$dokument->getPfad().$dokument->getName());
        $erg = $storage->deleteFile($delfile);
        
        return $erg;
    }

   
    /**
     * 
     * Reduce file size on upload of file
     *
     **/    
    function reduce_filesize($file, $filename, $pfad) {
        
        $filesize = $file['size'] ?? 0;
        if (is_array($file) && $filesize > 800000 && file_exists($pfad.$filename)) // bei Dateigrößen über 800 kB 
        {
            $fileName = $file['tmp_name'];
            $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileNamewoExt = pathinfo($file['name'], PATHINFO_FILENAME); 
            $percent = 35;
            
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
