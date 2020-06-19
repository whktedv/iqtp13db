<?php
namespace Ud\Iqtp13db\Controller;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Uli Dohmen <edv@whkt.de>, WHKT
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/***
 *
 * This file is part of the "IQ TP13 Datenbank Anerkennungserstberatung NRW" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Uli Dohmen <edv@whkt.de>, WHKT
 *
 ***/

/**
 * BeraterController
 */
class BeraterController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * beraterRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\BeraterRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $beraterRepository = NULL;

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $beraters = $this->beraterRepository->findAll();
        $this->view->assign('beraters', $beraters);
    }

    /**
     * action show
     *
     * @param \Ud\Iqtp13db\Domain\Model\Berater $berater
     * @return void
     */
    public function showAction(\Ud\Iqtp13db\Domain\Model\Berater $berater)
    {
        $this->view->assign('berater', $berater);
    }

    /**
     * action new
     *
     * @return void
     */
    public function newAction()
    {

    }

    /**
     * action create
     *
     * @param \Ud\Iqtp13db\Domain\Model\Berater $newBerater
     * @return void
     */
    public function createAction(\Ud\Iqtp13db\Domain\Model\Berater $newBerater)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See <a href="http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain" target="_blank">Wiki</a>', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->beraterRepository->add($newBerater);
        $this->redirect('list');
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
        $this->view->assign('berater', $berater);
    }

    /**
     * action update
     *
     * @param \Ud\Iqtp13db\Domain\Model\Berater $berater
     * @return void
     */
    public function updateAction(\Ud\Iqtp13db\Domain\Model\Berater $berater)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See <a href="http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain" target="_blank">Wiki</a>', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
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
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See <a href="http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain" target="_blank">Wiki</a>', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->beraterRepository->remove($berater);
        $this->redirect('list');
    }
}
