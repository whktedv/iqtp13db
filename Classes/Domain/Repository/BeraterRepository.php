<?php
namespace Ud\Iqtp13db\Domain\Repository;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

/**
 * The repository for Berater
 */
class BeraterRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    public function findAllBerater($customStoragePid)
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(TRUE);
        $querySettings->setStoragePageIds(array($customStoragePid));
        $this->setDefaultQuerySettings($querySettings);
        
        // Now get all (only Presets)
        $queryResult = $this->findAll();
        return $queryResult;
    }
    
    public function findBerater4Group($customStoragePid, $usergroup)
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(TRUE);
        $querySettings->setStoragePageIds(array($customStoragePid));
        $this->setDefaultQuerySettings($querySettings);

        // Now get all (only Presets)
        $queryResult = $this->findByUsergroup($usergroup);
        return $queryResult;
    }
    
}
