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
     */
    public function searchTeilnehmer($type, $filterArray, $verstecktundgelöscht, $niqbid, $berufearr, $orderby, $order, $beraterdiesergruppe, $thisusergroup)
    {
        $berateruidarray = array();        
        foreach($beraterdiesergruppe as $oneberater) $berateruidarray[] = $oneberater->getUid();
                
        $uid = $filterArray['uid'] == '' ? '%' : $filterArray['uid'];
        $name = $filterArray['name'] == '' ? '%' : $filterArray['name'];
        $ort = $filterArray['ort'] == '' ? '%' : $filterArray['ort'];
        $land = $filterArray['land'] == '' ? '%' : $filterArray['land'];
        $berater = $filterArray['berater'] == '' ? '' : " AND t.berater LIKE '".$filterArray['berater']."'";
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
                
                foreach ($berufearr as $beruf) {
                    if (strpos(strtolower($beruf->getTitel()), strtolower($fberuf)) !== false) { $results[] = $beruf->getBerufid(); }
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
       
        $sql = "SELECT t.* FROM tx_iqtp13db_domain_model_teilnehmer t
			LEFT JOIN tx_iqtp13db_domain_model_abschluss a ON a.teilnehmer = t.uid
            WHERE (t.nachname LIKE '%$name%' OR t.vorname LIKE '%$name%')
            AND (t.ort LIKE '%$ort%' OR t.plz LIKE '%$ort%')
            AND t.geburtsland LIKE '$land'
            $berater
            AND $beruf
            AND t.kooperationgruppe LIKE '%$gruppe%'
            $bescheid
            AND $sqlberatungsstatus $hidden
            AND niqidberatungsstelle LIKE $niqbid
            AND t.uid like '$uid' 
            GROUP BY t.uid ORDER BY $orderby $order";
                        
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
            $query->matching(
                $query->logicalAnd(
                    $query->logicalOr($query->like('beratungsstatus', '2'),$query->like('beratungsstatus', '3')),
                    $query->greaterThan('verification_date', '0'), 
                    $query->like('niqidberatungsstelle', $niqbid),                        
                )
            );
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
                    $orderby => $order,
                    'uid' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
                ]
                );
        } else {
            $query->setOrderings([ $orderby => $order ]);
        }
        
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
     * @param $email
     */
    public function findDublette4Anmeldung($nachname, $vorname, $email)
    {
        $query = $this->createQuery();
        $query->matching($query->logicalAnd(
            $query->like('nachname', '%'.$nachname.'%'),
            $query->like('vorname', '%'.$vorname.'%'),
            $query->like('email', $email),
            $query->logicalNot($query->like('beratungsstatus', '99'))
            ));
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
    
    /**
     * @param $orderby
     * @param $order
     */
    public function findLast4Admin()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(TRUE);
        $query->getQuerySettings()->setEnableFieldsToBeIgnored(array('disabled', 'hidden'));
        $query->setOrderings(array('uid' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        $query->setLimit(50);
        $query = $query->execute();
        return $query;
    }
    
    
    /**
     * @param $beratungsstatus
     */
    public function countAllOrder4Status($beratungsstatus, $niqbid)
    {
        $query = $this->createQuery();
        $query->statement("SELECT count(*) as anzahl FROM tx_iqtp13db_domain_model_teilnehmer WHERE
				beratungsstatus = '$beratungsstatus' AND deleted = 0 AND hidden = 0 AND niqidberatungsstelle LIKE '$niqbid'");
        
        $query = $query->execute(true);
        return $query;
    }
    
    public function count4Status($datum1, $datum2, $niqbid, $bstatus)
    {
        $addfield = '';
        if($bstatus == 1) $field = 'FROM_UNIXTIME(verification_date)';
        if($bstatus == 2) $field = 'beratungdatum';
        if($bstatus == 3) $field = 'erstberatungabgeschlossen';
        if($bstatus == 4) {
            $field = 'erstberatungabgeschlossen';
            $addfield = " AND niqchiffre != '' ";
        }
        if($bstatus == 5) {
            $field = 'erstberatungabgeschlossen';
            $addfield = " AND beratungsstatus = 4 ";
        }
        
        $query = $this->createQuery();        
        $query->statement("SELECT count(*) as anzahl FROM tx_iqtp13db_domain_model_teilnehmer WHERE
				DATEDIFF(STR_TO_DATE('".$datum1."', '%d.%m.%Y'),$field) <= 0 AND
				DATEDIFF(STR_TO_DATE('".$datum2."', '%d.%m.%Y'),$field) >= 0 $addfield
				AND deleted = 0 AND hidden = 0 AND niqidberatungsstelle LIKE '$niqbid'");
              
        $query = $query->execute(true);
        return $query;
    }
    
    /**
     * Counts Teilnehmer by Status for last 12 month
     *
     * @param $niqbid
     * @param $bstatus
     *  
     */
    public function countTNbyBID($niqbid, $bstatus)
    {
        $addfield = '';
        if($bstatus == 1) $field = 'FROM_UNIXTIME(verification_date)';
        if($bstatus == 2) $field = 'beratungdatum';
        if($bstatus == 3) $field = 'erstberatungabgeschlossen';
        if($bstatus == 4) {
            $field = 'erstberatungabgeschlossen';
            $addfield = " AND niqchiffre != '' ";
        }
        if($bstatus == 5) {
            $field = 'erstberatungabgeschlossen';
            $addfield = " AND beratungsstatus = 4 ";
        }

        $query = $this->createQuery();
        if(date('Y') > 2023) {
            $query->statement("SELECT MONTH($field) as monat, count(*) as anzahl
                FROM tx_iqtp13db_domain_model_teilnehmer
                WHERE YEAR($field) = YEAR(CURRENT_DATE())
                AND deleted = 0 AND hidden = 0 AND niqidberatungsstelle LIKE '$niqbid'
                $addfield
                GROUP BY MONTH($field)
                UNION
                SELECT MONTH($field) as monat, count(*) as anzahl
                FROM tx_iqtp13db_domain_model_teilnehmer
                WHERE YEAR($field) = YEAR(CURRENT_DATE())-1 AND MONTH($field) > MONTH(CURRENT_DATE())
                AND deleted = 0 AND hidden = 0 AND niqidberatungsstelle LIKE '$niqbid'
                $addfield
                GROUP BY MONTH($field)");
        } else {
            $query->statement("SELECT MONTH($field) as monat, count(*) as anzahl
                FROM tx_iqtp13db_domain_model_teilnehmer
                WHERE YEAR($field) = YEAR(CURRENT_DATE())
                AND deleted = 0 AND hidden = 0 AND niqidberatungsstelle LIKE '$niqbid'
                $addfield
                GROUP BY MONTH($field)");
        }
        
        $query = $query->execute(true);        
        return $query;
    }
    
    /**
     * Counts Teilnehmer by Bundesland and Status for last 12 month
     *
     * @param $bundesland
     * @param $bstatus
     *
     */
    public function countTNbyBundesland($bundesland, $bstatus)
    {
        $addfield = '';
        if($bstatus == 1) $field = 'FROM_UNIXTIME(verification_date)';
        if($bstatus == 2) $field = 'beratungdatum';
        if($bstatus == 3) $field = 'erstberatungabgeschlossen';
        if($bstatus == 4) {
            $field = 'erstberatungabgeschlossen';
            $addfield = " AND niqchiffre != '' ";
        }
        if($bstatus == 5) {
            $field = 'erstberatungabgeschlossen';
            $addfield = " AND beratungsstatus = 4 ";
        }
        
        $query = $this->createQuery();
        if(date('Y') > 2023) {
            $query->statement("SELECT MONTH($field) as monat, count(*) as anzahl
                FROM tx_iqtp13db_domain_model_teilnehmer as a
                LEFT JOIN fe_groups as b on a.niqidberatungsstelle = b.niqbid
                WHERE YEAR($field) = YEAR(CURRENT_DATE())
                AND a.deleted = 0 AND a.hidden = 0 AND b.bundesland LIKE '$bundesland'
                $addfield
                GROUP BY MONTH($field)
                UNION
                SELECT MONTH($field) as monat, count(*) as anzahl
                FROM tx_iqtp13db_domain_model_teilnehmer as a
                LEFT JOIN fe_groups as b on a.niqidberatungsstelle = b.niqbid
                WHERE YEAR($field) = YEAR(CURRENT_DATE())-1 AND MONTH($field) > MONTH(CURRENT_DATE())
                AND a.deleted = 0 AND a.hidden = 0 AND b.bundesland LIKE '$bundesland'
                $addfield
                GROUP BY MONTH($field)");
        } else {
            $query->statement("SELECT MONTH($field) as monat, count(*) as anzahl
                FROM tx_iqtp13db_domain_model_teilnehmer as a
                LEFT JOIN fe_groups as b on a.niqidberatungsstelle = b.niqbid
                WHERE YEAR($field) = YEAR(CURRENT_DATE())
                AND a.deleted = 0 AND a.hidden = 0 AND b.bundesland LIKE '$bundesland'
                $addfield
                GROUP BY MONTH($field)");
        }
        
        $query = $query->execute(true);
        return $query;
    }
    
    /**
     * Calculates average waiting days for last 12 month
     *
     * @param $niqbid
     * @param $status
     *
     */
    public function calcwaitingdays($niqbid, $status)
    {
        if($status == 'anmeldung') {
            $von = "FROM_UNIXTIME(verification_date)";
            $bis = "beratungdatum";
        }
        if($status == 'beratung') {
            $von = "beratungdatum";
            $bis = "erstberatungabgeschlossen";
        }
        
        $query = $this->createQuery();
        if(date('Y') > 2023) {
            $query->statement("SELECT MONTH($bis) as monat, SUM(IF(DATEDIFF($bis,$von) < 0, 0, DATEDIFF($bis,$von))) / count(*) as wert
                        FROM tx_iqtp13db_domain_model_teilnehmer 
                        WHERE $bis != '' AND deleted = 0 AND hidden = 0 AND niqidberatungsstelle LIKE '$niqbid' AND YEAR($von) = YEAR(CURRENT_DATE())
                        GROUP BY MONTH($bis)
                        UNION
                        SELECT MONTH($bis) as monat, SUM(DATEDIFF($bis,beratungdatum)) / count(*) as wert
                        FROM tx_iqtp13db_domain_model_teilnehmer 
                        WHERE $bis != '' AND deleted = 0 AND hidden = 0 AND niqidberatungsstelle LIKE '$niqbid' AND YEAR($von) = YEAR(CURRENT_DATE())-1 AND MONTH($von) > MONTH(CURRENT_DATE())
                        GROUP BY MONTH($bis)");
        } else {
            $query->statement("SELECT MONTH($bis) as monat, SUM(IF(DATEDIFF($bis,$von) < 0, 0, DATEDIFF($bis,$von))) / count(*) as wert
                        FROM tx_iqtp13db_domain_model_teilnehmer
                        WHERE $bis != '' AND deleted = 0 AND hidden = 0 AND niqidberatungsstelle LIKE '$niqbid' AND YEAR($von) = YEAR(CURRENT_DATE())
                        GROUP BY MONTH($bis)");
        }
        
        $query = $query->execute(true);        
        return $query;
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

    /**
     *
     */
    public function search4exportTeilnehmer($type, $verstecktundgelöscht, $filtervon, $filterbis, $niqbid, $bundesland, $staat)
    {
        $orderby = 'verification_date';
        
        if($bundesland != '%') $niqbid = "%"; 
            
        if($type == 0) {
            $sqlberatungsstatus = " beratungsstatus >= 0 ";
            $filternach = "FROM_UNIXTIME(verification_date)";
        } elseif($type == 1) {
            $sqlberatungsstatus = " beratungsstatus = 1 ";
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
                LEFT JOIN fe_groups as b on t.niqidberatungsstelle = b.niqbid
                WHERE
                DATEDIFF(STR_TO_DATE('$filtervon', '%d.%m.%Y'),$filternach) <= 0 AND
				DATEDIFF(STR_TO_DATE('$filterbis', '%d.%m.%Y'),$filternach) >= 0
                AND $sqlberatungsstatus $hidden
                AND niqidberatungsstelle LIKE '$niqbid' 
                AND b.bundesland LIKE '$bundesland'
                AND t.erste_staatsangehoerigkeit LIKE '$staat'
                GROUP BY t.uid ORDER BY $orderby ASC";
         
        $query->statement($sql);
        
        return $query->execute();
    }
    
    /**
     *
     */
    public function showAdminStatsBerufLand($type, $filtervon, $filterbis, $bundesland, $beruf, $staat)
    {
        if($type == 0) {
            $sqlberatungsstatus = " beratungsstatus >= 0 ";
            $filternach = "FROM_UNIXTIME(verification_date)";
        } elseif($type == 1) {
            $sqlberatungsstatus = " beratungsstatus = 1 ";
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
        
        
        if ($beruf == '%') {
            // Ausgabe Liste Berufe
            $sql = "SELECT c.titel, count(referenzberufzugewiesen) as anz
                    FROM  tx_iqtp13db_domain_model_teilnehmer as a
                    LEFT JOIN  tx_iqtp13db_domain_model_abschluss as b ON a.uid = b.teilnehmer
                    LEFT JOIN tx_iqtp13db_domain_model_berufe as c ON b.referenzberufzugewiesen = c.berufid
                    LEFT JOIN fe_groups as d ON niqidberatungsstelle = d.niqbid
                    WHERE 
                    DATEDIFF(STR_TO_DATE('$filtervon', '%d.%m.%Y'),$filternach) <= 0 AND
				    DATEDIFF(STR_TO_DATE('$filterbis', '%d.%m.%Y'),$filternach) >= 0
                    AND $sqlberatungsstatus
                    AND d.bundesland LIKE '$bundesland'
                    AND erste_staatsangehoerigkeit LIKE '$staat'
                    AND a.hidden = 0 and a.deleted = 0
                    GROUP BY referenzberufzugewiesen ORDER BY anz DESC LIMIT 20";
        } elseif ($staat == '%') {
            // Ausgabe Liste Staatsangehörigkeit
            $sql = "SELECT c.titel, count(erste_staatsangehoerigkeit) as anz
                    FROM  tx_iqtp13db_domain_model_teilnehmer as a
                    LEFT JOIN  tx_iqtp13db_domain_model_abschluss as b ON a.uid = b.teilnehmer
                    LEFT JOIN tx_iqtp13db_domain_model_staaten as c ON erste_staatsangehoerigkeit = c.staatid
                    LEFT JOIN fe_groups as d ON niqidberatungsstelle = d.niqbid
                    WHERE 
                    DATEDIFF(STR_TO_DATE('$filtervon', '%d.%m.%Y'),$filternach) <= 0 AND
				    DATEDIFF(STR_TO_DATE('$filterbis', '%d.%m.%Y'),$filternach) >= 0
                    AND $sqlberatungsstatus
                    AND d.bundesland LIKE '$bundesland'
                    AND b.referenzberufzugewiesen LIKE '$beruf'
                    AND a.hidden = 0 and a.deleted = 0
                    GROUP BY erste_staatsangehoerigkeit ORDER BY anz DESC LIMIT 20";
        } elseif($bundesland == '%') {
            // Ausgabe Liste Bundesland
            $sql = "SELECT d.bundesland, count(d.bundesland) as anz
                    FROM  tx_iqtp13db_domain_model_teilnehmer as a
                    LEFT JOIN  tx_iqtp13db_domain_model_abschluss as b ON a.uid = b.teilnehmer
                    LEFT JOIN tx_iqtp13db_domain_model_berufe as c ON b.referenzberufzugewiesen = c.berufid
                    LEFT JOIN fe_groups as d ON niqidberatungsstelle = d.niqbid
                    WHERE
                    DATEDIFF(STR_TO_DATE('$filtervon', '%d.%m.%Y'),$filternach) <= 0 AND
				    DATEDIFF(STR_TO_DATE('$filterbis', '%d.%m.%Y'),$filternach) >= 0
                    AND $sqlberatungsstatus
                    AND b.referenzberufzugewiesen LIKE '$beruf'
                    AND erste_staatsangehoerigkeit LIKE '$staat'
                    AND a.hidden = 0 and a.deleted = 0
                    GROUP BY d.bundesland ORDER BY anz DESC LIMIT 20";
        } else {
            // Fehler
        }
        //DebuggerUtility::var_dump($sql);

        $query->statement($sql);
        return $query->execute(true);
    }
}
