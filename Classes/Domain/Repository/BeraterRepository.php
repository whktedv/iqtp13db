<?php
namespace Ud\Iqtp13db\Domain\Repository;

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
 * The repository for Berater
 */
class BeraterRepository extends \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
{
    
    public function findAllBerater($customStoragePid)
    {
        // Get the default Settings
        $querySettings = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings');
        $querySettings->setStoragePageIds(array($customStoragePid));
        $this->setDefaultQuerySettings($querySettings);
        
        // Now get all (only Presets)
        $queryResult = $this->findAll();
        return $queryResult;
    }
    
    public function findBerater4Group($customStoragePid, $usergroup)
    {
        // Get the default Settings
        $querySettings = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings');
        $querySettings->setStoragePageIds(array($customStoragePid));
        $this->setDefaultQuerySettings($querySettings);
        
        // Now get all (only Presets)
        $queryResult = $this->findByUsergroup($usergroup);
        return $queryResult;
    }
    
}
