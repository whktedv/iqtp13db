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
 * The repository for Historie
 */
class HistorieRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
	/**
	 * @param $tnuid
	 */
	public function findByTeilnehmerOrdered($tnuid)
	{
		$query = $this->createQuery();
		$query->matching($query->like('teilnehmer.uid', $tnuid));
		$query->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
		$query = $query->execute();
		return $query;
	}
	
	/**
	 */
	public function findAllDesc()
	{
		$query = $this->createQuery();		
		$query->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
		$query = $query->execute();
		return $query;
	}
	
}
