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
 * The repository for Dokuments
 */
class DokumentRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    
    /**
     * @param $dokname
     * @param $teilnehmer
     */
    public function findDublette($dokname, $teilnehmeruid)
    {
        $query = $this->createQuery();
        $query->matching($query->logicalAnd(
            $query->like('name', $dokname),
            $query->like('teilnehmer', $teilnehmeruid),
            ));
        $query = $query->execute();
        if(count($query) > 1) {
            return true;
        } else {
            return false;
        }
    }
    
    }
