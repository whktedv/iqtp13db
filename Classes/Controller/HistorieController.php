<?php
namespace Ud\Iqtp13db\Controller;

/***
 *
 * This file is part of the "IQ Webapp Anerkennungsberatung" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Uli Dohmen <edv@whkt.de>, WHKT
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
     * @var \Ud\Iqtp13db\Domain\Repository\historieRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $historieRepository = NULL;

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
    	$historien = $this->historieRepository->findAll();
    	
    	$this->view->assign('historie', $historien);
    }
    
    
}
