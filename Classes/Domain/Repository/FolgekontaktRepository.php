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
                a.deleted = 0 AND b.niqidberatungsstelle LIKE '$niqbid' ORDER BY STR_TO_DATE(a.datum, '%Y-%m-%d') ");
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
	        $query->statement("SELECT MONTH(STR_TO_DATE(a.datum, '%Y-%m-%d')) as monat, count(*) as anzahl
                FROM tx_iqtp13db_domain_model_folgekontakt as a
                INNER JOIN tx_iqtp13db_domain_model_teilnehmer as b ON a.teilnehmer = b.uid 
                LEFT JOIN fe_groups as g on b.niqidberatungsstelle = g.niqbid 
                WHERE YEAR(STR_TO_DATE(a.datum, '%Y-%m-%d')) = YEAR(CURRENT_DATE())
                AND a.deleted = 0 AND niqidberatungsstelle LIKE '$niqbid' AND g.bundesland LIKE '$bundesland' AND erste_staatsangehoerigkeit LIKE '$staat'
                GROUP BY MONTH(STR_TO_DATE(a.datum, '%Y-%m-%d'))
                UNION
                SELECT MONTH(STR_TO_DATE(a.datum, '%Y-%m-%d')) as monat, count(*) as anzahl
                FROM tx_iqtp13db_domain_model_folgekontakt as a
                INNER JOIN tx_iqtp13db_domain_model_teilnehmer as b ON a.teilnehmer = b.uid 
                LEFT JOIN fe_groups as g on b.niqidberatungsstelle = g.niqbid 
                WHERE YEAR(STR_TO_DATE(a.datum, '%Y-%m-%d')) = YEAR(CURRENT_DATE())-1 AND MONTH(STR_TO_DATE(a.datum, '%Y-%m-%d')) > MONTH(CURRENT_DATE())
                AND a.deleted = 0 AND niqidberatungsstelle LIKE '$niqbid' AND g.bundesland LIKE '$bundesland' AND erste_staatsangehoerigkeit LIKE '$staat'
                GROUP BY MONTH(STR_TO_DATE(a.datum, '%Y-%m-%d'))");
	    } elseif($jahr == 99) {
	        $query->statement("SELECT MONTH(STR_TO_DATE(a.datum, '%Y-%m-%d')) as monat, count(*) as anzahl
                FROM tx_iqtp13db_domain_model_folgekontakt as a
                INNER JOIN tx_iqtp13db_domain_model_teilnehmer as b ON a.teilnehmer = b.uid
                LEFT JOIN fe_groups as g on b.niqidberatungsstelle = g.niqbid
                WHERE a.deleted = 0 AND niqidberatungsstelle LIKE '$niqbid' AND g.bundesland LIKE '$bundesland' AND erste_staatsangehoerigkeit LIKE '$staat' AND YEAR(STR_TO_DATE(a.datum, '%Y-%m-%d')) > 2022
                GROUP BY MONTH(STR_TO_DATE(a.datum, '%Y-%m-%d'))");
	    } else {
	        $sql = "SELECT MONTH(STR_TO_DATE(a.datum, '%Y-%m-%d')) as monat, count(*) as anzahl
                FROM tx_iqtp13db_domain_model_folgekontakt as a
                INNER JOIN tx_iqtp13db_domain_model_teilnehmer as b ON a.teilnehmer = b.uid
                LEFT JOIN fe_groups as g on b.niqidberatungsstelle = g.niqbid
                WHERE YEAR(STR_TO_DATE(a.datum, '%Y-%m-%d')) = $jahr
                AND a.deleted = 0 AND niqidberatungsstelle LIKE '$niqbid' AND g.bundesland LIKE '$bundesland' AND erste_staatsangehoerigkeit LIKE '$staat'
                GROUP BY MONTH(STR_TO_DATE(a.datum, '%Y-%m-%d'))";
	        $query->statement($sql);
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
                WHERE DATEDIFF(STR_TO_DATE(datum, '%Y-%m-%d'), '2022-12-31') > 0 AND
                DATEDIFF(STR_TO_DATE('31.12.2022', '%Y-%m-%d'),erstberatungabgeschlossen) >= 0 AND
                DATEDIFF(STR_TO_DATE('".$datum1."', '%Y-%m-%d'),STR_TO_DATE(datum, '%Y-%m-%d')) <= 0 AND
				DATEDIFF(STR_TO_DATE('".$datum2."', '%Y-%m-%d'),STR_TO_DATE(datum, '%Y-%m-%d')) >= 0 AND
        		b.deleted = 0 AND b.hidden = 0 AND niqidberatungsstelle LIKE '$niqbid' GROUP BY teilnehmer");
	    
	    
	    $query = $query->execute();
	    return $query;
	}
	
	public function fk4StatusFK2025($datum1, $datum2, $niqbid)
	{
	    $query = $this->createQuery();
	    $query->statement("SELECT * FROM tx_iqtp13db_domain_model_folgekontakt as a
                LEFT JOIN tx_iqtp13db_domain_model_teilnehmer as b ON a.teilnehmer = b.uid
                WHERE
                DATEDIFF(STR_TO_DATE(datum, '%Y-%m-%d'), '2025-12-31') > 0 AND
                DATEDIFF(STR_TO_DATE('31.12.2025', '%d.%m.%Y'),erstberatungabgeschlossen) >= 0 AND
                DATEDIFF(STR_TO_DATE('".$datum1."', '%d.%m.%Y'),STR_TO_DATE(datum, '%Y-%m-%d')) <= 0 AND
				DATEDIFF(STR_TO_DATE('".$datum2."', '%d.%m.%Y'),STR_TO_DATE(datum, '%Y-%m-%d')) >= 0 AND
        		b.deleted = 0 AND b.hidden = 0 AND a.deleted = 0 AND niqidberatungsstelle LIKE '$niqbid' GROUP BY teilnehmer");
	    
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
	    
	    $sql = "SELECT f.uid, f.teilnehmer, f.datum, f.berater, f.notizen, f.beratungsform, f.beratungsdauer FROM tx_iqtp13db_domain_model_folgekontakt as f
                INNER JOIN tx_iqtp13db_domain_model_teilnehmer as t ON f.teilnehmer = t.uid
                LEFT JOIN tx_iqtp13db_domain_model_abschluss as a ON f.teilnehmer = a.teilnehmer
                LEFT JOIN fe_groups as b on t.niqidberatungsstelle = b.niqbid
                LEFT JOIN tx_iqtp13db_domain_model_ort o ON t.plz = o.plz ";
	    $sql .= "WHERE
                STR_TO_DATE(f.datum, '%Y-%m-%d') BETWEEN STR_TO_DATE('$filtervon', '%d.%m.%Y') AND STR_TO_DATE('$filterbis', '%d.%m.%Y')
                AND niqidberatungsstelle LIKE '$niqbid' AND t.hidden = 0 AND t.deleted = 0 AND f.deleted = 0 AND f.hidden = 0";
                if($bundesland != '%') $sql .= " AND b.bundesland LIKE '$bundesland'";
                if($staat != '%') $sql .= " AND t.erste_staatsangehoerigkeit LIKE '$staat'";
                if($berater != '%') $sql .= " AND f.berater LIKE '$berater'";
                if($landkreis != '%') $sql .= " AND o.landkreis LIKE '$landkreis'";
                if($beruf != '%') $sql .= " AND a.referenzberufzugewiesen LIKE '$beruf'";
                if($branche != '%') $sql .= " AND a.branche LIKE '$branche'";
        $sql .= " GROUP BY f.uid ORDER BY f.datum ASC";

        $query->statement($sql);
        return $query->execute(true);
	}
	
	/**
	 *
	 */
	public function fksearch4exportFK2025($filtervon, $filterbis, $niqbid, $bundesland, $staat, $berater, $landkreis, $beruf, $branche)
	{
	    $niqbid = $niqbid == '12345' ? '%' : $niqbid; // Admin? dann Beratungsstelle ignorieren
	    
	    $query = $this->createQuery();
	    //fk.uid AS fkuid, fk.datum, fk.berater AS fkberater, fk.notizen AS fknotizen, fk.beratungsform as fkberatungsform, fk.beratungsdauer AS fkberatungsdauer,
	    $sql = "
            SELECT
            fk.fkdatum,
            fk.fkberater,
            fk.fknotizen,
            fk.fkberatungsform,
            a.uid,
            a.verification_date,
            a.nachname, 
            a.vorname,
            a.strasse,
            a.plz,
            a.ort,
            a.email,
            a.telefon,
            a.lebensalter,
            a.gebdat,
            a.erste_staatsangehoerigkeit,
            a.zweite_staatsangehoerigkeit,
            a.wohnsitz_deutschland,
            a.einreisejahr,
            a.wohnsitz_nein_in,
            a.deutschkenntnisse,
            a.zertifikat_sprachniveau,
            a.weiteresprachkenntnisse,
            a.sonstigerstatus,
            a.erwerbsstatus,
            a.leistungsbezugjanein,
            a.leistungsbezug,
            a.geburtsland,
            a.aufenthaltsstatus,
            a.geschlecht,
            a.notizen,
            a.berater,
            a.beratungsart,
            a.beratungsort,
            a.anerkennungsberatung,
            a.qualifizierungsberatung,
            a.name_beratungsstelle,
            a.beratungnotizen,
            a.beratungzu,
            fk.anzahl_folgekontakte,
            fk.gesamt_beratungsdauer,
            a.kooperationgruppe,
            a.beratungsdauer,
            a.beratungdatum,
            a.erstberatungabgeschlossen,
            a.einwilligunginfo,
            MAX(CASE WHEN b.rn = 1 THEN b.titel END)            AS abschluss1_beruf,
            MAX(CASE WHEN b.rn = 1 THEN b.sonstigerberuf END)   AS abschluss1_sonstigerberuf,
            MAX(CASE WHEN b.rn = 1 THEN b.nregberuf END)        AS abschluss1_nregberuf,
            MAX(CASE WHEN b.rn = 1 THEN b.abschlussart END)     AS abschluss1_art,
            MAX(CASE WHEN b.rn = 1 THEN b.branche END)          AS abschluss1_branche,
            MAX(CASE WHEN b.rn = 1 THEN b.erwerbsland END)      AS abschluss1_erwerbsland,
            MAX(CASE WHEN b.rn = 1 THEN b.abschlussjahr END)    AS abschluss1_jahr,
            MAX(CASE WHEN b.rn = 1 THEN b.ausbildungsort END)   AS abschluss1_ausbildungsort,
            MAX(CASE WHEN b.rn = 1 THEN b.abschluss END)        AS abschluss1_abschluss,
            MAX(CASE WHEN b.rn = 1 THEN b.dauer_berufsausbildung END) AS abschluss1_dauer,
            MAX(CASE WHEN b.rn = 1 THEN b.ausbildungsinstitution END) AS abschluss1_institution,
            MAX(CASE WHEN b.rn = 1 THEN b.berufserfahrung END)  AS abschluss1_berufserfahrung,
            MAX(CASE WHEN b.rn = 1 THEN b.wunschberuf END)      AS abschluss1_wunschberuf,
            MAX(CASE WHEN b.rn = 1 THEN b.deutscher_referenzberuf END) AS abschluss1_refberuf,
            MAX(CASE WHEN b.rn = 1 THEN b.antragstellungerfolgt END)   AS abschluss1_antrag,
            MAX(CASE WHEN b.rn = 2 THEN b.titel END)            AS abschluss2_beruf,
            MAX(CASE WHEN b.rn = 2 THEN b.sonstigerberuf END)   AS abschluss2_sonstigerberuf,
            MAX(CASE WHEN b.rn = 2 THEN b.nregberuf END)        AS abschluss2_nregberuf,
            MAX(CASE WHEN b.rn = 2 THEN b.abschlussart END)     AS abschluss2_art,
            MAX(CASE WHEN b.rn = 2 THEN b.branche END)          AS abschluss2_branche,
            MAX(CASE WHEN b.rn = 2 THEN b.erwerbsland END)      AS abschluss2_erwerbsland,
            MAX(CASE WHEN b.rn = 2 THEN b.abschlussjahr END)    AS abschluss2_jahr,
            MAX(CASE WHEN b.rn = 2 THEN b.ausbildungsort END)   AS abschluss2_ausbildungsort,
            MAX(CASE WHEN b.rn = 2 THEN b.abschluss END)        AS abschluss2_abschluss,
            MAX(CASE WHEN b.rn = 2 THEN b.dauer_berufsausbildung END) AS abschluss2_dauer,
            MAX(CASE WHEN b.rn = 2 THEN b.ausbildungsinstitution END) AS abschluss2_institution,
            MAX(CASE WHEN b.rn = 2 THEN b.berufserfahrung END)  AS abschluss2_berufserfahrung,
            MAX(CASE WHEN b.rn = 2 THEN b.wunschberuf END)      AS abschluss2_wunschberuf,
            MAX(CASE WHEN b.rn = 2 THEN b.deutscher_referenzberuf END) AS abschluss2_refberuf,
            MAX(CASE WHEN b.rn = 2 THEN b.antragstellungerfolgt END)   AS abschluss2_antrag,
            MAX(CASE WHEN b.rn = 3 THEN b.titel END)            AS abschluss3_beruf,
            MAX(CASE WHEN b.rn = 3 THEN b.sonstigerberuf END)   AS abschluss3_sonstigerberuf,
            MAX(CASE WHEN b.rn = 3 THEN b.nregberuf END)        AS abschluss3_nregberuf,
            MAX(CASE WHEN b.rn = 3 THEN b.abschlussart END)     AS abschluss3_art,
            MAX(CASE WHEN b.rn = 3 THEN b.branche END)          AS abschluss3_branche,
            MAX(CASE WHEN b.rn = 3 THEN b.erwerbsland END)      AS abschluss3_erwerbsland,
            MAX(CASE WHEN b.rn = 3 THEN b.abschlussjahr END)    AS abschluss3_jahr,
            MAX(CASE WHEN b.rn = 3 THEN b.ausbildungsort END)   AS abschluss3_ausbildungsort,
            MAX(CASE WHEN b.rn = 3 THEN b.abschluss END)        AS abschluss3_abschluss,
            MAX(CASE WHEN b.rn = 3 THEN b.dauer_berufsausbildung END) AS abschluss3_dauer,
            MAX(CASE WHEN b.rn = 3 THEN b.ausbildungsinstitution END) AS abschluss3_institution,
            MAX(CASE WHEN b.rn = 3 THEN b.berufserfahrung END)  AS abschluss3_berufserfahrung,
            MAX(CASE WHEN b.rn = 3 THEN b.wunschberuf END)      AS abschluss3_wunschberuf,
            MAX(CASE WHEN b.rn = 3 THEN b.deutscher_referenzberuf END) AS abschluss3_refberuf,
            MAX(CASE WHEN b.rn = 3 THEN b.antragstellungerfolgt END)   AS abschluss3_antrag,
            MAX(CASE WHEN b.rn = 4 THEN b.titel END)            AS abschluss4_beruf,
            MAX(CASE WHEN b.rn = 4 THEN b.sonstigerberuf END)   AS abschluss4_sonstigerberuf,
            MAX(CASE WHEN b.rn = 4 THEN b.nregberuf END)        AS abschluss4_nregberuf,
            MAX(CASE WHEN b.rn = 4 THEN b.abschlussart END)     AS abschluss4_art,
            MAX(CASE WHEN b.rn = 4 THEN b.branche END)          AS abschluss4_branche,
            MAX(CASE WHEN b.rn = 4 THEN b.erwerbsland END)      AS abschluss4_erwerbsland,
            MAX(CASE WHEN b.rn = 4 THEN b.abschlussjahr END)    AS abschluss4_jahr,
            MAX(CASE WHEN b.rn = 4 THEN b.ausbildungsort END)   AS abschluss4_ausbildungsort,
            MAX(CASE WHEN b.rn = 4 THEN b.abschluss END)        AS abschluss4_abschluss,
            MAX(CASE WHEN b.rn = 4 THEN b.dauer_berufsausbildung END) AS abschluss4_dauer,
            MAX(CASE WHEN b.rn = 4 THEN b.ausbildungsinstitution END) AS abschluss4_institution,
            MAX(CASE WHEN b.rn = 4 THEN b.berufserfahrung END)  AS abschluss4_berufserfahrung,
            MAX(CASE WHEN b.rn = 4 THEN b.wunschberuf END)      AS abschluss4_wunschberuf,
            MAX(CASE WHEN b.rn = 4 THEN b.deutscher_referenzberuf END) AS abschluss4_refberuf,
            MAX(CASE WHEN b.rn = 4 THEN b.antragstellungerfolgt END)   AS abschluss4_antrag
            FROM tx_iqtp13db_domain_model_teilnehmer a
            LEFT JOIN fe_groups g ON a.niqidberatungsstelle = g.niqbid
            LEFT JOIN (
                SELECT
                fk.uid AS fkuid,
                fk.teilnehmer,
                fk.berater AS fkberater,
                fk.notizen AS fknotizen,
                fk.beratungsform AS fkberatungsform,
                fk.beratungsdauer AS fkberatungsdauer,
                MAX(fk.datum) AS fkdatum,
                COUNT(*) AS anzahl_folgekontakte,
                SUM(CAST(REPLACE(fk.beratungsdauer, ',', '.') AS DECIMAL(10,2))) AS gesamt_beratungsdauer
                FROM tx_iqtp13db_domain_model_folgekontakt fk 
                GROUP BY fk.teilnehmer 
                ) fk ON fk.teilnehmer = a.uid                 
            LEFT JOIN (
                SELECT
                ab.teilnehmer,
                ab.sonstigerberuf,
                ab.nregberuf,
                ab.abschlussart,
                br.titel AS branche,
                br.brancheid AS brancheid,
                st.titel AS erwerbsland,
                ab.abschlussjahr,
                ab.ausbildungsort,
                ab.abschluss,
                ab.dauer_berufsausbildung,
                ab.ausbildungsinstitution,
                ab.berufserfahrung,
                ab.wunschberuf,
                ab.deutscher_referenzberuf,
                ab.antragstellungerfolgt,
                ab.referenzberufzugewiesen,
                c.titel,
                ROW_NUMBER() OVER (PARTITION BY ab.teilnehmer ORDER BY ab.uid) AS rn
                FROM tx_iqtp13db_domain_model_abschluss ab
                LEFT JOIN (
                    SELECT berufid, titel
                    FROM tx_iqtp13db_domain_model_berufe
                    WHERE langisocode = 'de'
                    ) c ON c.berufid = ab.referenzberufzugewiesen
                LEFT JOIN (
                    SELECT brancheid, titel
                    FROM tx_iqtp13db_domain_model_branche
                    WHERE langisocode = 'de'
                    ) br ON br.brancheid = ab.branche
                LEFT JOIN (
                    SELECT staatid, titel
                    FROM tx_iqtp13db_domain_model_staaten
                    WHERE langisocode = 'de'
                    ) st ON ab.erwerbsland = st.staatid
                ) b ON b.teilnehmer = a.uid
            LEFT JOIN tx_iqtp13db_domain_model_ort o ON a.plz = o.plz ";
	    $sql .= " WHERE 
                DATEDIFF(STR_TO_DATE(fk.fkdatum, '%Y-%m-%d'), '2025-12-31') > 0 AND 
                DATEDIFF(STR_TO_DATE('31.12.2025', '%d.%m.%Y'),a.erstberatungabgeschlossen) >= 0 AND 
                STR_TO_DATE(fk.fkdatum, '%Y-%m-%d') BETWEEN STR_TO_DATE('$filtervon', '%d.%m.%Y') AND STR_TO_DATE('$filterbis', '%d.%m.%Y') 
                AND niqidberatungsstelle LIKE '$niqbid' AND a.hidden = 0 AND a.deleted = 0 ";
	    if($bundesland != '%') $sql .= " AND g.bundesland LIKE '$bundesland'";
	    if($staat != '%') $sql .= " AND a.erste_staatsangehoerigkeit LIKE '$staat'";
	    if($berater != '%') $sql .= " AND fk.berater LIKE '$berater'";
	    if($landkreis != '%') $sql .= " AND o.landkreis LIKE '$landkreis'";
	    if($beruf != '%') $sql .= " AND b.referenzberufzugewiesen LIKE '$beruf'";
	    if($branche != '%') $sql .= " AND b.branche LIKE '$branche'";
	    $sql .= " GROUP BY a.uid ORDER BY fk.fkdatum ASC";

	    $query->statement($sql);
	    return $query->execute(true);
	}
}
