<?php
namespace Ud\Iqtp13db\Controller;
use \Datetime;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;

use Psr\Http\Message\ResponseInterface;
use Ud\Iqtp13db\Domain\Repository\UserGroupRepository;
use Ud\Iqtp13db\Domain\Repository\TeilnehmerRepository;
use Ud\Iqtp13db\Domain\Repository\BeraterRepository;
use Ud\Iqtp13db\Domain\Repository\GruppenberatungRepository;
use TYPO3\CMS\Core\Resource\StorageRepository;

require_once(Environment::getPublicPath() . '/' . 'typo3conf/ext/iqtp13db/Resources/Private/Libraries/xlsxwriter.class.php');

/***
 *
 * This file is part of the "IQ Webapp Anerkennungserstberatung" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2023 Uli Dohmen <edv@whkt.de>, WHKT
 *
 ***/

/**
 * GruppenberatungController
 */
class GruppenberatungController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    
    protected $generalhelper, $usergroup, $niqbid, $beratungsstellenname, $anzbstellen;
    
    protected $userGroupRepository;
    protected $teilnehmerRepository;    
    protected $beraterRepository;
    protected $storageRepository;
    protected $gruppenberatungRepository;
    
    public function __construct(
        UserGroupRepository $userGroupRepository, 
        TeilnehmerRepository $teilnehmerRepository, 
        BeraterRepository $beraterRepository, 
        StorageRepository $storageRepository,
        GruppenberatungRepository $gruppenberatungRepository
    )
    {
        $this->userGroupRepository = $userGroupRepository;
        $this->teilnehmerRepository = $teilnehmerRepository;
        $this->beraterRepository = $beraterRepository;
        $this->storageRepository = $storageRepository;
        $this->gruppenberatungRepository = $gruppenberatungRepository;
    }
    
    /**
     * action init
     *
     * @param void
     */
    public function initializeAction()
    {
        $this->generalhelper = new \Ud\Iqtp13db\Helper\Generalhelper();
        
        $this->user=null;
        $context = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class);
        if($context->getPropertyFromAspect('frontend.user', 'isLoggedIn')){
            $this->user=$GLOBALS['TSFE']->fe_user->user;
        } else {
            $this->user = NULL;
        }
        
        if($this->user != NULL) {
            $standardniqidberatungsstelle = $this->settings['standardniqidberatungsstelle'];
            
            $ugroupsarray = explode(",",$this->user['usergroup']);
            
            $this->anzbstellen = count($ugroupsarray);
            
            $thisusrgrpid = array_pop($ugroupsarray);
            $this->usergroup = $this->userGroupRepository->findByIdentifier($thisusrgrpid);
            if($this->usergroup->getTitle() == "Gruppenberatungen") {
                $thisusrgrpid = array_pop($ugroupsarray);
                $this->usergroup = $this->userGroupRepository->findByIdentifier($thisusrgrpid);
            }
            
            if($this->usergroup != NULL) {
                $userniqidbstelle = $this->usergroup->getNiqbid() ?? $standardniqidberatungsstelle;
            }
            $sesniqbid = $GLOBALS['TSFE']->fe_user->getKey('ses', 'currentusergroup') ?? '';
            $this->niqbid = $sesniqbid != '' ? $sesniqbid : $userniqidbstelle;
            $thisgroup = $this->userGroupRepository->findBeratungsstellebyNiqbid($this->settings['beraterstoragepid'], $this->niqbid);            
            $this->beratungsstellenname = $thisgroup[0]->getTitle();
        } else {
            // FEHLER oder Frontend für Ratsuchende
        }
    }
    
    /**
     * action listgruppenberatung
     *
     * @param int $currentPage
     * @return void
     */
    public function listgruppenberatungAction(int $currentPage = 1): ResponseInterface
    {
        $valArray = $this->request->getArguments();
                
        $gruppenberatungen = $this->gruppenberatungRepository->findAvailable($this->niqbid);
        $alleberater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->user['usergroup']);
        
        $this->view->assignMultiple(
            [
                'gruppenberatungen' => $gruppenberatungen,
                'calleraction' => 'listgruppenberatung',
                'callercontroller' => 'Gruppenberatung',
                'callerpage' => $currentPage,
                'alleberater' => $alleberater
            ]);
        return $this->htmlResponse();    
    }
    
    /**
     * action show
     *
     * @param \Ud\Iqtp13db\Domain\Model\Gruppenberatung $gruppenberatung
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("gruppenberatung")
     * @return void
     */
    public function showAction(\Ud\Iqtp13db\Domain\Model\Gruppenberatung $gruppenberatung): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        $berater = $this->beraterRepository->findByUid($gruppenberatung->getBerater());
        
        // Initialisiere Objectstorage für teilnehmer
        $teilnehmeros = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $teilnehmeros = $gruppenberatung->getTeilnehmer();
        
        $this->view->assign('gruppenberatung', $gruppenberatung);
        $this->view->assign('calleraction', $valArray['calleraction']);
        $this->view->assign('callercontroller', $valArray['callercontroller']);
        $this->view->assign('callerpage', $valArray['callerpage'] ?? '1');
        $this->view->assign('thisaction', 'show');
        $this->view->assign('berater', $berater);
        $this->view->assign('teilnehmeros', $teilnehmeros);
        return $this->htmlResponse();
    }
    
    /**
     * action new
     *
     * @return void
     */
    public function newAction(): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        $alleberater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->user['usergroup']);      
        
        $this->view->assign('thisaction', 'new');
        $this->view->assign('calleraction', $valArray['calleraction']);
        $this->view->assign('callercontroller', $valArray['callercontroller']);
        $this->view->assign('callerpage', $valArray['callerpage'] ?? '1');
        $this->view->assign('settings', $this->settings);
        $this->view->assign('alleberater', $alleberater);
        
        return $this->htmlResponse();
    }
    
    /**
     * action create
     *
     * @param \Ud\Iqtp13db\Domain\Model\Gruppenberatung $gruppenberatung
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("gruppenberatung")
     * @return void
     */
    public function createAction(\Ud\Iqtp13db\Domain\Model\Gruppenberatung $gruppenberatung): ResponseInterface
    {        
        $valArray = $this->request->getArguments();
        
        // Checkboxen werden als Array übergeben
        if ($this->request->hasArgument('beratungsarten')) {
            $beratungsartenArray = $this->request->getArgument('beratungsarten');
            if (is_array($beratungsartenArray)) {
                $gruppenberatung->setBeratungsarten(implode(',', $beratungsartenArray));
            }
        }
        // Anerkennungsberatung
        if ($this->request->hasArgument('anerkennungsberatung')) {
            $anerkennungsberatungArray = $this->request->getArgument('anerkennungsberatung');
            if (is_array($anerkennungsberatungArray)) {
                $gruppenberatung->setAnerkennungsberatung(implode(',', $anerkennungsberatungArray));
            }
        }
        
        // Qualifizierungsberatung
        if ($this->request->hasArgument('qualifizierungsberatung')) {
            $qualifizierungsberatungArray = $this->request->getArgument('qualifizierungsberatung');
            if (is_array($qualifizierungsberatungArray)) {
                $gruppenberatung->setQualifizierungsberatung(implode(',', $qualifizierungsberatungArray));
            }
        }
        
        $gruppenberatung->setNiqbid($this->niqbid);
        $this->gruppenberatungRepository->add($gruppenberatung);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        $this->addFlashMessage('Gruppenberatung erstellt.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
        
        return $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage']));
    }
    

    /**
     * action edit
     *
     * @param \Ud\Iqtp13db\Domain\Model\Gruppenberatung $gruppenberatung
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("gruppenberatung")
     * @return void
     */
    public function editAction(\Ud\Iqtp13db\Domain\Model\Gruppenberatung $gruppenberatung): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        $alleberater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->user['usergroup']); 
        
        // Initialisiere Objectstorage für teilnehmer
        $teilnehmeros = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $teilnehmeros = $gruppenberatung->getTeilnehmer();
        
        $this->view->assign('gruppenberatung', $gruppenberatung);
        $this->view->assign('thisaction', 'edit');
        $this->view->assign('callerpage', $valArray['callerpage']  ?? '1');
        $this->view->assign('calleraction', $valArray['calleraction']);
        $this->view->assign('callercontroller', $valArray['callercontroller']);
        $this->view->assign('settings', $this->settings);
        $this->view->assign('alleberater', $alleberater);
        $this->view->assign('teilnehmeros', $teilnehmeros);
        
        return $this->htmlResponse();
    }
    
   
    /**
     * action update
     *
     * @param \Ud\Iqtp13db\Domain\Model\Gruppenberatung $gruppenberatung
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("gruppenberatung")
     * @return void
     */
    public function updateAction(\Ud\Iqtp13db\Domain\Model\Gruppenberatung $gruppenberatung): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        // Checkboxen werden als Array übergeben
        if ($this->request->hasArgument('beratungsarten')) {
            $beratungsartenArray = $this->request->getArgument('beratungsarten');
            if (is_array($beratungsartenArray)) {
                $gruppenberatung->setBeratungsarten(implode(',', $beratungsartenArray));
            } else {
                // Wenn keine Checkbox aktiviert, leeren String setzen
                $gruppenberatung->setBeratungsarten('');
            }
        } else {
            // Wenn keine Checkbox aktiviert, leeren String setzen
            $gruppenberatung->setBeratungsarten('');
        }
        
        // Anerkennungsberatung
        if ($this->request->hasArgument('anerkennungsberatung')) {
            $anerkennungsberatungArray = $this->request->getArgument('anerkennungsberatung');
            if (is_array($anerkennungsberatungArray)) {
                $gruppenberatung->setAnerkennungsberatung(implode(',', $anerkennungsberatungArray));
            } else {
                $gruppenberatung->setAnerkennungsberatung('');
            }
        } else {
            $gruppenberatung->setAnerkennungsberatung('');
        }
        
        // Qualifizierungsberatung
        if ($this->request->hasArgument('qualifizierungsberatung')) {
            $qualifizierungsberatungArray = $this->request->getArgument('qualifizierungsberatung');
            if (is_array($qualifizierungsberatungArray)) {
                $gruppenberatung->setQualifizierungsberatung(implode(',', $qualifizierungsberatungArray));
            } else {
                $gruppenberatung->setQualifizierungsberatung('');
            }
        } else {
            $gruppenberatung->setQualifizierungsberatung('');
        }
        
        // Initialisiere Objectstorage für teilnehmer
        $teilnehmeros = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $teilnehmeros = $gruppenberatung->getTeilnehmer();
        $gberatungsarten = $gruppenberatung->getBeratungsarten();
        $berater = $this->beraterRepository->findByUid($gruppenberatung->getBerater());

        foreach($teilnehmeros as $teilnehmer) {
            $teilnehmer->setAnerkennendestellen($gruppenberatung->getAnerkennendestellen());
            $teilnehmer->setBeratungdatum($gruppenberatung->getBeratungdatum());                        
            $teilnehmer->setBerater($berater);
            $teilnehmer->setBeratungsart(explode(',', $gberatungsarten));
            if(str_contains($gberatungsarten, "1") || str_contains($gberatungsarten, "6")) {
                $teilnehmer->setBeratungsort($gruppenberatung->getOrt());
            }
            $teilnehmer->setBeratungsdauer($gruppenberatung->getBeratungsdauer());
            $teilnehmer->setBeratungzu($gruppenberatung->getBeratungzu());
            $teilnehmer->setAnerkennungsberatung(explode(',', $gruppenberatung->getAnerkennungsberatung()));
            $teilnehmer->setAnerkennungsberatungfreitext($gruppenberatung->getAnerkennungsberatungfreitext());
            $teilnehmer->setQualifizierungsberatung(explode(',', $gruppenberatung->getQualifizierungsberatung()));
            $teilnehmer->setQualifizierungsberatungfreitext($gruppenberatung->getQualifizierungsberatungfreitext());
            $teilnehmer->setErstberatungabgeschlossen($gruppenberatung->getErstberatungabgeschlossen());
            if($gruppenberatung->getErstberatungabgeschlossen() != '') {
                if($teilnehmer->getBeratungsstatus() != 4) $teilnehmer->setBeratungsstatus(3);
            } else {
                if($teilnehmer->getBeratungsstatus() != 4) $teilnehmer->setBeratungsstatus(2);
            }            
            $this->teilnehmerRepository->update($teilnehmer);
        }

        $this->gruppenberatungRepository->update($gruppenberatung);

        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        $this->addFlashMessage('Gruppenberatung aktualisiert.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
        return $this->redirect($valArray['thisaction'], $valArray['callercontroller'], null, array('gruppenberatung' => $gruppenberatung, 'calleraction' => $valArray['calleraction'], 'callercontroller' => $valArray['callercontroller']));
    }
    
    /**
     * action delete
     *
     * @param \Ud\Iqtp13db\Domain\Model\Gruppenberatung $gruppenberatung
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("gruppenberatung")
     * @return void
     */
    public function deleteAction(\Ud\Iqtp13db\Domain\Model\Gruppenberatung $gruppenberatung): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        $this->gruppenberatungRepository->remove($gruppenberatung);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        $this->addFlashMessage('Gruppenberatung gelöscht.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
        return $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage'] ?? '1'));
        
    }
    
    /**
     * action delete
     *
     * @param \Ud\Iqtp13db\Domain\Model\Gruppenberatung $gruppenberatung
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("gruppenberatung")
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function removeFromGroupConsultationAction(\Ud\Iqtp13db\Domain\Model\Gruppenberatung $gruppenberatung, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        $gruppenberatung->removeTeilnehmer($teilnehmer);
        
        $this->gruppenberatungRepository->update($gruppenberatung);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        $this->addFlashMessage('Teilnehmer aus Gruppenberatung entfernt, Beratungsdaten wurden NICHT gelöscht!', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::WARNING);
        return $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage'] ?? '1', 'gruppenberatung' => $gruppenberatung, 'teilnehmer' => $teilnehmer));
        
    }
    
    /**
     * action deleteall
     *
     * @param \Ud\Iqtp13db\Domain\Model\Gruppenberatung $gruppenberatung     
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("gruppenberatung")
     * @return void
     */
    public function removeAllFromGroupConsultationAction(\Ud\Iqtp13db\Domain\Model\Gruppenberatung $gruppenberatung): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        $newtnstorage = new ObjectStorage();
        $gruppenberatung->setTeilnehmer($newtnstorage);
        
        $this->gruppenberatungRepository->update($gruppenberatung);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        $this->addFlashMessage('Alle Teilnehmenden aus Gruppenberatung entfernt, Beratungsdaten wurden NICHT gelöscht!', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::WARNING);
        return $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage'] ?? '1', 'gruppenberatung' => $gruppenberatung));
        
    }
    
        /**
     * action archiveall
     *
     * @param \Ud\Iqtp13db\Domain\Model\Gruppenberatung $gruppenberatung
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("gruppenberatung")
     * @return void
     */
    public function archiveAllFromGroupConsultationAction(\Ud\Iqtp13db\Domain\Model\Gruppenberatung $gruppenberatung): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        // Initialisiere Objectstorage für teilnehmer
        $teilnehmeros = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $teilnehmeros = $gruppenberatung->getTeilnehmer();
        
        foreach($teilnehmeros as $teilnehmer) {
            $teilnehmer->setBeratungsstatus(4);
            $this->teilnehmerRepository->update($teilnehmer);
        }
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        $this->addFlashMessage('Alle Ratsuchenden ins Archiv verschoben, Beratungsdaten wurden NICHT geändert!', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::WARNING);
        return $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage'] ?? '1', 'gruppenberatung' => $gruppenberatung));        
    }
    
}
