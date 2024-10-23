<?php
namespace Ud\Iqtp13db\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Ud\Iqtp13db\Domain\Repository\DokumentRepository;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class RequestController
{
    /**
     * @var DokumentRepository
     */
    protected $dokumentRepository;

    public function __construct(DokumentRepository $dokumentRepository)
    {       
        // Damit die Dependency Injection hier funktinoiert, unbedingt in die Datei Configuration/Services.yaml eintragen! Siehe "Dependency Injection" in der Typo3 Doku
        $this->dokumentRepository = $dokumentRepository;
    }

    public function doksaveEidAction()
    {
        // Argumente aus dem POST-Request holen
        $uid = GeneralUtility::trimExplode('=', GeneralUtility::_POST('dokuid'), true)[0];
        $beschreibung = GeneralUtility::trimExplode('=', GeneralUtility::_POST('dokdescr'), true)[0] ?? '';
        
        // Daten speichern
        $dokument = $this->dokumentRepository->findByUid($uid);        
        $dokument->setBeschreibung($beschreibung);
        $this->dokumentRepository->update($dokument);

        // Persistierung erzwingen
        $persistenceManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class);
        $persistenceManager->persistAll();
        
        // Antwort zurückgeben
        header('Content-Type: application/json');
        //echo json_encode(['message' => 'Beschreibung gespeichert: ' . htmlspecialchars($beschreibung)]);        
        exit;
    }
}
