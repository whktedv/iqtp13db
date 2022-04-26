<?php
namespace Ud\Iqtp13db\Domain\Repository;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

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
 * The repository for Teilnehmers
 */
class TeilnehmerRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    
    /**
     * Finds Teilnehmer by the specified name, ort and/or geburtsland
     *
     * @param string $type
     * @param string $name
     * @param string $ort
     * @param string $beruf
     * @param string $land
     * @param string $gruppe
     * @param int $auchverstecktundgelöscht
     * @return Tx_Extbase_Persistence_QueryResultInterface Teilnehmer
     */
    public function searchTeilnehmer($type, $name, $ort, $beruf, $land, $gruppe, $auchverstecktundgelöscht, $niqbid)
    {
        $name = $name == '' ? '%' : $name;
        $ort = $ort == '' ? '%' : $ort;
        $land = $land == '' ? '%' : $land;
        $gruppe = $gruppe == '' ? '%' : $gruppe;
        
        if($type == 0 || $type == 1) $sqlberatungsstatus = "(beratungsstatus = 0 OR beratungsstatus = 1)";
        elseif($type == 2 || $type == 3) $sqlberatungsstatus = "(beratungsstatus = 2 OR beratungsstatus = 3)";
        else $sqlberatungsstatus = "beratungsstatus = 4";
        
        $query = $this->createQuery();
        if($auchverstecktundgelöscht == 1) {
            $query->getQuerySettings()->setIgnoreEnableFields(TRUE);
            $query->getQuerySettings()->setEnableFieldsToBeIgnored(array('disabled', 'hidden'));
        } else {
           $hidden = " AND t.hidden = 0 ";
        }
        
        $sql = "SELECT t.* FROM tx_iqtp13db_domain_model_teilnehmer t
    			LEFT JOIN tx_iqtp13db_domain_model_abschluss a ON a.teilnehmer = t.uid WHERE
                (t.nachname LIKE '%$name%' OR t.vorname LIKE '%$name%') AND t.ort LIKE '%$ort%' AND t.geburtsland LIKE '$land'
                AND $beruf AND t.kooperationgruppe LIKE '%$gruppe%' AND $sqlberatungsstatus $hidden AND niqidberatungsstelle LIKE $niqbid GROUP BY t.uid";            
              
        $query->statement($sql);
        
        return $query->execute();
    }
    
    /**
     * @param $beratungsstatus
     * @param $orderby
     * @param $order
     */
    public function findAllOrder4List($beratungsstatus, $orderby, $order, $niqbid)
    {
        $query = $this->createQuery();
        
        if($beratungsstatus == 0 || $beratungsstatus == 1) $query->matching($query->logicalAnd($query->logicalOr($query->like('beratungsstatus', '0'),$query->like('beratungsstatus', '1')), $query->like('niqidberatungsstelle', $niqbid)));
        elseif($beratungsstatus == 2 || $beratungsstatus == 3) $query->matching($query->logicalAnd($query->logicalOr($query->like('beratungsstatus', '2'),$query->like('beratungsstatus', '3')),$query->greaterThan('verification_date', '0'), $query->like('niqidberatungsstelle', $niqbid)));
        else $query->matching($query->logicalAnd($query->like('beratungsstatus', '4'),$query->greaterThan('verification_date', '0'), $query->like('niqidberatungsstelle', $niqbid)));
        
        if($order == 'DESC') $order = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING;
        else $order = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING;

        if($beratungsstatus == 0) {
            $query->setOrderings(
                [
                    'beratungsstatus' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
                    $orderby => $order
                ]
            );
        } else {
            $query->setOrderings([ $orderby => $order ]);
        }
        
        $query = $query->execute();
        return $query;
    }
        
    /**
     * @param $beratungsstatus
     */
    public function findAllOrder4Status($beratungsstatus, $niqbid)
    {
        $query = $this->createQuery();        
        $query->matching($query->logicalAnd($query->like('beratungsstatus', $beratungsstatus), $query->like('niqidberatungsstelle', $niqbid)));
        $query = $query->execute();
        return $query;
    }
    
    
    /**
     * @param $uid
     */
    public function findHiddenByUid($uid)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(TRUE);
        $query->getQuerySettings()->setEnableFieldsToBeIgnored(array('disabled', 'hidden'));
        return $query->matching($query->equals('uid', $uid))->execute()->getFirst();
    }

    /**
     * @param $orderby
     * @param $order
     */
    public function findhidden4list($orderby, $order, $niqbid)
    {
    	$query = $this->createQuery();
    	$query->getQuerySettings()->setIgnoreEnableFields(TRUE);
    	$query->getQuerySettings()->setEnableFieldsToBeIgnored(array('disabled', 'hidden'));
    	$query->matching($query->logicalAnd($query->like('hidden', '1'), $query->like('niqidberatungsstelle', $niqbid)));
   		if($order == 'DESC') {
        	$query->setOrderings(array($orderby => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        } else {
        	$query->setOrderings(array($orderby => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        }
    	$query = $query->execute();
    	return $query;
    }

    
    /**
     * @param $nachname
     * @param $vorname
     */
    public function findDublette4Angemeldet($nachname, $vorname, $niqbid)
    {
        $query = $this->createQuery();        
        $query->matching($query->logicalAnd(
            $query->like('nachname', '%'.$nachname.'%'), 
            $query->like('vorname', '%'.$vorname.'%'),
            $query->like('niqidberatungsstelle', $niqbid),
            $query->logicalNot($query->like('beratungsstatus', '99'))
        ));        
        $query = $query->execute();
        return count($query);
    }
    
    /**
     * @param $nachname
     * @param $vorname
     */
    public function findDublette4Deleted($nachname, $vorname, $niqbid)
    {
        $query = $this->createQuery();
        $query->matching($query->logicalAnd(
            $query->like('nachname', '%'.$nachname.'%'),
            $query->like('vorname', '%'.$vorname.'%'),
            $query->like('niqidberatungsstelle', $niqbid)
            ));
        $query = $query->execute();
        return count($query);
    }

    
    public function count4Status($datum1, $datum2, $niqbid)
    {
    	$query = $this->createQuery();
		$query->statement("SELECT * FROM tx_iqtp13db_domain_model_teilnehmer WHERE 
				DATEDIFF(STR_TO_DATE('".$datum1."', '%d.%m.%Y'),FROM_UNIXTIME(verification_date)) <= 0 AND
				DATEDIFF(STR_TO_DATE('".$datum2."', '%d.%m.%Y'),FROM_UNIXTIME(verification_date)) >= 0 AND
				verification_date > 0 AND deleted = 0 AND niqidberatungsstelle LIKE $niqbid"); 
		$query = $query->execute();
    	return count($query);
    }
    
    public function count4Statusniqerfasst($datum1, $datum2, $niqbid)
    {
    	$query = $this->createQuery();
		$query->statement("SELECT * FROM tx_iqtp13db_domain_model_teilnehmer WHERE				
				DATEDIFF(STR_TO_DATE('".$datum1."', '%d.%m.%Y'),erstberatungabgeschlossen) <= 0 AND
				DATEDIFF(STR_TO_DATE('".$datum2."', '%d.%m.%Y'),erstberatungabgeschlossen) >= 0 AND
        		niqchiffre != '' AND deleted = 0 AND niqidberatungsstelle LIKE $niqbid"); 
		$query = $query->execute();
    	return count($query);
    }
            
    /**
     * Gets the Teilnehmer by verificationCode
     *
     * @param string $verificationCode
     * @return Tx_Extbase_Persistence_QueryResultInterface Teilnehmer
     */
    public function findByVerificationCode(String $verificationCode)
    {
        $query = $this->createQuery();
        
        $constraints = array(
            $query->equals('verification_code', $verificationCode),
            $query->equals('verification_date', 0)
        );
        
        $query->matching(
            $query->logicalAnd($constraints)
            );
        
        $result = $query->execute()->toArray();
        if(count($result) > 0) {
            return $result[0];
        } else {
            return NULL;
        }
    }
   
    public function count4StatusErstberatung($datum1, $datum2, $niqbid)
    {
        $query = $this->createQuery();
        $query->statement("SELECT * FROM tx_iqtp13db_domain_model_teilnehmer WHERE
				DATEDIFF(STR_TO_DATE('".$datum1."', '%d.%m.%Y'),beratungdatum) <= 0 AND
				DATEDIFF(STR_TO_DATE('".$datum2."', '%d.%m.%Y'),beratungdatum) >= 0
                AND deleted = 0 AND niqidberatungsstelle LIKE $niqbid");
        $query = $query->execute();
        return count($query);
    }
    
    public function count4StatusBeratungfertig($datum1, $datum2, $niqbid)
    {
        $query = $this->createQuery();
        $query->statement("SELECT * FROM tx_iqtp13db_domain_model_teilnehmer WHERE
				DATEDIFF(STR_TO_DATE('".$datum1."', '%d.%m.%Y'),erstberatungabgeschlossen) <= 0 AND
				DATEDIFF(STR_TO_DATE('".$datum2."', '%d.%m.%Y'),erstberatungabgeschlossen) >= 0
                AND deleted = 0 AND niqidberatungsstelle LIKE $niqbid");
        $query = $query->execute();
        return count($query);
    }
    
    public function days4Beratungfertig($datum1, $datum2, $niqbid)
    {
        $query = $this->createQuery();
        $query->statement("SELECT * FROM tx_iqtp13db_domain_model_teilnehmer WHERE
				DATEDIFF(STR_TO_DATE('".$datum1."', '%d.%m.%Y'),erstberatungabgeschlossen) <= 0 AND
				DATEDIFF(STR_TO_DATE('".$datum2."', '%d.%m.%Y'),erstberatungabgeschlossen) >= 0
                AND deleted = 0 AND niqidberatungsstelle LIKE $niqbid");
        $query = $query->execute();
        
        return $query;
    }
    
    public function days4Wartezeit($datum1, $datum2, $niqbid)
    {
        $query = $this->createQuery();
        $query->statement("SELECT * FROM tx_iqtp13db_domain_model_teilnehmer WHERE
				DATEDIFF(STR_TO_DATE('".$datum1."', '%d.%m.%Y'),beratungdatum) <= 0 AND
				DATEDIFF(STR_TO_DATE('".$datum2."', '%d.%m.%Y'),beratungdatum) >= 0
                AND deleted = 0 AND niqidberatungsstelle LIKE $niqbid");
        $query = $query->execute();
        
        return $query;
    }
}
