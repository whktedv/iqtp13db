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
 * The repository for Beratungs
 */
class BeratungRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
	/**
	 * @param $beratungsstatus
	 * @param $orderby
	 * @param $order
	 */
	public function findAllOrder4List($beratungsstatus, $orderby, $order)
	{
		$query = $this->createQuery();		
		$query->matching($query->logicalAnd($query->like('teilnehmer.beratungsstatus', $beratungsstatus), $query->logicalAnd($query->greaterThan('teilnehmer.verification_date', 0))));
	   		if($order == 'DESC') {
        	$query->setOrderings(array($orderby => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        } else {
        	$query->setOrderings(array($orderby => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        }
		$query = $query->execute();
		return $query;
	}
	
	public function count4StatusErstberatung($datum1, $datum2)
	{		 
		$query = $this->createQuery();
		$query->statement("SELECT * FROM tx_iqtp13db_domain_model_beratung WHERE
				DATEDIFF(STR_TO_DATE('".$datum1."', '%d.%m.%Y'),STR_TO_DATE(datum, '%d.%m.%Y')) <= 0 AND
				DATEDIFF(STR_TO_DATE('".$datum2."', '%d.%m.%Y'),STR_TO_DATE(datum, '%d.%m.%Y')) >= 0"); 
		$query = $query->execute();
		return count($query);
	}
	
	public function count4StatusBeratungfertig($datum1, $datum2)
	{
		$query = $this->createQuery();
		$query->statement("SELECT * FROM tx_iqtp13db_domain_model_beratung WHERE
				DATEDIFF(STR_TO_DATE('".$datum1."', '%d.%m.%Y'),STR_TO_DATE(erstberatungabgeschlossen, '%d.%m.%Y')) <= 0 AND
				DATEDIFF(STR_TO_DATE('".$datum2."', '%d.%m.%Y'),STR_TO_DATE(erstberatungabgeschlossen, '%d.%m.%Y')) >= 0");
		$query = $query->execute();
		return count($query);
	}
	
	public function days4Beratungfertig($datum1, $datum2)
	{
		$query = $this->createQuery();
		$query->statement("SELECT * FROM tx_iqtp13db_domain_model_beratung WHERE
				DATEDIFF(STR_TO_DATE('".$datum1."', '%d.%m.%Y'),STR_TO_DATE(erstberatungabgeschlossen, '%d.%m.%Y')) <= 0 AND
				DATEDIFF(STR_TO_DATE('".$datum2."', '%d.%m.%Y'),STR_TO_DATE(erstberatungabgeschlossen, '%d.%m.%Y')) >= 0");
		$query = $query->execute();
		
		return $query;
	}

	public function days4Wartezeit($datum1, $datum2)
	{
		$query = $this->createQuery();
		$query->statement("SELECT * FROM tx_iqtp13db_domain_model_teilnehmer
    			LEFT JOIN tx_iqtp13db_domain_model_beratung b ON b.teilnehmer = tx_iqtp13db_domain_model_teilnehmer.uid WHERE
				DATEDIFF(STR_TO_DATE('".$datum1."', '%d.%m.%Y'),STR_TO_DATE(datum, '%d.%m.%Y')) <= 0 AND
				DATEDIFF(STR_TO_DATE('".$datum2."', '%d.%m.%Y'),STR_TO_DATE(datum, '%d.%m.%Y')) >= 0");
		$query = $query->execute();
	
		return $query;
	}
	
}
