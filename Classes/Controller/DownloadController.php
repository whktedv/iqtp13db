<?php
namespace Ud\Iqtp13db\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Http\ResponseFactory;
use TYPO3\CMS\Core\Http\StreamFactory;
use Ud\Iqtp13db\Helper\DownloadTokenHelper;

class DownloadController
{
    public function processRequest(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $token = $queryParams['token'] ?? '';
        
        $responseFactory = GeneralUtility::makeInstance(ResponseFactory::class);
        $streamFactory = GeneralUtility::makeInstance(StreamFactory::class);
        $downloadTokenHelper = GeneralUtility::makeInstance(DownloadTokenHelper::class);
        
        // Bereinige abgelaufene Tokens
        $downloadTokenHelper->cleanupExpiredTokens();
        
        // Validiere Token
        $tokenData = $downloadTokenHelper->validateToken($token);
        
        if ($tokenData === null) {
            return $responseFactory->createResponse(403)
            ->withHeader('Content-Type', 'text/html; charset=utf-8')
            ->withBody($streamFactory->createStream(
                '<h1>Zugriff verweigert</h1><p>Der Download-Link ist ungültig oder abgelaufen.</p>'
                ));
        }
         
        try {
            $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
            $file = $resourceFactory->getFileObject($tokenData['fileUid']);
            
            if (!$file->exists()) {
                throw new \Exception('Datei nicht gefunden');
            }
            
            // Optional: Token nach Download entfernen (einmalige Nutzung)
            // $downloadTokenHelper->removeToken($token);
            
            // Response erstellen
            $response = $responseFactory->createResponse()
            ->withHeader('Content-Type', $file->getMimeType())
            ->withHeader('Content-Disposition', 'attachment; filename="' . $file->getName() . '"')
            ->withHeader('Content-Length', (string)$file->getSize())
            ->withHeader('Cache-Control', 'private, no-store, no-cache, must-revalidate')
            ->withHeader('Pragma', 'no-cache')
            ->withHeader('Expires', '0');
            
            $stream = $streamFactory->createStream($file->getContents());
            return $response->withBody($stream);
            
        } catch (\Exception $e) {
            return $responseFactory->createResponse(404)
            ->withHeader('Content-Type', 'text/html; charset=utf-8')
            ->withBody($streamFactory->createStream(
                '<h1>Fehler</h1><p>Datei nicht gefunden: ' . htmlspecialchars($e->getMessage()) . '</p>'
                ));
        }
    }
}