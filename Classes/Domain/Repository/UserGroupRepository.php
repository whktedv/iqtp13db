<?php
namespace Ud\Iqtp13db\Domain\Repository;

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
        $query->setOrderings(
            array('bundesland' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING),
            array('title' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING)
            );        
        $query->matching($query->logicalAnd(
            $query->logicalNot($query->like('niqbid', '')),
            $query->logicalNot($query->like('title', 'AA%')) 
            ));
        
        $query = $query->execute();
        return $query;
    }
    
    public function findAllBeratungsstellenABC($customStoragePid)
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(TRUE);
        $querySettings->setStoragePageIds(array($customStoragePid));
        $this->setDefaultQuerySettings($querySettings);
        
        $query = $this->createQuery();
        $query->matching($query->logicalAnd(
            $query->logicalNot($query->like('niqbid', '')),
            $query->logicalNot($query->like('title', 'AA%'))
            ));
        $query->setOrderings(
            array('title' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING)
            );
        $query = $query->execute();
        return $query;
    }
    
    public function findBeratungsstellebyNiqbid($customStoragePid, $niqbid)
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(TRUE);
        $querySettings->setStoragePageIds(array($customStoragePid));
        $this->setDefaultQuerySettings($querySettings);
        
        $query = $this->createQuery();
        $query->matching($query->like('niqbid', $niqbid));
        $query = $query->execute();
        return $query;
    }
    
    public function getBeratungsstelle4PLZ($plz, $customStoragePid) {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(TRUE);
        $querySettings->setStoragePageIds(array($customStoragePid));
        $this->setDefaultQuerySettings($querySettings);
 
        if($plz == '' || $plz == 0) $plz = '12345';
        
        $query = $this->createQuery();
        $query->matching($query->logicalOr(
            $query->like('plzlist', '%,' . $plz . ',%'),
            $query->like('plzlist',  $plz . ',%'),
            $query->like('plzlist', '%,' . $plz),
            $query->like('plzlist', $plz)
            ));
        $query = $query->execute();
        
        return $query;
    }
    
}
