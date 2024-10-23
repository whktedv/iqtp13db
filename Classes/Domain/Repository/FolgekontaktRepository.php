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
 * The repository for Folgekontakt
 */
class FolgekontaktRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * @param $niqbid
     */
    public function findAll4List($niqbid)
    {
        $query = $this->createQuery();
        $query->statement("SELECT a.* FROM tx_iqtp13db_domain_model_folgekontakt as a LEFT JOIN tx_iqtp13db_domain_model_teilnehmer as b ON a.teilnehmer = b.uid WHERE
                a.deleted = 0 AND b.niqidberatungsstelle LIKE '$niqbid' ORDER BY STR_TO_DATE(a.datum, '%d.%m.%Y') ");
        $query = $query->execute();
        
        return $query;
    }
    
    /**
     * @param $niqbid
     * @param $jahr
     */
    public function countFKby($niqbid, $bundesland, $jahr, $staat)
	{
	    $query = $this->createQuery();
	    if($jahr == 0) {
	        $query->statement("SELECT MONTH(STR_TO_DATE(a.datum, '%d.%m.%Y')) as monat, count(*) as anzahl
                FROM tx_iqtp13db_domain_model_folgekontakt as a
                INNER JOIN tx_iqtp13db_domain_model_teilnehmer as b ON a.teilnehmer = b.uid 
                LEFT JOIN fe_groups as g on b.niqidberatungsstelle = g.niqbid 
                WHERE YEAR(STR_TO_DATE(a.datum, '%d.%m.%Y')) = YEAR(CURRENT_DATE())
                AND a.deleted = 0 AND niqidberatungsstelle LIKE '$niqbid' AND g.bundesland LIKE '$bundesland' AND erste_staatsangehoerigkeit LIKE '$staat'
                GROUP BY MONTH(STR_TO_DATE(a.datum, '%d.%m.%Y'))
                UNION
                SELECT MONTH(STR_TO_DATE(a.datum, '%d.%m.%Y')) as monat, count(*) as anzahl
                FROM tx_iqtp13db_domain_model_folgekontakt as a
                INNER JOIN tx_iqtp13db_domain_model_teilnehmer as b ON a.teilnehmer = b.uid 
                LEFT JOIN fe_groups as g on b.niqidberatungsstelle = g.niqbid 
                WHERE YEAR(STR_TO_DATE(a.datum, '%d.%m.%Y')) = YEAR(CURRENT_DATE())-1 AND MONTH(STR_TO_DATE(a.datum, '%d.%m.%Y')) > MONTH(CURRENT_DATE())
                AND a.deleted = 0 AND niqidberatungsstelle LIKE '$niqbid' AND g.bundesland LIKE '$bundesland' AND erste_staatsangehoerigkeit LIKE '$staat'
                GROUP BY MONTH(STR_TO_DATE(a.datum, '%d.%m.%Y'))");
	    } else {
	        $query->statement("SELECT MONTH(STR_TO_DATE(a.datum, '%d.%m.%Y')) as monat, count(*) as anzahl
                FROM tx_iqtp13db_domain_model_folgekontakt as a
                INNER JOIN tx_iqtp13db_domain_model_teilnehmer as b ON a.teilnehmer = b.uid 
                LEFT JOIN fe_groups as g on b.niqidberatungsstelle = g.niqbid 
                WHERE YEAR(STR_TO_DATE(a.datum, '%d.%m.%Y')) = $jahr
                AND a.deleted = 0 AND niqidberatungsstelle LIKE '$niqbid' AND g.bundesland LIKE '$bundesland' AND erste_staatsangehoerigkeit LIKE '$staat'
                GROUP BY MONTH(STR_TO_DATE(a.datum, '%d.%m.%Y'))");
	    }
        $query = $query->execute(true);
        return $query;
	}
	
	/**
	 * @param $teilnehmeruid
	 */
	public function findLastByTNuid($teilnehmeruid)
	{
	    $query = $this->createQuery();
	    $query->matching($query->like('teilnehmer', $teilnehmeruid));
        $query->setOrderings(array('uid' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        $query->setLimit(1);
	    $query = $query->execute();
	    return $query[0];
	}
	
	public function fk4StatusFK2022($datum1, $datum2, $niqbid)
	{
	    $query = $this->createQuery();
	    $query->statement("SELECT * FROM tx_iqtp13db_domain_model_folgekontakt as a
                LEFT JOIN tx_iqtp13db_domain_model_teilnehmer as b ON a.teilnehmer = b.uid
                WHERE DATEDIFF(STR_TO_DATE(datum, '%d.%m.%Y'), '2022-12-31') > 0 AND
                DATEDIFF(STR_TO_DATE('31.12.2022', '%d.%m.%Y'),erstberatungabgeschlossen) >= 0 AND
                DATEDIFF(STR_TO_DATE('".$datum1."', '%d.%m.%Y'),STR_TO_DATE(datum, '%d.%m.%Y')) <= 0 AND
				DATEDIFF(STR_TO_DATE('".$datum2."', '%d.%m.%Y'),STR_TO_DATE(datum, '%d.%m.%Y')) >= 0 AND
        		b.deleted = 0 AND b.hidden = 0 AND niqidberatungsstelle LIKE '$niqbid' GROUP BY teilnehmer");
	    
	    
	    $query = $query->execute();
	    return $query;
	}
	
	/**
	 *
	 */
	public function fksearch4export($filtervon, $filterbis, $niqbid, $bundesland, $staat, $berater, $landkreis, $beruf, $branche)
	{
	    
	    $niqbid = $niqbid == '12345' ? '%' : $niqbid; // Admin? dann Beratungsstelle ignorieren
	    
	    $query = $this->createQuery();
	    
	    $sql = "SELECT * FROM tx_iqtp13db_domain_model_folgekontakt as f
                INNER JOIN tx_iqtp13db_domain_model_teilnehmer as t ON f.teilnehmer = t.uid
                LEFT JOIN tx_iqtp13db_domain_model_abschluss as a ON f.teilnehmer = a.teilnehmer
                LEFT JOIN fe_groups as b on t.niqidberatungsstelle = b.niqbid
                LEFT JOIN tx_iqtp13db_domain_model_ort o ON t.plz = o.plz ";
	    $sql .= "WHERE
                STR_TO_DATE(f.datum, '%d.%m.%Y') BETWEEN STR_TO_DATE('$filtervon', '%d.%m.%Y') AND STR_TO_DATE('$filterbis', '%d.%m.%Y')
                AND niqidberatungsstelle LIKE '$niqbid' AND t.hidden = 0 AND t.deleted = 0";
                if($bundesland != '%') $sql .= " AND b.bundesland LIKE '$bundesland'";
                if($staat != '%') $sql .= " AND t.erste_staatsangehoerigkeit LIKE '$staat'";
                if($berater != '%') $sql .= " AND t.berater LIKE '$berater'";
                if($landkreis != '%') $sql .= " AND o.landkreis LIKE '$landkreis'";
                if($beruf != '%') $sql .= " AND a.referenzberufzugewiesen LIKE '$beruf'";
                if($branche != '%') $sql .= " AND a.branche LIKE '$branche'";
        $sql .= " GROUP BY t.uid ORDER BY f.datum ASC LIMIT 500";
                  
        //(\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($sql);
        //die;
        $query->statement($sql);
        
        return $query->execute();
	}
	
}
