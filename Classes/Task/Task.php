<?php
namespace Ud\Iqtp13db\Task;

use Doctrine\DBAL\DBALException;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Task\AbstractTask;


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
         
         if($del90 && $delberatung99) {
             return true;
         } else {
             return false;
         }
         // TODO: lösche Abschlüsse und Dokumente von gelöschten Teilnehmern
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
         
         $queryBuilder->delete('tx_iqtp13db_domain_model_teilnehmer')
         ->where($queryBuilder->expr()->eq('beratungsstatus', $queryBuilder->createNamedParameter('99')))
         ->andWhere($queryBuilder->expr()->lt('tstamp',$queryBuilder->createNamedParameter($yesterday, \PDO::PARAM_INT)))
         ->execute();
         
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
         
         $date90 = strtotime('-3 day');
         
         $queryBuilder->delete('tx_iqtp13db_domain_model_teilnehmer')
         ->where($queryBuilder->expr()->eq('hidden',$queryBuilder->createNamedParameter(1, \PDO::PARAM_INT)))
         ->andWhere($queryBuilder->expr()->lt('tstamp',$queryBuilder->createNamedParameter($date90, \PDO::PARAM_INT)))
         ->execute();
         
         return true;
     }
     
}
?>