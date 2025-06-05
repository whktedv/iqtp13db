<?php
namespace Ud\Iqtp13db\Controller;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use Ud\Iqtp13db\Domain\Repository\UserGroupRepository;
use Ud\Iqtp13db\Domain\Repository\BeraterRepository;

use Psr\Http\Message\ResponseInterface;

/**
 * BeraterController
 */
class BeraterController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    protected $usergroup;
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
            $this->usergroup = $this->userGroupRepository->findByUid($this->user['usergroup']);
        }
        
    }
    
    /**
     * action list
     *
     * @param int $currentPage
     * @return void
     */
    public function listAction(int $currentPage = 1): ResponseInterface
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
    	return $this->htmlResponse();
    }
    
    /**
     * action edit
     *
     * @param \Ud\Iqtp13db\Domain\Model\Berater $berater
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("berater")
     * @return void
     */
    public function editAction(\Ud\Iqtp13db\Domain\Model\Berater $berater): ResponseInterface
    {
        $usergroups = $this->userGroupRepository->findAll();
        
        $isLoaded = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('ud_totpauth');
        
        $this->view->assign('isudtotpauthloaded', $isLoaded);        
        $this->view->assign('berater', $berater);
        $this->view->assign('usergroups', $usergroups);
        $this->view->assign('thisuser', $this->user);
        $this->view->assign('userId', $this->user['uid']);
        $this->view->assign('pageid2facode', $this->settings['pageid2facode']);
        return $this->htmlResponse();
    }
    
    /**
     * action update
     *
     * @param \Ud\Iqtp13db\Domain\Model\Berater $berater
     * @return void
     */
    public function updateAction(\Ud\Iqtp13db\Domain\Model\Berater $berater): ResponseInterface
    {
        $this->addFlashMessage('Berater*in aktualisiert.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
        
        $valArray = $this->request->getArguments();
        
        $berater->setPassword(password_hash($berater->getPassword(), PASSWORD_ARGON2I));
                
        $this->beraterRepository->update($berater);
        return $this->redirect('editsettings', 'Backend', 'Iqtp13db', null);
    }
    
    /**
     * action delete
     *
     * @param \Ud\Iqtp13db\Domain\Model\Berater $berater
     * @return void
     */
    public function deleteAction(\Ud\Iqtp13db\Domain\Model\Berater $berater): ResponseInterface
    {
        $this->addFlashMessage('Berater*in gelöscht.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
        $this->beraterRepository->remove($berater);
        return $this->redirect('editsettings', 'Backend', 'Iqtp13db', null);
    }   
    
}
