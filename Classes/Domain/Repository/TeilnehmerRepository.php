<?php
namespace Ud\Iqtp13db\Domain\Repository;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
 * The repository for Teilnehmers
 */
class TeilnehmerRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * Finds Teilnehmer by the specified name, ort, beruf and/or geburtsland
     *
     * @param string $name
     * @param string $ort
     * @param string $beruf
     * @param string $land
     * @param int $auchverstecktundgelöscht
     * @return Tx_Extbase_Persistence_QueryResultInterface Teilnehmer
     */
    public function searchTeilnehmer($name, $ort, $beruf, $land, $auchverstecktundgelöscht)
    {
        $name = $name == '' ? '%' : $name;
        $ort = $ort == '' ? '%' : $ort;
        $beruf = $beruf == '' ? '%' : $beruf;
        $land = $land == '' ? '%' : $land;
        $query = $this->createQuery();
        if($auchverstecktundgelöscht == 1) {
            $query->getQuerySettings()->setIgnoreEnableFields(TRUE);
            $query->getQuerySettings()->setEnableFieldsToBeIgnored(array('disabled', 'hidden'));
            $query->matching($query->logicalAnd(
                $query->logicalOr($query->like('nachname', '%' . $name . '%'), $query->like('vorname', '%' . $name . '%')),
                $query->like('ort', $ort),
                $query->logicalOr($query->like('deutscher_referenzberuf1', '%' .$beruf. '%'), $query->like('deutscher_referenzberuf2', '%' .$beruf. '%')),
                $query->like('geburtsland', $land),
                $query->like('hidden', $auchverstecktundgelöscht))
                );
            
        } else {
            $query->matching($query->logicalAnd(
                $query->logicalOr($query->like('nachname', '%' . $name . '%'), $query->like('vorname', '%' . $name . '%')),
                $query->like('ort', $ort),
                $query->logicalOr($query->like('deutscher_referenzberuf1', '%' .$beruf. '%'), $query->like('deutscher_referenzberuf2', '%' .$beruf. '%')),
                $query->like('geburtsland', $land),
                $query->logicalOr($query->like('beratungsstatus', '0'), $query->like('beratungsstatus', '1'))
             ));            
        }
        
        return $query->execute();
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
    public function findhidden4list($orderby, $order)
    {
    	$query = $this->createQuery();
    	$query->getQuerySettings()->setIgnoreEnableFields(TRUE);
    	$query->getQuerySettings()->setEnableFieldsToBeIgnored(array('disabled', 'hidden'));
    	$query->matching($query->like('hidden', '1'));
   		if($order == 'DESC') {
        	$query->setOrderings(array($orderby => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        } else {
        	$query->setOrderings(array($orderby => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        }
    	$query = $query->execute();
    	return $query;
    }

    /**
     * @param $orderby
     * @param $order
     */
    public function findAllOrder4List($orderby, $order)
    {
        $query = $this->createQuery();        
        $query->matching($query->logicalOr($query->like('beratungsstatus', '0'), $query->like('beratungsstatus', '1')));        
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
    public function findDublette4Angemeldet($nachname, $vorname)
    {
        $query = $this->createQuery();        
        $query->matching($query->logicalAnd(
            $query->like('nachname', '%'.$nachname.'%'), 
            $query->like('vorname', '%'.$vorname.'%'),
            $query->logicalNot($query->like('beratungsstatus', '99'))
        ));        
        $query = $query->execute();
        return count($query);
    }
    
    /**
     * @param $nachname
     * @param $vorname
     */
    public function findDublette4Deleted($nachname, $vorname)
    {
        $query = $this->createQuery();
        $query->matching($query->logicalAnd(
            $query->like('nachname', '%'.$nachname.'%'),
            $query->like('vorname', '%'.$vorname.'%')
            ));
        $query = $query->execute();
        return count($query);
    }

    
    public function count4Status($datum1, $datum2)
    {
    	$query = $this->createQuery();
		$query->statement("SELECT * FROM tx_iqtp13db_domain_model_teilnehmer WHERE 
				DATEDIFF(STR_TO_DATE('".$datum1."', '%d.%m.%Y'),FROM_UNIXTIME(verification_date)) <= 0 AND
				DATEDIFF(STR_TO_DATE('".$datum2."', '%d.%m.%Y'),FROM_UNIXTIME(verification_date)) >= 0 AND
				verification_date > 0 AND deleted = 0"); 
		$query = $query->execute();
    	return count($query);
    }
    
    public function count4Statusniqerfasst($datum1, $datum2)
    {
    	$query = $this->createQuery();
		$query->statement("SELECT * FROM tx_iqtp13db_domain_model_teilnehmer 
				LEFT JOIN tx_iqtp13db_domain_model_beratung b ON b.teilnehmer = tx_iqtp13db_domain_model_teilnehmer.uid WHERE 
				DATEDIFF(STR_TO_DATE('".$datum1."', '%d.%m.%Y'),STR_TO_DATE(b.erstberatungabgeschlossen, '%d.%m.%Y')) <= 0 AND
				DATEDIFF(STR_TO_DATE('".$datum2."', '%d.%m.%Y'),STR_TO_DATE(b.erstberatungabgeschlossen, '%d.%m.%Y')) >= 0 AND
        		niqchiffre != '' AND tx_iqtp13db_domain_model_teilnehmer.deleted = 0"); 
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
}
