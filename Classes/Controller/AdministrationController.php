<?php
namespace Ud\Iqtp13db\Controller;
use \Datetime;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;

use Psr\Http\Message\ResponseInterface;
use Ud\Iqtp13db\Domain\Repository\UserGroupRepository;
use Ud\Iqtp13db\Domain\Repository\TeilnehmerRepository;
use Ud\Iqtp13db\Domain\Repository\FolgekontaktRepository;
use Ud\Iqtp13db\Domain\Repository\DokumentRepository;
use Ud\Iqtp13db\Domain\Repository\BeraterRepository;
use Ud\Iqtp13db\Domain\Repository\AbschlussRepository;
use TYPO3\CMS\Core\Resource\StorageRepository;

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
 * AdministrationController
 */
class AdministrationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    
    protected $generalhelper, $niqinterface, $niqapiurl, $usergroup, $niqbid, $groupbccmail;
    
    protected $userGroupRepository;
    protected $teilnehmerRepository;
    protected $folgekontaktRepository;
    protected $dokumentRepository;
    protected $beraterRepository;
    protected $abschlussRepository;
    protected $storageRepository;
    
    public function __construct(UserGroupRepository $userGroupRepository, TeilnehmerRepository $teilnehmerRepository, FolgekontaktRepository $folgekontaktRepository, DokumentRepository $dokumentRepository, BeraterRepository $beraterRepository, AbschlussRepository $abschlussRepository, StorageRepository $storageRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
        $this->teilnehmerRepository = $teilnehmerRepository;
        $this->folgekontaktRepository = $folgekontaktRepository;
        $this->dokumentRepository = $dokumentRepository;
        $this->beraterRepository = $beraterRepository;
        $this->abschlussRepository = $abschlussRepository;
        $this->storageRepository = $storageRepository;
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
        $context = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class);
        if($context->getPropertyFromAspect('frontend.user', 'isLoggedIn')){
            $this->user=$GLOBALS['TSFE']->fe_user->user;
        } else {
            $this->user = NULL;
        }
        
        if($this->user != NULL) {
            $standardniqidberatungsstelle = $this->settings['standardniqidberatungsstelle'];
            $standardbccmail = $this->settings['standardbccmail'];
            
            $this->usergroup = $this->userGroupRepository->findByIdentifier($this->user['usergroup']);
            
            if($this->usergroup != NULL) {
                $userniqidbstelle = $this->usergroup->getNiqbid();
                $userbccmail = $this->usergroup->getGeneralmail();
            }
            
            $this->niqbid = $userniqidbstelle == '' ? $standardniqidberatungsstelle : $userniqidbstelle;
            $this->groupbccmail = $userbccmail == '' ? $standardbccmail : $userbccmail;
        } else {
            $this->groupbccmail = $this->settings['standardbccmail'];
        }
    }
    
    /**
     * action adminuebersicht
     *
     * @return void
     */
    public function adminuebersichtAction()
    {       
        $datum = strtotime("now");
        
        // Seite "Admin-Übersicht"
        $valArray = $this->request->getArguments();
        
        $buser = $this->beraterRepository->findByUid($this->user['uid']);
        
        if($buser != NULL) $userusergroups = $buser->getUsergroup();
        else $userusergroups = 1;
        
        if(isset($valArray['switch']) && $valArray['bstellen'] != 0) {
            $allusergroups = $this->userGroupRepository->findAllGroups($this->settings['beraterstoragepid']);
            $selectedgroup = $this->userGroupRepository->findByNiqbid($valArray['bstellen']);    
                        
            $buser->addUserGroup($selectedgroup[0]);
            
            $userusergroupssortedOS = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
            for($i = count($userusergroups)-1; $i >= 0; $i--) {
                $userusergroupssortedOS->attach($userusergroups[$i]);  
            }
            $buser->setUsergroup($userusergroupssortedOS);
            $this->beraterRepository->update($buser);
            //DebuggerUtility::var_dump($userusergroupssortedOS);
        } elseif(isset($valArray['remove'])) {  
            for($i = count($userusergroups)-1; $i >= 1; $i--) {
                $userusergroups->detach($userusergroups[$i]);
            }
            $buser->setUsergroup($userusergroups);
            $this->beraterRepository->update($buser);            
            //DebuggerUtility::var_dump($buser);           
        }
        
        
        $heute = date('Y-m-d');
        $diesesjahr = date('Y');
        $diesermonat = idate('m');
        $letztesjahr = idate('Y') - 1;
        
        for($i=1;$i<13;$i++) {
            $monatsnamen[$i] = date("M", mktime(0, 0, 0, $i, 1, $diesesjahr));
        }
        
        for($m = $diesermonat + 1; $m < 13; $m++) {
            $angemeldeteTN[$m] = $this->teilnehmerRepository->count4Status("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr, '%');
            $qfolgekontakte[$m] = $this->folgekontaktRepository->count4Status("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr, '%');
            $erstberatung[$m] = $this->teilnehmerRepository->count4StatusErstberatung("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr, '%');
            $beratungfertig[$m] = $this->teilnehmerRepository->count4StatusBeratungfertig("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr, '%');
            $niqerfasst[$m] =  $this->teilnehmerRepository->count4Statusniqerfasst("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr, '%');
            
            $days4beratung[$m] =  $this->teilnehmerRepository->days4Beratungfertig("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr, '%');
            $days4wartezeit[$m] =  $this->teilnehmerRepository->days4Wartezeit("01.".$m.".".$letztesjahr, date("t", mktime(0, 0, 0, $m, 1, $letztesjahr)).".".$m.".".$letztesjahr, '%');
        }
        
        for($m = 1; $m <= $diesermonat; $m++) {
            $angemeldeteTN[$m] = $this->teilnehmerRepository->count4Status("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr, '%');
            $qfolgekontakte[$m] = $this->folgekontaktRepository->count4Status("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr, '%');
            $erstberatung[$m] = $this->teilnehmerRepository->count4StatusErstberatung("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr, '%');
            $beratungfertig[$m] = $this->teilnehmerRepository->count4StatusBeratungfertig("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr, '%');
            $niqerfasst[$m] = $this->teilnehmerRepository->count4Statusniqerfasst("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr, '%');
            
            $days4beratung[$m] =  $this->teilnehmerRepository->days4Beratungfertig("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr, '%');
            $days4wartezeit[$m] = $this->teilnehmerRepository->days4Wartezeit("01.".$m.".".$diesesjahr, date("t", mktime(0, 0, 0, $m, 1, $diesesjahr)).".".$m.".".$diesesjahr, '%');
        }
        /*
         * durchschnittl. Tage Beratung abgeschl. und durchschnittl. Tage Wartezeit	berechnen
         */
        $anz4avgmonthb = 0;
        $anz4avgmonthw = 0;
        for($n = 1; $n <= 12; $n++) {
            $diffdaysb = 0;
            for($k = 0; $k < count($days4beratung[$n]); $k++) {
                $dat1 = new Datetime($days4beratung[$n][$k]->getBeratungdatum());
                $dat2 = new Datetime($days4beratung[$n][$k]->getErstberatungabgeschlossen());
                $diffdaysb += date_diff($dat1, $dat2)->format('%a');
            }
            
            if(count($days4beratung[$n]) > 0) {
                $totalavgmonthb[$n] = floatval($diffdaysb)/floatval(count($days4beratung[$n]));
                $anz4avgmonthb++;
            } else {
                $totalavgmonthb[$n] = '-';
            }
            
            $diffdaysw = 0;
            for($k = 0; $k < count($days4wartezeit[$n]); $k++) {
                if($days4wartezeit[$n][$k] != null) {
                    $dat1 = new DateTime();
                    $dat1->setTimestamp($days4wartezeit[$n][$k]->getVerificationDate());
                    $dat2 = new Datetime($days4wartezeit[$n][$k]->getBeratungdatum());
                    $diffdaysw += date_diff($dat1, $dat2)->format('%a');
                }
            }
            
            if(count($days4wartezeit[$n]) > 0) {
                $totalavgmonthw[$n] = floatval($diffdaysw)/floatval(count($days4wartezeit[$n]));
                $anz4avgmonthw++;
            } else {
                $totalavgmonthw[$n] = '-';
            }
        }
        
        ksort($angemeldeteTN);
        ksort($qfolgekontakte);
        ksort($erstberatung);
        ksort($beratungfertig);
        ksort($niqerfasst);
        ksort($days4beratung);
        ksort($days4wartezeit);
        
        $aktuelleanmeldungen = count($this->teilnehmerRepository->findAllOrder4Status(0, '%')) + count($this->teilnehmerRepository->findAllOrder4Status(1, '%'));
        $aktuellerstberatungen = count($this->teilnehmerRepository->findAllOrder4Status(2, '%'));
        $aktuellberatungenfertig = count($this->teilnehmerRepository->findAllOrder4Status(3, '%'));
        $archivierttotal = count($this->teilnehmerRepository->findAllOrder4Status(4, '%'));
        $sumalleaktuell = $aktuelleanmeldungen + $aktuellerstberatungen + $aktuellberatungenfertig + $archivierttotal;
        
        // keine Berater vorhanden?
        $alleberater = $this->beraterRepository->findAllBerater($this->settings['beraterstoragepid']);
        if(count($alleberater) == 0) {
            $this->addFlashMessage('Es sind noch keine Berater:innen vorhanden. Bitte im Menü Berater*innen anlegen.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        }
        $alleberatungsstellen = $this->userGroupRepository->findAllBeratungsstellen($this->settings['beraterstoragepid']);
        
        $anzberater = array();
        $anzratsuchendeanmeld = array();
        $anzratsuchendeerstb = array();
        $anzratsuchendearch = array();
        foreach ($alleberatungsstellen as $bst) {
            $anzberater[$bst->getUid()] = 0;
            $anzratsuchendeanmeld[$bst->getUid()] = count($this->teilnehmerRepository->findAllOrder4Status(0, $bst->getNiqbid())) + count($this->teilnehmerRepository->findAllOrder4Status(1, $bst->getNiqbid()));
            $anzratsuchendeerstb[$bst->getUid()] = count($this->teilnehmerRepository->findAllOrder4Status(2, $bst->getNiqbid())) + count($this->teilnehmerRepository->findAllOrder4Status(3, $bst->getNiqbid()));
            $anzratsuchendearch[$bst->getUid()] = count($this->teilnehmerRepository->findAllOrder4Status(4, $bst->getNiqbid()));
            
            foreach ($alleberater as $brtr) {
                foreach ($brtr->getUsergroup() as $onegrp) {
                    if($onegrp->getUid() == $bst->getUid()) {
                        $anzberater[$bst->getUid()]++;
                    }
                }   
            }
        }
        
        $sumangemeldet = array_sum($angemeldeteTN);
        $sumerstberatung = array_sum($erstberatung);
        $sumerstberatungfertig = array_sum($beratungfertig);
        $sumarchiv = array_sum($niqerfasst);
        
        $statsgesamtratsuchende = $this->teilnehmerRepository->count4Status("01.1.1970", "31.12.".$diesesjahr, '%');
        $statsgesamtfertigberaten = $this->teilnehmerRepository->count4StatusBeratungfertig("01.1.1970", "31.12.".$diesesjahr, '%');
        $statsgesamtarchiviert = $this->teilnehmerRepository->count4StatusArchiviert("01.1.1970", "31.12.".$diesesjahr, '%');
        //DebuggerUtility::var_dump($anzberater);
        
        $neuanmeldungen7tage = array();
        for($i = 7; $i >= 0; $i--) {
            $reftag = date("d.m.Y", strtotime( '-'.$i.' days' ));
            $neuanmeldungen7tage[$i]["tag"] = date("l, d.m.Y", strtotime( '-'.$i.' days' ));
            $neuanmeldungen7tage[$i]["wert"] = $this->teilnehmerRepository->count4Status($reftag, $reftag, '%');
        }
        
        $this->view->assignMultiple(
            [
                'monatsnamen'=> $monatsnamen,
                'aktmonat'=> $diesermonat-1,
                'angemeldeteTN'=> $angemeldeteTN,
                'SUMangemeldeteTN'=> $sumangemeldet,
                'qfolgekontakte'=> $qfolgekontakte,
                'SUMqfolgekontakte'=> array_sum($qfolgekontakte),
                'erstberatung'=> $erstberatung,
                'SUMerstberatung'=> $sumerstberatung,
                'beratungfertig'=> $beratungfertig,
                'SUMberatungfertig'=> $sumerstberatungfertig,
                'niqerfasst'=> $niqerfasst,
                'SUMniqerfasst'=> $sumarchiv,
                'totalavgmonthb'=> $totalavgmonthb,
                'SUMtotalavgmonthb'=>  $anz4avgmonthb > 0 ? array_sum($totalavgmonthb)/$anz4avgmonthb : 0,
                'totalavgmonthw'=> $totalavgmonthw,
                'SUMtotalavgmonthw'=>  $anz4avgmonthw > 0 ? array_sum($totalavgmonthw)/$anz4avgmonthw : 0,
                'aktuelleanmeldungen'=> $aktuelleanmeldungen,
                'aktuellerstberatungen'=> $aktuellerstberatungen,
                'aktuellberatungenfertig'=> $aktuellberatungenfertig,
                'archivierttotal'=> $archivierttotal,                
                'anzberatungsstellen' => count($alleberatungsstellen),
                'alleberatungsstellen' => $alleberatungsstellen,
                'anzalleberater' => count($alleberater),
                'anzberater' => $anzberater,
                'anzratsuchendeanmeld' => $anzratsuchendeanmeld,
                'anzratsuchendeerstb' => $anzratsuchendeerstb,
                'anzratsuchendearch' => $anzratsuchendearch,
                'anzuserberatungsstellen' => count($userusergroups),
                'alleRatsuchendentotal' => $sumalleaktuell,
                'statsgesamtratsuchende' => $statsgesamtratsuchende,
                'statsgesamtfertigberaten' => $statsgesamtfertigberaten,
                'statsgesamtarchiviert' => $statsgesamtarchiviert,
                'neuanmeldungen7tage' => $neuanmeldungen7tage,
                'diesesjahr' => date('y'),
                'letztesjahr' => idate('y') - 1
            ]
            );
    }
}
    