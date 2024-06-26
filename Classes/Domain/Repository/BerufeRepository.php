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
 * The repository for Berufe
 */
class BerufeRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    public function findAllOrdered($langisocode)
    {
        $query = $this->createQuery();
        //$query->matching($query->logicalAnd(
        //    $query->like('langisocode', $langisocode),
        //    ));
        //$query->setOrderings(array('titel' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        
        $query->statement("SELECT * FROM tx_iqtp13db_domain_model_berufe WHERE
                            langisocode LIKE '$langisocode'
                            ORDER BY CASE WHEN berufid < 0 THEN berufid END DESC, CASE WHEN berufid > 0 THEN titel END ASC");
        $query = $query->execute();
        
        return $query;
    }
}
