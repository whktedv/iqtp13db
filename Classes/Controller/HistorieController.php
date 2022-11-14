<?php
namespace Ud\Iqtp13db\Controller;

use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;


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
 * HistorieController
 */
class HistorieController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
   
    /**
     * historieRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\HistorieRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $historieRepository = NULL;

    /**
     * action list
     *
     * @param int $currentPage
     * @return void
     */
    public function listAction(int $currentPage = 1)
    {
    	$historien = $this->historieRepository->findAll();
    	
    	$currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
    	$paginator = new QueryResultPaginator($historien, $currentPage, 25);
    	$pagination = new SimplePagination($paginator);
    	
    	$this->view->assignMultiple(
    	    [
    	        'callerpage' => $currentPage,
    	        'paginator' => $paginator,
    	        'pagination' => $pagination,
    	        'pages' => range(1, $pagination->getLastPageNumber()),
    	        'historie' => $historien
    	    ]
    	);
    }
    
    
}
