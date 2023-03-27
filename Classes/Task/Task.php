<?php
namespace Ud\Iqtp13db\Task;

use Doctrine\DBAL\DBALException;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Task\AbstractTask;
use TYPO3\CMS\Core\Resource\StorageRepository;

class Task extends AbstractTask {
    
     /**
      * The main method of the task. Iterates through
      * the tables and calls the cleaning function
      *
      * @return bool Returns TRUE on successful execution, FALSE on error
      */
     public function execute()
     {
         $delberatung99 = $this->deleteBeratungsstatu99();
         $del90 = $this->deleteDeleted90days();
         
         $delabschl = $this->deleteDeletedAbschluesse();
         $deldoks = $this->deleteDeletedDokumente();
         $delfks = $this->deleteDeletedFolgekontakte();
         
         if($del90 && $delberatung99 && $delabschl && $deldoks && $delfks) {             
             return true;
         } else {
             return false;
         }
     }
     
     /**
      * Executes the delete-query for beratungsstatus = 99 (abgebrochene Anmeldung)
      *
      * @return bool
      */
     protected function deleteBeratungsstatu99() {
         $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_iqtp13db_domain_model_teilnehmer');
         $queryBuilder->getRestrictions()->removeAll();
         
         $yesterday = strtotime('-1 day');
         
         $queryBuilder->update('tx_iqtp13db_domain_model_teilnehmer')
         ->where($queryBuilder->expr()->eq('beratungsstatus', $queryBuilder->createNamedParameter('99')))
         ->andWhere($queryBuilder->expr()->lt('tstamp',$queryBuilder->createNamedParameter($yesterday, \PDO::PARAM_INT)))
         ->set('deleted', 1)
         ->executeStatement();
         
         return true;
     }
     
     /**
      * Executes the delete-query for the deleted table
      *
      * @return bool
      */
     protected function deleteDeleted90days() {
         $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_iqtp13db_domain_model_teilnehmer');
         $queryBuilder->getRestrictions()->removeAll();
         
         $date90 = strtotime('-90 day');
         //$date90 = strtotime('now');
         
         $queryBuilder->update('tx_iqtp13db_domain_model_teilnehmer')
         ->where($queryBuilder->expr()->eq('hidden',$queryBuilder->createNamedParameter(1, \PDO::PARAM_INT)))
         ->andWhere($queryBuilder->expr()->lt('tstamp',$queryBuilder->createNamedParameter($date90, \PDO::PARAM_INT)))
         ->set('deleted', 1)
         ->executeStatement();
         
         return true;
     }
     
     /**
      * Executes the delete-query for the deleted table
      *
      * @return bool
      */
     protected function deleteDeletedAbschluesse() {
         $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_iqtp13db_domain_model_abschluss');
         $queryBuilder->getRestrictions()->removeAll();
          
         $result = $queryBuilder->select('tx_iqtp13db_domain_model_abschluss.uid')
         ->from('tx_iqtp13db_domain_model_abschluss')
         ->leftJoin(
             'tx_iqtp13db_domain_model_abschluss',
             'tx_iqtp13db_domain_model_teilnehmer',
             't',
             $queryBuilder->expr()->eq('t.uid', $queryBuilder->quoteIdentifier('tx_iqtp13db_domain_model_abschluss.teilnehmer'))
             ) 
         ->where($queryBuilder->expr()->eq('t.deleted',$queryBuilder->createNamedParameter(1, \PDO::PARAM_INT)))
         ->executeQuery();
         
         while ($row = $result->fetchAssociative()) {
             $queryBuilder->update('tx_iqtp13db_domain_model_abschluss')
             ->where($queryBuilder->expr()->eq('uid',$queryBuilder->createNamedParameter($row['uid'], \PDO::PARAM_INT)))
             ->set('deleted', 1)
             ->executeStatement();
         }
         
         return true;
     }
     
     /**
      * Executes the delete-query for the deleted table
      *
      * @return bool
      */
     protected function deleteDeletedFolgekontakte() {
         $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_iqtp13db_domain_model_folgekontakt');
         $queryBuilder->getRestrictions()->removeAll();
         
         $result = $queryBuilder->select('tx_iqtp13db_domain_model_folgekontakt.uid')
         ->from('tx_iqtp13db_domain_model_folgekontakt')
         ->leftJoin(
             'tx_iqtp13db_domain_model_folgekontakt',
             'tx_iqtp13db_domain_model_teilnehmer',
             't',
             $queryBuilder->expr()->eq('t.uid', $queryBuilder->quoteIdentifier('tx_iqtp13db_domain_model_folgekontakt.teilnehmer'))
             )
         ->where($queryBuilder->expr()->eq('t.deleted',$queryBuilder->createNamedParameter(1, \PDO::PARAM_INT)))
         ->executeQuery();
         
         while ($row = $result->fetchAssociative()) {
             $queryBuilder->update('tx_iqtp13db_domain_model_folgekontakt')
             ->where($queryBuilder->expr()->eq('uid',$queryBuilder->createNamedParameter($row['uid'], \PDO::PARAM_INT)))
             ->set('deleted', 1)
             ->executeStatement();                
         }
             
         return true;
     }
     
     /**
      * Executes the delete-query for the deleted table
      *
      * @return bool
      */
     protected function deleteDeletedDokumente() {
         $storages = GeneralUtility::makeInstance(StorageRepository::class)->findAll();
         
         foreach ( $storages as $s ) {
             $storageObject = $s;
             $storageRecord = $storageObject->getStorageRecord ();
             
             if ($storageRecord ['name'] == 'iqwebappdata') {
                 $storage = $s;
                 break;
             }
         }
         
         $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_iqtp13db_domain_model_dokument');
         $queryBuilder->getRestrictions()->removeAll();
         
         $result = $queryBuilder->select('tx_iqtp13db_domain_model_dokument.uid','tx_iqtp13db_domain_model_dokument.pfad','tx_iqtp13db_domain_model_dokument.name')
         ->from('tx_iqtp13db_domain_model_dokument')
         ->leftJoin(
             'tx_iqtp13db_domain_model_dokument',
             'tx_iqtp13db_domain_model_teilnehmer',
             't',
             $queryBuilder->expr()->eq('t.uid', $queryBuilder->quoteIdentifier('tx_iqtp13db_domain_model_dokument.teilnehmer'))
         )
         ->where($queryBuilder->expr()->eq('t.deleted',$queryBuilder->createNamedParameter(1, \PDO::PARAM_INT)))
         ->executeQuery();
         
         while ($row = $result->fetchAssociative()) {
             $queryBuilder->update('tx_iqtp13db_domain_model_dokument')
             ->where($queryBuilder->expr()->eq('uid',$queryBuilder->createNamedParameter($row['uid'], \PDO::PARAM_INT)))
             ->set('deleted', 1)
             ->executeStatement();
             
             $fullpath = '/'.$row['pfad'].$row['name'];
             if($storage->hasFile($fullpath)) {
                $delfile = $storage->getFile($fullpath);
                $ergfi = $storage->deleteFile($delfile);
             }
                          
             if($row['pfad'] != '') {
                 $folderpath = '/'.$row['pfad'];
                 if($storage->hasFolder($folderpath)) {
                     $delfolder = $storage->getFolder($folderpath);
                     $filesinfolder = $storage->countFilesInFolder($delfolder);
                     if($filesinfolder == 0) $ergfo = $storage->deleteFolder($delfolder);                 
                 }
             }
         }
         
         return true;
     }
     
     
}
