<?php
namespace Ud\Iqtp13db\Controller;
use \Datetime;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;

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
            $standardbccmail = $this->settings['standardbccmail'];
            
            $ugroupsarray = explode(",",$this->user['usergroup']);
            
            $this->anzbstellen = count($ugroupsarray);
            
            $thisusrgrpid = array_pop($ugroupsarray);
            $this->usergroup = $this->userGroupRepository->findByIdentifier($thisusrgrpid);
            
            if($this->usergroup != NULL) {
                $userniqidbstelle = $this->usergroup->getNiqbid() ?? $standardniqidberatungsstelle;
            }
            //$this->niqbid = $userniqidbstelle == '' ? $standardniqidberatungsstelle : $userniqidbstelle;
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

        $this->view->assignMultiple(
            [
                'gruppenberatungen' => $gruppenberatungen,
                'calleraction' => 'listgruppenberatung',
                'callercontroller' => 'Gruppenberatung',
                'callerpage' => $currentPage,
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
        
        $this->view->assign('gruppenberatung', $gruppenberatung);
        // Initialisiere Objectstorage für teilnehmer
        $teilnehmeros = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $teilnehmeros = $gruppenberatung->getTeilnehmer();
        
        DebuggerUtility::var_dump($teilnehmeros);
        
        $this->view->assign('calleraction', $valArray['calleraction']);
        $this->view->assign('callercontroller', $valArray['callercontroller']);
        $this->view->assign('callerpage', $valArray['callerpage'] ?? '1');
        $this->view->assign('thisaction', $valArray['thisaction'] ?? '');
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
        
        $this->view->assign('thisaction', $valArray['thisaction'] ?? '');
        $this->view->assign('calleraction', $valArray['calleraction']);
        $this->view->assign('callercontroller', $valArray['callercontroller']);
        $this->view->assign('callerpage', $valArray['callerpage'] ?? '1');
        $this->view->assign('settings', $this->settings);
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
        
        $this->view->assign('gruppenberatung', $gruppenberatung);
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
     * @param \Ud\Iqtp13db\Domain\Model\Gruppenberatung $gruppenberatung
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("gruppenberatung")
     * @return void
     */
    public function updateAction(\Ud\Iqtp13db\Domain\Model\Gruppenberatung $gruppenberatung): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        $this->gruppenberatungRepository->update($gruppenberatung);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        $this->addFlashMessage('Gruppenberatung aktualisiert.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
        return $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage'] ?? '1'));
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
        $teilnehmer = $folgekontakt->getTeilnehmer();
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        $this->addFlashMessage('Gruppenberatung gelöscht.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
        return $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage'] ?? '1'));
        
    }
}
