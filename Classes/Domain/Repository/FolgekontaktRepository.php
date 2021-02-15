<?php
namespace Ud\Iqtp13db\Domain\Repository;

/***
 *
 * This file is part of the "IQ TP13 Datenbank Anerkennungserstberatung NRW" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Uli Dohmen <edv@whkt.de>, WHKT
 *
 ***/

/**
 * The repository for Folgekontakt
 */
class FolgekontaktRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
	public function count4Status($datum1, $datum2)
	{
		 
		$query = $this->createQuery();	
		$query->statement("SELECT * FROM tx_iqtp13db_domain_model_folgekontakt WHERE 
				DATEDIFF(STR_TO_DATE('".$datum1."', '%d.%m.%Y'),STR_TO_DATE(datum, '%d.%m.%Y')) <= 0 AND
				DATEDIFF(STR_TO_DATE('".$datum2."', '%d.%m.%Y'),STR_TO_DATE(datum, '%d.%m.%Y')) >= 0
                AND deleted = 0"); 
		$query = $query->execute();
		
		return count($query);
	}
	
}
