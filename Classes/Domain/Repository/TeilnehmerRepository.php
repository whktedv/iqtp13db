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
    public function searchTeilnehmer($type, $filterArray, $verstecktundgelöscht, $niqbid, $berufearr, $orderby, $order, $beraterdiesergruppe, $thisusergroup, $limit)
    {
        //$berateruidarray = array();        
        //foreach($beraterdiesergruppe as $oneberater) $berateruidarray[] = $oneberater->getUid();
                
        $uid = $filterArray['uid'] == '' ? '%' : $filterArray['uid'];
        $name = $filterArray['name'] == '' ? '%' : $filterArray['name'];
        $ort = $filterArray['ort'] == '' ? '%' : $filterArray['ort'];
        $land = $filterArray['land'] == '' ? '%' : $filterArray['land'];
        $berater = $filterArray['berater'] == '' ? '' : " AND t.berater LIKE '".$filterArray['berater']."'";
        $fberuf = $filterArray['beruf']; // == '' ? '%' : $filterArray['beruf'];
        $gruppe = $filterArray['gruppe'] == '' ? '%' : $filterArray['gruppe'];
        $fbescheid = $filterArray['bescheid'] == '' ? '%' : $filterArray['bescheid'];
        
        $orderby = $orderby == 'verificationDate' ? 'verification_date' : $orderby;
        
        // Beratungsstatus
        if($type == 0 || $type == 1) $sqlberatungsstatus = " (beratungsstatus = 0 OR beratungsstatus = 1) ";
        elseif($type == 2 || $type == 3) $sqlberatungsstatus = " (beratungsstatus = 2 OR beratungsstatus = 3) ";
        elseif($type == 999) $sqlberatungsstatus = " beratungsstatus LIKE '%' ";
        else $sqlberatungsstatus = " beratungsstatus = 4 ";
                
        // Beruf
        if($type != 0) {
            if($fberuf != '') {
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
            if($fberuf != '') {
                $beruf = "(a.deutscher_referenzberuf LIKE '%".$fberuf."%')";
            } else {
                $beruf = "(a.deutscher_referenzberuf LIKE '%".$fberuf."%' OR a.deutscher_referenzberuf IS NULL)";
            }            
        }
        
        // Antragstellungvorher
        if($fbescheid != '%') {
            $bescheid = "AND (a.antragstellungvorher > 0 AND a.antragstellungvorher < 4) ";
        } else {
            $bescheid = '';
        }
                
        // Erstelle Query
        $query = $this->createQuery();
        
        if($verstecktundgelöscht == 1) {
            $query->getQuerySettings()->setIgnoreEnableFields(TRUE);
            $query->getQuerySettings()->setEnableFieldsToBeIgnored(array('disabled', 'hidden'));
            $hidden = " AND t.hidden = 1 ";
        } elseif($verstecktundgelöscht == 0) {
            $hidden = " AND t.hidden = 0 ";
        } else {
            $hidden = ""; // für Suche ist es egal, ob hidden or nicht
        }
        
        $limitsql = $limit == 0 ? '' : ' LIMIT '.$limit;
        
        $sql = "SELECT t.*, GROUP_CONCAT(a.deutscher_referenzberuf) deutschereferenzberufe FROM tx_iqtp13db_domain_model_teilnehmer t
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
            AND t.deleted = 0
            GROUP BY t.uid ORDER BY $orderby $order $limitsql";

        $query->statement($sql);
        return $query->execute();
    }
    
    /**
     */
    
    /*
    public function findAllOrder4List($beratungsstatus, $orderby, $order, $niqbid)
    {
        $query = $this->createQuery();
        
        //$berateruidarray = array();
        //foreach($beraterdiesergruppe as $oneberater) $berateruidarray[] = $oneberater->getUid();
        
        // Beratungsstatus
        if($beratungsstatus == 0 || $beratungsstatus == 1) $sqlberatungsstatus = " (beratungsstatus = 0 OR beratungsstatus = 1) ";
        elseif($beratungsstatus == 2 || $beratungsstatus == 3) $sqlberatungsstatus = " (beratungsstatus = 2 OR beratungsstatus = 3) ";
        else $sqlberatungsstatus = " beratungsstatus = 4 ";
        
        $orderby = $orderby == 'verificationDate' ? 'verification_date' : $orderby;
        
        $sql = "SELECT t.*, GROUP_CONCAT(a.deutscher_referenzberuf) deutschereferenzberufe, GROUP_CONCAT(a.referenzberufzugewiesen) referenzberufzugewiesen, GROUP_CONCAT(a.antragstellungvorher) antragstellungvorher
            FROM tx_iqtp13db_domain_model_teilnehmer t
			LEFT JOIN tx_iqtp13db_domain_model_abschluss a ON a.teilnehmer = t.uid
            WHERE 
            $sqlberatungsstatus
            AND niqidberatungsstelle LIKE $niqbid
            AND t.hidden = 0 AND t.deleted = 0
            GROUP BY t.uid ORDER BY $orderby $order";

        $query->statement($sql);
            
        $query = $query->execute();
        return $query;
    }
    */ 
    
    /**
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
     * @param $niqbid
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
     * @param $niqbid
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
     * @param $niqbid
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
            $sqlberatungsstatus = " beratungsstatus >= 1 ";
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
    
    
    // STATUS
    
    
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
     * Counts Teilnehmer by Status for last 12 month or $jahr
     *
     * @param $niqbid
     * @param $bstatus
     * @param $jahr
     *  
     */
    public function countTNby($niqbid, $bundesland, $bstatus, $jahr, $staat)
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
        if($jahr == 0) {
            $query->statement("SELECT MONTH($field) as monat, count(*) as anzahl
                FROM tx_iqtp13db_domain_model_teilnehmer as a
                LEFT JOIN fe_groups as b on a.niqidberatungsstelle = b.niqbid
                WHERE YEAR($field) = YEAR(CURRENT_DATE())
                AND a.deleted = 0 AND a.hidden = 0 AND niqidberatungsstelle LIKE '$niqbid' AND b.bundesland LIKE '$bundesland' AND erste_staatsangehoerigkeit LIKE '$staat'
                $addfield
                GROUP BY MONTH($field)
                UNION
                SELECT MONTH($field) as monat, count(*) as anzahl
                FROM tx_iqtp13db_domain_model_teilnehmer as a
                LEFT JOIN fe_groups as b on a.niqidberatungsstelle = b.niqbid
                WHERE YEAR($field) = YEAR(CURRENT_DATE())-1 AND MONTH($field) > MONTH(CURRENT_DATE())
                AND a.deleted = 0 AND a.hidden = 0 AND niqidberatungsstelle LIKE '$niqbid' AND b.bundesland LIKE '$bundesland' AND erste_staatsangehoerigkeit LIKE '$staat'
                $addfield
                GROUP BY MONTH($field)");
        } else {
            $query->statement("SELECT MONTH($field) as monat, count(*) as anzahl
                FROM tx_iqtp13db_domain_model_teilnehmer as a
                LEFT JOIN fe_groups as b on a.niqidberatungsstelle = b.niqbid
                WHERE YEAR($field) = $jahr
                AND a.deleted = 0 AND a.hidden = 0 AND niqidberatungsstelle LIKE '$niqbid' AND b.bundesland LIKE '$bundesland' AND erste_staatsangehoerigkeit LIKE '$staat'
                $addfield
                GROUP BY MONTH($field)");
        }
        
        $query = $query->execute(true);        
        return $query;
    }
    
    /**
     * Calculates average waiting days for last 12 month or $jahr
     *
     * @param $niqbid
     * @param $status
     * @param $jahr
     *
     */
    public function calcwaitingdays($niqbid, $bundesland, $status, $jahr, $staat)
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
        if($jahr == 0) {
            $query->statement("SELECT MONTH($bis) as monat, SUM(IF(DATEDIFF($bis,$von) < 0, 0, DATEDIFF($bis,$von))) / count(*) as wert
                        FROM tx_iqtp13db_domain_model_teilnehmer as a 
                        LEFT JOIN fe_groups as b on a.niqidberatungsstelle = b.niqbid
                        WHERE $bis != '' AND a.deleted = 0 AND a.hidden = 0 AND niqidberatungsstelle LIKE '$niqbid' AND b.bundesland LIKE '$bundesland' AND YEAR($von) = YEAR(CURRENT_DATE()) AND erste_staatsangehoerigkeit LIKE '$staat'
                        GROUP BY MONTH($bis)
                        UNION
                        SELECT MONTH($bis) as monat, SUM(DATEDIFF($bis,$von)) / count(*) as wert
                        FROM tx_iqtp13db_domain_model_teilnehmer as a
                        LEFT JOIN fe_groups as b on a.niqidberatungsstelle = b.niqbid
                        WHERE $bis != '' AND a.deleted = 0 AND a.hidden = 0 AND niqidberatungsstelle LIKE '$niqbid' AND b.bundesland LIKE '$bundesland' AND YEAR($von) = YEAR(CURRENT_DATE())-1 AND MONTH($von) > MONTH(CURRENT_DATE()) AND erste_staatsangehoerigkeit LIKE '$staat'
                        GROUP BY MONTH($bis)");
        } else {
            $query->statement("SELECT MONTH($bis) as monat, SUM(IF(DATEDIFF($bis,$von) < 0, 0, DATEDIFF($bis,$von))) / count(*) as wert
                        FROM tx_iqtp13db_domain_model_teilnehmer as a
                        LEFT JOIN fe_groups as b on a.niqidberatungsstelle = b.niqbid
                        WHERE $bis != '' AND a.deleted = 0 AND a.hidden = 0 AND niqidberatungsstelle LIKE '$niqbid' AND b.bundesland LIKE '$bundesland' AND YEAR($von) = $jahr AND erste_staatsangehoerigkeit LIKE '$staat'
                        GROUP BY MONTH($bis)");
        }
        
        $query = $query->execute(true);        
        return $query;
    }
    
    
    /**
     * Abschlussart für Adminübersicht
     */
    public function showAbschlussart($type, $jahr, $bundesland, $staat) {
        
        if($type == 0) {
            // Anmeldungen (unbestätigt und bestätigt)
            $filternach = "FROM_UNIXTIME(verification_date)";
        } elseif($type == 4) {
            $filternach = "erstberatungabgeschlossen";
        } else {
            // fehler!
        }
        
        $query = $this->createQuery();
        
        $sql = "SELECT abschlussart, count(abschlussart) as anz
                    FROM  tx_iqtp13db_domain_model_teilnehmer as a
                    LEFT JOIN  tx_iqtp13db_domain_model_abschluss as b ON a.uid = b.teilnehmer
                    LEFT JOIN fe_groups as d ON niqidberatungsstelle = d.niqbid
                    WHERE
				    YEAR($filternach) LIKE $jahr
                    AND d.bundesland LIKE '$bundesland'
                    AND erste_staatsangehoerigkeit LIKE '$staat'
                    AND a.hidden = 0 and a.deleted = 0
                    AND b.teilnehmer IS NOT NULL 
                    GROUP BY abschlussart ORDER BY anz DESC";
        //DebuggerUtility::var_dump($sql);
        
        $query->statement($sql);
        $query = $query->execute(true);
        return $query;
    }
    
    
    /**
     * Herkunft für Adminübersicht
     */
    public function showHerkunft($type, $jahr, $bundesland) {
        
        if($type == 0) {
            // Anmeldungen (unbestätigt und bestätigt)
            $filternach = "FROM_UNIXTIME(verification_date)";
        } elseif($type == 4) {
            $filternach = "erstberatungabgeschlossen";
        } else {
            // fehler!
        }
        
        $query = $this->createQuery();
        
        $sql = "SELECT s.titel, count(erste_staatsangehoerigkeit) as anz
                    FROM  tx_iqtp13db_domain_model_teilnehmer as a
                    LEFT JOIN fe_groups as d ON niqidberatungsstelle = d.niqbid
                    LEFT JOIN tx_iqtp13db_domain_model_staaten as s ON erste_staatsangehoerigkeit = s.staatid
                    WHERE
				    YEAR($filternach) LIKE $jahr
                    AND d.bundesland LIKE '$bundesland'
                    AND a.hidden = 0 and a.deleted = 0
                    AND s.langisocode = 'de'
                    GROUP BY erste_staatsangehoerigkeit ORDER BY anz DESC LIMIT 20";
        
        $query->statement($sql);
        $query = $query->execute(true);
        return $query;
    }
    
    /**
     *  Abschlüsse/Berufe für Adminübersicht
     */
    public function showAbschluesseBerufe($type, $jahr, $bundesland, $staat) {
        
        if($type == 0) {
            // Anmeldungen (unbestätigt und bestätigt)
            $filternach = "FROM_UNIXTIME(verification_date)";
        } elseif($type == 4) {
            $filternach = "erstberatungabgeschlossen";
        } else {
            // fehler!
        }
        
        $query = $this->createQuery();
        
        $sql = "SELECT c.titel, count(b.referenzberufzugewiesen) as anz
                    FROM  tx_iqtp13db_domain_model_teilnehmer as a
                    LEFT JOIN tx_iqtp13db_domain_model_abschluss as b ON a.uid = b.teilnehmer
                    LEFT JOIN tx_iqtp13db_domain_model_berufe as c ON b.referenzberufzugewiesen = c.berufid
                    LEFT JOIN fe_groups as d ON niqidberatungsstelle = d.niqbid
                    WHERE
                    YEAR($filternach) LIKE $jahr
                    AND d.bundesland LIKE '$bundesland'
                    AND erste_staatsangehoerigkeit LIKE '$staat'
                    AND a.hidden = 0 and a.deleted = 0
                    AND b.teilnehmer IS NOT NULL
                    GROUP BY b.referenzberufzugewiesen ORDER BY anz DESC LIMIT 20";
        
        $query->statement($sql);
        $query = $query->execute(true);
        return $query;
    }
      
    /**
     * Geschlecht für Adminübersicht
     */
    public function showGeschlecht($type, $jahr, $bundesland, $staat) {
        
        if($type == 0) {
            // Anmeldungen (unbestätigt und bestätigt)
            $filternach = "FROM_UNIXTIME(verification_date)";
        } elseif($type == 4) {
            $filternach = "erstberatungabgeschlossen";
        } else {
            // fehler!
        }
        
        $query = $this->createQuery();
        
        $sql = "SELECT geschlecht, count(geschlecht) as anz
                    FROM  tx_iqtp13db_domain_model_teilnehmer as a
                    LEFT JOIN fe_groups as d ON niqidberatungsstelle = d.niqbid
                    WHERE
				    YEAR($filternach) LIKE $jahr
                    AND d.bundesland LIKE '$bundesland'
                    AND erste_staatsangehoerigkeit LIKE '$staat'
                    AND a.hidden = 0 and a.deleted = 0
                    GROUP BY geschlecht ORDER BY anz DESC";
        
        $query->statement($sql);
        $query = $query->execute(true);
        return $query;
    }
    
    /**
     * Alter für Adminübersicht
     */
    public function showAlter($type, $jahr, $bundesland, $staat) {
        
        if($type == 0) {
            // Anmeldungen (unbestätigt und bestätigt)
            $filternach = "FROM_UNIXTIME(verification_date)";
        } elseif($type == 4) {
            $filternach = "erstberatungabgeschlossen";
        } else {
            // fehler!
        }
        
        $query = $this->createQuery();
        
        $sql = "SELECT lebensalter, count(lebensalter) as anz
                    FROM  tx_iqtp13db_domain_model_teilnehmer as a
                    LEFT JOIN fe_groups as d ON niqidberatungsstelle = d.niqbid
                    WHERE
				    YEAR($filternach) LIKE $jahr
                    AND d.bundesland LIKE '$bundesland'
                    AND erste_staatsangehoerigkeit LIKE '$staat'
                    AND a.hidden = 0 and a.deleted = 0
                    GROUP BY lebensalter ORDER BY lebensalter DESC";
        
        $query->statement($sql);
        $query = $query->execute(true);
        return $query;
    }
    
    /**
     *
     */
    public function showAdminStatsBerufLand($type, $filtervon, $filterbis, $bundesland, $beratungsstelle, $beruf, $staat, $filterberufstaat)
    {
        if($type == 0) {
            $sqlberatungsstatus = " beratungsstatus >= 0 ";
            $filternach = "FROM_UNIXTIME(verification_date)";
        } elseif($type == 1) {
            $sqlberatungsstatus = " beratungsstatus >= 1 ";
            $filternach = "FROM_UNIXTIME(verification_date)";
        } elseif($type == 2) {
            $sqlberatungsstatus = " (beratungsstatus = 2 OR beratungsstatus = 3) ";
            $filternach = "beratungdatum";
        } elseif($type == 4) {
            $sqlberatungsstatus = " beratungsstatus = 4 ";
            $filternach = "erstberatungabgeschlossen";
        } elseif($type == 5) {
            $sqlberatungsstatus = " (beratungsstatus = 2 OR beratungsstatus = 3 OR beratungsstatus = 4) ";
            $filternach = "erstberatungabgeschlossen";
        } else {
            // fehler!
        }
        
        $query = $this->createQuery();
        
        if ($beruf == '%' && $filterberufstaat != 'staat') {
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
                    AND d.niqbid LIKE '$beratungsstelle' 
                    AND erste_staatsangehoerigkeit LIKE '$staat'
                    AND a.hidden = 0 and a.deleted = 0
                    GROUP BY referenzberufzugewiesen HAVING anz > 0 ORDER BY anz DESC";
        } elseif ($staat == '%' && $filterberufstaat != 'beruf') {
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
                    AND d.niqbid LIKE '$beratungsstelle'
                    AND b.referenzberufzugewiesen LIKE '$beruf'
                    AND a.hidden = 0 and a.deleted = 0
                    AND c.langisocode = 'de' 
                    GROUP BY erste_staatsangehoerigkeit HAVING anz > 0 ORDER BY anz DESC";
        } elseif($bundesland == '%') {
            // Ausgabe Liste Bundesland
            $sql = "SELECT d.bundesland as titel, count(d.bundesland) as anz
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
                    AND b.teilnehmer IS NOT NULL AND c.berufid IS NOT NULL
                    GROUP BY d.bundesland HAVING anz > 0 ORDER BY anz DESC";
        } else {
            $sql = "WITH temptable_gender AS
                    (
                      SELECT 1 as id,'weiblich' as val UNION
                      SELECT 2 as id,'männlich' as val UNION
                      SELECT 3 as id,'divers' as val
                    )
                    SELECT e.val as titel, count(*) as anz
                    FROM tx_iqtp13db_domain_model_teilnehmer as a
                    LEFT JOIN  tx_iqtp13db_domain_model_abschluss as b ON a.uid = b.teilnehmer
                    LEFT JOIN tx_iqtp13db_domain_model_berufe as c ON b.referenzberufzugewiesen = c.berufid
                    LEFT JOIN fe_groups as d ON niqidberatungsstelle = d.niqbid
                    LEFT JOIN temptable_gender as e ON a.geschlecht = e.id
                    WHERE
                    DATEDIFF(STR_TO_DATE('$filtervon', '%d.%m.%Y'),$filternach) <= 0 AND
				    DATEDIFF(STR_TO_DATE('$filterbis', '%d.%m.%Y'),$filternach) >= 0
                    AND $sqlberatungsstatus
                    AND d.bundesland LIKE '$bundesland'
                    AND b.referenzberufzugewiesen LIKE '$beruf'
                    AND erste_staatsangehoerigkeit LIKE '$staat'
                    AND a.hidden = 0 and a.deleted = 0
                    AND b.teilnehmer IS NOT NULL AND c.berufid IS NOT NULL
                    GROUP BY geschlecht HAVING anz > 0 ORDER BY geschlecht";
        }

        $query->statement($sql);
        return $query->execute(true);
    }
}
