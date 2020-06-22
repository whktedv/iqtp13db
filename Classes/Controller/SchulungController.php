<?php
namespace Ud\Iqtp13db\Controller;

/***
 *
 * This file is part of the "IQ TP13 Datenbank Anerkennungserstberatung NRW" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Uli Dohmen <edv@whkt.de>, WHKT
 *
 ***/

/**
 * SchulungController
 */
class SchulungController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * schulungRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\SchulungRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $schulungRepository = NULL;

    /**
     * dokumentRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\DokumentRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $dokumentRepository = NULL;

    /**
     * beraterRepository
     *
     * @var \Ud\Iqtp13db\Domain\Repository\BeraterRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $beraterRepository = NULL;

    /**
     * action init
     *
     * @param void
     */
    public function initializeAction()
    {
        if (isset($this->arguments['schulung'])) {
            $this->arguments->getArgument('schulung')->getPropertyMappingConfiguration()->forProperty('datum')->setTypeConverterOption('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter', \TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'd.m.Y');
        }
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $schulungs = $this->schulungRepository->findAll();
        $this->view->assign('schulungs', $schulungs);
    }

    /**
     * action show
     *
     * @param \Ud\Iqtp13db\Domain\Model\Schulung $schulung
     * @return void
     */
    public function showAction(\Ud\Iqtp13db\Domain\Model\Schulung $schulung)
    {
        $dokumente = $this->dokumentRepository->findBySchulung($schulung);
        $berater = $this->beraterRepository->findAll();
        $this->view->assign('berater', $berater);
        $this->view->assign('dokumente', $dokumente);
        $this->view->assign('schulung', $schulung);
    }

    /**
     * action new
     *
     * @return void
     */
    public function newAction()
    {
        $berater = $this->beraterRepository->findAll();
        $this->view->assign('berater', $berater);
    }

    /**
     * action create
     *
     * @param \Ud\Iqtp13db\Domain\Model\Schulung $schulung
     * @return void
     */
    public function createAction(\Ud\Iqtp13db\Domain\Model\Schulung $schulung)
    {
        $this->addFlashMessage('The object was created.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->schulungRepository->add($schulung);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \Ud\Iqtp13db\Domain\Model\Schulung $schulung
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("schulung")
     * @return void
     */
    public function editAction(\Ud\Iqtp13db\Domain\Model\Schulung $schulung)
    {
        $berater = $this->beraterRepository->findAll();
        $this->view->assign('berater', $berater);
        $this->view->assign('schulung', $schulung);
    }

    /**
     * action update
     *
     * @param \Ud\Iqtp13db\Domain\Model\Schulung $schulung
     * @return void
     */
    public function updateAction(\Ud\Iqtp13db\Domain\Model\Schulung $schulung)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See <a href="http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain" target="_blank">Wiki</a>', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->schulungRepository->update($schulung);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \Ud\Iqtp13db\Domain\Model\Schulung $schulung
     * @return void
     */
    public function deleteAction(\Ud\Iqtp13db\Domain\Model\Schulung $schulung)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See <a href="http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain" target="_blank">Wiki</a>', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->schulungRepository->remove($schulung);
        $this->redirect('list');
    }
}
