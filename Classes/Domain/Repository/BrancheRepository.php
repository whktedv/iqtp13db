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
 * The repository for Branche
 */
class BrancheRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    
    public function findAllOberkategorie($langisocode)
    {
        $query = $this->createQuery();
        $query->statement("SELECT brancheid, titel FROM `tx_iqtp13db_domain_model_branche`WHERE (brancheid % 100) = 0 AND langisocode LIKE '$langisocode'");
        $query = $query->execute(true);
        return $query;
    }
    
    public function findAllUnterkategorie($langisocode)
    {
        $query = $this->createQuery();
        $query->statement("SELECT * FROM `tx_iqtp13db_domain_model_branche` WHERE brancheok != 0 AND langisocode LIKE '$langisocode';");
        $query = $query->execute();
        return $query;
    }
    
    
    
}
