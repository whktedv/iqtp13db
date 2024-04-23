<?php
namespace Ud\Iqtp13db\Domain\Repository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;

use \TYPO3\CMS\Extbase\Persistence\QueryInterface;
use \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;

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
    public function searchTeilnehmer($type, $filterArray, $verstecktundgelöscht, $niqbid, $berufearr, $orderby, $order, $thisusergroup, $limit)
    {
        $uid = $filterArray['uid'];
        $name = $filterArray['name'];
        $ort = $filterArray['ort'];
        $land = $filterArray['land'];
        $berater = $filterArray['berater'];
        $gruppe = $filterArray['gruppe'];
        $fberuf = $filterArray['beruf'];        
        $fbescheid = $filterArray['bescheid'];
        
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_iqtp13db_domain_model_teilnehmer');
        
        $whereExpressions = array();
        $orwhereExpressionsName = array();
        $orwhereExpressionsOrt = array();
        $andwhereExpressionAntrag = array();
        $orwhereExpressionsBeratungsstatus = array();
        $orwhereExpressionsBeruf = array();
        
        $whereExpressions = [
            $queryBuilder->expr()->eq('niqidberatungsstelle', $queryBuilder->createNamedParameter($niqbid, Connection::PARAM_INT)),
            //$queryBuilder->expr()->eq('tx_iqtp13db_domain_model_teilnehmer.deleted', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT))
        ];
        
        if($uid != '') {
            $whereExpressions[] = $queryBuilder->expr()->eq('tx_iqtp13db_domain_model_teilnehmer.uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT));
        }
        if($name != '') {
            $orwhereExpressionsName = [
                $queryBuilder->expr()->like('nachname', $queryBuilder->createNamedParameter('%' . $queryBuilder->escapeLikeWildcards($name) . '%')),
                $queryBuilder->expr()->like('vorname', $queryBuilder->createNamedParameter('%' . $queryBuilder->escapeLikeWildcards($name) . '%'))
            ];
        }
        if($ort != '') {
            $orwhereExpressionsOrt = [
                $queryBuilder->expr()->like('ort', $queryBuilder->createNamedParameter('%' . $queryBuilder->escapeLikeWildcards($ort) . '%')),
                $queryBuilder->expr()->like('plz', $queryBuilder->createNamedParameter('%' . $queryBuilder->escapeLikeWildcards($ort) . '%'))
            ];
        }
        if($land != '') {
            $whereExpressions[] = $queryBuilder->expr()->like('geburtsland', $queryBuilder->createNamedParameter('%' . $queryBuilder->escapeLikeWildcards($land) . '%'));
        }
        if($berater != '') {
            $whereExpressions[] = $queryBuilder->expr()->eq('tx_iqtp13db_domain_model_teilnehmer.berater', $queryBuilder->createNamedParameter($berater, Connection::PARAM_INT));
        }
        if($gruppe != '') {
            $whereExpressions[] = $queryBuilder->expr()->like('kooperationgruppe', $queryBuilder->createNamedParameter('%' . $queryBuilder->escapeLikeWildcards($gruppe) . '%'));
        }
        
        // Beruf        
        if($type != 0) {
            if($fberuf != '') {
                foreach ($berufearr as $beruf) {
                    if (strpos(strtolower($beruf->getTitel()), strtolower($fberuf)) !== false) { $results[] = $beruf->getBerufid(); }
                }
                if(!empty($results)) {
                    $whereExpressions[] = $queryBuilder->expr()->in('a.referenzberufzugewiesen', $queryBuilder->createNamedParameter($results, Connection::PARAM_INT_ARRAY));
                }
            } else {
                $orwhereExpressionsBeruf = [
                    $queryBuilder->expr()->like('a.deutscher_referenzberuf', $queryBuilder->createNamedParameter('%' . $queryBuilder->escapeLikeWildcards($fberuf) . '%')),
                    $queryBuilder->expr()->isNull('a.deutscher_referenzberuf')
                ];
            }
        } else {
            if($fberuf != '') {
                $whereExpressions[] = $queryBuilder->expr()->like('a.deutscher_referenzberuf', $queryBuilder->createNamedParameter('%' . $queryBuilder->escapeLikeWildcards($fberuf) . '%'));
            } else {
                $orwhereExpressionsBeruf = [
                    $queryBuilder->expr()->like('a.deutscher_referenzberuf', $queryBuilder->createNamedParameter('%' . $queryBuilder->escapeLikeWildcards($fberuf) . '%')),
                    $queryBuilder->expr()->isNull('a.deutscher_referenzberuf')
                ];
            }
        }
        
        // Antragstellungvorher / Bescheid
        if($fbescheid != '') {
            $andwhereExpressionAntrag[] = [
                $queryBuilder->expr()->gt('a.antragstellungvorher', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT)),
                $queryBuilder->expr()->lt('a.antragstellungvorher', $queryBuilder->createNamedParameter(4, Connection::PARAM_INT))
            ];
        }    
        
        // Beratungsstatus        
        if($type == 0 || $type == 1) {
            $orwhereExpressionsBeratungsstatus = [
                $queryBuilder->expr()->eq('beratungsstatus', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT)),
                $queryBuilder->expr()->eq('beratungsstatus', $queryBuilder->createNamedParameter(1, Connection::PARAM_INT))
            ];
        } elseif($type == 2 || $type == 3) {
            $orwhereExpressionsBeratungsstatus = [
                $queryBuilder->expr()->eq('beratungsstatus', $queryBuilder->createNamedParameter(2, Connection::PARAM_INT)),
                $queryBuilder->expr()->eq('beratungsstatus', $queryBuilder->createNamedParameter(3, Connection::PARAM_INT))
            ];
        } elseif($type == 999) {
            //
        } else {
            $whereExpressions[] = $queryBuilder->expr()->eq('beratungsstatus', $queryBuilder->createNamedParameter(4, Connection::PARAM_INT));
        }
        
        if($verstecktundgelöscht == 1) {
            $whereExpressions[] = $queryBuilder->expr()->eq('tx_iqtp13db_domain_model_teilnehmer.hidden', $queryBuilder->createNamedParameter(1, Connection::PARAM_INT));
        } elseif($verstecktundgelöscht == 0) {
            $whereExpressions[] = $queryBuilder->expr()->eq('tx_iqtp13db_domain_model_teilnehmer.hidden', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT));
        } 
        
        $orderby = $orderby == 'verificationDate' ? 'verification_date' : $orderby;
        
        //$limitsql = $limit == 0 ? '' : ' LIMIT '.$limit;
        
        $result = $queryBuilder
            ->select('tx_iqtp13db_domain_model_teilnehmer.*')
            ->from('tx_iqtp13db_domain_model_teilnehmer')
            ->leftJoin(
                'tx_iqtp13db_domain_model_teilnehmer',
                'tx_iqtp13db_domain_model_abschluss',
                'a',
                $queryBuilder->expr()->eq('a.teilnehmer',$queryBuilder->quoteIdentifier('tx_iqtp13db_domain_model_teilnehmer.uid'))
            )
            ->leftJoin(
                'tx_iqtp13db_domain_model_teilnehmer',
                'fe_users',
                'berater',
                $queryBuilder->expr()->eq('berater.uid',$queryBuilder->quoteIdentifier('tx_iqtp13db_domain_model_teilnehmer.berater'))
                )
            ->where(
                $queryBuilder->expr()->or(...$orwhereExpressionsName),
                $queryBuilder->expr()->or(...$orwhereExpressionsOrt),
                $queryBuilder->expr()->or(...$orwhereExpressionsBeruf),
                $queryBuilder->expr()->or(...$orwhereExpressionsBeratungsstatus),
                $queryBuilder->expr()->and(...$andwhereExpressionAntrag),
                ...$whereExpressions,                
            )
            ->groupBy('tx_iqtp13db_domain_model_teilnehmer.uid')            
            ->orderBy($orderby, $order)
            ->addOrderBy('uid', 'DESC')
            ->executeQuery();

           //DebuggerUtility::var_dump($queryBuilder->getSQL());
           //DebuggerUtility::var_dump($queryBuilder->getParameters());
           //die;
        
        $dataMapper = GeneralUtility::makeInstance(DataMapper::class);
        $teilnehmerresult = $dataMapper->map(\Ud\Iqtp13db\Domain\Model\Teilnehmer::class, $result->fetchAll());
        return $teilnehmerresult;
            
    }
    
    /**
     */    
    public function findAllOrder4List($beratungsstatus, $orderby, $order, $niqbid)
    {
        $query = $this->createQuery();
        
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
        if($type == 1) {
            $filternach = "FROM_UNIXTIME(verification_date)";
        } elseif($type == 2) {
            $filternach = "beratungdatum";
        } elseif($type == 3) {
            $filternach = "erstberatungabgeschlossen";
        } else {
            $filternach = "FROM_UNIXTIME(verification_date)";            
        }
        
        $query = $this->createQuery();
        
        if($verstecktundgelöscht == 1) {
            $query->getQuerySettings()->setIgnoreEnableFields(TRUE);
            $query->getQuerySettings()->setEnableFieldsToBeIgnored(array('disabled', 'hidden'));
            $hidden = " AND t.hidden = 1 ";
        } else {
            $hidden = " AND t.hidden = 0 AND t.deleted = 0 ";
        }
        
        $sql = "SELECT t.* FROM tx_iqtp13db_domain_model_teilnehmer t
    			LEFT JOIN tx_iqtp13db_domain_model_abschluss a ON a.teilnehmer = t.uid
                LEFT JOIN fe_groups as b on t.niqidberatungsstelle = b.niqbid
                WHERE
                DATEDIFF(STR_TO_DATE('$filtervon', '%d.%m.%Y'),$filternach) <= 0 AND
				DATEDIFF(STR_TO_DATE('$filterbis', '%d.%m.%Y'),$filternach) >= 0
                $hidden 
                AND niqidberatungsstelle LIKE '$niqbid'
                AND b.bundesland LIKE '$bundesland'
                AND t.erste_staatsangehoerigkeit LIKE '$staat'
                GROUP BY t.uid ORDER BY verification_date ASC";
        
        //DebuggerUtility::var_dump($sql);
        //die;
        
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
