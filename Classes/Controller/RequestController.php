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
        //echo json_encode(['message' => 'Beschreibung gespeichert: ' . htmlspecialchars($beschreibung)]);        
        exit;
    }
}
