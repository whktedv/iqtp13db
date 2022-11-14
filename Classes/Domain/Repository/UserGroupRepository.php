<?php
namespace Ud\Iqtp13db\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

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
 * The repository for UserGroup
 */
class UserGroupRepository
{
    
    public function findAllGroups($customStoragePid)
    {
        // Get the default Settings
        $querySettings = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings');
        $querySettings->setStoragePageIds(array($customStoragePid));
        $this->setDefaultQuerySettings($querySettings);
        
        // Now get all (only Presets)
        $queryResult = $this->findAll();
        return $queryResult;
    }
       
}
