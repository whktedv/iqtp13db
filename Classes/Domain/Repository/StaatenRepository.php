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
 * The repository for Staaten
 */
class StaatenRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    public function findStaatname($staatid)
    {
        $query = $this->createQuery();
        $query->matching($query->logicalAnd(
            $query->like('langisocode', 'de'),
            $query->like('staatid', $staatid)
            ));
        $query = $query->execute();
        return $query;
    }
}
