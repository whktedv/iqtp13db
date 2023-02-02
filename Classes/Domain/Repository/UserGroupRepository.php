<?php
namespace Ud\Iqtp13db\Domain\Repository;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;


/**
 * The repository for UserGroup
 */
class UserGroupRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    
    public function findAllGroups($customStoragePid)
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(TRUE);
        $querySettings->setStoragePageIds(array($customStoragePid));
        $this->setDefaultQuerySettings($querySettings);
        
        // Now get all (only Presets)
        $queryResult = $this->findAll();
        return $queryResult;
    }
    
    public function findAllBeratungsstellen($customStoragePid)
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(TRUE);
        $querySettings->setStoragePageIds(array($customStoragePid));
        $this->setDefaultQuerySettings($querySettings);
        
        $query = $this->createQuery();
        $query->matching($query->logicalAnd(
            $query->logicalNot($query->like('niqbid', '12345')),
            $query->logicalNot($query->like('niqbid', ''))
            ));
        $query = $query->execute();
        return $query;
    }
    
}
