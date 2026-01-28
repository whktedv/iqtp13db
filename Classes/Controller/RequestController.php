<?php
namespace Ud\Iqtp13db\Controller;

use \Datetime;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\ServerRequestFactory;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Log\LogManager;

use Ud\Iqtp13db\Domain\Repository\DokumentRepository;
use Ud\Iqtp13db\Domain\Repository\TeilnehmerRepository;
use Ud\Iqtp13db\Domain\Repository\GruppenberatungRepository;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
/**
 * AJAX Controller für JSON-Requests
 * Wird NICHT über Extbase, sondern direkt über eID aufgerufen
 */
class RequestController
{
    /**
     * @var DokumentRepository
     */
    protected $dokumentRepository;

    public function __construct(DokumentRepository $dokumentRepository, TeilnehmerRepository $teilnehmerRepository, GruppenberatungRepository $gruppenberatungRepository)
    {       
        // Damit die Dependency Injection hier funktinoiert, unbedingt in die Datei Configuration/Services.yaml eintragen! Siehe "Dependency Injection" in der Typo3 Doku
        $this->dokumentRepository = $dokumentRepository;
        $this->teilnehmerRepository = $teilnehmerRepository;
        $this->gruppenberatungRepository = $gruppenberatungRepository;
    }

    public function doksaveEidAction(ServerRequestInterface $request)
    {
     
        $uid = GeneralUtility::trimExplode('=', $request->getParsedBody()['dokuid'], true)[0];
        $tnfreigabe = GeneralUtility::trimExplode('=', $request->getParsedBody()['dokfreigabe'], true)[0] ?? 0;
        $beschreibung = GeneralUtility::trimExplode('=', $request->getParsedBody()['dokdescr'], true)[0] ?? '';
        
        // Daten speichern
        $dokument = $this->dokumentRepository->findByUid($uid);        
        $dokument->setBeschreibung($beschreibung);
        $dokument->setTnfreigabe($tnfreigabe);
        $this->dokumentRepository->update($dokument);

        // Persistierung erzwingen
        $persistenceManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class);
        $persistenceManager->persistAll();
        
        // Antwort zurückgeben
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Beschreibung gespeichert.']);        
        exit;
    }
    
    public function tneditlinksaveEidAction(ServerRequestInterface $request)
    {
        $uid = GeneralUtility::trimExplode('=', $request->getParsedBody()['tnuid'], true)[0];
        
        // Daten speichern
        $teilnehmer = $this->teilnehmerRepository->findByUid($uid);
        $teilnehmer->setEditexternsent(new \DateTime);
        $this->teilnehmerRepository->update($teilnehmer);
        
        // Persistierung erzwingen
        $persistenceManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class);
        $persistenceManager->persistAll();
        
        // Antwort zurückgeben
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Zeitstempel gespeichert.']);
        exit;
    }
    
    
    private function getFrontendUser(ServerRequestInterface $request): FrontendUserAuthentication
    {
        $feUser = new FrontendUserAuthentication();
        
        // **Logger setzen** (sonst $this->logger == null → debug() auf null)
        // LoggerAwareTrait via AbstractUserAuthentication [1](https://api.typo3.org/main/classes/TYPO3-CMS-Core-Authentication-AbstractUserAuthentication.html)[6](https://www.typo3lexikon.de/typo3-tutorials/core/log/)
        $logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(get_class($feUser));
        $feUser->setLogger($logger); 
        
        $feUser->start($request);
        
        // ... jetzt kannst du wie gewohnt mit setKey()/getKey() arbeiten:
        // $feUser->setKey('ses', 'my_ext_key', $data);
        // $feUser->storeSessionData();                                                   
        
        return $feUser;
        
    }
    
    
    /**
     * Toggle checkbox visibility
     */
    public function toggleCheckboxesAction(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();
        $postParams = $request->getParsedBody();
        
        $show = (bool)($postParams['show'] ?? $params['show'] ?? false);
        
        // Frontend-User holen
        $frontendUser = $this->getFrontendUser($request);
        
        if (!$show && $frontendUser) {
            $frontendUser->setAndSaveSessionData('selectedItemIds', []);
        }
        
        return new JsonResponse([
            'success' => true,
            'show' => $show,
        ]);
    }
    
    /**
     * Update selection across pages
     */
    public function updateSelectionAction(ServerRequestInterface $request): ResponseInterface
    {
        $postParams = $request->getParsedBody();
        $selectedIds = $postParams['selectedIds'] ?? [];
        
        if (!is_array($selectedIds)) {
            $selectedIds = [];
        }
        
        // Frontend-User holen
        $frontendUser = $this->getFrontendUser($request);
        
        if ($frontendUser) {
            $frontendUser->setAndSaveSessionData('selectedItemIds', $selectedIds);
        }
        
        return new JsonResponse([
            'success' => true,
            'count' => count($selectedIds),
            'selectedIds' => $selectedIds,
        ]);
    }
    
    /**
     * Add to group consultation
     */
    public function addToGroupConsultationAction(ServerRequestInterface $request): ResponseInterface
    {
        // Frontend-User holen
        $frontendUser = $this->getFrontendUser($request);
                
        if (!$frontendUser) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Keine Session verfügbar',
            ]);
        }
        
        $selectedIds = $frontendUser->getSessionData('selectedItemIds') ?? [];
        
        $postParams = $request->getParsedBody();
        $groupConsulId = $postParams['selectedGroupConsult'] ?? 0;
        
        if (empty($selectedIds)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Keine Datensätze ausgewählt',
            ]);
        }
        
        // Gruppenberatung mit ID
        $frontendUser->setAndSaveSessionData('groupConsultId', $groupConsulId);
        
        // Auswahl zurücksetzen
        $frontendUser->setAndSaveSessionData('selectedItemIds', []);
        
        // GP holen
        $gruppenberatung = $this->gruppenberatungRepository->findByUid($groupConsulId);
        
        foreach($selectedIds as $tnid) {
            $teilnehmer = $this->teilnehmerRepository->findByUid((int)$tnid);
            if ($teilnehmer === null) {
                continue; // ignorieren oder Fehler sammeln
            }
            
            // Falls du Duplikate vermeiden willst:
            $bereitsDrin = false;
            foreach ($gruppenberatung->getTeilnehmer() as $t) {
                if ($t->getUid() === $teilnehmer->getUid()) {
                    $bereitsDrin = true;
                    break;
                }
            }
            if ($bereitsDrin) {
                continue;
            }
            
            // Kapazität beachten
            //if ($gruppenberatung->istVollBelegt()) {
            //    break; // oder Fehler zurückgeben
            //}
            
            // Anhängen
            $gruppenberatung->addTeilnehmer($teilnehmer);
            
            // Wenn die Beziehung bidirektional ist (z. B. Teilnehmer hat "gruppenberatungen"),
            // solltest du dort ebenfalls die Gegenseite setzen, z. B.:
            // $teilnehmer->addGruppenberatung($gruppenberatung);
            // (nur falls dein Teilnehmer-Modell so eine Relation besitzt)
        }

        $this->gruppenberatungRepository->update($gruppenberatung);     
        
        // Persistierung erzwingen
        $persistenceManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class);
        $persistenceManager->persistAll();
                
        return new JsonResponse([
            'success' => true,
            'message' => count($selectedIds) . ' Datensatz/Datensätze zur Gruppenberatung mit ID '. $groupConsulId . ' hinzugefügt',
            'count' => count($selectedIds),
            //'anzahl' => $gruppenberatung->getAnzahlTeilnehmer(),
            //'voll' => $gruppenberatung->istVollBelegt(),           
        ]);
    }
}
