<?php
namespace Ud\Iqtp13db\Controller;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

use Psr\Http\Message\ResponseInterface;
use Ud\Iqtp13db\Domain\Repository\TeilnehmerRepository;
use Ud\Iqtp13db\Domain\Repository\AbschlussRepository;
use Ud\Iqtp13db\Domain\Repository\BerufeRepository;
use Ud\Iqtp13db\Domain\Repository\StaatenRepository;
use Ud\Iqtp13db\Domain\Repository\BrancheRepository;

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
 * AbschlussController
 */
class AbschlussController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    protected $generalhelper, $niqinterface, $niqapiurl, $allusergroups, $usergroup, $niqbid, $groupbccmail;
    
    protected $teilnehmerRepository;
    protected $abschlussRepository;
    protected $berufeRepository;
    protected $staatenRepository;
    protected $brancheRepository;
    
    public function __construct(TeilnehmerRepository $teilnehmerRepository, AbschlussRepository $abschlussRepository, BerufeRepository $berufeRepository, StaatenRepository $staatenRepository, BrancheRepository $brancheRepository)
    {
        $this->teilnehmerRepository = $teilnehmerRepository;
        $this->abschlussRepository = $abschlussRepository;
        $this->berufeRepository = $berufeRepository;
        $this->staatenRepository = $staatenRepository;
        $this->brancheRepository = $brancheRepository;
    }
    
    

    /**
     * action show
     *
     * @param \Ud\Iqtp13db\Domain\Model\Abschluss $abschluss
     * @return void
     */
    public function showAction(\Ud\Iqtp13db\Domain\Model\Abschluss $abschluss)
    {
        $valArray = $this->request->getArguments();
        $language = $this->request->getAttribute('language');
        $isocode= $language->getTwoLetterIsoCode();
        
        $teilnehmer = $this->teilnehmerRepository->findByUid($valArray['teilnehmer']);
        $berufe = $this->berufeRepository->findAllOrdered($isocode);
        $staaten = $this->staatenRepository->findByLangisocode($isocode);
        $abschlussartarr = $this->settings['abschlussart'];
        unset($abschlussartarr[2]);
        
        $brancheunterkat = $this->brancheRepository->findAllUnterkategorie($isocode);
        $this->view->assign('abschlussartarr', $abschlussartarr);
        $this->view->assign('abschluss', $abschluss);
        $this->view->assign('teilnehmer', $teilnehmer);
        $this->view->assign('calleraction', $valArray['calleraction'] ?? 'edit');
        $this->view->assign('callercontroller', $valArray['callercontroller'] ?? 'Backend');
        $this->view->assign('callerpage', $valArray['callerpage'] ?? '1');
        $this->view->assign('thisaction', $valArray['thisaction']);
        $this->view->assign('berufe', $berufe);
        $this->view->assign('staaten', $staaten);
        $this->view->assign('brancheunterkat', $brancheunterkat);
    }

    /**
     * action new
     *
     * @return void
     */
    public function newAction()
    {
        $valArray = $this->request->getArguments();
        $language = $this->request->getAttribute('language');
        $isocode= $language->getTwoLetterIsoCode();
        
        $teilnehmer = $this->teilnehmerRepository->findByUid($valArray['teilnehmer']);
        
        $kastring = LocalizationUtility::translate('ka', 'iqtp13db');
        
        $aktuellesJahr = (int)date("Y");
        $abschlussjahre = array();
        $abschlussjahre[-1] = $kastring;
        for($jahr = $aktuellesJahr; $jahr > $aktuellesJahr-60; $jahr--) {
            $abschlussjahre[$jahr] = (String)$jahr;
        }
        $berufe = $this->berufeRepository->findAllOrdered($isocode);
        foreach($berufe as $beruf) {
            $berufearr[$beruf->getBerufid()] = $beruf->getTitel();
        }
        $staaten = $this->staatenRepository->findAll4Abschluss($isocode);
        if(count($staaten) == 0) $staaten = $this->staatenRepository->findByLangisocode('en');
        unset($staaten[201]);
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }
       
        $abschlussartarr = $this->settings['abschlussart'];
        unset($abschlussartarr[2]);
        
        $brancheokarr = $this->brancheRepository->findAllOberkategorie($isocode);
        foreach($brancheokarr as $brok) {
            $brancheoberkat[$brok['brancheid']] = $brok['titel'];
        }        
        $brancheunterkat = $this->brancheRepository->findAllUnterkategorie($isocode);
        
        $this->view->assign('abschlussartarr', $abschlussartarr);
        $this->view->assign('abschlussjahre', $abschlussjahre);
        $this->view->assign('teilnehmer', $teilnehmer);
        $this->view->assign('calleraction', $valArray['calleraction'] ?? 'edit');
        $this->view->assign('callercontroller', $valArray['callercontroller'] ?? 'Backend');
        $this->view->assign('callerpage', $valArray['callerpage'] ?? '1');
        $this->view->assign('thisaction', $valArray['thisaction']);
        $this->view->assign('berufearr', $berufearr);
        $this->view->assign('staatenarr', $staatenarr);
        $this->view->assign('brancheoberkat', $brancheoberkat);
        $this->view->assign('brancheunterkat', $brancheunterkat);                
    }

    /**
     * action create
     *     
     * @param \Ud\Iqtp13db\Domain\Model\Abschluss $abschluss
     * @return void
     */
    public function createAction(\Ud\Iqtp13db\Domain\Model\Abschluss $abschluss)
    {
        $valArray = $this->request->getArguments();
        
        $teilnehmer = $this->teilnehmerRepository->findByUid($valArray['teilnehmer']);

        $abschluss->setTeilnehmer($teilnehmer);
        $this->abschlussRepository->add($abschluss);
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        $this->redirect($valArray['thisaction'], 'Backend', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'], 'callercontroller' => $valArray['callercontroller'], 'callerpage' => $valArray['callerpage'], 'showabschluesse' => '1'));
    }

    /**
     * action edit
     *
     * @param \Ud\Iqtp13db\Domain\Model\Abschluss $abschluss
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("Abschluss")
     * @return void
     */
    public function editAction(\Ud\Iqtp13db\Domain\Model\Abschluss $abschluss)
    {
        $valArray = $this->request->getArguments();
        $language = $this->request->getAttribute('language');
        $isocode= $language->getTwoLetterIsoCode();
        
        $teilnehmer = $this->teilnehmerRepository->findByUid($valArray['teilnehmer']);
        
        $kastring = LocalizationUtility::translate('ka', 'iqtp13db');
        
        $aktuellesJahr = (int)date("Y");
        $abschlussjahre = array();
        $abschlussjahre[-1] = $kastring;
        for($jahr = $aktuellesJahr; $jahr > $aktuellesJahr-60; $jahr--) {
            $abschlussjahre[$jahr] = (String)$jahr;
        }
        $berufe = $this->berufeRepository->findAllOrdered($isocode);
        foreach($berufe as $beruf) {
            $berufearr[$beruf->getBerufid()] = $beruf->getTitel();
        }
        $staaten = $this->staatenRepository->findAll4Abschluss($isocode);
        if(count($staaten) == 0) $staaten = $this->staatenRepository->findByLangisocode('en');
        unset($staaten[201]);        
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }
        $abschlussartarr = $this->settings['abschlussart'];
        unset($abschlussartarr[2]);
        
        $brancheokarr = $this->brancheRepository->findAllOberkategorie($isocode);
        foreach($brancheokarr as $brok) {
            $brancheoberkat[$brok['brancheid']] = $brok['titel'];
        }
        $brancheunterkat = $this->brancheRepository->findAllUnterkategorie($isocode);
                
        $this->view->assign('abschlussartarr', $abschlussartarr);
        $this->view->assign('abschlussjahre', $abschlussjahre);
        $this->view->assign('teilnehmer', $teilnehmer);             
        $this->view->assign('abschluss', $abschluss);
        $this->view->assign('calleraction', $valArray['calleraction'] ?? 'edit');
        $this->view->assign('callercontroller', $valArray['callercontroller'] ?? 'Backend');
        $this->view->assign('callerpage', $valArray['callerpage'] ?? '1');
        $this->view->assign('thisaction', $valArray['thisaction']);
        $this->view->assign('berufearr', $berufearr);
        $this->view->assign('staatenarr', $staatenarr);
        $this->view->assign('brancheoberkat', $brancheoberkat);
        $this->view->assign('brancheunterkat', $brancheunterkat);        
    }

    /**
     * action update
     *
     * @param \Ud\Iqtp13db\Domain\Model\Abschluss $abschluss
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function updateAction(\Ud\Iqtp13db\Domain\Model\Abschluss $abschluss, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();

        // TODO: ggf. hier Daten in History einfügen
        //$this->createHistory($teilnehmer, "erwerbsstatus");
        
        $teilnehmer = $this->teilnehmerRepository->findByUid($valArray['teilnehmer']);
        
        $this->abschlussRepository->update($abschluss);
        $this->redirect($valArray['thisaction'], 'Backend', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'], 'callercontroller' => $valArray['callercontroller'], 'callerpage' => $valArray['callerpage'], 'showabschluesse' => '1'));
    }

    /**
     * action delete
     *     
     * @param \Ud\Iqtp13db\Domain\Model\Abschluss $abschluss
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function deleteAction(\Ud\Iqtp13db\Domain\Model\Abschluss $abschluss, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();
        
        $this->abschlussRepository->remove($abschluss);
        $this->redirect($valArray['thisaction'], 'Backend', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'], 'callercontroller' => $valArray['callercontroller'], 'callerpage' => $valArray['callerpage'], 'showabschluesse' => '1'));
    }    
    
    /**
     * action initnewwebapp
     *
     * @return void
     */
    public function initializeNewWebappAction() {
        $this->exists_teilnehmer($this->request->getArguments());
    }
    
    /**
     * action newWebapp
     * 
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function newWebappAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();
        $language = $this->request->getAttribute('language');
        $isocode= $language->getTwoLetterIsoCode();
        
        $abschluesse = new \Ud\Iqtp13db\Domain\Model\Abschluss();
        $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
        
        $kastring = LocalizationUtility::translate('ka', 'iqtp13db');
        
        $aktuellesJahr = (int)date("Y");
        $abschlussjahre = array();
        $abschlussjahre[-1] = $kastring;
        for($jahr = $aktuellesJahr; $jahr > $aktuellesJahr-60; $jahr--) {
            $abschlussjahre[$jahr] = (String)$jahr;
        }
        $berufe = $this->berufeRepository->findAllOrdered($isocode);
        foreach($berufe as $beruf) {
            $berufearr[$beruf->getBerufid()] = $beruf->getTitel();
        }
        $staaten = $this->staatenRepository->findAll4Abschluss($isocode);
        if(count($staaten) == 0) $staaten = $this->staatenRepository->findByLangisocode('en');
        unset($staaten[201]);        
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }
        $abschlussartarr = $this->settings['abschlussart'];
        unset($abschlussartarr[2]);
        
        $brancheokarr = $this->brancheRepository->findAllOberkategorie($isocode);
        foreach($brancheokarr as $brok) {
            $brancheoberkat[$brok['brancheid']] = $brok['titel'];
        }
        $brancheunterkat = $this->brancheRepository->findAllUnterkategorie($isocode);
                
        $this->view->assignMultiple(
            [
                'settings' => $this->settings,
                'abschluesse' => $abschluesse,
                'teilnehmer' => $teilnehmer,
                'beratungsstelle' => $GLOBALS['TSFE']->fe_user->getKey('ses', 'beratungsstellenid'),
                'abschlussjahre' => $abschlussjahre,
                'berufearr' => $berufearr,
                'staatenarr' => $staatenarr,
                'abschlussartarr' => $abschlussartarr,
                'brancheoberkat'=> $brancheoberkat,
                'brancheunterkat' => $brancheunterkat
            ]
        );
    }
    
    
    /**
     * action initcreatewebapp
     *
     * @return void
     */
    public function initializeCreateWebappAction() {
        $this->exists_teilnehmer($this->request->getArguments());
        $this->exists_abschluss($this->request->getArguments());
    }

    /**
     * action createWebapp
     *
     * @param \Ud\Iqtp13db\Domain\Model\Abschluss $abschluss
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function createWebappAction(\Ud\Iqtp13db\Domain\Model\Abschluss $abschluss = NULL, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();
        
        $tnarr = $this->teilnehmerRepository->findByUid($teilnehmer->getUid());
        if($tnarr == NULL) {
            // TN ist (nicht) mehr vorhanden (gelöscht z.B. durch Task)
            $this->redirect('anmeldseite2', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer));
        }
        
        if (!isset($valArray['btnzurueck'])) {
            $abschluss->setTeilnehmer($teilnehmer);

            $abschluesse = new \Ud\Iqtp13db\Domain\Model\Abschluss();
            $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
            if(count($abschluesse) == 0) $abschluss->setNiquebertragung(1);
            $this->abschlussRepository->add($abschluss);
            
        }
        $this->redirect('anmeldseite2', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer));
    }
    
    /**
     * action initeditwebapp
     *
     * @return void
     */
    public function initializeEditWebappAction() {
        $this->exists_teilnehmer($this->request->getArguments());
    }
    
    /**
     * action editWebapp
     *
     * @param \Ud\Iqtp13db\Domain\Model\Abschluss $abschluss
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function editWebappAction(\Ud\Iqtp13db\Domain\Model\Abschluss $abschluss, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $abschluesse = new \Ud\Iqtp13db\Domain\Model\Abschluss();
        $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
        $language = $this->request->getAttribute('language');
        $isocode= $language->getTwoLetterIsoCode();
        
        $kastring = LocalizationUtility::translate('ka', 'iqtp13db');
        
        $aktuellesJahr = (int)date("Y");
        $abschlussjahre = array();
        $abschlussjahre[-1] = $kastring;
        for($jahr = $aktuellesJahr; $jahr > $aktuellesJahr-60; $jahr--) {
            $abschlussjahre[$jahr] = (String)$jahr;
        }
        $berufe = $this->berufeRepository->findAllOrdered($isocode);
        foreach($berufe as $beruf) {
            $berufearr[$beruf->getBerufid()] = $beruf->getTitel();
        }
        $staaten = $this->staatenRepository->findAll4Abschluss($isocode);        
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }
        $abschlussartarr = $this->settings['abschlussart'];
        unset($abschlussartarr[2]);
       
        $brancheokarr = $this->brancheRepository->findAllOberkategorie($isocode);
        foreach($brancheokarr as $brok) {
            $brancheoberkat[$brok['brancheid']] = $brok['titel'];
        }
        $brancheunterkat = $this->brancheRepository->findAllUnterkategorie($isocode);
        
        $this->view->assignMultiple(
            [
                'settings' => $this->settings,
                'abschluesse' => $abschluesse,
                'abschluss' => $abschluss,
                'teilnehmer' => $teilnehmer,
                'beratungsstelle' => $GLOBALS['TSFE']->fe_user->getKey('ses', 'beratungsstellenid'),
                'abschlussjahre' => $abschlussjahre,
                'berufearr' => $berufearr,
                'staatenarr' => $staatenarr,
                'abschlussartarr' => $abschlussartarr,
                'brancheoberkat'=> $brancheoberkat,
                'brancheunterkat' => $brancheunterkat
            ]
            );
    }
    
    /**
     * action initupdatewebapp
     *
     * @return void
     */
    public function initializeUpdateWebappAction() {
        $valArray = $this->request->getArguments();
        if(array_key_exists('abschluss', $valArray)) {
            if($valArray['abschluss']['branche'] == '') {
                $this->addFlashMessage("FEHLER: Branche ist Pflichtangabe.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR); // TODO: Localization
                $this->redirect('editWebapp', 'Abschluss', null, array('teilnehmer' => $valArray['teilnehmer'], 'abschluss' => $valArray['abschluss']['__identity']));
            }
        }
        
        $this->exists_teilnehmer($this->request->getArguments());
       // 
    }
    /**
     * action updateWebapp
     *
     * @param \Ud\Iqtp13db\Domain\Model\Abschluss $abschluss
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function updateWebappAction(\Ud\Iqtp13db\Domain\Model\Abschluss $abschluss, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $valArray = $this->request->getArguments();
        
        if (!isset($valArray['btnzurueck'])) {
            $this->abschlussRepository->update($abschluss);
        }
        $this->redirect('anmeldseite2', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer));
    }
    
    /**
     * action initdeletewebapp
     *
     * @return void
     */
    public function initializeDeleteWebappAction() {
        $this->exists_teilnehmer($this->request->getArguments());
    }

    /**
     * action deleteWebapp
     *
     * @param \Ud\Iqtp13db\Domain\Model\Abschluss $abschluss
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function deleteWebappAction(\Ud\Iqtp13db\Domain\Model\Abschluss $abschluss, \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {        
        $this->abschlussRepository->remove($abschluss);
        
        $this->redirect('anmeldseite2', 'Teilnehmer', null, array('teilnehmer' => $teilnehmer));
    }
        
    /*
     * Checks if teilnehmer exists
     */
    protected function exists_teilnehmer($valArray) {
        
        $valarrteilnehmer = $valArray['teilnehmer'] ?? '';
        
        if(is_string($valarrteilnehmer)) $tnuid = $valarrteilnehmer;
        else $tnuid = $valarrteilnehmer['__identity'];
        
        $thistn = $this->teilnehmerRepository->findByUid($tnuid);
        
        if($thistn == null) {
            // TN ist (nicht) mehr vorhanden (gelöscht z.B. durch Task)
            $this->addFlashMessage("ERROR: Session expired or data not found. Please restart registration.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('tnseite1', null);
            $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('tnuid', null);
            $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('ses', null);
            $this->forward('startseite', 'Teilnehmer', 'Iqtp13db');
        }
    }
    
    
    /*
     * Checks if abschluss exists
     */
    
    protected function exists_abschluss($valArray) {
        $valarrabschluss = $valArray['abschluss'] ?? '';
        
        if($valarrabschluss == '') {
            // TN ist (nicht) mehr vorhanden (gelöscht z.B. durch Task)
            $this->addFlashMessage("ERROR: Session expired or data not found. Please restart registration.", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('tnseite1', null);
            $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('tnuid', null);
            $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('ses', null);
            $this->forward('startseite', 'Teilnehmer', 'Iqtp13db');
        }
    }
    
    /**
     * A template method for displaying custom error flash messages, or to
     * display no flash message at all on errors.
     * Override this to customize
     * the flash message in your action controller.
     *
     * @api
     *
     * @return string boolean flash message or FALSE if no flash message should be set
     */
    protected function getErrorFlashMessage() {
        return FALSE;
    }
}
