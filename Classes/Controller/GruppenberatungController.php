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
    public function listgruppenberatung(int $currentPage = 1): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        
        //$gruppenberatungen = $this->gruppenberatungRepository->
                // Daten sofort in die Datenbank schreiben
    
    }
    
    
}
