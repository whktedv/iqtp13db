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
	 * @param $niqbid
	 */
	public function findAllDesc($niqbid)
	{
		$query = $this->createQuery();
		$query->matching(
		    $query->equals('teilnehmer.niqidberatungsstelle', $niqbid)
        );
		$query->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
		$query = $query->execute();
		return $query;
	}
	
}
