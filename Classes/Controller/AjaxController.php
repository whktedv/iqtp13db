<?php
namespace Ud\Iqtp13db\Controller;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\Response;

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
 * DokumentController
 */
class AjaxInterface extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
     
    /** @var ResponseFactoryInterface */
    private $responseFactory;

    
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }
        
    
    /**
     * action doksaveAction
     *
     * @return void
     */
    public function doksaveAction(ServerRequestInterface $request) : Response 
    {
        $response = $this->responseFactory->createResponse()
        ->withHeader('Content-Type', 'application/json; charset=utf-8');
        $response->getBody()->write(json_encode($data));
        
        DebuggerUtility::var_dump($response);
        die;
        
        return $response;  
        
    /*
        $valArray = $this->request->getArguments();
               
        if(array_key_exists('Dokument', $valArray)) {
            
            $dokid = $valArray['Dokument']['__identity'];
            $thisdok = $this->dokumentRepository->findByUid($dokid);
            $thisdok->setBeschreibung($valArray['Dokument']['beschreibung']);
            
            $this->dokumentRepository->update($thisdok);
            //Daten sofort in die Datenbank schreiben
            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
            $persistenceManager->persistAll();
        }
        */
        //$this->redirect($valArray['thisaction'], 'Backend', null, array('teilnehmer' => $teilnehmer, 'calleraction' => $valArray['calleraction'] ?? 'edit', 'callercontroller' => $valArray['callercontroller'] ?? 'Backend', 'callerpage' => $valArray['callerpage'] ?? '1', 'showdokumente' => '1'));
    }
}
    