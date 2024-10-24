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
 * The repository for Abschluss
 */
class AbschlussRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    public function findByTnByUidarray(array $uids)
    {
        $q = $this->createQuery();
        $q->matching($q->in('teilnehmer', $uids));
       
        return $q->execute();
    }
}
