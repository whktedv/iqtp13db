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
     *
     */
    public function searchTeilnehmer($type, $filterArray, $verstecktundgelöscht, $niqbid, $berufearr, $orderby, $order, $beraterdiesergruppe, $thisusergroup)
    {
        $berateruidarray = array();        
        foreach($beraterdiesergruppe as $oneberater) $berateruidarray[] = $oneberater->getUid();
        
        
        $uid = $filterArray['uid'] == '' ? '%' : $filterArray['uid'];
        $name = $filterArray['name'] == '' ? '%' : $filterArray['name'];
        $ort = $filterArray['ort'] == '' ? '%' : $filterArray['ort'];
        $land = $filterArray['land'] == '' ? '%' : $filterArray['land'];
        $fberuf = $filterArray['beruf'] == '' ? '%' : $filterArray['beruf'];
        $gruppe = $filterArray['gruppe'] == '' ? '%' : $filterArray['gruppe'];
        $fbescheid = $filterArray['bescheid'] == '' ? '%' : $filterArray['bescheid'];
        
        $orderby = $orderby == 'verificationDate' ? 'verification_date' : $orderby;
        
        if($type == 0 || $type == 1) $sqlberatungsstatus = " (beratungsstatus = 0 OR beratungsstatus = 1) ";
        elseif($type == 2 || $type == 3) $sqlberatungsstatus = " (beratungsstatus = 2 OR beratungsstatus = 3) ";
        elseif($type == 999) $sqlberatungsstatus = " beratungsstatus LIKE '%' ";
        else $sqlberatungsstatus = " beratungsstatus = 4 ";
        
        // Beruf
        if($type != 0) {
            if($filterArray['beruf'] != '') {
                
                foreach ($berufearr as $beruf => $bkey) {
                    if (strpos(strtolower($bkey), strtolower($fberuf)) !== false) { $results[] = $beruf; }
                }
                if(!empty($results)) {
                    $beruf = " a.referenzberufzugewiesen IN ('".implode("','", $results)."') ";
                }
            } else {
                $beruf = "(a.deutscher_referenzberuf LIKE '%".$fberuf."%' OR a.deutscher_referenzberuf IS NULL)";
            }
        } else {
            $beruf = "(a.deutscher_referenzberuf LIKE '%".$fberuf."%' OR a.deutscher_referenzberuf IS NULL)";
        }
        
        if($fbescheid != '%') {
            $bescheid = "AND (a.antragstellungvorher > 0 AND a.antragstellungvorher < 4) ";
        } else {
            $bescheid = '';
        }
        
        $query = $this->createQuery();
        
        if($verstecktundgelöscht == 1) {
            $query->getQuerySettings()->setIgnoreEnableFields(TRUE);
            $query->getQuerySettings()->setEnableFieldsToBeIgnored(array('disabled', 'hidden'));
            $hidden = " AND t.hidden = 1 ";
        } else {
            $hidden = " AND t.hidden = 0 ";
        }
        
        if(substr($thisusergroup->getTitle(),0,2) == 'AA'){
            $sql = "SELECT t.* FROM tx_iqtp13db_domain_model_teilnehmer t
    			LEFT JOIN tx_iqtp13db_domain_model_abschluss a ON a.teilnehmer = t.uid
                WHERE (t.nachname LIKE '%$name%' OR t.vorname LIKE '%$name%')
                AND t.ort LIKE '%$ort%'
                AND t.geburtsland LIKE '$land'
                AND $beruf
                AND t.kooperationgruppe LIKE '%$gruppe%'
                $bescheid
                AND $sqlberatungsstatus $hidden
                AND niqidberatungsstelle LIKE $niqbid
                AND (verification_date > '1672527600' OR verification_date = '0')
                AND t.uid like '$uid' 
                GROUP BY t.uid ORDER BY $orderby $order";                
        } else {
            $sql = "SELECT t.* FROM tx_iqtp13db_domain_model_teilnehmer t
    			LEFT JOIN tx_iqtp13db_domain_model_abschluss a ON a.teilnehmer = t.uid
                WHERE (t.nachname LIKE '%$name%' OR t.vorname LIKE '%$name%')
                AND t.ort LIKE '%$ort%'
                AND t.geburtsland LIKE '$land'
                AND $beruf
                AND t.kooperationgruppe LIKE '%$gruppe%'
                $bescheid
                AND $sqlberatungsstatus $hidden
                AND niqidberatungsstelle LIKE $niqbid
                AND t.uid like '$uid' 
                GROUP BY t.uid ORDER BY $orderby $order";
                
        }
        //DebuggerUtility::var_dump($sql);
              
        $query->statement($sql);
        
        return $query->execute();
    }
    
    /**
     * @param $beratungsstatus
     * @param $orderby
     * @param $order
     */
    public function findAllOrder4List($beratungsstatus, $orderby, $order, $niqbid, $beraterdiesergruppe, $thisusergroup)
    {
        $query = $this->createQuery();
        
        $berateruidarray = array();
        
        foreach($beraterdiesergruppe as $oneberater) $berateruidarray[] = $oneberater->getUid();
        
        if($beratungsstatus == 0 || $beratungsstatus == 1) {
            $query->matching(
                $query->logicalAnd(
                    $query->logicalOr($query->like('beratungsstatus', '0'),$query->like('beratungsstatus', '1')),
                    $query->like('niqidberatungsstelle', $niqbid)                    
                )
            );
        }
        elseif($beratungsstatus == 2 || $beratungsstatus == 3) {
            // Hier Ausnahme Frau Hollands/AA
            if(substr($thisusergroup->getTitle(),0,2) == 'AA'){
                $query->matching(
                    $query->logicalAnd(
                        $query->logicalOr($query->like('beratungsstatus', '2'),$query->like('beratungsstatus', '3')),
                        $query->greaterThan('verification_date', '0'), 
                        $query->like('niqidberatungsstelle', $niqbid),
                        $query->logicalOr(
                            $query->logicalOr($query->greaterThan('verification_date', '1672527600'), $query->equals('verification_date', '0')),
                            $query->in('berater', $berateruidarray)
                        )
                    )
                );
            } else {
                $query->matching(
                    $query->logicalAnd(
                        $query->logicalOr($query->like('beratungsstatus', '2'),$query->like('beratungsstatus', '3')),
                        $query->greaterThan('verification_date', '0'), 
                        $query->like('niqidberatungsstelle', $niqbid),                        
                    )
                );
            }
        }
        else {
            $query->matching(
                $query->logicalAnd(
                    $query->like('beratungsstatus', '4'),
                    $query->greaterThan('verification_date', '0'), 
                    $query->like('niqidberatungsstelle', $niqbid)
                )
            );
        }
        
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
    
    
    /**
     *
     */
    public function search4exportTeilnehmer($type, $verstecktundgelöscht, $filtervon, $filterbis, $niqbid)
    {
        $orderby = 'verification_date';
        
        if($type == 0 || $type == 1) {
            $sqlberatungsstatus = " (beratungsstatus = 0 OR beratungsstatus = 1) ";
            $filternach = "FROM_UNIXTIME(verification_date)";
        } elseif($type == 2) {
            $sqlberatungsstatus = " (beratungsstatus = 2 OR beratungsstatus = 3) ";
            $filternach = "beratungdatum";
        } elseif($type == 4) {
            $sqlberatungsstatus = " beratungsstatus = 4 ";
            $filternach = "erstberatungabgeschlossen";
        } else {
            // fehler!
        }
        
        $query = $this->createQuery();
        
        if($verstecktundgelöscht == 1) {
            $query->getQuerySettings()->setIgnoreEnableFields(TRUE);
            $query->getQuerySettings()->setEnableFieldsToBeIgnored(array('disabled', 'hidden'));
            $hidden = " AND t.hidden = 1 ";
        } else {
            $hidden = " AND t.hidden = 0 ";
        }
        
        
        $sql = "SELECT t.* FROM tx_iqtp13db_domain_model_teilnehmer t
    			LEFT JOIN tx_iqtp13db_domain_model_abschluss a ON a.teilnehmer = t.uid
                WHERE
                DATEDIFF(STR_TO_DATE('$filtervon', '%d.%m.%Y'),$filternach) <= 0 AND
				DATEDIFF(STR_TO_DATE('$filterbis', '%d.%m.%Y'),$filternach) >= 0
                AND $sqlberatungsstatus $hidden
                AND niqidberatungsstelle LIKE $niqbid
                GROUP BY t.uid ORDER BY $orderby ASC";
         
        //DebuggerUtility::var_dump($sql);
        $query->statement($sql);
        
        return $query->execute();
    }
}
