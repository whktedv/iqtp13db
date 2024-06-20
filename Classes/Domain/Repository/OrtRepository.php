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
 * The repository for Ort
 */
class OrtRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    public function findLandkreiseByBundesland($bundesland)
    {
        $query = $this->createQuery();
        $query->statement("SELECT DISTINCT landkreis FROM tx_iqtp13db_domain_model_ort WHERE bundesland LIKE '$bundesland' ORDER BY landkreis");
        $query = $query->execute(true);
        return $query;
    }
}
