<?php
namespace Ud\Iqtp13db\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ServerRequestInterface;

use Ud\Iqtp13db\Domain\Repository\DokumentRepository;


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

    public function doksaveEidAction(ServerRequestInterface $request)
    {
        // Argumente aus dem POST-Request holen
        //alt typo3-11: $uid = GeneralUtility::trimExplode('=', GeneralUtility::_POST('dokuid'), true)[0];
        //alt typo3-11: $beschreibung = GeneralUtility::trimExplode('=', GeneralUtility::_POST('dokdescr'), true)[0] ?? '';
        
        $uid = GeneralUtility::trimExplode('=', $request->getParsedBody()['dokuid'], true)[0];
        $beschreibung = GeneralUtility::trimExplode('=', $request->getParsedBody()['dokdescr'], true)[0] ?? '';
        
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
