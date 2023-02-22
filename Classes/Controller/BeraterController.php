<?php
namespace Ud\Iqtp13db\Controller;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use Ud\Iqtp13db\Domain\Repository\UserGroupRepository;
use Ud\Iqtp13db\Domain\Repository\BeraterRepository;

/**
 * BeraterController
 */
class BeraterController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    protected $niqbid, $usergroup;
    protected $userGroupRepository;
    protected $beraterRepository;
    
    public function __construct(UserGroupRepository $userGroupRepository, BeraterRepository $beraterRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
        $this->beraterRepository = $beraterRepository;
    }
    
    /**
     * action init
     *
     * @param void
     */
    public function initializeAction()
    {
        $this->user=null;
        $context = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class);
        if($context->getPropertyFromAspect('frontend.user', 'isLoggedIn')){
            $this->user=$GLOBALS['TSFE']->fe_user->user;
        }
        
        if($this->user != NULL) {
            $standardniqidberatungsstelle = $this->settings['standardniqidberatungsstelle'];
            $this->usergroup = $this->userGroupRepository->findByUid($this->user['usergroup']);
            $userniqidbstelle = $this->usergroup->getNiqbid();
            $this->niqbid = $userniqidbstelle == '' ? $standardniqidberatungsstelle : $userniqidbstelle;
        }
        
    }
    
    /**
     * action list
     *
     * @param int $currentPage
     * @return void
     */
    public function listAction(int $currentPage = 1)
    {
        $berater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->user['usergroup']);
        
    	$currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
    	$paginator = new QueryResultPaginator($berater, $currentPage, 25);
    	$pagination = new SimplePagination($paginator);
    	
    	$this->view->assignMultiple(
    	    [
    	        'callerpage' => $currentPage,
    	        'paginator' => $paginator,
    	        'pagination' => $pagination,
    	        'pages' => range(1, $pagination->getLastPageNumber()),
    	        'berater' => $berater,
    	        'thisuser' => $this->user
    	    ]
   	    );
    }
    
    /**
     * action edit
     *
     * @param \Ud\Iqtp13db\Domain\Model\Berater $berater
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("berater")
     * @return void
     */
    public function editAction(\Ud\Iqtp13db\Domain\Model\Berater $berater)
    {
        $usergroups = $this->userGroupRepository->findAll();
        
        $this->view->assign('berater', $berater);
        $this->view->assign('usergroups', $usergroups);
        $this->view->assign('thisuser', $this->user);
    }
    
    /**
     * action update
     *
     * @param \Ud\Iqtp13db\Domain\Model\Berater $berater
     * @return void
     */
    public function updateAction(\Ud\Iqtp13db\Domain\Model\Berater $berater)
    {
        $this->addFlashMessage('Berater*in aktualisiert.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        
        $valArray = $this->request->getArguments();
        
        $berater->setPassword(password_hash($berater->getPassword(), PASSWORD_ARGON2I));
                
        $this->beraterRepository->update($berater);
        $this->redirect('list');
    }
    
    /**
     * action delete
     *
     * @param \Ud\Iqtp13db\Domain\Model\Berater $berater
     * @return void
     */
    public function deleteAction(\Ud\Iqtp13db\Domain\Model\Berater $berater)
    {
        $this->addFlashMessage('Berater*in gelöscht.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->beraterRepository->remove($berater);
        $this->redirect('list');
    }   
    
}
