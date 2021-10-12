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
        $this->addFlashMessage('Berater erstellt.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
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
        $this->addFlashMessage('Berater aktualisiert.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
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
        $this->addFlashMessage('Berater gelöscht.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->beraterRepository->remove($berater);
        $this->redirect('list');
    }
}
