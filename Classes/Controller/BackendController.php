<?php
namespace Ud\Iqtp13db\Controller;
use \Datetime;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Core\Database\ConnectionPool;

use Psr\Http\Message\ResponseInterface;
use \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;

use Ud\Iqtp13db\Domain\Repository\UserGroupRepository;
use Ud\Iqtp13db\Domain\Repository\TeilnehmerRepository;
use Ud\Iqtp13db\Domain\Repository\FolgekontaktRepository;
use Ud\Iqtp13db\Domain\Repository\DokumentRepository;
use Ud\Iqtp13db\Domain\Repository\HistorieRepository;
use Ud\Iqtp13db\Domain\Repository\BeraterRepository;
use Ud\Iqtp13db\Domain\Repository\AbschlussRepository;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use Ud\Iqtp13db\Domain\Repository\BerufeRepository;
use Ud\Iqtp13db\Domain\Repository\StaatenRepository;
use Ud\Iqtp13db\Domain\Repository\OrtRepository;
use Ud\Iqtp13db\Domain\Repository\BrancheRepository;
use Ud\Iqtp13db\Domain\Repository\GruppenberatungRepository;

use Ud\Iqtp13db\Service\QRCodeGenerator;


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
 * BackendController
 */
class BackendController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    
    protected $user, $generalhelper, $usergroup, $niqbid, $beratungsstellenname, $anzbstellen;
    
    protected $userGroupRepository;
    protected $teilnehmerRepository;
    protected $folgekontaktRepository;
    protected $dokumentRepository;
    protected $historieRepository;
    protected $beraterRepository;
    protected $abschlussRepository;
    protected $storageRepository;
    protected $berufeRepository;
    protected $staatenRepository;
    protected $ortRepository;
    protected $brancheRepository;
    protected $gruppenberatungRepository;
    protected $qrCodeGenerator;

    protected $headerblattTN = [
        'UID' => 'string',
        'Bestätigungsdatum' => 'string',
        'Nachname' => 'string',
        'Vorname' => 'string',
        'Strasse' => 'string',
        'PLZ' => 'string',
        'Ort' => 'string',
        'E-Mail' => 'string',
        'Telefon' => 'string',
        'Geburtsdatum' => 'string',
        'Lebensalter' => 'string',
        'Erste Staatsangehoerigkeit' => 'string',
        'Zweite Staatsangehoerigkeit' => 'string',
        'WohnsitzDeutschland' => 'string',
        'Landkreis' => 'string',
        'Einreisejahr' => 'string',
        'WohnsitzNeinIn' => 'string',
        'Deutschkenntnisse' => 'string',
        'ZertifikatSprachniveau' => 'string',
        'Sonstiger Status' => 'string',
        'Erwerbsstatus' => 'string',
        'Leistungsbezug ja/nein' => 'string',
        'Leistungsbezug' => 'string',
        'Geburtsland' => 'string',
        'Aufenthaltsstatus' => 'string',
        'Geschlecht' => 'string',
        'Notizen Ratsuchender' => 'string',
        'Berater:in' => 'string',
        'Beratungsart' => 'string',
        'Beratungsort' => 'string',
        'Anerkennungsberatung' => 'string',
        'Qualifizierungsberatung' => 'string',
        'Beratungsstelle' => 'string',
        'Beratung Notizen' => 'string',
        'Beratung zu Schulabschluss' => 'string',
        'Anz. Folgekontakte' => 'string',
        'Summe Dauer Folgekontakte' => 'string',
        'Kooperationgruppe' => 'string',
        'Beratungsdauer' => 'string',
        'Beratungdatum' => 'string',
        'Erstberatungabgeschlossen' => 'string',
        'Einwilligung Infos' => 'string',
        'Abschluss1 Referenzberuf zugewiesen' => 'string',
        'Abschluss1 Referenzberuf - sonstiger Beruf' => 'string',
        'Abschluss1 Referenzberuf - nicht reglementierter Beruf' => 'string',
        'Abschluss1 Abschlussart' => 'string',
        'Abschluss1 Branche' => 'string',
        'Abschluss1 Erwerbsland' => 'string',
        'Abschluss1 Abschlussjahr' => 'string',
        'Abschluss1 Ausbildungsort' => 'string',
        'Abschluss1 Abschluss' => 'string',
        'Abschluss1 DauerBerufsausbildung' => 'string',
        'Abschluss1 Ausbildungsinstitution' => 'string',
        'Abschluss1 Berufserfahrung' => 'string',
        'Abschluss1 Wunschberuf' => 'string',
        'Abschluss1 Deutscher Referenzberuf' => 'string',
        'Abschluss1 Antragstellung erfolgt' => 'string',
        'Abschluss2 Referenzberuf zugewiesen' => 'string',
        'Abschluss2 Referenzberuf - sonstiger Beruf' => 'string',
        'Abschluss2 Referenzberuf - nicht reglementierter Beruf' => 'string',
        'Abschluss2 Abschlussart' => 'string',
        'Abschluss2 Branche' => 'string',
        'Abschluss2 Erwerbsland' => 'string',
        'Abschluss2 Abschlussjahr' => 'string',
        'Abschluss2 Ausbildungsort' => 'string',
        'Abschluss2 Abschluss' => 'string',
        'Abschluss2 DauerBerufsausbildung' => 'string',
        'Abschluss2 Ausbildungsinstitution' => 'string',
        'Abschluss2 Berufserfahrung' => 'string',
        'Abschluss2 Wunschberuf' => 'string',
        'Abschluss2 Deutscher Referenzberuf' => 'string',
        'Abschluss2 Antragstellung erfolgt' => 'string',
        'Abschluss3 Referenzberuf zugewiesen' => 'string',
        'Abschluss3 Referenzberuf - sonstiger Beruf' => 'string',
        'Abschluss3 Referenzberuf - nicht reglementierter Beruf' => 'string',
        'Abschluss3 Abschlussart' => 'string',
        'Abschluss3 Branche' => 'string',
        'Abschluss3 Erwerbsland' => 'string',
        'Abschluss3 Abschlussjahr' => 'string',
        'Abschluss3 Ausbildungsort' => 'string',
        'Abschluss3 Abschluss' => 'string',
        'Abschluss3 DauerBerufsausbildung' => 'string',
        'Abschluss3 Ausbildungsinstitution' => 'string',
        'Abschluss3 Berufserfahrung' => 'string',
        'Abschluss3 Wunschberuf' => 'string',
        'Abschluss3 Deutscher Referenzberuf' => 'string',
        'Abschluss3 Antragstellung erfolgt' => 'string',
        'Abschluss4 Referenzberuf zugewiesen' => 'string',
        'Abschluss4 Referenzberuf - sonstiger Beruf' => 'string',
        'Abschluss4 Referenzberuf - nicht reglementierter Beruf' => 'string',
        'Abschluss4 Abschlussart' => 'string',
        'Abschluss4 Branche' => 'string',
        'Abschluss4 Erwerbsland' => 'string',
        'Abschluss4 Abschlussjahr' => 'string',
        'Abschluss4 Ausbildungsort' => 'string',
        'Abschluss4 Abschluss' => 'string',
        'Abschluss4 DauerBerufsausbildung' => 'string',
        'Abschluss4 Ausbildungsinstitution' => 'string',
        'Abschluss4 Berufserfahrung' => 'string',
        'Abschluss4 Wunschberuf' => 'string',
        'Abschluss4 Deutscher Referenzberuf' => 'string',
        'Abschluss4 Antragstellung erfolgt' => 'string'
    ];
    
    protected $headerblattFK = [
        'FKUID' => 'string',
        'Nachname' => 'string',
        'Vorname' => 'string',
        'Datum' => 'string',
        'Berater' => 'string',
        'Notizen' => 'string',
        'Beratungsform' => 'string',
        'Beratungsdauer' => 'string',
    ];
    
    protected $headerblattFK25 = [
        'Datum' => 'string',
        'Berater' => 'string',
        'Notizen' => 'string',
        'Beratungsform' => 'string',
    ];
    
    protected $headerblattanonym = [
        'Bestätigungsdatum' => 'string',
        'PLZ' => 'string',
        'Ort' => 'string',
        'Lebensalter' => 'string',
        'Erste Staatsangehoerigkeit' => 'string',
        'Zweite Staatsangehoerigkeit' => 'string',
        'Landkreis' => 'string',
        'Einreisejahr' => 'string',
        'Deutschkenntnisse' => 'string',
        'ZertifikatSprachniveau' => 'string',
        'Geburtsland' => 'string',
        'Geschlecht' => 'string',
        'Anz. Folgekontakte' => 'string',
        'Erstberatungabgeschlossen' => 'string',
        'Abschluss1 Referenzberuf zugewiesen' => 'string',
        'Abschluss1 Abschlussart' => 'string',
        'Abschluss1 Erwerbsland' => 'string',
        'Abschluss1 Deutscher Referenzberuf' => 'string',
        'Abschluss2 Referenzberuf zugewiesen' => 'string',
        'Abschluss2 Abschlussart' => 'string',
        'Abschluss2 Erwerbsland' => 'string',
        'Abschluss2 Deutscher Referenzberuf' => 'string',
        'Abschluss3 Referenzberuf zugewiesen' => 'string',
        'Abschluss3 Abschlussart' => 'string',
        'Abschluss3 Erwerbsland' => 'string',
        'Abschluss3 Deutscher Referenzberuf' => 'string',
        'Abschluss4 Referenzberuf zugewiesen' => 'string',
        'Abschluss4 Abschlussart' => 'string',
        'Abschluss4 Erwerbsland' => 'string',
        'Abschluss4 Deutscher Referenzberuf' => 'string',
    ];
    
    public function __construct(
        UserGroupRepository $userGroupRepository, 
        TeilnehmerRepository $teilnehmerRepository, 
        FolgekontaktRepository $folgekontaktRepository, 
        DokumentRepository $dokumentRepository, 
        HistorieRepository $historieRepository, 
        BeraterRepository $beraterRepository, 
        AbschlussRepository $abschlussRepository, 
        StorageRepository $storageRepository, 
        BerufeRepository $berufeRepository, 
        StaatenRepository $staatenRepository, 
        OrtRepository $ortRepository, 
        BrancheRepository $brancheRepository,
        GruppenberatungRepository $gruppenberatungRepository,
        QRCodeGenerator $qrCodeGenerator,
        FrontendUserAuthentication $frontendUser
    )
    {
        $this->userGroupRepository = $userGroupRepository;
        $this->teilnehmerRepository = $teilnehmerRepository;
        $this->folgekontaktRepository = $folgekontaktRepository;
        $this->dokumentRepository = $dokumentRepository;
        $this->historieRepository = $historieRepository;
        $this->beraterRepository = $beraterRepository;
        $this->abschlussRepository = $abschlussRepository;
        $this->storageRepository = $storageRepository;
        $this->berufeRepository = $berufeRepository;
        $this->staatenRepository = $staatenRepository;
        $this->ortRepository = $ortRepository;
        $this->brancheRepository = $brancheRepository;
        $this->gruppenberatungRepository = $gruppenberatungRepository;
        $this->qrCodeGenerator = $qrCodeGenerator;
        $this->user = $frontendUser;
    }

    private function getConnectionPool(): ConnectionPool
    {
        return GeneralUtility::makeInstance(ConnectionPool::class);
    }
    
    /**
     * action init
     */
    public function initializeAction(): void
    {
        /*
         * PropertyMapping für die multiple ankreuzbaren Checkboxen.
         * Annehmen eines String-Arrays, das im Setter und Getter des Models je per implode/explode wieder in Strings bzw. Array (of Strings) konvertiert wird
         */        
        if ($this->arguments->hasArgument('teilnehmer')) {
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('sonstigerstatus');
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('sonstigerstatus', 'array');
            
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('einwAnerkstellemedium');
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('einwAnerkstellemedium', 'array');
            
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('einwPersonmedium');
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('einwPersonmedium', 'array');
            
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('beratungsart');
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('beratungsart', 'array');
            
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('anerkennungsberatung');
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('anerkennungsberatung', 'array');
            
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('qualifizierungsberatung');
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('qualifizierungsberatung', 'array');
            
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->allowProperties('wieberaten');
            $this->arguments->getArgument('teilnehmer')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('wieberaten', 'array');
            
        }             
        /* Propertymapping bis hier */
        
        $this->generalhelper = new \Ud\Iqtp13db\Helper\Generalhelper();
        
        $this->user=null;
        $context = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class);
        if($context->getPropertyFromAspect('frontend.user', 'isLoggedIn')){
            $this->user = $this->request->getAttribute('frontend.user');
        } else {
            $this->user = NULL;
        }
        
        if($this->user != NULL) {
            $standardniqidberatungsstelle = $this->settings['standardniqidberatungsstelle'];
            $ugroupsarray = explode(",",$this->user->user['usergroup']);
            $this->anzbstellen = count($ugroupsarray);            
            $thisusrgrpid = array_pop($ugroupsarray);
            $this->usergroup = $this->userGroupRepository->findByIdentifier($thisusrgrpid);
            if($this->usergroup->getTitle() == "Gruppenberatungen") {
                $thisusrgrpid = array_pop($ugroupsarray);
                $this->usergroup = $this->userGroupRepository->findByIdentifier($thisusrgrpid);
            }
                
            if($this->usergroup != NULL) {
                $userniqidbstelle = $this->usergroup->getNiqbid() ?? $standardniqidberatungsstelle;
            }
            
            $sesniqbid = $this->user->getKey('ses', 'currentusergroup') ?? '';
            $this->niqbid = $sesniqbid != '' ? $sesniqbid : $userniqidbstelle;
            $thisgroup = $this->userGroupRepository->findBeratungsstellebyNiqbid($this->settings['beraterstoragepid'], $this->niqbid);            
            $this->beratungsstellenname = $thisgroup[0]->getTitle();
        } else {
            // FEHLER oder Frontend für Ratsuchende
        }
    }
    
    /**
     * action start
     *
     * @return void
     */
    public function startAction(): ResponseInterface
    {                
        if ($this->settings['modtyp'] == 'uebersicht') {
            return (new ForwardResponse('status'))->withControllerName('Backend')->withExtensionName('Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'angemeldet') {
            return (new ForwardResponse('listangemeldet'))->withControllerName('Backend')->withExtensionName('Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'erstberatung') {
            return (new ForwardResponse('listerstberatung'))->withControllerName('Backend')->withExtensionName('Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'archiv') {
            return (new ForwardResponse('listarchiv'))->withControllerName('Backend')->withExtensionName('Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'export') {
            return (new ForwardResponse('export'))->withControllerName('Backend')->withExtensionName('Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'berater') {
            return (new ForwardResponse('list'))->withControllerName('Berater')->withExtensionName('Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'deleted') {
            return (new ForwardResponse('listdeleted'))->withControllerName('Backend')->withExtensionName('Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'adminuebersicht') {
            return (new ForwardResponse('adminuebersicht'))->withControllerName('Administration')->withExtensionName('Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'einstellungen') {
            return (new ForwardResponse('editsettings'))->withControllerName('Backend')->withExtensionName('Iqtp13db');
        }
        if ($this->settings['modtyp'] == 'gruppenberatung') {
            return (new ForwardResponse('listgruppenberatung'))->withControllerName('Gruppenberatung')->withExtensionName('Iqtp13db');
        } else {
            return (new ForwardResponse('status'))->withControllerName('Backend')->withExtensionName('Iqtp13db');
        }     
    }
    
    /**
     * action status
     *
     * @param int $currentPage
     * @return void
     */
    public function statusAction(int $currentPage = 1): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        // Gruppenwechsel Beratungsstelle, wenn ein User mehreren Beratungsstellen zugeordnet ist
        $backenduser = $this->beraterRepository->findByUid($this->user->user['uid']);
        $backendusergroups = array();
        $backendusergroups = $backenduser->getUsergroup();   
        if(isset($valArray['bstellen']) && $valArray['bstellen'] != '') {            
            $niqbidgruppeselected = $valArray['bstellen'];            
            $this->user->setKey('ses', 'currentusergroup', $niqbidgruppeselected);
            $this->niqbid = $this->user->getKey('ses', 'currentusergroup');
        }
        // Gruppenwechsel bis hier 
        
        $thisgroup = $this->userGroupRepository->findBeratungsstellebyNiqbid($this->settings['beraterstoragepid'], $this->niqbid);
        $this->beratungsstellenname = $thisgroup[0]->getTitle();
        
        $jahrselected = $valArray['jahrauswahl'] ?? date('Y');               
        $monatsnamen = $this->generalhelper->getMonthNames($jahrselected);
        $jahrarray = array();
        for($j=2023;$j<=date('Y');$j++){
            $jahrarray[$j] = $j;
        }
        $jahrarray[99] = "- alle seit 01/2023 -";
        
        if(isset($valArray['zeigebstelle'])) {
            $plzbstelle = $this->userGroupRepository->getBeratungsstelle4PLZ($valArray['plzeingabe'], $this->settings['beraterstoragepid']);
            $plzgroup = $plzbstelle[0];
        }
        
        // (Bundesland-)Admin? Ja, dann Landes-Statistik anzeigen
        $thisniqbid = intval($this->niqbid) < 999 ? '%' : $this->niqbid;
        
        // Statistik-Daten aus Cache-Tabelle auslesen:
        $qb = $this->getConnectionPool()->getQueryBuilderForTable('tx_iqtp13db_domain_model_statistik_cache');
        $rows = $qb
            ->select('metric', 'wert_json', 'generated_at')
            ->from('tx_iqtp13db_domain_model_statistik_cache')
            ->where($qb->expr()->eq('niqbid', $qb->createNamedParameter($thisniqbid)))
            ->andWhere($qb->expr()->eq('bezugsjahr', $qb->createNamedParameter($jahrselected)))
            ->executeQuery()
            ->fetchAllAssociative();
        
        foreach ($rows as $row) {
            $stand = date('d.m.Y H:i', (int)$row['generated_at']);
            if($row['metric'] == 'angemeldeteTN') $angemeldeteTN = json_decode($row['wert_json'], true);
            if($row['metric'] == 'erstberatung') $erstberatung = json_decode($row['wert_json'], true);
            if($row['metric'] == 'beratungfertig') $beratungfertig = json_decode($row['wert_json'], true);
            if($row['metric'] == 'qfolgekontakte') $qfolgekontakte = json_decode($row['wert_json'], true);
            if($row['metric'] == 'days4wartezeit') $days4wartezeit = json_decode($row['wert_json'], true);
            if($row['metric'] == 'days4beratung') $days4beratung = json_decode($row['wert_json'], true);
            if($row['metric'] == 'beratungfk22') $beratungfk22 = json_decode($row['wert_json'], true);
            if($row['metric'] == 'beratungfk25') $beratungfk25 = json_decode($row['wert_json'], true);
            if($row['metric'] == 'tnberatungenfk22') $tnberatungenfk22 = json_decode($row['wert_json'], true);
            if($row['metric'] == 'tnberatungenfk25') $tnberatungenfk25 = json_decode($row['wert_json'], true);
        }

        $qb2 = $this->getConnectionPool()->getQueryBuilderForTable('tx_iqtp13db_domain_model_statistik_cache');
        $rows2 = $qb2
            ->select('metric', 'wert_json', 'generated_at')
            ->from('tx_iqtp13db_domain_model_statistik_cache')
            ->where($qb2->expr()->eq('niqbid', $qb2->createNamedParameter($thisniqbid)))
            ->andWhere($qb2->expr()->eq('bezugsjahr', $qb2->createNamedParameter(999)))
            ->executeQuery()
            ->fetchAllAssociative();
        foreach ($rows2 as $row) {
            if($row['metric'] == 'aktuelleanmeldungen') $aktuelleanmeldungen = json_decode($row['wert_json'], true);
            if($row['metric'] == 'aktuellerstberatungen') $aktuellerstberatungen = json_decode($row['wert_json'], true);
            if($row['metric'] == 'aktuellberatungenfertig') $aktuellberatungenfertig = json_decode($row['wert_json'], true);
            if($row['metric'] == 'archivierttotal') $archivierttotal = json_decode($row['wert_json'], true);
            if($row['metric'] == 'neuanmeldungen7tage') $neuanmeldungen7tage = json_decode($row['wert_json'], true);
        }
        
        // keine Berater vorhanden?
        $alleberater = $this->beraterRepository->findAllBerater($this->settings['beraterstoragepid']);
        if(count($alleberater) == 0) {
            $this->addFlashMessage('Es sind noch keine Berater:innen vorhanden. Bitte im Menü Berater*innen anlegen.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
        }
        
        $historie = $this->historieRepository->findAllDesc($this->niqbid);
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
        $paginator = new QueryResultPaginator($historie, $currentPage, 25);
        $pagination = new SimplePagination($paginator);
        
        // ******************** EXPORT Statistik ****************************
        if (isset($valArray['statsexport'])) {
            
            $rows[0] = $monatsnamen;
            array_unshift($rows[0], " ");
            $rows[1] = $angemeldeteTN;
            array_unshift($rows[1], "Anmeldungen");
            $rows[2] = $erstberatung;
            array_unshift($rows[2], "Erstberatungen");
            $rows[3] = $qfolgekontakte;
            array_unshift($rows[3], "Folgekontakte");
            $rows[4] = $beratungfertig;
            array_unshift($rows[4], "Beratungen fertig");
            $rows[5] = $days4wartezeit;
            array_unshift($rows[5], "durchschn. Tage Wartezeit");
            $rows[6] = $days4beratung;
            array_unshift($rows[6], "durchschn. Tage Beratungsdauer");
            
            // XLSX
            $filename = 'statistik_'.date('Y-m-d_H-i-s', time()).'.xlsx';
            $headerblatt1 = [
                'Statistik '.($jahrselected != 0 ? $jahrselected : 'letzte 12 Monate') => 'string',
            ];
            
            $writer = new \XLSXWriter();
            $writer->setAuthor('IQ Webapp');
            
            $writer->writeSheet($rows, 'Statistik', $headerblatt1);
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer->writeToStdOut();
            exit;
        }        
        
        // ******************** EXPORT Statistik bis hier ****************************
        $emptystatusarray = array(1 => 0,2 => 0,3 => 0,4 => 0,5 => 0,6 => 0,7 => 0,8 => 0,9 => 0,10 => 0,11 => 0, 12 => 0);
        $this->view->assignMultiple(
            [
                'beratungfk22'=> $beratungfk22 ?? $emptystatusarray,
                'SUMberatungfk22'=> count($tnberatungenfk22 ?? $emptystatusarray),
                'beratungfk25'=> $beratungfk25 ?? $emptystatusarray,
                'SUMberatungfk25'=> count($tnberatungenfk25 ?? $emptystatusarray),
                'monatsnamen'=> $monatsnamen,
                'jahrauswahl' => $jahrarray,
                'jahrselected' => $jahrselected,
                'aktmonat'=> $jahrselected == 0 ? idate('m')-1 : '',
                'angemeldeteTN'=> $angemeldeteTN ?? $emptystatusarray,
                'SUMangemeldeteTN'=> array_sum($angemeldeteTN ?? $emptystatusarray),
                'qfolgekontakte'=> $qfolgekontakte ?? $emptystatusarray,
                'SUMqfolgekontakte'=> array_sum($qfolgekontakte ?? $emptystatusarray),
                'erstberatung'=> $erstberatung ?? $emptystatusarray,
                'SUMerstberatung'=> array_sum($erstberatung ?? $emptystatusarray),
                'beratungfertig'=> $beratungfertig ?? $emptystatusarray,
                'SUMberatungfertig'=> array_sum($beratungfertig ?? $emptystatusarray),
                'totalavgmonthb'=> $days4beratung ?? $emptystatusarray,
                'totalavgmonthw'=> $days4wartezeit ?? $emptystatusarray,
                'stand' => $stand ?? 0,
                'aktuelleanmeldungen'=> $aktuelleanmeldungen ?? 0,
                'aktuellerstberatungen'=> $aktuellerstberatungen ?? 0,
                'aktuellberatungenfertig'=> $aktuellberatungenfertig ?? 0,
                'archivierttotal'=> $archivierttotal ?? 0,
                'paginator' => $paginator,
                'pagination' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
                'historie' => $historie,
                'beratungsstelle' => $this->beratungsstellenname,
                'niqbid' => $this->niqbid,
                'username' => $this->user->user['username'],
                'neuanmeldungen7tage' => $neuanmeldungen7tage ?? 0,
                'bstellevonplz' => $plzgroup ?? '',
                'backendusergroups' => $backendusergroups,
                'anzbstellen' => $this->anzbstellen
            ]
            );
        return $this->htmlResponse();
    }
    
    /**
     * action listangemeldet
     *
     * @param int $currentPage
     * @return void
     */
    public function listangemeldetAction(int $currentPage = 1): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        $arrberater  = $this->getberater4Bstelle('%', TRUE);
        $plzarray = $this->userGroupRepository->getallplzarray();
        $mail4externstandardmailtext = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mailtextedit', 'Iqtp13db');

        // Gespeicherte Auswahl aus Session laden
        $selectedIds = $this->getSelectedIdsFromSession();
        $auswahlmodus = $this->request->getAttribute('frontend.user')->getSessionData('auswahlmodus');
        
        if(($valArray['allemodule'] ?? '') == '1') {
            return $this->redirect('showsearchresult', 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1', 'searchparams' => $valArray));
        }
        // zuletzt bearbeiteten User zurücksetzen
        if(isset($valArray['tn'])) {
            $editedteilnehmer = $this->teilnehmerRepository->findByUid($valArray['tn']);
            $tnedituser = $editedteilnehmer->getEdituser();
            if($this->user->user['uid'] == $tnedituser) {
                $editedteilnehmer->setEdituser(0);
                $editedteilnehmer->setEdittstamp(0);
                $this->teilnehmerRepository->update($editedteilnehmer);
                // Daten sofort in die Datenbank schreiben
                $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
                $persistenceManager->persistAll();
            }
        }

        if(!empty($valArray['callerpage'])) $currentPage = $valArray['callerpage'];
        
        if(empty($valArray['orderby'])) {            
            $orderby = $this->user->getKey('ses', 'listangemeldetorderby') ?? 'verificationDate';
            $order = $this->user->getKey('ses', 'listangemeldetorder') ?? 'DESC';
        } else {
            $orderby = $valArray['orderby'];
            $order = $this->user->getKey('ses', 'listangemeldetorder') ?? 'DESC';
        }        
        if(isset($valArray['changeorder']) && $valArray['changeorder'] == 1) {
            $orderby = $valArray['orderby'];
            $order = $order == 'DESC' ? 'ASC' : 'DESC';
            $this->user->setKey('ses', 'listangemeldetorderby', $orderby);
            $this->user->setKey('ses', 'listangemeldetorder', $order);
        }
        
        $teilnehmer = $this->setfilter(0, $valArray, $orderby, $order, 0, 9999);
        
        // Wegen Bug in Paginator, der nicht mit Custom SQL Queryresults funktioniert, werden hier alle gefilterten Einträge auf einer Seite dargestellt. Queryresultpaginator hat dann keine Auswahl an Datensätzen, sondern alle.
        $anzperpag = $this->user->getKey('ses', 'filtermodus') == '1' ? 20 : 20;
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
                
        if(is_array($teilnehmer)) {
            $paginator = new ArrayPaginator($teilnehmer, $currentPage, $anzperpag);
        } else {
            $paginator = new QueryResultPaginator($teilnehmer, $currentPage, $anzperpag);
        }
        $pagination = new SimplePagination($paginator);
        $teilnehmerpag = $paginator->getPaginatedItems();
        
        $tnuiddublette = $this->teilnehmerRepository->findDubletten4Angemeldetneu($this->niqbid);
        
        $tnuidarray = array();
        foreach($teilnehmerpag as $tn) {
            $tnuidarray[] = $tn->getUid();   
        }
        $abschluesserepo = $this->abschlussRepository->findByTnByUidarray($tnuidarray);
        
        $abschluesse = array();
        $plzberatungsstelle4tn = array();
        for($j=0; $j < count($teilnehmerpag); $j++) {
            foreach($tnuiddublette as $tnuid) {
                if(trim($teilnehmerpag[$j]->getNachname()) == trim($tnuid['nachname']) && trim($teilnehmerpag[$j]->getVorname()) == trim($tnuid['vorname']) && $teilnehmerpag[$j]->getEmail() == $tnuid['email']) $teilnehmerpag[$j]->setDublette(TRUE);
            }
            
            foreach($abschluesserepo as $ab) {
                if($ab->getTeilnehmer()->getUid() == $teilnehmerpag[$j]->getUid()) {
                    $abschluesse[$j][] = $ab;
                }
            }
            
            foreach($plzarray as $bid) {                
                $bidplz = explode(',', $bid['plzlist']);
                if(in_array($teilnehmerpag[$j]->getPlz(), $bidplz)) {
                    $plzberatungsstelle4tn[$j] = $bid['niqbid'];
                }
            }
        }
        
        $staaten = $this->staatenRepository->findByLangisocode('de');       
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }
        
        $orderchar = $order == 'ASC' ? "↓" : "↑";        
        
        $gruppenberatungenarr = array();
        $gruppenberatungen = $this->gruppenberatungRepository->findAvailable($this->niqbid);
        foreach($gruppenberatungen as $gb) {
            $gruppenberatungenarr[$gb->getUid()] = $gb->getTitel();
        }
        
        $this->view->assignMultiple(
            [
                'anzgesamt' => count($teilnehmer),
                'calleraction' => 'listangemeldet',
                'callercontroller' => 'Backend',
                'callerpage' => $currentPage,
                'paginator' => $paginator,
                'pagination' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
                'orderby' => $orderby,
                'orderchar' => $orderchar,
                'staatenarr' => $staatenarr,
                'plzberatungsstelle4tn' => $plzberatungsstelle4tn,
                'beratungsstelle' => $this->beratungsstellenname,
                'niqbid' => $this->niqbid,
                'alleberater' => $arrberater,
                'anzbstellen' => $this->anzbstellen,
                'abschluesse' => $abschluesse,
                'betafeaturesaktiviert' => $this->usergroup->getBetafeatures(),
                'mail4externstandardmailtext' => $mail4externstandardmailtext,
                'anmeldeditseite' => $this->settings['anmeldeditseite'],
                'gruppenberatungarr' => $gruppenberatungenarr,
                'selectedIds' => $selectedIds,
                'auswahlmodus' => $auswahlmodus ? 1 : 0
            ]);
        return $this->htmlResponse();
    }
        
    /**
     * action listerstberatung
     *
     * @param int $currentPage
     * @return void
     */
    public function listerstberatungAction(int $currentPage = 1): ResponseInterface
    {
        $valArray = $this->request->getArguments();                
        $arrberater  = $this->getberater4Bstelle('%', TRUE);

        if(($valArray['allemodule'] ?? '') == '1') {
            return $this->redirect('showsearchresult', 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1', 'searchparams' => $valArray));
        }
        
        // zuletzt bearbeiteten User zurücksetzen
        if(isset($valArray['tn'])) {
            $editedteilnehmer = $this->teilnehmerRepository->findByUid($valArray['tn']);
            $tnedituser = $editedteilnehmer->getEdituser();
            if($this->user->user['uid'] == $tnedituser) {
                $editedteilnehmer->setEdituser(0);
                $editedteilnehmer->setEdittstamp(0);
                $this->teilnehmerRepository->update($editedteilnehmer);
                // Daten sofort in die Datenbank schreiben
                $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
                $persistenceManager->persistAll();
            }
        }
        
        if(!empty($valArray['callerpage'])) $currentPage = $valArray['callerpage'];
        
        if(empty($valArray['orderby'])) {
            $orderby = $this->user->getKey('ses', 'listerstberatungorderby') ?? 'verificationDate';
            $order = $this->user->getKey('ses', 'listerstberatungorder') ?? 'DESC';
        } else {
            $orderby = $valArray['orderby'];
            $order = $this->user->getKey('ses', 'listerstberatungorder');
        }
        if(isset($valArray['changeorder']) && $valArray['changeorder'] == 1) {
            $orderby = $valArray['orderby'];
            $order = $order == 'DESC' ? 'ASC' : 'DESC';
            $this->user->setKey('ses', 'listerstberatungorderby', $orderby);
            $this->user->setKey('ses', 'listerstberatungorder', $order);
        }
        
        $teilnehmer = $this->setfilter(3, $valArray, $orderby, $order, 0, 9999);
        
        // Wegen Bug in Paginator, der nicht mit Custom SQL Queryresults funktioniert, werden hier alle gefilterten Einträge auf einer Seite dargestellt. Queryresultpaginator hat dann keine Auswahl an Datensätzen, sondern alle.
        $anzperpag = $this->user->getKey('ses', 'filtermodus') == '1' ? 20 : 20;
        
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
        if(is_array($teilnehmer)) {
            $paginator = new ArrayPaginator($teilnehmer, $currentPage, $anzperpag);
        } else {
            $paginator = new QueryResultPaginator($teilnehmer, $currentPage, $anzperpag);
        }
        $pagination = new SimplePagination($paginator);
        
        $teilnehmerpag = $paginator->getPaginatedItems();
        
        $anzfolgekontakte = array();
        $summeberatungsdauer = array();
        $abschluesse = array();
        
        $folgekontakte = $this->folgekontaktRepository->findAll4List($this->niqbid);
        $berufeliste = $this->berufeRepository->findAllOrdered('de');
                
        $tnuidarray = array();
        foreach($teilnehmerpag as $tn) {
            $tnuidarray[] = $tn->getUid();
        }
        $abschluesserepo = $this->abschlussRepository->findByTnByUidarray($tnuidarray);
        
        $abschluesse = array();
        for($j=0; $j < count($teilnehmerpag); $j++) {
            $fk4tn = $this->folgekontaktRepository->findByTeilnehmer($teilnehmerpag[$j]->getUid());
            $anzfolgekontakte[$j] = count($fk4tn);
            $summebdauerfk = 0;
            foreach($fk4tn as $singlefk) $summebdauerfk = $summebdauerfk + floatval(str_replace(',','.',$singlefk->getBeratungsdauer()));
            $summeberatungsdauer[$j] = str_replace('.',',',floatval(str_replace(',','.',$teilnehmerpag[$j]->getBeratungsdauer())) + $summebdauerfk);
                
            foreach($abschluesserepo as $ab) {
                if($ab->getTeilnehmer()->getUid() == $teilnehmerpag[$j]->getUid()) {
                    $abschluesse[$j][] = $ab;
                }
            }
        }
        
        $staaten = $this->staatenRepository->findByLangisocode('de');
        
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }
        
        $orderchar = $order == 'ASC' ? "↓" : "↑";
        
        $mail4externstandardmailtext = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mailtextedit', 'Iqtp13db');
        
        $this->view->assignMultiple(
            [
                'anzgesamt' => count($teilnehmer),
                'abschluesse' => $abschluesse,
                'anzfolgekontakte' => $anzfolgekontakte,
                'folgekontakte' => $folgekontakte,
                'summeberatungsdauer' => $summeberatungsdauer,                
                'calleraction' => 'listerstberatung',
                'callercontroller' => 'Backend',
                'callerpage' => $currentPage,
                'paginator' => $paginator,
                'pagination' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
                'orderby' => $orderby,
                'orderchar' => $orderchar,
                'staatenarr' => $staatenarr,
                'berufe' => $berufeliste,
                'beratungsstelle' => $this->beratungsstellenname,
                'niqbid' => $this->niqbid,
                'alleberater' => $arrberater,
                'anzbstellen' => $this->anzbstellen,
                'betafeaturesaktiviert' => $this->usergroup->getBetafeatures(),
                'mail4externstandardmailtext' => $mail4externstandardmailtext,
                'anmeldeditseite' => $this->settings['anmeldeditseite']
            ]
            );
        return $this->htmlResponse();
    }
    
    /**
     * action listarchiv
     *
     * @param int $currentPage
     * @return void
     */
    public function listarchivAction(int $currentPage = 1): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        $arrberater  = $this->getberater4Bstelle('%', TRUE);

        if(($valArray['allemodule'] ?? '') == '1') {
            return $this->redirect('showsearchresult', 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1', 'searchparams' => $valArray));
        }
        
        // zuletzt bearbeiteten User zurücksetzen
        if(isset($valArray['tn'])) {
            $editedteilnehmer = $this->teilnehmerRepository->findByUid($valArray['tn']);
            $tnedituser = $editedteilnehmer->getEdituser();
            if($this->user->user['uid'] == $tnedituser) {
                $editedteilnehmer->setEdituser(0);
                $editedteilnehmer->setEdittstamp(0);
                $this->teilnehmerRepository->update($editedteilnehmer);
                // Daten sofort in die Datenbank schreiben
                $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
                $persistenceManager->persistAll();
            }
        }
        
        if(!empty($valArray['callerpage'])) $currentPage = $valArray['callerpage'];
        
        if(empty($valArray['orderby'])) {
            $orderby = $this->user->getKey('ses', 'listarchivorderby') ?? 'verificationDate';
            $order = $this->user->getKey('ses', 'listarchivorder') ?? 'DESC';
        } else {
            $orderby = $valArray['orderby'];
            $order = $this->user->getKey('ses', 'listarchivorder');
        }
        if(isset($valArray['changeorder']) && $valArray['changeorder'] == 1) {
            $orderby = $valArray['orderby'];
            $order = $order == 'DESC' ? 'ASC' : 'DESC';
            $this->user->setKey('ses', 'listarchivorderby', $orderby);
            $this->user->setKey('ses', 'listarchivorder', $order);
        }
        
        $teilnehmer = $this->setfilter(4, $valArray, $orderby, $order, 0, 9999);
        
        // Wegen Bug in Paginator, der nicht mit Custom SQL Queryresults funktioniert, werden hier alle gefilterten Einträge auf einer Seite dargestellt. Queryresultpaginator hat dann keine Auswahl an Datensätzen, sondern alle.
        $anzperpag = $this->user->getKey('ses', 'filtermodus') == '1' ? 25 : 25;
        
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
        if(is_array($teilnehmer)) {
            $paginator = new ArrayPaginator($teilnehmer, $currentPage, $anzperpag);
        } else {
            $paginator = new QueryResultPaginator($teilnehmer, $currentPage, $anzperpag);
        }
        $pagination = new SimplePagination($paginator);
        
        $teilnehmerpag = $paginator->getPaginatedItems();
        
        $anzfolgekontakte = array();
        $summeberatungsdauer = array();
        
        $tnuidarray = array();
        foreach($teilnehmerpag as $tn) {
            $tnuidarray[] = $tn->getUid();
        }
        $abschluesserepo = $this->abschlussRepository->findByTnByUidarray($tnuidarray);
        
        $abschluesse = array();
        for($j=0; $j < count($teilnehmerpag); $j++) {
            $fk4tn = $this->folgekontaktRepository->findByTeilnehmer($teilnehmerpag[$j]->getUid());
            $anzfolgekontakte[$j] = count($fk4tn);
            $summebdauerfk = 0;
            foreach($fk4tn as $singlefk) $summebdauerfk = $summebdauerfk + floatval(str_replace(',','.',$singlefk->getBeratungsdauer()));
            $summeberatungsdauer[$j] = str_replace('.',',',floatval(str_replace(',','.',$teilnehmerpag[$j]->getBeratungsdauer())) + $summebdauerfk);
            
            foreach($abschluesserepo as $ab) {
                if($ab->getTeilnehmer()->getUid() == $teilnehmerpag[$j]->getUid()) {
                    $abschluesse[$j][] = $ab;
                }
            }
        }
        
        $folgekontakte = $this->folgekontaktRepository->findAll4List($this->niqbid);
        
        $berufeliste = $this->berufeRepository->findAllOrdered('de');
        $staaten = $this->staatenRepository->findByLangisocode('de');
        
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }
                
        $orderchar = $order == 'ASC' ? "↓" : "↑";
        
        $mail4externstandardmailtext = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mailtextedit', 'Iqtp13db');
        
        $this->view->assignMultiple(
            [
                'anzgesamt' => count($teilnehmer),
                'anzfolgekontakte' => $anzfolgekontakte,
                'folgekontakte' => $folgekontakte,
                'summeberatungsdauer' => $summeberatungsdauer,
                'abschluesse' => $abschluesse,
                'calleraction' => 'listarchiv',
                'callercontroller' => 'Backend',
                'callerpage' => $currentPage,
                'paginator' => $paginator,
                'pagination' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
                'orderby' => $orderby,
                'orderchar' => $orderchar,
                'staatenarr' => $staatenarr,
                'berufe' => $berufeliste,
                'beratungsstelle' => $this->beratungsstellenname,
                'niqbid' => $this->niqbid,
                'alleberater' => $arrberater,
                'anzbstellen' => $this->anzbstellen,
                'betafeaturesaktiviert' => $this->usergroup->getBetafeatures(),
                'mail4externstandardmailtext' => $mail4externstandardmailtext,
                'anmeldeditseite' => $this->settings['anmeldeditseite']
            ]
            );
        return $this->htmlResponse();
    }
    
    /**
     * action listdeleted
     *
     * @param int $currentPage
     * @return void
     */
    public function listdeletedAction(int $currentPage = 1): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        $arrberater  = $this->getberater4Bstelle('%', TRUE);

        if(($valArray['allemodule'] ?? '') == '1') {
            return $this->redirect('showsearchresult', 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1', 'searchparams' => $valArray));
        }
        if(!empty($valArray['callerpage'])) $currentPage = $valArray['callerpage'];
        
        if(empty($valArray['orderby'])) {
            $orderby = $this->user->getKey('ses', 'listdeletedorderby') ?? 'verificationDate';
            $order = $this->user->getKey('ses', 'listdeletedorder') ?? 'DESC';
        } else {
            $orderby = $valArray['orderby'];
            $order = $this->user->getKey('ses', 'listdeletedorder');
        }
        if(isset($valArray['changeorder']) && $valArray['changeorder'] == 1) {
            $orderby = $valArray['orderby'];
            $order = $order == 'DESC' ? 'ASC' : 'DESC';
            $this->user->setKey('ses', 'listdeletedorderby', $orderby);
            $this->user->setKey('ses', 'listdeletedorder', $order);
        }
               
        $teilnehmer = $this->setfilter(999, $valArray, $orderby, $order, 1, 9999);
        
        // Wegen Bug in Paginator, der nicht mit Custom SQL Queryresults funktioniert, werden hier alle gefilterten Einträge auf einer Seite dargestellt. Queryresultpaginator hat dann keine Auswahl an Datensätzen, sondern alle.
        $anzperpag = $this->user->getKey('ses', 'filtermodus') == '1' ? 25 : 25;
        
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : $currentPage;
        if(is_array($teilnehmer)) {
            $paginator = new ArrayPaginator($teilnehmer, $currentPage, $anzperpag);
        } else {
            $paginator = new QueryResultPaginator($teilnehmer, $currentPage, $anzperpag);
        }
        $pagination = new SimplePagination($paginator);
        
        $teilnehmerpag = $paginator->getPaginatedItems();
        
        $abschluesse = array();
        for($j=0; $j < count($teilnehmerpag); $j++) {
            $anz = $this->teilnehmerRepository->findDublette4Deleted($teilnehmerpag[$j]->getNachname(), $teilnehmerpag[$j]->getVorname(), $this->niqbid);
            if($anz > 1) $teilnehmerpag[$j]->setDublette(TRUE);
            $abschluesse[$j] = $this->abschlussRepository->findByTeilnehmer($teilnehmerpag[$j]);
        }
        
        $staaten = $this->staatenRepository->findByLangisocode('de');
        
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }
        
        $orderchar = $order == 'ASC' ? "↓" : "↑";
        
        $this->view->assignMultiple(
            [
                'anzgesamt' => count($teilnehmer),
                'abschluesse' => $abschluesse,
                'calleraction' => 'listdeleted',
                'callercontroller' => 'Backend',
                'callerpage' => $currentPage,
                'paginator' => $paginator,
                'pagination' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
                'orderby' => $orderby,
                'orderchar' => $orderchar,
                'staatenarr' => $staatenarr,
                'beratungsstelle' => $this->beratungsstellenname,
                'niqbid' => $this->niqbid,
                'alleberater' => $arrberater,
                'anzbstellen' => $this->anzbstellen
            ]
            );
        return $this->htmlResponse();
    }
    
    /**
     * action showsearchresult
     * @param int $currentPage
     * @return void
     */
    public function showsearchresultAction(int $currentPage = 1): ResponseInterface
    {        
        $valArray = $this->request->getArguments();
        
        $filtermodus = $valArray['filtermodus'] ?? '1';
        if($filtermodus == '0')
        {
            return $this->redirect($valArray['searchparams']['action'] ?? 'listangemeldet', 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1'));
        }
        
        if(isset($valArray['searchparams']) && $valArray['searchparams']['berater'] == '0' &&
            $valArray['searchparams']['beruf'] == '' &&
            $valArray['searchparams']['bescheid'] == '' &&
            $valArray['searchparams']['gruppe'] == '' &&
            $valArray['searchparams']['land'] == '-1000' &&
            $valArray['searchparams']['name'] == '' &&
            $valArray['searchparams']['ort'] == '' &&
            $valArray['searchparams']['email'] == '' &&
            $valArray['searchparams']['uid'] == '') {
                $this->addFlashMessage("FEHLER: Bitte Suchkriterium angeben.", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
                return $this->redirect($valArray['searchparams']['action'] ?? 'listangemeldet', 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1'));
        }
        
        if(array_key_exists("searchparams", $valArray)) {
            $searchparams = $valArray['searchparams'];
        } else {
            if($valArray['action'] == 'showsearchresult') {
                if(!isset($valArray['filteran'])) {
                    // Filterfelder sind leer, weil z.B. Abschlüsse geöffnet wurden, dann ist die Suche nicht mehr aktiv und das aktive Standardmodul kann aufgerufen werden
                    return $this->redirect('start');
                } else {
                    $searchparams['uid'] = $valArray['uid'];
                    $searchparams['name'] = $valArray['name'];
                    $searchparams['ort'] = $valArray['ort'];
                    $searchparams['email'] = $valArray['email'];
                    $searchparams['beruf'] = $valArray['beruf'];
                    $searchparams['land'] = $valArray['land'];
                    $searchparams['berater'] = $valArray['berater'];
                    $searchparams['gruppe'] = $valArray['gruppe'];
                    $searchparams['bescheid'] = $valArray['bescheid'];
                    $searchparams['filteran'] = $valArray['filteran'];
                    $searchparams['allemodule'] = $valArray['allemodule'];
                }
            }
        }
        
        $alleteilnehmer = $this->setfilter(999, $searchparams, "beratungsstatus", "DESC", -1, 50);

        $folgekontakte = $this->folgekontaktRepository->findAll4List($this->niqbid);
        
        $abschluesse = array();
        $anzfolgekontakte = array();
        
        $tnuiddublette = $this->teilnehmerRepository->findDubletten4Angemeldetneu($this->niqbid);
        
        $j = 0;
        foreach($alleteilnehmer as $key => $teilnehmer) {
            // Dublettenprüfung
            foreach($tnuiddublette as $tnuid) {
                if($teilnehmer->getNachname() == $tnuid['nachname'] && $teilnehmer->getVorname() == $tnuid['vorname'] && $teilnehmer->getEmail() == $tnuid['email'] && $teilnehmer->getAnonym() == '0') $alleteilnehmer[$key]->setDublette(TRUE);
            }

            // Modul
            $beratungsstatus = $teilnehmer->getBeratungsstatus();
            if($teilnehmer->getHidden() == 1) $alleteilnehmer[$key]->setModul("Gelöscht");
            elseif($beratungsstatus == 0 || $beratungsstatus == 1) $alleteilnehmer[$key]->setModul("Angemeldet");
            elseif($beratungsstatus == 2 || $beratungsstatus == 3) $alleteilnehmer[$key]->setModul("Erstberatung");
            else $alleteilnehmer[$key]->setModul("Archiv");
            
            // Abschlüsse
            $abschluesse[$teilnehmer->getUid()] = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
            
            if($teilnehmer->getBeratungsstatus() > 1) {
                $fk4tn = $this->folgekontaktRepository->findByTeilnehmer($teilnehmer);
                $anzfolgekontakte[$teilnehmer->getUid()] = count($fk4tn);
                $summebdauerfk = 0;
                foreach($fk4tn as $singlefk) $summebdauerfk = $summebdauerfk + floatval(str_replace(',','.',$singlefk->getBeratungsdauer()));
                $summeberatungsdauer[$teilnehmer->getUid()] = str_replace('.',',',floatval(str_replace(',','.',$teilnehmer->getBeratungsdauer())) + $summebdauerfk);
            }
            $j++;
        }
        
        $berufeliste = $this->berufeRepository->findAllOrdered('de');
        $staaten = $this->staatenRepository->findByLangisocode('de');        
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }
        
        $this->view->assignMultiple(
            [
                'anzgesamt' => count($alleteilnehmer),
                'abschluesse' => $abschluesse,
                'anzfolgekontakte' => $anzfolgekontakte,
                'folgekontakte' => $folgekontakte,
                'summeberatungsdauer' => $summeberatungsdauer ?? 0,
                'calleraction' => $valArray['searchparams']['action'] ?? 'showsearchresult',
                'callercontroller' => 'Backend',
                'staatenarr' => $staatenarr,
                'beratungsstelle' => $this->beratungsstellenname,
                'niqbid' => $this->niqbid,
                'berufe' => $berufeliste,
                'searchparams' => $searchparams,
                'alleteilnehmer' => $alleteilnehmer,
                'anzbstellen' => $this->anzbstellen
            ]
        );
        return $this->htmlResponse();
    }
    
    
    
     /**
     * action initshow
     *
     * @return void
     */
    public function initializeShowAction() {
        $valArray = $this->request->getArguments();
        
        if(is_string($valArray['teilnehmer'])) $tnuid = $valArray['teilnehmer'];
        else $tnuid = $valArray['teilnehmer']->getUid();
        
        $thistn = $this->teilnehmerRepository->findByUid($tnuid);   
        
        if($thistn != NULL) {
            if($thistn->getPlz() == '') $thistn->setPlz('0');
            $tnanonym = $thistn->getAnonym();
            $anonymeberatung = $valArray['newanonymeberatung'] ?? '';
            if($anonymeberatung == '1' || $tnanonym == '1') {
                $this->addFlashMessage("Bitte beachten: Für anonyme Beratungen ist zur Wahrung des Datenschutzes kein Dokumentenupload möglich!", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::WARNING);
            }            
        } else {
            // TN ist (nicht) mehr vorhanden (gelöscht z.B. durch Task)
            echo "FEHLER: Datensatz mit ID $tnuid nicht vorhanden.";
            die;            
            $this->addFlashMessage("FEHLER: Datensatz mit ID $tnuid nicht vorhanden.", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            return $this->redirect($valArray['calleraction'] ?? 'listangemeldet', $valArray['callercontroller'] ?? 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1'));
        }
    }
    
    /**
     * action show
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function showAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        $language = $this->request->getAttribute('language');
        $isocode  = $language->getLocale()->getLanguageCode();
        
        if(array_key_exists("searchparams", $valArray)) {
            $searchparams = $valArray['searchparams'];
        }
                
        $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
        foreach($abschluesse as $abschl) {
            if(strstr($abschl->getAbschlussart(), ',')) $abschl->setAbschlussart(2);
        }
        $historie = $this->historieRepository->findByTeilnehmerOrdered($teilnehmer->getUid());
        $dokumente = $this->dokumentRepository->findByTeilnehmer($teilnehmer);
        $dokumentpfad = $this->generalhelper->sanitizeFileFolderName($teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid(). '/');
                
        $storage = $this->generalhelper->getTP13Storage($this->storageRepository->findAll());
        $folder = $storage->getConfiguration()['basePath'].'/';
        
        $filesizes = array();
        $filesizesum = 0;
        
        foreach($dokumente as $key => $dok) {
            $dokfs = $dok->getFilesize($folder) ?? 0;
            $filesizes[$key] = $dokfs == 0 ? 0 : $this->generalhelper->human_filesize($dokfs, 1);
            $filesizesum += $dokfs;
        }
        $speicherbelegung = intval(($filesizesum/31457280)*100);
        
        $berufeliste = $this->berufeRepository->findAll();
        $staaten = $this->staatenRepository->findByLangisocode($isocode);
        $abschlussartarr = $this->settings['abschlussart'];
        
        $brancheunterkat = $this->brancheRepository->findAllUnterkategorie($isocode);
        
        $fk4tn = $this->folgekontaktRepository->findByTeilnehmer($teilnehmer->getUid());
        
        $dublettenbstellen = $this->teilnehmerRepository->findDublettenBstellen($teilnehmer->getNachname(), $teilnehmer->getVorname(), $teilnehmer->getEmail());
        if(count($dublettenbstellen) != 0 && $teilnehmer->getEmail() != "no-reply@iq-webapp.de"){
            $auch_bei_beratungsstelle = array();
            foreach($dublettenbstellen as $bid) {
                if($bid['niqidberatungsstelle'] != intval($this->niqbid)) $auch_bei_beratungsstelle = $this->userGroupRepository->findBeratungsstellebyNiqbid($this->settings['beraterstoragepid'], $bid);
            }
            foreach($auch_bei_beratungsstelle as $bstelle) {
                $this->addFlashMessage("Achtung: Diese/r Ratsuchende/r ist auch bei der Beratungsstelle <b>".$bstelle->getDescription()."</b> angemeldet.", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::WARNING);
            }
        }
             
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        $this->view->assignMultiple(
            [
                'dokumente' => $dokumente,
                'dokumentpfad' => $dokumentpfad,
                'calleraction' => $valArray['calleraction'] ?? 'listangemeldet',
                'callercontroller' => $valArray['callercontroller'] ?? 'Backend',
                'callerpage' => $valArray['callerpage'] ?? '1',
                'historie' => $historie,
                'teilnehmer' => $teilnehmer,
                'abschluesse' => $abschluesse,
                'showabschluesse' => $valArray['showabschluesse'] ?? '0',
                'showdokumente' => $valArray['showdokumente'] ?? '0',
                'beratungsstelle' => $this->beratungsstellenname,
                'niqbid' => $this->niqbid,
                'staaten' => $staaten,
                'berufe' => $berufeliste,
                'filesizes' => $filesizes,
                'speicherbelegung' => $speicherbelegung,
                'searchparams' => $searchparams ?? '',
                'abschlussartarr' => $abschlussartarr,
                'brancheunterkat' => $brancheunterkat,
                'anzbstellen' => $this->anzbstellen,
                'folgekontakte' => $fk4tn,
                'anmeldeditseite' => $this->settings['anmeldeditseite']
            ]
            );
        return $this->htmlResponse();
    }
    
    /**
     * action initnew
     *
     * @return void
     */
    public function initializeNewAction() {
        $valArray = $this->request->getArguments();
        
        $anonymeberatung = $valArray['newanonymeberatung'] ?? '';
        if($anonymeberatung == '1') {
            $this->addFlashMessage("Bitte beachten: Für anonyme Beratungen ist zur Wahrung des Datenschutzes kein Dokumentenupload möglich!", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::WARNING);
        }
    }
    
    /**
     * action new
     *
     * @return void
     */
    public function newAction(): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        $abschluss = new \Ud\Iqtp13db\Domain\Model\Abschluss();
        
        $alleberater  = $this->getberater4Bstelle('%', FALSE);

        $staaten = $this->staatenRepository->findByLangisocode('de');
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }
        
        $aktuellesJahr = (int)date("Y");
        $jahre = array();
        $jahre[-1] = 'k.A.';
        for($jahr = $aktuellesJahr; $jahr > $aktuellesJahr-60; $jahr--) {
            $jahre[$jahr] = (String)$jahr;
        }
                
        $group = $this->userGroupRepository->findOneByNiqbid($this->niqbid);
        
        $beratungsartenarray = $group->getBeratungsarten();
        $newwieberatenarray = array();
        foreach($this->settings['wieberaten'] as $key => $wieber){
            if(in_array($key, $beratungsartenarray)) $newwieberatenarray[$key] = $wieber;
        }
        if(count($newwieberatenarray) != 0) {
            $this->view->assign('wieberatenarr', $newwieberatenarray);
        } else {
            $this->view->assign('wieberatenarr', $this->settings['wieberaten']);
        }
        
        $uriBuilder = $this->uriBuilder;
        $uriBuilder->reset();
        if($group->getEinwilligungserklaerungsseite() != 0) {
            $uriBuilder->setTargetPageUid($group->getEinwilligungserklaerungsseite());
        } else {
            $uriBuilder->setTargetPageUid($this->settings['datenschutzeinwilligungurluid']);
        }
        
        $urleinwilligung = $uriBuilder->build();
        
        $alleberatungsstellen = $this->userGroupRepository->findAllBeratungsstellen($this->settings['beraterstoragepid']);
                
        $backenduser = $this->beraterRepository->findByUid($this->user->user['uid']);
        $this->view->assignMultiple(
            [
                'alleberatungsstellen' => $alleberatungsstellen,
                'calleraction' => $valArray['calleraction'] ?? 'listangemeldet',
                'callercontroller' => $valArray['callercontroller'] ?? 'Backend',
                'callerpage' => $valArray['callerpage'] ?? '1',
                'abschluss' => $abschluss,
                'staatenarr' => $staatenarr,
                'alleberater' => $alleberater,
                'berater' => $this->user,
                'settings' => $this->settings,
                'urleinwilligung' => $urleinwilligung,
                'newnacherfassung' => $valArray['newnacherfassung'] ?? '0',
                'newanonymeberatung' => $valArray['newanonymeberatung'] ?? '0',
                'beratungsstelle' => $this->beratungsstellenname,
                'niqbid' => $this->niqbid,
                'anzbstellen' => $this->anzbstellen,
                'jahre' => $jahre
            ]
            );
        return $this->htmlResponse();
    }
    
    /**
     * action create
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function createAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        if($teilnehmer->getVerificationDate() == 0 && $teilnehmer->getNacherfassung() == 0 && ($this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen()) || $this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum()))) {
            $this->addFlashMessage("HINWEIS: Bitte unmittelbar nach Eintragung Einwilligung anfordern!", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
        }
        
        if($valArray['newnacherfassung'] == '1' && $teilnehmer->getNacherfassung() == '') {
            $teilnehmer->setBeratungsstatus(99);
            $this->addFlashMessage("Datensatz NICHT gespeichert. Feld 'Nacherfassung' muss angekreuzt sein!", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);            
        } elseif($this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen()) && !$this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum())) {
            $teilnehmer->setBeratungsstatus(99);
            $this->addFlashMessage("Datensatz NICHT gespeichert. 'Datum Erstberatung' muss eingetragen sein, wenn 'Erstberatung abgeschlossen' ausgefüllt ist.", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
        } elseif($teilnehmer->getNacherfassung() == 1 && (!$this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum()) || !$this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen()))) {
            $teilnehmer->setBeratungsstatus(99);
            $this->addFlashMessage("Datensatz NICHT gespeichert. Bei Nacherfassungen müssen -Datum Erstberatung– und -Erstberatung abgeschlossen- ausgefüllt sein.", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
        } else {
            $teilnehmer->setBeratungsstatus(0);
        }
        if($teilnehmer->getAnonym() == 1) {
            if($teilnehmer->getErstberatungabgeschlossen() != '') {
                $teilnehmer->setBeratungsstatus(3);
            } else {
                $teilnehmer->setBeratungsstatus(2);
            }            
            $teilnehmer->setVerificationDate(new DateTime('now'));
            $teilnehmer->setVerificationIp($_SERVER['REMOTE_ADDR']);
        }
        if($teilnehmer->getNacherfassung() == 1) {
            $teilnehmer->setBeratungsstatus(4);
            $teilnehmer->setVerificationDate(new DateTime('now'));
            $teilnehmer->setVerificationIp($_SERVER['REMOTE_ADDR']);
        }        
        $teilnehmer->setNiqidberatungsstelle($this->niqbid);
        if($teilnehmer->getBerater() == 0) $teilnehmer->setBerater($this->beraterRepository->findByUid($this->user->user['uid']));
        $teilnehmer->setCrdate(time());
        $this->teilnehmerRepository->add($teilnehmer);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        return $this->redirect('edit', 'Backend', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'], 'callercontroller' => $valArray['callercontroller'], 'callerpage' => $valArray['callerpage'], 'newnacherfassung' => $valArray['newnacherfassung']));
    }
    
    /**
     * action initedit
     *
     * @return void
     */
    public function initializeEditAction() {
        $valArray = $this->request->getArguments();
        
        if(array_key_exists('teilnehmer', $valArray)) {
            if(is_string($valArray['teilnehmer'])) {
                $tnuid = $valArray['teilnehmer'];
    
                $thistn = $this->teilnehmerRepository->findByUid($tnuid);
                if($tnuid != null) $tnanonym = $thistn->getAnonym();
                else $tnanonym = 0;
                $anonymeberatung = $valArray['newanonymeberatung'] ?? '';
                
                if($anonymeberatung == '1' || $tnanonym == '1') {
                    $this->addFlashMessage("Bitte beachten: Für anonyme Beratungen ist zur Wahrung des Datenschutzes kein Dokumentenupload möglich!", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::WARNING);
                }
            }
        }
    }
    
    /**
     * action edit
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @param \Ud\Iqtp13db\Domain\Model\Abschluss $abschluss
     * @param String $selectboxabschluss
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function editAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer, \Ud\Iqtp13db\Domain\Model\Abschluss $abschluss = NULL): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        $language = $this->request->getAttribute('language');
        $isocode  = $language->getLocale()->getLanguageCode();
        $alleberater  = $this->getberater4Bstelle('%', FALSE);

        if(array_key_exists("searchparams", $valArray)) {
            $searchparams = $valArray['searchparams'];
        }
        
        $edituserfield = '';
        
        if($teilnehmer->getEdittstamp() == 0 || $teilnehmer->getEdituser() == $this->user->user['uid'] || (time() - $teilnehmer->getEdittstamp()) > 10) {
            $teilnehmer->setEdittstamp(time());
            $teilnehmer->setEdituser($this->user->user['uid']);
            $this->teilnehmerRepository->update($teilnehmer);
            
            // Daten sofort in die Datenbank schreiben
            $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
        } else {
            $editberater = $this->beraterRepository->findByUid($teilnehmer->getEdituser());
            $edituserfield = $editberater->getUsername();
            $edittstampfield = date("G:i:s", $teilnehmer->getEdittstamp());
        }
        
        $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
        foreach($abschluesse as $abschl) {
            if(strstr($abschl->getAbschlussart(), ',')) $abschl->setAbschlussart(2);
        }
        
        $dokumente = $this->dokumentRepository->findByTeilnehmer($teilnehmer);
        $dokumentpfad = $this->generalhelper->sanitizeFileFolderName($teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid(). '/');
        
        $storage = $this->generalhelper->getTP13Storage($this->storageRepository->findAll());
        $folder = $storage->getConfiguration()['basePath'].'/';
        
        $filesizes = array();
        $filesizesum = 0;
        foreach($dokumente as $key => $dok) {
            $dokfs = $dok->getFilesize($folder) ?? 0;
            $filesizes[$key] = $dokfs == 0 ? 0 : $this->generalhelper->human_filesize($dokfs, 1);
            $filesizesum += $dokfs;
        }
        $speicherbelegung = intval(($filesizesum/31457280)*100);
        
        $berufe = $this->berufeRepository->findAllOrdered($isocode);
        $staaten = $this->staatenRepository->findByLangisocode($isocode);
        //unset($staaten[200]); // entfernt 'staatenlos'
        foreach($staaten as $staat) {
            $staatenarr[$staat->getStaatid()] = $staat->getTitel();
        }        
        
        $aktuellesJahr = (int)date("Y");
        $jahre = array();
        $jahre[-1] = 'k.A.';
        for($jahr = $aktuellesJahr; $jahr > $aktuellesJahr-60; $jahr--) {
            $jahre[$jahr] = (String)$jahr;
        }
        
        $group = $this->userGroupRepository->findOneByNiqbid($this->niqbid);
        
        $beratungsartenarray = $group->getBeratungsarten();
        $newwieberatenarray = array();
        foreach($this->settings['wieberaten'] as $key => $wieber){
            if(in_array($key, $beratungsartenarray)) $newwieberatenarray[$key] = $wieber;
        }
        if(count($newwieberatenarray) != 0) {
            $this->view->assign('wieberatenarr', $newwieberatenarray);
        } else {
            $this->view->assign('wieberatenarr', $this->settings['wieberaten']);
        }
       
        $abschlusshinzu = isset($valArray['abschlusshinzu']) ? $valArray['abschlusshinzu'] : '';
        
        $alleberatungsstellen = $this->userGroupRepository->findAllBeratungsstellen($this->settings['beraterstoragepid']);
        
        if($group->getEinwilligungserklaerungsseite() != '') {
            $uriBuilder = $this->uriBuilder;
            $uriBuilder->reset();
            $uriBuilder->setTargetPageUid($group->getEinwilligungserklaerungsseite());
            $urleinwilligung = $uriBuilder->build();
        } else {
            $urleinwilligung = $this->settings['datenschutzeinwilligungurl'];
        }
        
        $nacherfassung = $valArray['newnacherfassung'] ?? '0';
        if($teilnehmer->getNacherfassung() == 1) {
            $nacherfassung = 1;
        }
        $abschlussartarr = $this->settings['abschlussart'];
        
        $brancheunterkat = $this->brancheRepository->findAllUnterkategorie($isocode);
        
        $gebjahrberechnetausalter = (intval(date('Y', $teilnehmer->getCrdate()))-intval($teilnehmer->getLebensalter()));
        $gebjahrberechnetausalter = ($gebjahrberechnetausalter > 0 && $gebjahrberechnetausalter < 100) ? $gebjahrberechnetausalter : 'Lebensalter nicht angegeben';
        
        $fk4tn = $this->folgekontaktRepository->findByTeilnehmer($teilnehmer->getUid());
        
        $dublettenbstellen = $this->teilnehmerRepository->findDublettenBstellen($teilnehmer->getNachname(), $teilnehmer->getVorname(), $teilnehmer->getEmail());
        if(count($dublettenbstellen) != 0){
            $auch_bei_beratungsstelle = array();
            foreach($dublettenbstellen as $bid) {
                if($bid['niqidberatungsstelle'] != intval($this->niqbid)) $auch_bei_beratungsstelle = $this->userGroupRepository->findBeratungsstellebyNiqbid($this->settings['beraterstoragepid'], $bid);
            }
            foreach($auch_bei_beratungsstelle as $bstelle) {
                $this->addFlashMessage("Achtung: Diese/r Ratsuchende/r ist auch bei der Beratungsstelle <b>".$bstelle->getDescription()."</b> angemeldet.", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::WARNING);
            }
        }
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        $this->view->assignMultiple(
            [
                'alleberatungsstellen' => $alleberatungsstellen,
                'calleraction' => $valArray['calleraction'] ?? 'listangemeldet',
                'callercontroller' => $valArray['callercontroller'] ?? 'Backend',
                'callerpage' => $valArray['callerpage'] ?? '1',
                'abschluesse' => $abschluesse,
                'alleberater' => $alleberater,
                'settings' => $this->settings,
                'staatenarr' => $staatenarr,
                'berufe' => $berufe,
                'staaten' => $staaten,
                'teilnehmer' => $teilnehmer,
                'jahre' => $jahre,
                'dokumente' => $dokumente,
                'dokumentpfad' => $dokumentpfad,
                'filesizes' => $filesizes,
                'speicherbelegung' => $speicherbelegung,
                'abschlusshinzu' => $abschlusshinzu,
                'showabschluesse' => $valArray['showabschluesse'] ?? '0',
                'showdokumente' => $valArray['showdokumente'] ?? '0',
                'edituserfield' => $edituserfield ?? '0',
                'edittstampfield' => $edittstampfield ?? '0',
                'urleinwilligung' => $urleinwilligung,
                'newnacherfassung' => $nacherfassung,
                'beratungsstelle' => $this->beratungsstellenname,
                'niqbid' => $this->niqbid,
                'searchparams' => $searchparams ?? '',
                'abschlussartarr' => $abschlussartarr,
                'brancheunterkat' => $brancheunterkat,
                'anzbstellen' => $this->anzbstellen,
                'jahraltereintraglebensalter' => $gebjahrberechnetausalter,
                'folgekontakte' => $fk4tn,
                'anmeldeditseite' => $this->settings['anmeldeditseite']
            ]
            );
        return $this->htmlResponse();
    }
    
    /**
     * action update
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function updateAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        if(array_key_exists("searchparams", $valArray)) {
            $searchparams = $valArray['searchparams'];
        }
                 
        $nacherfassung = $valArray['newnacherfassung'] ?? '0';
        if($nacherfassung == '1' && $teilnehmer->getNacherfassung() == '') {
            $this->addFlashMessage("Datensatz NICHT gespeichert. Feld 'Nacherfassung' muss angekreuzt sein!", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            return $this->redirect('edit', 'Backend', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'], 'callercontroller' => $valArray['callercontroller'], 'callerpage' => $valArray['callerpage'] ?? '1', 'newnacherfassung' => $valArray['newnacherfassung'], 'searchparams' => $searchparams));
        }
        
        if($teilnehmer->getNacherfassung() == 1 && (!$this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum()) || !$this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen()))) {
            $this->addFlashMessage("Datensatz NICHT gespeichert. Bei Nacherfassungen müssen 'Datum Erstberatung' und 'Erstberatung abgeschlossen' ausgefüllt sein.", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            return $this->redirect('edit', 'Backend', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'], 'callercontroller' => $valArray['callercontroller'], 'callerpage' => $valArray['callerpage'] ?? '1', 'newnacherfassung' => $valArray['newnacherfassung'], 'searchparams' => $searchparams));
        }
        
        if($teilnehmer->getNacherfassung() != 1 && $teilnehmer->getVerificationDate() == 0 && ($this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen()) || $this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum()))) {
            $this->addFlashMessage("Datensatz NICHT gespeichert. Vor Eintragung von 'Datum Erstberatung' oder 'Erstberatung abgeschlossen' muss die Anmeldung bestätigt werden!", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            return $this->redirect('edit', 'Backend', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'], 'callercontroller' => $valArray['callercontroller'], 'callerpage' => $valArray['callerpage'] ?? '1', 'newnacherfassung' => $valArray['newnacherfassung'], 'searchparams' => $searchparams));
        }
        
        if($this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen()) && !$this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum())) {
            $teilnehmer->setBeratungdatum($teilnehmer->getErstberatungabgeschlossen());
            
            $this->addFlashMessage("Datensatz gespeichert. Für 'Datum Erstberatung' wurde automatisch das Datum 'Erstberatung abgeschlossen' eingetragen.", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::WARNING);
        }

        if($teilnehmer->getGebdat() != '') {
            $birthdate = DateTime::createFromFormat('Y-m-d', $teilnehmer->getGebdat());
            $today = new DateTime();
            $age = $today->diff($birthdate)->y;
            $teilnehmer->setLebensalter($age);
        }
        
        // Stammdaten (im Fragebogen Seite 1)
        $this->createHistory($teilnehmer, "niqidberatungsstelle");
        $this->createHistory($teilnehmer, "einwilligung");
        $this->createHistory($teilnehmer, "schonberaten");
        $this->createHistory($teilnehmer, "schonberatenvon");
        $this->createHistory($teilnehmer, "nachname");
        $this->createHistory($teilnehmer, "vorname");
        $this->createHistory($teilnehmer, "strasse");
        $this->createHistory($teilnehmer, "plz");
        $this->createHistory($teilnehmer, "ort");
        $this->createHistory($teilnehmer, "email");
        $this->createHistory($teilnehmer, "telefon");
        $this->createHistory($teilnehmer, "gebdat");
        $this->createHistory($teilnehmer, "geburtsland");
        $this->createHistory($teilnehmer, "geschlecht");
        $this->createHistory($teilnehmer, "ersteStaatsangehoerigkeit");
        $this->createHistory($teilnehmer, "zweiteStaatsangehoerigkeit");
        $this->createHistory($teilnehmer, "einreisejahr");
        $this->createHistory($teilnehmer, "wohnsitzDeutschland");
        $this->createHistory($teilnehmer, "wohnsitzNeinIn");
        $this->createHistory($teilnehmer, "aufenthaltsstatus");
        $this->createHistory($teilnehmer, "aufenthaltsstatusfreitext");
        $this->createHistory($teilnehmer, "sonstigerstatus");
        $this->createHistory($teilnehmer, "deutschkenntnisse");
        $this->createHistory($teilnehmer, "zertifikatSprachniveau");
        $this->createHistory($teilnehmer, "weiteresprachkenntnisse");

        // Stammdaten (im Fragebogen Seite 3)
        $this->createHistory($teilnehmer, "erwerbsstatus");
        $this->createHistory($teilnehmer, "leistungsbezugjanein");
        $this->createHistory($teilnehmer, "leistungsbezug");
        $this->createHistory($teilnehmer, "nameBeraterAA");
        $this->createHistory($teilnehmer, "kontaktBeraterAA");
        $this->createHistory($teilnehmer, "kundennummerAA");
        $this->createHistory($teilnehmer, "einwAnerkstelle");
        $this->createHistory($teilnehmer, "einwAnerkstelledatum");
        $this->createHistory($teilnehmer, "einwAnerkstellemedium");
        $this->createHistory($teilnehmer, "einwAnerkstellename");
        $this->createHistory($teilnehmer, "einwAnerkstellekontakt");
        $this->createHistory($teilnehmer, "einwPerson");
        $this->createHistory($teilnehmer, "einwPersondatum");
        $this->createHistory($teilnehmer, "einwPersonmedium");
        $this->createHistory($teilnehmer, "einwPersonname");
        $this->createHistory($teilnehmer, "einwPersonkontakt");
        $this->createHistory($teilnehmer, "einwPersonkontaktmail");
        $this->createHistory($teilnehmer, "nameBeratungsstelle");
        $this->createHistory($teilnehmer, "wieberaten");
        $this->createHistory($teilnehmer, "notizen");
        $this->createHistory($teilnehmer, "einwilligunginfo");

        // Beratungsdaten (nur Backend!)
        $this->createHistory($teilnehmer, "anerkennendestellen");
        $this->createHistory($teilnehmer, "beratungdatum");
        $this->createHistory($teilnehmer, "berater");
        $this->createHistory($teilnehmer, "beratungsart");
        $this->createHistory($teilnehmer, "beratungsartfreitext");
        $this->createHistory($teilnehmer, "beratungsort");
        $this->createHistory($teilnehmer, "beratungsdauer");
        $this->createHistory($teilnehmer, "anerkennungsberatung");
        $this->createHistory($teilnehmer, "anerkennungsberatungfreitext");
        $this->createHistory($teilnehmer, "qualifizierungsberatung");
        $this->createHistory($teilnehmer, "qualifizierungsberatungfreitext");
        $this->createHistory($teilnehmer, "beratungzu");
        $this->createHistory($teilnehmer, "anerkennungszuschussbeantragt");
        $this->createHistory($teilnehmer, "kooperationgruppe");
        $this->createHistory($teilnehmer, "beratungnotizen");
        $this->createHistory($teilnehmer, "erstberatungabgeschlossen");
        
        $bstatus = $this->checkberatungsstatus($teilnehmer);
        if($bstatus == 999) {
            $this->addFlashMessage("Fehler in Update-Routine -> beratungsstatus = 999. Bitte Admin informieren.", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
        }
       
        $teilnehmer->setBeratungsstatus($bstatus);
        
        if($teilnehmer->getNacherfassung() == 1) {
            $teilnehmer->setBeratungsstatus(4);
            if($teilnehmer->getVerificationDate() == 0) $teilnehmer->setVerificationDate(new DateTime('now'));
            if($teilnehmer->getVerificationIp() == '') $teilnehmer->setVerificationIp($_SERVER['REMOTE_ADDR']);
        }
        $this->teilnehmerRepository->update($teilnehmer);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        return $this->redirect('edit', $valArray['callercontroller'] ?? 'Backend', null, array('teilnehmer'=> $teilnehmer, 'callerpage' => $valArray['callerpage'] ?? '1', 'calleraction' => $valArray['calleraction'] ?? 'listangemeldet', 'newnacherfassung' => $nacherfassung, 'searchparams' => $searchparams));
    }
    
    /**
     * action initdelete
     *
     * @return void
     */
    public function initializeDeleteAction() {
        $valArray = $this->request->getArguments();
        
        if(is_string($valArray['teilnehmer'])) $tnuid = $valArray['teilnehmer'];
        else $tnuid = $valArray['teilnehmer']->getUid();
        
        $thistn = $this->teilnehmerRepository->findByUid($tnuid);
        
        if($thistn == null) {
            // TN ist (nicht) mehr vorhanden (gelöscht z.B. durch Task)
            $this->addFlashMessage("FEHLER: Datensatz mit ID $tnuid nicht vorhanden.", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            return $this->redirect($valArray['calleraction'] ?? 'listangemeldet', $valArray['callercontroller'] ?? 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1'));
        }
    }
    
    /**
     * action delete
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function deleteAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        if(array_key_exists("searchparams", $valArray)) {
            $searchparams = $valArray['searchparams'];
        }
        
        if($teilnehmer->getNiqchiffre() == '') {
            $teilnehmer->setHidden(1);
            
            $this->teilnehmerRepository->update($teilnehmer);
            
            // Daten sofort in die Datenbank schreiben
            $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
            
            $this->addFlashMessage($teilnehmer->getNachname().', '.$teilnehmer->getVorname().' (UID: '.$teilnehmer->getUid().') gelöscht.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
        } else {
            $this->addFlashMessage('Bereits in NIQ übertragene Datensätze können nicht gelöscht werden.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
        }
        return $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage'] ?? '1', 'searchparams' => $searchparams ?? ''));
    }
    
    /**
     * action undelete
     *
     * @param int $tnuid
     * @return void
     */
    public function undeleteAction($tnuid): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        $searchparams  = array();
        if(array_key_exists("searchparams", $valArray)) {
            $searchparams = $valArray['searchparams'];
        }
        
        $teilnehmer = $this->teilnehmerRepository->findHiddenByUid($tnuid);
        $teilnehmer->setHidden(0);
        
        $this->teilnehmerRepository->update($teilnehmer);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        $this->addFlashMessage($teilnehmer->getNachname().', '.$teilnehmer->getVorname().' (UID: '.$teilnehmer->getUid().') wiederhergestellt.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
        
        return $this->redirect($valArray['calleraction'] ?? 'listdeleted', $valArray['callercontroller'] ?? 'Backend', null, array('callerpage' => $valArray['callerpage'] ?? '1', 'searchparams' => $searchparams));
    }
    
    
    /**
     * action takeover
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function takeoverAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        if(array_key_exists("searchparams", $valArray)) {
            $searchparams = $valArray['searchparams'];
        }
        
        $berater = $this->beraterRepository->findByUid($this->user->user['uid']);
        
        $teilnehmer->setBerater($berater);
        $this->teilnehmerRepository->update($teilnehmer);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        return $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage'] ?? '1', 'searchparams' => $searchparams ?? ''));
    }
    
    /**
     * action setBeratungsstellebyPLZ
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function setBeratungsstellebyPLZAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        $plzberatungsstelle = array();
        $plzberatungsstelle = $this->userGroupRepository->getBeratungsstelle4PLZ($teilnehmer->getPlz(), $this->settings['beraterstoragepid']);
        $bstid = count($plzberatungsstelle) > 0 ? $plzberatungsstelle[0]->getNiqbid() : '';
        
        if($bstid == '') {
            $this->addFlashMessage('Keine der PLZ zugehörige Beratungsstelle vorhanden.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
        } else {
            if($bstid == $teilnehmer->getNiqidberatungsstelle()) {
                $this->addFlashMessage('Keine Änderung der Beratungsstelle, da die PLZ dieser Beratungsstelle zugewiesen ist.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            } else {
                $teilnehmer->setNiqidberatungsstelle($bstid);
                $teilnehmer->setBerater(null);
                
                $this->teilnehmerRepository->update($teilnehmer);
                
                // Daten sofort in die Datenbank schreiben
                $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
                $persistenceManager->persistAll();
                
                $this->addFlashMessage('Datensatz zu Beratungsstelle '.$plzberatungsstelle[0]->getTitle(). ' verschoben.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
            }
        }
        
        return $this->redirect('listangemeldet', 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer, 'callerpage' => $valArray['callerpage'] ?? '1'));
    }
    
    /**
     * action askconsent
     * Einwilligungs-E-Mail aus dem Backend anfordern
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function askconsentAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        if(array_key_exists("searchparams", $valArray)) {
            $searchparams = $valArray['searchparams'];
        }
        
        $bcc = '';
        $sender = $this->settings['sender'];
        if($sender == '') {
            $this->addFlashMessage('Error 101 in askconsent.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            return $this->redirect('listangemeldet', 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
        } else {
            $recipient = $teilnehmer->getEmail();
            if($recipient == '') {
                $this->addFlashMessage('Keine E-Mail-Adresse eingetragen.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
                return $this->redirect('listangemeldet', 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
            }

            $templateName = 'Mailtoconfirm';
            $confirmmailtext1 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmmailtext1', 'Iqtp13db');
            $confirmmailtext1 = str_replace("VORNAMENACHNAME", $teilnehmer->getVorname().' '.$teilnehmer->getNachname(), $confirmmailtext1);
            $confirmlinktext = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmlinktext', 'Iqtp13db');
            $confirmmailtext2 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmmailtext2', 'Iqtp13db');
            $subject = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('confirmsubject', 'Iqtp13db');
            
            $zugewieseneberatungsstelle = $this->userGroupRepository->findBeratungsstellebyNiqbid($this->settings['beraterstoragepid'], $teilnehmer->getNiqidberatungsstelle());
            $datenberatungsstelle = $zugewieseneberatungsstelle != NULL ? $zugewieseneberatungsstelle[0]->getDescription() : '';
            if($datenberatungsstelle != '') $kontaktlabel = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('kontaktberatungsstelle', 'Iqtp13db');
            else $kontaktlabel = '';
            
            $request = $GLOBALS['TYPO3_REQUEST'];
            $normalizedParams = $request->getAttribute('normalizedParams');
            $baseUri = $normalizedParams->getSiteUrl();
            
            $variables = array(
                'teilnehmer' => $teilnehmer,
                'confirmmailtext1' => $confirmmailtext1,
                'confirmlinktext' => $confirmlinktext,
                'confirmmailtext2' => $confirmmailtext2,
                'datenberatungsstelle' => $datenberatungsstelle,
                'kontaktlabel' => $kontaktlabel,
                'startseitelink' => $this->settings['startseitelink'],
                'logolink' => $this->settings['logolink'],
                'registrationpageuid' => $this->settings['registrationpageuid'],
                'askconsent' => '1',
                'baseurl' => $baseUri
            );
            
            $emailview = GeneralUtility::makeInstance('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
            $emailview->setRequest($this->request);
            
            $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
            $this->generalhelper->sendTemplateEmail(array($recipient), array($bcc), array($sender), $subject, $templateName, $variables, $emailview, $this->uriBuilder, $extbaseFrameworkConfiguration);
            
            $this->addFlashMessage('Einwilligungsanforderung versendet.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
            
            return $this->redirect('listangemeldet', 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer, 'callerpage' => $valArray['callerpage'] ?? '1', 'searchparams' => $searchparams ?? ''));
        }
    }   
    
    /**
     * action mail4editextern
     * E-Mail mit Link für nachträgliches Bearbeiten an RS senden
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function mail4editexternAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        $emailBody = $valArray['emailBody'];
                
        $bcc = '';
        $sender = $this->settings['sender'];
        if($sender == '') {
            $this->addFlashMessage('Error 101 in mail4editextern.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            return $this->redirect($valArray['calleraction'], 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
        } else {
            $recipient = $teilnehmer->getEmail();
            if($recipient == '') {
                $this->addFlashMessage('Keine E-Mail-Adresse eingetragen.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
                return $this->redirect($valArray['calleraction'], 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
            }            
            if($teilnehmer->getGebdat() == '') {
                $this->addFlashMessage('Kein Geburtsdatum eingetragen, externer Login nicht möglich. Bitte Geburtsdatum eintragen.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
                return $this->redirect($valArray['calleraction'], 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
            }
            
            $templateName = 'MailEditExtern';
            $subject = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('subjecteditextern', 'Iqtp13db')." - UID: ".$teilnehmer->getUid();
            $anrede = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('anredemail', 'Iqtp13db');
            $zugewieseneberatungsstelle = $this->userGroupRepository->findBeratungsstellebyNiqbid($this->settings['beraterstoragepid'], $teilnehmer->getNiqidberatungsstelle());
            $datenberatungsstelle = $zugewieseneberatungsstelle != NULL ? $zugewieseneberatungsstelle[0]->getDescription() : '';
            if($datenberatungsstelle != '') $kontaktlabel = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('kontaktberatungsstelle', 'Iqtp13db');
            else $kontaktlabel = '';
            
            $request = $GLOBALS['TYPO3_REQUEST'];
            $normalizedParams = $request->getAttribute('normalizedParams');
            $baseUri = $normalizedParams->getSiteUrl();
            
            $mailtextedit = $emailBody;
            $linktitleeditregistration = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('linktitleeditregistration', 'Iqtp13db');                        
            
            $variables = array(
                'teilnehmer' => $teilnehmer,
                'anrede' => $anrede . $teilnehmer->getVorname(). ' ' . $teilnehmer->getNachname() . ',',
                'mailtextedit' => $mailtextedit,
                'linktitleeditregistration' => $linktitleeditregistration,
                'datenberatungsstelle' => $datenberatungsstelle,
                'kontaktlabel' => $kontaktlabel,
                'logolink' => $this->settings['logolink'],
                'anmeldeditseite' => $this->settings['anmeldeditseite'],
                'baseurl' => $baseUri
            );
            
            $emailview = GeneralUtility::makeInstance('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
            $emailview->setRequest($this->request);
            
            $teilnehmer->setEditexternsent(new \DateTime);
            $teilnehmer->setNeuedokumente(0);
            $teilnehmer->setAnzloginfehlgeschlagen(0);
            $this->teilnehmerRepository->update($teilnehmer);
            
            $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
            $this->generalhelper->sendTemplateEmail(array($recipient), array($bcc), array($sender), $subject, $templateName, $variables, $emailview, $this->uriBuilder, $extbaseFrameworkConfiguration);
            
            $this->addFlashMessage('E-Mail zum nachträglichen Bearbeiten an '.$recipient.' versendet.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
            
            return $this->redirect($valArray['calleraction'], 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer, 'callerpage' => $valArray['callerpage'] ?? '1', 'searchparams' => $searchparams ?? ''));
        }
    }   
       
    
    /**
     * action mail4datenblatt
     * E-Mail mit Link und QR-Code zu Datenblatt RS senden
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    /*
    public function mail4datenblattAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        $sender = $this->settings['sender'];
        if($sender == '') {
            $this->addFlashMessage('Error 101 in mail4datenblatt.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            return $this->redirect($valArray['calleraction'], 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
        } else {
            $recipient = $teilnehmer->getEmail();
            if($recipient == '') {
                $this->addFlashMessage('Keine E-Mail-Adresse eingetragen.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
                return $this->redirect($valArray['calleraction'], 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
            }
            if($teilnehmer->getGebdat() == '') {
                $this->addFlashMessage('Kein Geburtsdatum eingetragen, externer Login nicht möglich. Bitte Geburtsdatum eintragen.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
                return $this->redirect($valArray['calleraction'], 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
            }
            
            $templateName = 'MailDatenblatt';
            $subject = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('subjecteditextern', 'Iqtp13db')." - UID: ".$teilnehmer->getUid();
            $anrede = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('anredemail', 'Iqtp13db');
            $mailtext = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mailtextdatenblatt', 'Iqtp13db');
            $zugewieseneberatungsstelle = $this->userGroupRepository->findBeratungsstellebyNiqbid($this->settings['beraterstoragepid'], $teilnehmer->getNiqidberatungsstelle());
            $datenberatungsstelle = $zugewieseneberatungsstelle != NULL ? $zugewieseneberatungsstelle[0]->getDescription() : '';
            if($datenberatungsstelle != '') $kontaktlabel = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('kontaktberatungsstelle', 'Iqtp13db');
            else $kontaktlabel = '';            
                        
            //$datenblattdokument =
            
            $storage = $this->generalhelper->getTP13Storage($this->storageRepository->findAll());
            $beratenepath = $dokument->getPfad();
            $tmpName = $dokument->getName();
            $targetfile = $storage->getFile($beratenepath . $tmpName);
            
            // Token generieren
            $token = $this->downloadTokenHelper->generateToken(
                $targetfile->getUid(),
                $dokument->getUid(),
                $teilnehmer->getUid()
                );
            
            // Redirect zur eID-Download-URL mit Token
            $downloadUrl = $this->uriBuilder->reset()
            ->setCreateAbsoluteUri(true)
            ->buildFrontendUri() . '?eID=iqtp13db_download&token=' . $token;
            $qrBase64 = $this->qrCodeGenerator->generateBase64Png($downloadUrl, QRCodeGenerator::ECC_M, 10, 4);
            // Alternativ SVG-String (direkt inline einbettbar)
            //$qrSvg = $this->qrCodeGenerator->generateSvg($data, QRCodeGenerator::ECC_M, 4);
                        
            $variables = array(
                'teilnehmer' => $teilnehmer,
                'anrede' => $anrede . $teilnehmer->getVorname(). ' ' . $teilnehmer->getNachname() . ',',
                'mailtext' => $mailtext,
                'datenblattdokumentuid' => $datenblattdokument->getUid(),
                'datenberatungsstelle' => $datenberatungsstelle,
                'kontaktlabel' => $kontaktlabel,
                'logolink' => $this->settings['logolink'],
                'anmeldeditseite' => $this->settings['anmeldeditseite'],
                'qrBase64' => $qrBase64 ?? ''
            );
            
            $emailview = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
            $emailview->setRequest($this->request);
            
            $teilnehmer->setAnzloginfehlgeschlagen(0);
            $this->teilnehmerRepository->update($teilnehmer);
            
            $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
            $this->generalhelper->sendTemplateEmail(array($recipient), array($bcc), array($sender), $subject, $templateName, $variables, $emailview, $this->uriBuilder, $extbaseFrameworkConfiguration);
            
            $this->addFlashMessage('E-Mail zum nachträglichen Bearbeiten an '.$recipient.' versendet.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
            
            return $this->redirect($valArray['calleraction'], 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer, 'callerpage' => $valArray['callerpage'] ?? '1', 'searchparams' => $searchparams ?? ''));
        }    
    }  
    */

    /**
     * action sendtoarchiv
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function sendtoarchivAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        if($teilnehmer->getVerificationDate() == 0) {
            $this->addFlashMessage('FEHLER: Einwilligung noch nicht eingeholt.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
        } else {
            $teilnehmer->setBeratungsstatus(4);
            
            $this->teilnehmerRepository->update($teilnehmer);
            // Daten sofort in die Datenbank schreiben
            $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
            
            $this->addFlashMessage($teilnehmer->getNachname().', '.$teilnehmer->getVorname().' (UID: '.$teilnehmer->getUid().') archiviert.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
        }
        
        return $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage'] ?? '1'), null);
    }
    
    /**
     * action unarchive
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function unarchiveAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        if($teilnehmer->getErstberatungabgeschlossen() == "") $teilnehmer->setBeratungsstatus(2);
        else $teilnehmer->setBeratungsstatus(3);
        
        $this->teilnehmerRepository->update($teilnehmer);
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        $this->addFlashMessage($teilnehmer->getNachname().', '.$teilnehmer->getVorname().' (UID: '.$teilnehmer->getUid().') zurück aus Archiv in Modul Erstberatung verschoben.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
    
        return $this->redirect($valArray['calleraction'], $valArray['callercontroller'], null, array('callerpage' => $valArray['callerpage'] ?? '1'), null);
    }
    
    /**
     * action savedatenblattpdf
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("teilnehmer")
     * @return void
     */
    public function savedatenblattpdfAction(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        if(array_key_exists("searchparams", $valArray)) {
            $searchparams = $valArray['searchparams'];
        }
        
        // MPDF per composer einbinden - wenn nicht vorhanden, dann s.u.
        $mpdfComposer = Environment::getConfigPath() . '/ext/vendor/autoload.php';
        if (file_exists($mpdfComposer)) {
            require_once($mpdfComposer);
        } else {
            // MPDF nicht per composer eingebunden, dann prüfe, ob extension web2pdf installiert ist und binde MPDF aus der ext web2pdf ein
            $mpdfAutoload = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('web2pdf') . 'Resources/Private/Libraries/vendor/autoload.php';
            if (file_exists($mpdfAutoload)) {
                require_once($mpdfAutoload);
            } else {
                // PDF erstellen nicht möglich
                $this->addFlashMessage('Datenblatt kann nicht erstellt werden, da MPDF nicht installiert. Bitte Admin kontaktieren.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
                return $this->redirect('show', 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer));
            }
        }
        
        $abschluesse = $this->abschlussRepository->findByTeilnehmer($teilnehmer);
        $dokumente = $this->dokumentRepository->findByTeilnehmer($teilnehmer);
        
        $thisdate = new DateTime();
        $zeitstempel = $thisdate->format('d.m.Y - H:i:s');
        $zeitstempel4filename = $thisdate->format('dmY-His');
        $berufeliste = $this->berufeRepository->findAllOrdered('de');
        $staaten = $this->staatenRepository->findByLangisocode('de');
               
        $this->view->assign('teilnehmer', $teilnehmer);
        $this->view->assign('abschluesse', $abschluesse);
        $this->view->assign('dokumente', $dokumente);
        $this->view->assign('berufe', $berufeliste);
        $this->view->assign('staaten', $staaten);
        
        $htmlcode = $this->view->render();
        
        $default_mpdfconfig = [
            'mode' => 'c',
            'format' => 'A4',
            'default_font_size' => 0,
            'default_font' => '',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'margin_header' => 9,
            'margin_footer' => 9,
            'orientation' => 'P',
        ];
        
        $mpdf = new \Mpdf\Mpdf($default_mpdfconfig);
        
        $stylesheet = file_get_contents(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('iqtp13db').'/Resources/Public/CSS/customtp13db.css');
        
        $mpdf->SetHeader('Datenblatt vom '.$zeitstempel.'||IQ Webapp');
        $mpdf->SetFooter('|{PAGENO}|');
        
        $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($htmlcode,\Mpdf\HTMLParserMode::HTML_BODY);
        
        $pfad = $this->generalhelper->createFolder($teilnehmer, $this->storageRepository->findAll());
        $filename = 'DB-' .$this->generalhelper->sanitizeFileFolderName($teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid()). '_' . $zeitstempel4filename. '.pdf';
        $storage = $this->generalhelper->getTP13Storage( $this->storageRepository->findAll());
        
        $niqbid = $this->niqbid;
        $beratungsstellenfolder = $niqbid == '' ? 'Beratene' : $niqbid;
        $fullpath = $storage->getConfiguration()['basePath']. '/' .$beratungsstellenfolder. '/' .$pfad->getName().'/'. $filename;
        
        $mpdf->Output($fullpath, 'F');
        
        // ******* Als Dokument speichern, damit aus Webapp abrufbar *******
        $dbexists = $this->dokumentRepository->findByName($filename);
        
        if(count($dbexists) == 0) {
            $dokument = new \Ud\Iqtp13db\Domain\Model\Dokument();
            
            $dokument->setBeschreibung("DATENBLATT vom ".$zeitstempel);
            $dokument->setName($filename);
            $dokument->setPfad($beratungsstellenfolder. '/' .$pfad->getName().'/');
            $dokument->setTeilnehmer($teilnehmer);
            
            $this->dokumentRepository->add($dokument);
            
            //Daten sofort in die Datenbank schreiben
            $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
            
            $this->addFlashMessage('Datenblatt wurde in '.$pfad->getIdentifier().' erstellt.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
            
        } else {
            $this->addFlashMessage('Datenblatt mit diesem Zeitstempel schon vorhanden.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
        }
        //********************************************************************
        
        return $this->redirect('show', 'Backend', 'Iqtp13db', array('teilnehmer' => $teilnehmer, 'callerpage' => $valArray['callerpage'] ?? '1', 'showdokumente' => '1', 'searchparams' => $searchparams ?? ''));
    }
    
    /**
     * action saveAVpdf
     *
     * @return void
     */
    public function saveAVpdfAction(): ResponseInterface
    {
        $valArray = $this->request->getArguments();
        
        // MPDF per composer einbinden - wenn nicht vorhanden, dann s.u.
        $mpdfComposer = Environment::getConfigPath() . '/ext/vendor/autoload.php';
        if (file_exists($mpdfComposer)) {
            //require_once __DIR__ . '/vendor/autoload.php';
            require_once($mpdfComposer);
        } else {
            // MPDF nicht per composer eingebunden, dann prüfe, ob extension web2pdf installiert ist und binde MPDF aus der ext web2pdf ein
            $mpdfAutoload = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('web2pdf') . 'Resources/Private/Libraries/vendor/autoload.php';
            if (file_exists($mpdfAutoload)) {
                require_once($mpdfAutoload);
            } else {
                // PDF erstellen nicht möglich
                $this->addFlashMessage('AV kann nicht erstellt werden, da MPDF nicht installiert. Bitte Admin kontaktieren.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
                return $this->redirect('show', 'Backend', 'Iqtp13db', null);
            }
        }
        
        $thisdate = new DateTime();
        $zeitstempel = $thisdate->format('d.m.Y - H:i:s');        
        $zeitstempel4filename = $thisdate->format('dmY-His');
        
        $default_mpdfconfig = [
            'mode' => 'c',
            'format' => 'A4',
            'default_font_size' => 10.5,
            'default_font' => 'Arial',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'margin_header' => 9,
            'margin_footer' => 9,
            'orientation' => 'P',
        ];
        
        $mpdf = new \Mpdf\Mpdf($default_mpdfconfig);
        
        $sourcefile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('iqtp13db') . 'Resources/Public/' . '/IQWebapp_Auftragsverarbeitung.pdf'; // absolute path to pdf file
        $mpdf->setSourceFile($sourcefile);
        
        $mpdf->SetHeader('AV generiert am '.$zeitstempel.'||IQ Webapp zur Anerkennungs- und Qualifizierungsberatung');

        $tplIdx = $mpdf->importPage(1);
        $mpdf->useTemplate($tplIdx, 10, 10, 200);
        
        $mpdf->SetTextColor(0, 0, 0);
        $mpdf->SetXY(34, 187);
        $mpdf->WriteHTML('<b>'.$this->usergroup->getAvadresse().'</b>');
        
        $mpdf->AddPage();
        $mpdf->setSourceFile($sourcefile);
        $tplIdx = $mpdf->importPage(2);
        $mpdf->useImportedPage($tplIdx, 0, 0, 210);
        $mpdf->AddPage();
        $mpdf->setSourceFile($sourcefile);
        $tplIdx = $mpdf->importPage(3);
        $mpdf->useImportedPage($tplIdx, 0, 0, 210);
        $mpdf->AddPage();
        $mpdf->setSourceFile($sourcefile);
        $tplIdx = $mpdf->importPage(4);
        $mpdf->useImportedPage($tplIdx, 0, 0, 210);
        $mpdf->AddPage();
        $mpdf->setSourceFile($sourcefile);
        $tplIdx = $mpdf->importPage(5);
        $mpdf->useImportedPage($tplIdx, 0, 0, 210);
        $mpdf->AddPage();
        $mpdf->setSourceFile($sourcefile);
        $tplIdx = $mpdf->importPage(6);
        $mpdf->useImportedPage($tplIdx, 0, 0, 210);
        $mpdf->AddPage();
        $mpdf->setSourceFile($sourcefile);
        $tplIdx = $mpdf->importPage(7);
        $mpdf->useImportedPage($tplIdx, 0, 0, 210);
        
        $mpdf->SetTextColor(0, 0, 0);
        $mpdf->SetXY(55, 22);
        $mpdf->WriteHTML('<b>'.$thisdate->format('d.m.Y').'</b>');
        
        $niqbid = $this->niqbid;
        $filename = 'AV-IQWebapp_WHKT_' . $niqbid . '_' . $zeitstempel4filename. '.pdf';
        $storage = $this->generalhelper->getTP13Storage($this->storageRepository->findAll());
        
        $beratungsstellenfolder = $niqbid == '' ? 'Beratene' : $niqbid;
        $fullpath = $storage->getConfiguration()['basePath']. '/' .$beratungsstellenfolder. '/' . $filename;
        
        $mpdf->Output($fullpath, \Mpdf\Output\Destination::DOWNLOAD);
        $mpdf->Output($fullpath, \Mpdf\Output\Destination::FILE);
        
        die;        
    }
    
    /**
     * action editsettings
     *
     * @return void
     */
    public function editsettingsAction(): ResponseInterface {
        
        $berater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->user->user['usergroup']);
        foreach($berater as $currber) {
            $arrberater[] = $currber;
        }
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : 1;        
        $paginator = new ArrayPaginator($arrberater, $currentPage, 25);
        $pagination = new SimplePagination($paginator);
        
        $this->view->assignMultiple(
            [
                'callerpage' => $currentPage,
                'paginator' => $paginator,
                'pagination' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
                'berater' => $arrberater,
                'thisuser' => $this->user,
                'beratungsstelle' => $this->beratungsstellenname,
                'niqbid' => $this->niqbid,
                'custominfotextstart' => $this->usergroup->getCustominfotextstart() ?? '',
                'custominfotextmail'=> $this->usergroup->getCustominfotextmail() ?? '',
                'beschreibunggrauerkasten' => $this->usergroup->getDescription() ?? '',
                'anzbstellen' => $this->anzbstellen
            ]
        );
        return $this->htmlResponse();
    }
    
    /**
     * action updatesettings
     *
     * @return void
     */
    public function updatesettingsAction(): ResponseInterface {
        $valArray = $this->request->getArguments();

        $this->usergroup->setCustominfotextstart($valArray['custominfotextstart']);
        $this->usergroup->setCustominfotextmail($valArray['custominfotextmail']);
        $this->usergroup->setDescription($valArray['beschreibunggrauerkasten']);
        
        $this->userGroupRepository->update($this->usergroup);
        
        // Daten sofort in die Datenbank schreiben
        $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();
        
        $this->addFlashMessage("Einstellungen gespeichert.", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK);
                
        return (new ForwardResponse('editsettings'))->withControllerName('Backend')->withExtensionName('Iqtp13db');
        
    }
    
    /**
     * action export
     *
     * @param int $currentPage
     * @return void
     */
    public function exportAction(int $currentPage = 1): ResponseInterface
    {
        $valArray = $this->request->getArguments();

        $beraterselected = $valArray['filterberater'] ?? '%';
        $fanonym = isset($valArray['filteranonym']) ? $valArray['filteranonym'] : '';
        $filterfolgekontakte = isset($valArray['filterfolgekontakte']) ? $valArray['filterfolgekontakte'] : '';
        $bundeslandselected = $valArray['filterbundesland'] ?? $this->usergroup->getBundesland();
        $allebundeslaender = $this->userGroupRepository->findAllBundeslaender();
        $staatselected = $valArray['filterstaat'] ?? '%';
        $landkreisselected = $valArray['filterlandkreis'] ?? '%';
        $berufselected = $valArray['filterreferenzberuf'] ?? '%';
        $brancheselected = $valArray['filterbranche'] ?? '%';
        
        $arrberater = $this->getberater4Bstelle('%', TRUE);
        $arrlandkreise = array();
        $arrlandkreise = $this->ortRepository->findLandkreiseByBundesland($bundeslandselected);
        $brancheunterkat = $this->brancheRepository->findAllUnterkategorie('de');
        foreach($brancheunterkat as $branche) {
            $arrbranchen[$branche->getBrancheid()] = $branche->getTitel();
        }
        $berufeliste = $this->berufeRepository->findAllOrdered('de');
        foreach($berufeliste as $beruf) {
            $arrberufe[$beruf->getBerufid()] = $beruf->getTitel();
        }
        $staaten = $this->staatenRepository->findByLangisocode('de');
        foreach($staaten as $staat) {
            $arrstaaten[$staat->getStaatid()] = $staat->getTitel();
        }
        
        $fberatungsstatus = $valArray['filterberatungsstatus'] ?? 11;
        $bezbstatus = $this->settings['filterberatungsstatus'][$fberatungsstatus];
        
        $del = 0;
        if($fberatungsstatus == 11) {
            $type = 1;
        } elseif($fberatungsstatus == 12) {
            $type = 2;
        } elseif($fberatungsstatus == 13) {
            $type = 3;
        } elseif($fberatungsstatus == 14) {
            $type = 0;
            $del = 1;
        } else {
            $type = 1;
        }
        // **** Datumswerte berechnen ****
        $current_quarter = ceil(date('n') / 3);
        $first_day_of_this_quarter = date('d.m.Y', strtotime(date('Y').'-'.(($current_quarter*3)-2).'-01'));
        $last_day_of_this_quarter = date('d.m.Y', strtotime(date('Y').'-'.($current_quarter*3).'-'.(date("t",strtotime(date('Y').'-'.($current_quarter*3).'-01')))));
        $today = date("d.m.Y");
        
        if(isset($valArray['filtervon'])) {
            $filtervon = $valArray['filtervon'];
        } else {
            $filtervon = $first_day_of_this_quarter;
        }
        
        if(isset($valArray['filterbis'])) {
            $filterbis = $valArray['filterbis'];
        } else {
            $filterbis = $today;
        }
        // *****
        
        $anzteilnehmers = 0;
        if($filtervon != '' && $filterbis != '') {
            $teilnehmers = $this->teilnehmerRepository->search4exportTeilnehmer($type, $del, $filtervon, $filterbis, $this->niqbid, $bundeslandselected, $staatselected, $beraterselected, $landkreisselected, $berufselected, $brancheselected);
            $anzteilnehmers = count($teilnehmers);
        }
        
        // **** Starte Export und Download der Export-Datei für alle Status außer Folgeberatungen ****
        if (isset($valArray['export']) && $fberatungsstatus != '' && $fberatungsstatus != '15') {
            
            if($anzteilnehmers == 0) {
                $this->addFlashMessage("Keine Einträge, bitte Suchparameter anpassen.", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
                $anzgesamt = $anzteilnehmers;
            } else {
                $rowstn = $this->getTeilnehmerdata4Export($teilnehmers, $bundeslandselected, $arrlandkreise, $arrbranchen, $arrberufe, $arrstaaten, 'TN');
                $rowstnanonym = $this->getTeilnehmerdata4Export($teilnehmers, $bundeslandselected, $arrlandkreise, $arrbranchen, $arrberufe, $arrstaaten, 'TNANONYM');
                
                
                $tnuids = array();                
                for ($i = 0; $i < count($rowstn); $i++) {
                    $tnuids[$i] = $rowstn[$i]['uid'];
                }
                $writer = new \XLSXWriter();
                $writer->setAuthor('IQ Webapp');
                
                if($fanonym  == '1') {
                    $filename = 'export_anonym_'.$bezbstatus.'_'.date('Y-m-d_H-i', time()).'.xlsx';
                    $writer->writeSheet($rowstnanonym, 'Ratsuchende', $this->headerblattanonym);
                } else {
                    $filename = 'export_'.$bezbstatus.'_'.date('Y-m-d_H-i', time()).'.xlsx';
                    $writer->writeSheet($rowstn, 'Ratsuchende', $this->headerblattTN);
                    if($filterfolgekontakte  == '1') {
                        $folgekontakte = $this->folgekontaktRepository->fksearch4exportNew($tnuids, $filtervon, $filterbis);
                        $rowsfk = $this->getFolgekontaktdata4Export($folgekontakte);                        
                        $writer->writeSheet($rowsfk, 'Zugehörige Folgekontakte', $this->headerblattFK);
                    }
                }
                
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="'.$filename.'"');
                header('Cache-Control: max-age=0');
                $writer->writeToStdOut();
                exit;
            }
        } elseif(isset($valArray['export']) && $fberatungsstatus == '15') {
            // **** nur Folgekontakte exportieren ****
            $folgekontakte = $this->folgekontaktRepository->fksearch4export($filtervon, $filterbis, $this->niqbid, $bundeslandselected, $staatselected, $beraterselected, $landkreisselected, $berufselected, $brancheselected);
            $folgekontakteFK2025 = $this->folgekontaktRepository->fksearch4exportFK2025($filtervon, $filterbis, $this->niqbid, $bundeslandselected, $staatselected, $beraterselected, $landkreisselected, $berufselected, $brancheselected);
            $anzfolgekontakte = count($folgekontakte);
            
            if($anzfolgekontakte == 0) {
                $this->addFlashMessage("Keine Einträge, bitte Suchparameter anpassen.", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
                $anzgesamt = $anzfolgekontakte;
            } else {
                
                $rowsfk = $this->getFolgekontaktdata4Export($folgekontakte);
                $rowstnfk2025 = $this->getTeilnehmerdata4Export($folgekontakteFK2025, $bundeslandselected, $arrlandkreise, $arrbranchen, $arrberufe, $arrstaaten, 'FK25');
             
                // XLSX
                $filename = 'export_folgekontakte_'.date('Y-m-d_H-i', time()).'.xlsx';
                
                $writer = new \XLSXWriter();
                $writer->setAuthor('IQ Webapp');
                
                $writer->writeSheet($rowsfk, 'Alle Folgekontakte', $this->headerblattFK);
                $writer->writeSheet($rowstnfk2025, 'Davon Folgekontakte von Beratungen aus Förderphase 23-25', array_merge($this->headerblattFK25, $this->headerblattTN));
                
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="'.$filename.'"');
                header('Cache-Control: max-age=0');
                $writer->writeToStdOut();
                exit;
            }
        } elseif(isset($valArray['export']) && $valArray['export'] && $fberatungsstatus == '') {
            $this->addFlashMessage("Bitte Status für Export auswählen.", '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            $anzgesamt = count($teilnehmers);
        } else {
            
            // nur Folgekontakte
            if($fberatungsstatus == '15') {
                $folgekontakte = $this->folgekontaktRepository->fksearch4export($filtervon, $filterbis, $this->niqbid, $bundeslandselected, $staatselected, $beraterselected, $landkreisselected, $berufselected, $brancheselected);
                $anzgesamt = count($folgekontakte);
            } else {
                $anzgesamt = $anzteilnehmers;
            }
            
            $this->view->assignMultiple(
                [
                    'anzgesamt' => $anzgesamt,
                    'filtervon' => $filtervon,
                    'filterbis' => $filterbis,
                    'beratungsstelle' => $this->beratungsstellenname,
                    'niqbid' => $this->niqbid,
                    'allebundeslaender' => $allebundeslaender,
                    'alleberufe' => $arrberufe,
                    'allebranchen' => $arrbranchen,
                    'gewlandkreise' => $arrlandkreise,
                    'anzbstellen' => $this->anzbstellen
                ]
                );
        }        
        
        $this->view->assignMultiple(
            [
                'anzgesamt' => $anzgesamt,
                'calleraction' => 'export',
                'callercontroller' => 'Backend',
                'callerpage' => $currentPage,
                'alleberater' => $arrberater ?? '',
                'staatenarr' => $arrstaaten,
                'filteranonym' => $fanonym,
                'filterfolgekontakte' => $filterfolgekontakte,
                'filterberatungsstatus' => $fberatungsstatus,
                'filterbundesland' => $bundeslandselected,
                'filterstaat' => $staatselected,
                'filterberater' => $beraterselected,
                'filterlandkreis' => $landkreisselected,
                'filterreferenzberuf' => $berufselected,
                'filterberuf' => $berufselected,
                'filterbranche' => $brancheselected,
                'filteron' => $this->user->getKey('ses', 'filtermodus')
            ]
            );
        return $this->htmlResponse();
    }
    
    /*************************************************************************/
    /********** NO ACTION FUNCTIONS - TODO: in Hilfsklasse auslagern **********/
    /*************************************************************************/
    
    /**
     * Set Filter
     */
    function setfilter(int $type, array $searchparams, $orderby, $order, $deleted, $limit) {
        
        if (isset($searchparams['filteran'])) {
            $this->user->setKey('ses', 'fuid', $searchparams['uid'] ?? '');
            $this->user->setKey('ses', 'fname', $searchparams['name'] ?? '');
            $this->user->setKey('ses', 'fort', $searchparams['ort'] ?? '');
            $this->user->setKey('ses', 'femail', $searchparams['email'] ?? '');
            $this->user->setKey('ses', 'fberuf', $searchparams['beruf'] ?? '');
            $this->user->setKey('ses', 'fland', $searchparams['land'] ?? '');
            $this->user->setKey('ses', 'fgebdat', $searchparams['gebdat'] ?? '');
            $this->user->setKey('ses', 'fberater', $searchparams['berater'] ?? '');            
            $this->user->setKey('ses', 'fberatername', $searchparams['berater'] ?? '');            
            $this->user->setKey('ses', 'fgruppe', $searchparams['gruppe'] ?? '');
            $this->user->setKey('ses', 'fbescheid', $searchparams['bescheid'] ?? ''); // antragstellungvorher            
            $this->user->setKey('ses', 'filtermodus', '1');
            $this->user->setKey('ses', 'fallemodule', $searchparams['allemodule'] ?? ''); 
        } 
        $filtermodus = $searchparams['filtermodus'] ?? '1';
        if($filtermodus == '0') 
        {
            $this->user->setKey('ses', 'fuid', NULL);
            $this->user->setKey('ses', 'fname', NULL);
            $this->user->setKey('ses', 'fort', NULL);
            $this->user->setKey('ses', 'femail', NULL);
            $this->user->setKey('ses', 'fberuf', NULL);
            $this->user->setKey('ses', 'fland', NULL);
            $this->user->setKey('ses', 'fgebdat', NULL);
            $this->user->setKey('ses', 'fberater', NULL);
            $this->user->setKey('ses', 'fgruppe', NULL);
            $this->user->setKey('ses', 'fbescheid', NULL); // antragstellungvorher
            $this->user->setKey('ses', 'filtermodus', NULL);
            $this->user->setKey('ses', 'filterallemodule', NULL);
        }
        
        $f['uid'] = $this->user->getKey('ses', 'fuid');
        $f['name'] = preg_replace('/\s+/', ' ', trim($this->user->getKey('ses', 'fname')));
        $f['ort'] = preg_replace('/\s+/', ' ', trim($this->user->getKey('ses', 'fort')));
        $f['email'] = preg_replace('/\s+/', ' ', trim($this->user->getKey('ses', 'femail')));
        $f['beruf'] = preg_replace('/\s+/', ' ', trim($this->user->getKey('ses', 'fberuf')));
        $f['gebdat'] = $this->user->getKey('ses', 'fgebdat');
        $f['land'] = $this->user->getKey('ses', 'fland');
        $f['berater'] = $this->user->getKey('ses', 'fberater');
        $f['gruppe'] = $this->user->getKey('ses', 'fgruppe');
        $f['bescheid'] = $this->user->getKey('ses', 'fbescheid'); // antragstellungvorher
        $f['allemodule'] = $this->user->getKey('ses', 'fallemodule'); 
        
        if($f['land'] == '-1000' || $f['land'] == NULL) $f['land'] = '';
        if($f['berater'] == -1 || $f['berater'] == NULL) $f['berater'] = '';
        if($f['uid'] == '' && $f['name'] == '' && $f['ort'] == '' && $f['email'] == '' && $f['beruf'] == '' && $f['gebdat'] == ''  && $f['land'] == '' && $f['berater'] == '' && $f['gruppe'] == '' && $f['bescheid'] == '') {
            if($deleted == 1) {
                $teilnehmers = $this->teilnehmerRepository->findhidden4list($orderby, $order, $this->niqbid);
            } else {               
                $teilnehmers = $this->teilnehmerRepository->findAllOrder4List($type, $orderby, $order, $this->niqbid);      
            }
        } else {
            $berufearr = $this->berufeRepository->findAllOrdered('de');
            if($limit > 0) {
                $teilnehmers = $this->teilnehmerRepository->searchTeilnehmer($type, $f, $deleted, $this->niqbid, $berufearr, $orderby, $order, $this->usergroup, $limit);
            } else {
                $teilnehmers = $this->teilnehmerRepository->searchTeilnehmer($type, $f, $deleted, $this->niqbid, $berufearr, $orderby, $order, $this->usergroup, $limit);
            }            
            $this->view->assign('filteruid', $f['uid']);
            $this->view->assign('filtername', $f['name']);
            $this->view->assign('filterort', $f['ort']);
            $this->view->assign('filteremail', $f['email']);
            $this->view->assign('filterberuf', $f['beruf']);
            $this->view->assign('filtergebdat', $f['gebdat']);
            $this->view->assign('filterland', $f['land']);
            if($f['land'] != '') {
                $land = $this->staatenRepository->findStaatname($f['land']);                
                $this->view->assign('filterlandname', $land[0]->getTitel());
            }
            $this->view->assign('filterberater', $f['berater']);
            if($f['berater'] != '' && $f['berater'] != 0) {
                $berater = $this->beraterRepository->findBerater4Search($this->settings['beraterstoragepid'], $f['berater']);
                $this->view->assign('filterberatername', $berater->getUsername());
            } elseif($f['berater'] == 0) {
                $this->view->assign('filterberatername', '- nicht zugeordnet -');
            }
            $this->view->assign('filtergruppe', $f['gruppe']);
            $this->view->assign('filterbescheid', $f['bescheid']); // antragstellungvorher
            $this->view->assign('filteron', $this->user->getKey('ses', 'filtermodus'));
            $this->view->assign('filterallemodule', $f['allemodule']);
        }
        
        return $teilnehmers;
    }
    
    /**
     * createHistory
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @param string $property
     * @return void
     */
    public function createHistory(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer, $property)
    {
        if($teilnehmer->_isDirty($property)) {
            $history = new \Ud\Iqtp13db\Domain\Model\Historie();
            $berater = $this->beraterRepository->findAllBerater($this->settings['beraterstoragepid']);
            foreach($berater as $thisberater) {
                if($this->user->user['username'] == $thisberater->getUsername()) $history->setBerater($thisberater);
            }
            
            $history->setTeilnehmer($teilnehmer);
            $history->setProperty($property);
            
            $oldvalue = $teilnehmer->_getCleanProperty($property) ?? '';
            $newvalue = $teilnehmer->_getProperty($property) ?? '';
            
            $staaten = $this->staatenRepository->findByLangisocode('de');
            foreach($staaten as $staat) {
                $staatenarr[$staat->getStaatid()] = $staat->getTitel();
            }
            
            if($property == 'geburtsland' || $property == 'ersteStaatsangehoerigkeit' || $property == 'zweiteStaatsangehoerigkeit' || $property == 'wohnsitzNeinIn') $newvalue = $staatenarr[$newvalue];
            if($property == 'geschlecht') {
                if($newvalue == 2) $newvalue = 'männlich';
                if($newvalue == 1) $newvalue = 'weiblich';
                if($newvalue == 3) $newvalue = 'divers';
            }
            if($property == 'wohnsitzDeutschland' || $property == 'deutschkenntnisse' || $property == 'leistungsbezugjanein' || $property == 'einwAnerkstelle' || $property == 'einwPerson') {
                if($newvalue == 1) $newvalue = 'ja';
                if($newvalue == 2) $newvalue = 'nein';
            }
            if($property == 'aufenthaltsstatus') $newvalue = $this->settings['aufenthaltsstatus'][$newvalue];
            if($property == 'zertifikatSprachniveau') $newvalue = $this->settings['zertifikatlevel'][$newvalue];
            if($property == 'erwerbsstatus') $newvalue = $this->settings['erwerbsstatus'][$newvalue];
            if($property == 'leistungsbezug') $newvalue = $this->settings['leistungsbezug'][$newvalue];
            
            if($property == 'nameBeratungsstelle') $newvalue = $this->settings['beratungsstelle'][$newvalue];
            
            if($property == 'berater') {
                if($newvalue == 0) {
                    $newvalue = '-';
                } else {
                    $berater = $this->beraterRepository->findBerater4Search($this->settings['beraterstoragepid'], $newvalue);                    
                    $newvalue = $berater ? $berater->getUsername() : '?';
                }
            }            
                 
            if($oldvalue == '-1000') $oldvalue = '-';
            if($oldvalue == '-1') $oldvalue = 'k.A.';
            
            if($newvalue == '-1000') $newvalue = '-';
            if($newvalue == '-1') $newvalue = 'k.A.';
                            
            $history->setOldvalue($oldvalue);
            $history->setNewvalue($newvalue);
            
            $this->historieRepository->add($history);
        }
    }
         
    
    /**
     * Check Beratungsstatus
     *
     * Beratungsstatus: 0 = angemeldet, 1 = Anmeldung bestätigt, 2 = Erstberatung Start, 3 = Erstberatung abgeschlossen, 4 = Archiviert (NIQ erfasst), 99 = Anmeldung nicht abgesendet
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer     
     * @return int
     */
    public function checkberatungsstatus(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer) {
        if($teilnehmer != NULL) {
            if($teilnehmer->getVerificationDate() == 0) {
                return 0;
            } else {
                if($teilnehmer->getBeratungsstatus() == 4) return 4;
                
                if($teilnehmer->getVerificationDate() > 0 && !$this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum()) && !$this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen())) return 1;
                
                if($teilnehmer->getVerificationDate() > 0 && $this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum()) && !$this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen())) return 2;
                
                if($teilnehmer->getVerificationDate() > 0 && $this->generalhelper->validateDateYmd($teilnehmer->getBeratungdatum()) && $this->generalhelper->validateDateYmd($teilnehmer->getErstberatungabgeschlossen())) return 3;
            }
        }
        return 999;
    }
    
    /**
     * Get selected IDs from session
     */
    private function getSelectedIdsFromSession(): array
    {
        $sessionData = $this->request->getAttribute('frontend.user')->getSessionData('selectedItemIds');        
        return is_array($sessionData) ? $sessionData : [];
    }
    
    /**
     * Save selected IDs to session
     */
    private function saveSelectedIdsToSession(array $selectedIds): void
    {
        $this->request->getAttribute('frontend.user')->setAndSaveSessionData('selectedItemIds', $selectedIds);
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
    protected function getErrorFlashMessage(): string {
        return FALSE;
    }
    
    protected function getberater4Bstelle($bundeslandselected, $mitnichtzugeordnet) {
        
        // ************ Start - Beraterarray bestimmen *****************
        $arrberater = array();
        if($mitnichtzugeordnet) $arrberater[0] = '- nicht zugeordnet -';
        if($this->niqbid == '12345' || intval($this->niqbid) < 999) { // Admin
            $usergroups4bundesland = $this->userGroupRepository->findByBundesland($bundeslandselected ?? '%');
            foreach($usergroups4bundesland as $ug) {
                $ugberater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $ug);
                foreach($ugberater as $currber) {
                    $arrberater[$currber->getUid()] = $currber->getUsername();
                }
            }
        } else {
            $berater = $this->beraterRepository->findBerater4Group($this->settings['beraterstoragepid'], $this->user->user['usergroup']);
            foreach($berater as $currber) {
                $arrberater[$currber->getUid()] = $currber->getUsername();
            }
        }
        asort($arrberater);
        
        return $arrberater;
        // ***************** Ende - Beraterarray bestimmen *****************
        
    }
    
    protected function getFolgekontaktdata4Export($tnarrayfromrepo) {
        $arrberatungsartfk = $this->settings['beratungsformfolgeberatung'];
        $arrberatungsartfk[0] = 'keine Angabe';
        $arrberatungsartfk[-1000] = '-';

        $rowsfk = array();
        $fkcnt = 0;
        foreach($tnarrayfromrepo as $fk) {     
            $rowsfk[$fkcnt] = array();
            $rowsfk[$fkcnt]['fkuid']  = $fk['uid'];            
            $rowsfk[$fkcnt]['fknachname'] = $fk['nachname'];
            $rowsfk[$fkcnt]['fkvorname'] =$fk['vorname'];
            $rowsfk[$fkcnt]['fkdatum']  = $fk['datum'];
            $berater = $this->beraterRepository->findByUid($fk['berater']);
            if($berater != NULL) $rowsfk[$fkcnt]['fkberater'] = $berater->getUsername();
            else $rowsfk[$fkcnt]['fkberater'] = '-';
            $rowsfk[$fkcnt]['fknotizen'] = $fk['notizen'];
            $arrtnberatungsart = explode(",", $fk['beratungsform']);
            $stringberatungsart = '';
            foreach ($arrtnberatungsart as $atn) $stringberatungsart .= $atn == '' ? '-;' : $arrberatungsartfk[$atn].";";
            $rowsfk[$fkcnt]['fkberatungsform'] = $stringberatungsart;
            $rowsfk[$fkcnt]['fkberatungsdauer'] = $fk['beratungsdauer'];
            
            $fkcnt++;
        }
        
        return $rowsfk;
    }
        
    protected function getTeilnehmerdata4Export($tnarrayfromrepo, $bundeslandselected, $arrlandkreise, $arrbranchen, $arrberufe, $arrstaaten, $type) {
         
        // ************ Start - Beraterarray bestimmen *****************
        $arrberater = $this->getberater4Bstelle($bundeslandselected, TRUE);
        
        // **** Variablen vorbelegen ****
        $arrjanein = array(0 => '', 1 => 'ja', 2 => 'nein', 3 => 'keine Angabe');
        $arrerwerbsstatus = $this->settings['erwerbsstatus'];
        $arrleistungsbezug = $this->settings['leistungsbezug'];
        $arrleistungsbezug[0] = '';
        
        $orte = $this->ortRepository->findByBundesland($bundeslandselected);
        $arrorte = array();
        foreach($orte as $ort) {
            $arrorte[$ort->getPlz()] = $ort->getLandkreis();
        }
        
        $arraufenthaltsstatus = $this->settings['aufenthaltsstatus'];
        $arrberatungsart = $this->settings['beratungsart'];
        $arrberatungsart[0] = 'keine Angabe';
        $arrberatungsart[-1000] = '-';
        $arrberatungsartfk = $this->settings['beratungsformfolgeberatung'];
        $arrberatungsartfk[0] = 'keine Angabe';
        $arrberatungsartfk[-1000] = '-';
        $arrberufserfahrung = $this->settings['berufserfahrung'];
        
        $arranerkennungsberatung = $this->settings['anerkennungsberatung'];
        $arrqualifizierungsberatung = $this->settings['qualifizierungsberatung'];
        $arrberatungsstelle = $this->settings['beratungsstelle'];
        
        $arrzertifikatlevel = $this->settings['zertifikatlevel'];
        
        $arrabschlussart = $this->settings['abschlussart'];
        $arrantragstellungerfolgt = $this->settings['antragstellungerfolgt'];
        
        
        $rows = array();
        $rowsanonym = array();
        $stringberatungsart = '';
        foreach($tnarrayfromrepo as $x => $tn) {
            $deutschkenntnisse = $tn['deutschkenntnisse'];
            if($deutschkenntnisse == 1) $deutschkenntnisse = 'ja';
            if($deutschkenntnisse == 2) $deutschkenntnisse = 'nein';
            if($deutschkenntnisse == -1) $deutschkenntnisse = 'k.a.';
            $geschlecht = $tn['geschlecht'];
            if($geschlecht == 1) $geschlecht = 'w';
            if($geschlecht == 2) $geschlecht = 'm';
            if($geschlecht == 3) $geschlecht = 'd';
            if($type == 'FK25') {
                //$rows[$x]['fkuid'] = $tn['fkuid'];                
                $rows[$x]['fkdatum'] = $tn['fkdatum'];
                $berater = $this->beraterRepository->findByUid($tn['fkberater']);
                if($berater != NULL) $rows[$x]['fkberater'] = $berater->getUsername();
                else $rows[$x]['fkberater'] = '-';
                $rows[$x]['fknotizen'] = $tn['fknotizen'];                                
                $arrtnberatungsart = explode(",", $tn['fkberatungsform']);
                foreach ($arrtnberatungsart as $atn) $stringberatungsart .= $atn == '' ? '-;' : $arrberatungsartfk[$atn].";";
                $rows[$x]['fkberatungsform'] = $stringberatungsart;
            }
            if($type == 'TNANONYM') {
                $rowsanonym[$x]['verificationDate'] = date('d.m.Y H:i:s', $tn['verification_date']);
                $rowsanonym[$x]['PLZ'] = $tn['plz'];
                $rowsanonym[$x]['Ort'] = $tn['ort'];
                $rowsanonym[$x]['Lebensalter'] = $tn['lebensalter'];
                $rowsanonym[$x]['ErsteStaatsangehoerigkeit'] = $tn['erste_staatsangehoerigkeit'] == '' ? '-' : $arrstaaten[$tn['erste_staatsangehoerigkeit']];
                $rowsanonym[$x]['ZweiteStaatsangehoerigkeit'] = $tn['zweite_staatsangehoerigkeit'] == '' ? '-' : $arrstaaten[$tn['zweite_staatsangehoerigkeit']];
                $rowsanonym[$x]['Landkreis'] = (preg_match("/[0-9]{5}/", trim($tn['plz'])) && array_key_exists(trim($tn['plz']), $arrorte)) ? $arrorte[trim($tn['plz'])] : '-';
                $rowsanonym[$x]['Einreisejahr'] = $tn['einreisejahr'];
                $rowsanonym[$x]['Deutschkenntnisse'] = $deutschkenntnisse ?? '';
                $rowsanonym[$x]['ZertifikatSprachniveau'] = $tn['zertifikat_sprachniveau'] == '' ? '-' : $arrzertifikatlevel[$tn['zertifikat_sprachniveau']];
                $rowsanonym[$x]['Geburtsland'] = $tn['geburtsland'] == '' ? '-' : $arrstaaten[$tn['geburtsland']];
                $rowsanonym[$x]['Geschlecht'] = $geschlecht;
                $rowsanonym[$x]['AnzFolgekontakte'] = $tn['anzahl_folgekontakte'];
                $rowsanonym[$x]['erstberatungabgeschlossen'] = $tn['erstberatungabgeschlossen'];
            } else {
                $rows[$x]['uid'] = $tn['uid'];
                $rows[$x]['verificationDate'] = date('d.m.Y H:i:s', $tn['verification_date']);
                $rows[$x]['Nachname'] = $tn['nachname'];
                $rows[$x]['Vorname'] = $tn['vorname'];
                $rows[$x]['Strasse'] = $tn['strasse'];
                $rows[$x]['PLZ'] = $tn['plz'];
                $rows[$x]['Ort'] = $tn['ort'];
                $rows[$x]['Email'] = $tn['email'];
                $rows[$x]['Telefon'] = $tn['telefon'];
                $rows[$x]['Geburtsdatum'] = $tn['gebdat'];
                $rows[$x]['Lebensalter'] = $tn['lebensalter'];
                $rows[$x]['ErsteStaatsangehoerigkeit'] = $tn['erste_staatsangehoerigkeit'] == '' ? '-' : $arrstaaten[$tn['erste_staatsangehoerigkeit']];
                $rows[$x]['ZweiteStaatsangehoerigkeit'] = $tn['zweite_staatsangehoerigkeit'] == '' ? '-' : $arrstaaten[$tn['zweite_staatsangehoerigkeit']];
                
                $wohnsitzdeutschland = $tn['wohnsitz_deutschland'];
                if($wohnsitzdeutschland == 1) $wohnsitzdeutschland = 'ja';
                if($wohnsitzdeutschland == 2) $wohnsitzdeutschland = 'nein';
                if($wohnsitzdeutschland == -1) $wohnsitzdeutschland = 'k.a.';
                $rows[$x]['WohnsitzDeutschland'] = $wohnsitzdeutschland ?? '';
                
                $rows[$x]['Landkreis'] = (preg_match("/[0-9]{5}/", trim($tn['plz'])) && array_key_exists(trim($tn['plz']), $arrorte)) ? $arrorte[trim($tn['plz'])] : '-';
                $rows[$x]['Einreisejahr'] = $tn['einreisejahr'];
                $wohnsitzneinin = $tn['wohnsitz_nein_in'];
                $rows[$x]['WohnsitzNeinIn'] = $wohnsitzneinin == '' ? '-' : $arrstaaten[$wohnsitzneinin];
                $rows[$x]['Deutschkenntnisse'] = $deutschkenntnisse ?? '';
                $rows[$x]['ZertifikatSprachniveau'] = $tn['zertifikat_sprachniveau'] == '' ? '-' : $arrzertifikatlevel[$tn['zertifikat_sprachniveau']];
                
                // noch nicht implementiert: $rows[$x]['WeitereSprachkenntnisse'] = $tn['weiteresprachkenntnisse'];
                $rows[$x]['Sonstigerstatus'] = $tn['sonstigerstatus'];
                
                $tnerwerbsstatus = $tn['erwerbsstatus'];
                $rows[$x]['erwerbsstatus'] = $tnerwerbsstatus == 0 ? '-' : $arrerwerbsstatus[$tnerwerbsstatus];
                
                $tnleistungsbezugjanein = $tn['leistungsbezugjanein'];
                $rows[$x]['Leistungsbezugjanein'] = $tnleistungsbezugjanein == 0 ? '-' : $arrjanein[$tnleistungsbezugjanein];
                
                $tnleistungsbezug = $tn['leistungsbezug'];
                $rows[$x]['Leistungsbezug'] = ($tnleistungsbezug == '' || $tnleistungsbezug == 0) ? '-' : $arrleistungsbezug[$tnleistungsbezug];
                
                $rows[$x]['Geburtsland'] = $tn['geburtsland'] == '' ? '-' : $arrstaaten[$tn['geburtsland']];
                
                $tnaufenthaltsstatus = $tn['aufenthaltsstatus'];
                $rows[$x]['aufenthaltsstatus'] = $tnaufenthaltsstatus == 0 ? '-' : $arraufenthaltsstatus[$tnaufenthaltsstatus];
                
                $rows[$x]['Geschlecht'] = $geschlecht;
                
                $rows[$x]['notizen'] = $tn['notizen'];
                
                $beraterid = $tn['berater'];
                $rows[$x]['Beraterin'] = $arrberater[$beraterid] ?? '-';
                
                $stringberatungsart = '';                
                $arrtnberatungsart = explode(",", $tn['beratungsart']);                
                foreach ($arrtnberatungsart as $atn) $stringberatungsart .= $atn == '' ? '-;' : $arrberatungsart[$atn].";";
                $rows[$x]['beratungsart'] = $stringberatungsart;
                
                $rows[$x]['beratungsort'] = $tn['beratungsort'];
                
                $stringanerkennungsberatung = '';
                $arrtnanerkennungsberatung = explode(",", $tn['anerkennungsberatung']);
                foreach ($arrtnanerkennungsberatung as $atn) $stringanerkennungsberatung .= $atn == '' ? '-;' : $arranerkennungsberatung[$atn].";";
                $rows[$x]['anerkennungsberatung'] = $stringanerkennungsberatung;
                
                $stringqualifizierungsberatung = '';
                $arrtnqualifizierungsberatung = explode(",", $tn['qualifizierungsberatung']);
                if(is_array($arrtnqualifizierungsberatung)) {
                    foreach ($arrtnqualifizierungsberatung as $atn) $stringqualifizierungsberatung .= $atn == '' ? '-;' : $arrqualifizierungsberatung[$atn].";";
                } else {
                    $stringqualifizierungsberatung = $tn['qualifizierungsberatung'];
                }
                $rows[$x]['qualifizierungsberatung'] = $stringqualifizierungsberatung;
                
                $tnnameberatungsstelle = $tn['name_beratungsstelle'];
                $rows[$x]['nameberatungsstelle'] = $tnnameberatungsstelle == '' ? '-' : $arrberatungsstelle[$tnnameberatungsstelle];
                
                $rows[$x]['beratungnotizen'] = $tn['beratungnotizen'];
                $rows[$x]['beratungzuschulabschluss'] = $tn['beratungzu'] == 1 ? 'ja' : 'nein';
                
                $rows[$x]['AnzFolgekontakte'] = $tn['anzahl_folgekontakte'];
                $rows[$x]['sumDauerFolgekontakte'] = $tn['gesamt_beratungsdauer'];
                
                $rows[$x]['kooperationgruppe'] = $tn['kooperationgruppe'];
                $rows[$x]['beratungsdauer'] = $tn['beratungsdauer'];
                $rows[$x]['beratungdatum'] = $tn['beratungdatum'];
                $rows[$x]['erstberatungabgeschlossen'] = $tn['erstberatungabgeschlossen'];
                $einwilligunginfo = $tn['einwilligunginfo'];
                if($einwilligunginfo == 1) $rows[$x]['einwilligunginfo'] = 'ja';
                else $rows[$x]['einwilligunginfo'] = 'nein';
            }
            
            for($y = 1; $y <= 4; $y++) {
                $abschlussart = $tn['abschluss'.$y.'_art'];
                if(strstr($abschlussart, ',')) $abschlussart = '2';
                
                if($type == 'TNANONYM') {
                    $rowsanonym[$x]['Abschluss'.$y.' Referenzberufzugewiesen'] = $tn['abschluss'.$y.'_beruf'];
                    $rowsanonym[$x]['Abschluss'.$y.' Abschlussart'] = $abschlussart == '' ? '-' : $arrabschlussart[$abschlussart];
                    $rowsanonym[$x]['Abschluss'.$y.' Erwerbsland'] = $tn['abschluss'.$y.'_erwerbsland'];
                    $rowsanonym[$x]['Abschluss'.$y.' DeutscherReferenzberuf'] = $tn['abschluss'.$y.'_refberuf'];
                } else {
                    $rows[$x]['Abschluss'.$y.' Referenzberufzugewiesen'] = $tn['abschluss'.$y.'_beruf'];
                    $rows[$x]['Abschluss'.$y.' SonstigerBeruf'] = $tn['abschluss'.$y.'_sonstigerberuf'];
                    $rows[$x]['Abschluss'.$y.' NichtreglementierterBeruf'] = $tn['abschluss'.$y.'_nregberuf'];
                    $rows[$x]['Abschluss'.$y.' Abschlussart'] = $abschlussart == '' ? '-' : $arrabschlussart[$abschlussart];
                    $rows[$x]['Abschluss'.$y.' Branche'] = $tn['abschluss'.$y.'_branche'];
                    $rows[$x]['Abschluss'.$y.' Erwerbsland'] = $tn['abschluss'.$y.'_erwerbsland'];
                    $rows[$x]['Abschluss'.$y.' Abschlussjahr'] = $tn['abschluss'.$y.'_jahr'];
                    $rows[$x]['Abschluss'.$y.' Ausbildungsort'] = $tn['abschluss'.$y.'_ausbildungsort'];
                    $rows[$x]['Abschluss'.$y.' Abschluss'] = $tn['abschluss'.$y.'_abschluss'];
                    $rows[$x]['Abschluss'.$y.' DauerBerufsausbildung'] = $tn['abschluss'.$y.'_dauer'];
                    $rows[$x]['Abschluss'.$y.' Ausbildungsinstitution'] = $tn['abschluss'.$y.'_institution'];
                    $tnabschlussberufserfahrung = $tn['abschluss'.$y.'_berufserfahrung'] ?? '';
                    $rows[$x]['Abschluss'.$y.' Berufserfahrung'] = $tnabschlussberufserfahrung  == '' ? '' : $arrberufserfahrung[$tnabschlussberufserfahrung];
                    $rows[$x]['Abschluss'.$y.' Wunschberuf'] = $tn['abschluss'.$y.'_wunschberuf'];
                    $rows[$x]['Abschluss'.$y.' DeutscherReferenzberuf'] = $tn['abschluss'.$y.'_refberuf'];
                    $abantragstellungerfolgt = $tn['abschluss'.$y.'_antrag'] ?? '';
                    $rows[$x]['Abschluss'.$y.' Antragstellungerfolgt'] = $abantragstellungerfolgt == '' ? '' : $arrantragstellungerfolgt[$abantragstellungerfolgt];
                }                
            }
        }
        
        if($type == 'TNANONYM') {
            return $rowsanonym;            
        } else {
            return $rows;
        }
    }
    
}
