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
        $querySettings->setIgnoreEnableFields(TRUE);
        $querySettings->setEnableFieldsToBeIgnored(array('disabled', 'hidden', 'deleted'));
        
        $querySettings->setStoragePageIds(array($customStoragePid));
        $this->setDefaultQuerySettings($querySettings);

        //Now get all (only Presets)
        $queryResult = $this->findByUsergroup($usergroup);
        return $queryResult;
    }
    
    public function findBerater4Search($customStoragePid, $uid)
    {
        $query = $this->createQuery();
        
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setRespectSysLanguage(false);
        $query->getQuerySettings()->setEnableFieldsToBeIgnored(array('disabled', 'hidden', 'deleted'));        
        $query->getQuerySettings()->setStoragePageIds(array($customStoragePid));
        
        //Now get all (only Presets)
        $queryResult = $query->matching(
            $query->logicalAnd(
                $query->equals('uid', $uid),
                $query->equals('deleted', 0)
                )
            )->execute()->getFirst();
        return $queryResult;
    }
    
    // In BeraterRepository.php
    public function findByUidIgnoreDisabled($uid)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);
        $query->matching($query->equals('uid', $uid));
        
        return $query->execute()->getFirst();
    }
    
}
