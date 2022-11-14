<?php
namespace Ud\Iqtp13db\Helper;
use \Datetime;

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
 * NiqInterface
 */
class NiqInterface 
{
    
    /**
     * NIQ "Status" abfragen
     *  return:
     *  rot = 0
     *  gelb = 2
     *  grün = 1
     */
    public function niqstatus($teilnehmer, $abschluesse) {
        
        // ALTE Datensätze vor 18.03.2022 Erstberatung abgeschlossen sind nicht aktualisierbar!!!!
        if($teilnehmer->getNiqchiffre() != '') {
            //if($teilnehmer->getErstberatungabgeschlossen() != '' && $teilnehmer->getErstberatungabgeschlossen() != 0 && $teilnehmer->getErstberatungabgeschlossen() != '0000-00-00') {
            if($this->validateDateYmd($teilnehmer->getErstberatungabgeschlossen())) {
                if (DateTime::createFromFormat('d.m.Y', $teilnehmer->getErstberatungabgeschlossen()) !== false) {
                    $date = date_create_from_format('d.m.Y', $teilnehmer->getErstberatungabgeschlossen());
                } else {
                    $date = date_create_from_format('Y-m-d', $teilnehmer->getErstberatungabgeschlossen());
                }            
                if ($date->getTimestamp() < 1647558000) return 0; //rot - Datum 18.03.2022
            }
        }
        
        // Check, ob in der Untertabelle zur Anerkennungsberatung (Checkboxen) mindestens ein Element ausgewählt ist.
        // Wenn ja, weiter, wenn nein: Check, ob in der Untertabelle zur Qualifizierungsungsberatung (Checkboxen) mindestens ein Element ausgewählt ist.
        //Wenn ja, weiter, wenn nein $vollst = 0; // Setze rot  --> exit
        $berarr = $teilnehmer->getAnerkennungsberatung();
        $quaarr = $teilnehmer->getQualifizierungsberatung();        
        if(count($berarr) == 1 && $berarr[0] == '') {            
            if(count($quaarr) == 1 && $quaarr[0] == '') {
                return 0; //rot
            }           
        }
        
        // Check, ob in der Untertabelle Berufe bzw. Abschlüsse mindestens ein Referenzberuf angewählt ist.
        // Wenn ja, weiter, wenn nein $vollst = 0; // Setze rot  --> exit        
        if(count($abschluesse) == 0) {
            return 0; //rot
        }

        if (!$this->validateDateYmd($teilnehmer->getBeratungdatum())) return 0; //rot
        if(!$this->validateDateYmd($teilnehmer->getErstberatungabgeschlossen())) return 0; //rot
        
        // Überprüfe Untertabelle Berufe bzw. Abschlüsse:
        foreach ($abschluesse as $abschluss) {
            if($abschluss->getNiquebertragung() == 1) {
                $niqberufausgewaehlt = 1;
                $back = 1; // Setze grün
                $abschlussartarray = $abschluss->getAbschlussart();

                if ($abschluss->getErwerbsland() < -1) { $back = 2; }                
                elseif ($abschlussartarray[0] == '')  { $back = 2; }
                elseif ($abschluss->getBerufserfahrung() < -1)  { $back = 2; }              
                elseif ($abschluss->getAntragstellungvorher() <-1)  { $back = 2; }
                elseif ($abschluss->getAntragstellungvorher() == 1 && $abschluss->getAntragstellunggwpvorher() == 0)  { $back = 2; }
                elseif ($abschluss->getAntragstellungvorher() == 2 && $abschluss->getAntragstellungzabvorher() == 0)  { $back = 2; }
                elseif ($abschluss->getAntragstellungvorher() == 3 && ($abschluss->getAntragstellunggwpvorher() == 0 || $abschluss->getAntragstellungzabvorher() == 0))  { $back = 2; }
                if($back == 2) {
                    return 2; //gelb
                }
            }
        }
        if($niqberufausgewaehlt != 1) return 2;
        
        // Wenn weiterhin grün, überprüfe Stammtabelle
        
        if (!is_array($teilnehmer->getBeratungsart())) {
            if($teilnehmer->getBeratungsart() == '') return 0; //rot
        }
        
        //Ab hier kann es nur noch mit gelb enden, und zwar, wenn:
        if (!is_numeric($teilnehmer->getLebensalter())) return 2; //gelb
        elseif ($teilnehmer->getGeschlecht() == 0) return 2; //gelb 
        elseif ($teilnehmer->getWohnsitzDeutschland() == 0) return 2; //gelb; 
         
        // übertragene Staatsangehörigkeit ist standardmäßig die Erste
        $staatsangehoerigkeit = $teilnehmer->getErsteStaatsangehoerigkeit();
        // Wenn die erste Stattsangehörigkeit Deutsch ist, nimm die Zweite.
        if($teilnehmer->getErsteStaatsangehoerigkeit() == 37) $staatsangehoerigkeit = $teilnehmer->getZweiteStaatsangehoerigkeit() != -1000 ? $teilnehmer->getZweiteStaatsangehoerigkeit() : $teilnehmer->getErsteStaatsangehoerigkeit();
         
        elseif ($staatsangehoerigkeit == -1000) return 2; //gelb 
        if ($teilnehmer->getWohnsitzDeutschland() == 1 && $teilnehmer->getEinreisejahr() != '' && !is_numeric($teilnehmer->getEinreisejahr())) return 2; //gelb
        if ($teilnehmer->getWohnsitzDeutschland() == 1 && $teilnehmer->getEinreisejahr() != '' && ($teilnehmer->getEinreisejahr() < 1939 || $teilnehmer->getEinreisejahr() > date("Y"))) return 2; //gelb
        elseif ($teilnehmer->getWohnsitzDeutschland() == 2 && ($teilnehmer->getWohnsitzNeinIn() < -1 || $teilnehmer->getWohnsitzNeinIn() == '')) return 2; //gelb 
        elseif ($teilnehmer->getErwerbsstatus() == 0) return 2; //gelb 
        elseif ($teilnehmer->getAufenthaltsstatus() == 0) return 2; //gelb 
        elseif ($teilnehmer->getDeutschkenntnisse() == 0) return 2; //gelb 
        elseif ($teilnehmer->getDeutschkenntnisse() == 2 && ($teilnehmer->getZertifikatSprachniveau() == 1 || $teilnehmer->getZertifikatSprachniveau() == 1)) return 2; //gelb 
        elseif ($teilnehmer->getNameBeratungsstelle() < -1 || $teilnehmer->getNameBeratungsstelle() == '') return 2; //gelb 
        
        if($teilnehmer->getLeistungsbezugjanein() == 1 && $teilnehmer->getLeistungsbezug() == '') return 2;

        return 1; // grün         
    }
    
    /**
     * NIQ "Status" Was fehlt?
     *  return:
     *  rot = 0
     *  gelb = 2
     *  grün = 1
     */
    public function niqwasfehlt($teilnehmer, $abschluesse) {
        
        $returnarr = array();
        
        // ALTE Datensätze vor 18.03.2022 Erstberatung abgeschlossen sind nicht aktualisierbar!!!!
        if($teilnehmer->getNiqchiffre() != '') {
            if($this->validateDateYmd($teilnehmer->getErstberatungabgeschlossen())) {
                if (DateTime::createFromFormat('d.m.Y', $teilnehmer->getErstberatungabgeschlossen()) !== false) {
                    $date = date_create_from_format('d.m.Y', $teilnehmer->getErstberatungabgeschlossen());
                } else {
                    $date = date_create_from_format('Y-m-d', $teilnehmer->getErstberatungabgeschlossen());
                }
                if ($date->getTimestamp() < 1647558000) array_push($returnarr,"ALTER DATENSATZ: hier nicht aktualisierbar!"); //rot - Datum 18.03.2022
            }
        }
        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($post);
        //die();
        
        
        $berarr = $teilnehmer->getAnerkennungsberatung();
        $quaarr = $teilnehmer->getQualifizierungsberatung();
        if(count($berarr) == 1 && $berarr[0] == '') {
            if(count($quaarr) == 1 && $quaarr[0] == '') {
                array_push($returnarr, "Anerkennungsberatung oder Qualifizierungsberatung");
            }
        }
        
        // Check, ob in der Untertabelle Berufe bzw. Abschlüsse mindestens ein Referenzberuf angewählt ist.
        // Wenn ja, weiter, wenn nein $vollst = 0; // Setze rot  --> exit
        if(count($abschluesse) == 0) {
            array_push($returnarr, "Abschluss/Abschlüsse");
        }
        
        // Überprüfe Untertabelle Berufe bzw. Abschlüsse:
        foreach ($abschluesse as $abschluss) {
            if($abschluss->getNiquebertragung() == 1) {
                $niqberufausgewaehlt = 1;
                $abschlussartarray = $abschluss->getAbschlussart();
                
                if ($abschluss->getErwerbsland() < -1) { array_push($returnarr,"Erwerbsland"); }               
                if ($abschlussartarray[0] == '')  { array_push($returnarr,"Abschlussart"); }
                if ($abschluss->getBerufserfahrung() < -1)  { array_push($returnarr,"Berufserfahrung"); }
                if ($abschluss->getAntragstellungvorher() < -1)  { array_push($returnarr,"Antragstellung vorher"); }
                if ($abschluss->getAntragstellungvorher() == 1 && $abschluss->getAntragstellunggwpvorher() == 0)  { array_push($returnarr,"Antragstellung Gleichwertigkeitsprüfung"); }
                if ($abschluss->getAntragstellungvorher() == 2 && $abschluss->getAntragstellungzabvorher() == 0)  { array_push($returnarr,"Antragstellung ZAB-Zeugnisbewertung"); }
                if ($abschluss->getAntragstellungvorher() == 3 && ($abschluss->getAntragstellunggwpvorher() == 0 || $abschluss->getAntragstellungzabvorher() == 0))  { array_push($returnarr,"Antragstellung Gleichwertigkeitsprüfung und/oder ZAB-Zeugnisbewertung"); }                
            }
        }
        if($niqberufausgewaehlt != 1) array_push($returnarr,"Abschluss/Beruf komplett");
        
        // Wenn weiterhin grün, überprüfe Stammtabelle
        if (!$this->validateDateYmd($teilnehmer->getBeratungdatum())) array_push($returnarr,"Datum Erstberatung");
        if (!$this->validateDateYmd($teilnehmer->getErstberatungabgeschlossen())) array_push($returnarr,"Datum Erstberatung abgeschlossen");
        if (!is_array($teilnehmer->getBeratungsart())) {
            if($teilnehmer->getBeratungsart() == '') array_push($returnarr,"Beratungsart");
        }
        
        //Ab hier kann es nur noch mit gelb enden, und zwar, wenn:
        if (!is_numeric($teilnehmer->getLebensalter())) array_push($returnarr,"Alter");
        if ($teilnehmer->getGeschlecht() == 0) array_push($returnarr,"Geschlecht");
        if ($teilnehmer->getWohnsitzDeutschland() == 0) array_push($returnarr,"Wohnsitz Deutschland?");
        // übertragene Staatsangehörigkeit ist standardmäßig die Erste
        $staatsangehoerigkeit = $teilnehmer->getErsteStaatsangehoerigkeit();
        // Wenn die erste Stattsangehörigkeit Deutsch ist, nimm die Zweite. 
        if($teilnehmer->getErsteStaatsangehoerigkeit() == 37) $staatsangehoerigkeit = $teilnehmer->getZweiteStaatsangehoerigkeit() != -1000 ? $teilnehmer->getZweiteStaatsangehoerigkeit() : $teilnehmer->getErsteStaatsangehoerigkeit();
        
        if ($staatsangehoerigkeit == -1000) array_push($returnarr,"Staatsangehörigkeit");
        if ($teilnehmer->getWohnsitzDeutschland() == 1 && $teilnehmer->getEinreisejahr() != '' && !is_numeric($teilnehmer->getEinreisejahr())) array_push($returnarr,"Einreisejahr muss viertellige Zahl sein");
        if ($teilnehmer->getWohnsitzDeutschland() == 1 && $teilnehmer->getEinreisejahr() != '' && ($teilnehmer->getEinreisejahr() < 1939 || $teilnehmer->getEinreisejahr() > date("Y"))) array_push($returnarr,"Einreisejahr nicht plausibel");
        if ($teilnehmer->getWohnsitzDeutschland() == 2 && ($teilnehmer->getWohnsitzNeinIn() < -1 || $teilnehmer->getWohnsitzNeinIn() == '')) array_push($returnarr,"Wohnsitz in");
        if ($teilnehmer->getErwerbsstatus() == 0) array_push($returnarr,"Erwerbsstatus");
        if ($teilnehmer->getAufenthaltsstatus() == 0) array_push($returnarr,"Aufenthaltsstatus");
        if ($teilnehmer->getDeutschkenntnisse() == 0) array_push($returnarr,"Deutschkenntnisse"); 
        if ($teilnehmer->getDeutschkenntnisse() == 2 && ($teilnehmer->getZertifikatSprachniveau() == 1 || $teilnehmer->getZertifikatSprachniveau() == 1)) array_push($returnarr,"Sprachniveau");
        if ($teilnehmer->getNameBeratungsstelle() <-1 || $teilnehmer->getNameBeratungsstelle() == '') array_push($returnarr,"Wie haben Sie uns gefunden?");
        if($teilnehmer->getLeistungsbezugjanein() == 1 && $teilnehmer->getLeistungsbezug() == '') array_push($returnarr,"Leistungsbezug");
        
        return $returnarr;
    }
    
    /**
     * NIQ Upload/Update
     */
    public function uploadtoNIQ($teilnehmer, $abschluesse, $folgekontakte, $niqidberatungsstelle, $niqapiurl) {
        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($post);
        //die();

        // ALTE Datensätze vor 18.03.2022 Erstberatung abgeschlossen sind nicht aktualisierbar!!!!
        if($teilnehmer->getNiqchiffre() != '') {
            if($this->validateDateYmd($teilnehmer->getErstberatungabgeschlossen())) {
                if (DateTime::createFromFormat('d.m.Y', $teilnehmer->getErstberatungabgeschlossen()) !== false) {
                    $date = date_create_from_format('d.m.Y', $teilnehmer->getErstberatungabgeschlossen());
                } else {
                    $date = date_create_from_format('Y-m-d', $teilnehmer->getErstberatungabgeschlossen());
                }
                if ($date->getTimestamp() < 1647558000) {
                    $retstring = 'ALTER DATENSATZ: nicht automatisch aktualisiert! Bitte manuell in NIQ bearbeiten.';
                    return array($teilnehmer, $retstring);
                }
            }
        }
        
        if($this->check_curl($niqapiurl)) {
            
            // --------------------------------------
            // Parameterübersetzung Transformationsmatrizen Webapp <-> NIQ DB
            // --------------------------------------
            
            // Geschlecht:
            $geschlecht = $teilnehmer->getGeschlecht();
            if($geschlecht == 2) $geschlecht = 0; // männlich
            if($geschlecht == 3) $geschlecht = 2; // divers
            if($geschlecht == 1) $geschlecht = 1; // weiblich
            
            // Wohnsitz:
            $wohnsitzdeutschland = $teilnehmer->getWohnsitzDeutschland();
            if($wohnsitzdeutschland == 0) $wohnsitzdeutschland = -1000; // NICHTS AUSGEWÄHLT
            if($wohnsitzdeutschland == 2) $wohnsitzdeutschland = 0; // nein, also Ausland
            if($wohnsitzdeutschland == 1) $wohnsitzdeutschland = 1; // in Deutschland
            
            // Aufenthaltsstatus
            $aufenthaltsstatustn = $teilnehmer->getAufenthaltsstatus();
            if($aufenthaltsstatustn == 1) $aufenthaltsstatus = -1; // keine Angabe
            if($aufenthaltsstatustn == 6) $aufenthaltsstatus = 5; // Aufenthaltserlaubnis zur Arbeitsplatzsuche (§18c AufenthG)
            if($aufenthaltsstatustn == 11) $aufenthaltsstatus = 10; //Aufenthaltserlaubnis für in anderen Mitgliedsstaaten der EU langfristig Aufenthaltsberechtigte (§38a AufenthG)
            if($aufenthaltsstatustn == 12) $aufenthaltsstatus = 11; // ok - Aufenthaltsgestattung (§55 Abs. 1 AsylVfG)
            if($aufenthaltsstatustn == 8) $aufenthaltsstatus = 7; // Aufenthalt aus familiären Gründen (§27-36 AufenthG)
            if($aufenthaltsstatustn == 7) $aufenthaltsstatus = 6; // ok - Aufenthalt aus völkerrechtlichen, humanitären oder politischen Gründen (§22-26, 104a, 104b AufenthG)
            if($aufenthaltsstatustn == 4) $aufenthaltsstatus = 3; // ok - Aufenthalt zum Zweck der Ausbildung (§16-17 AufenthG)
            if($aufenthaltsstatustn == 5) $aufenthaltsstatus = 4; // ok - Aufenthalt zum Zweck der Erwerbstätigkeit (§18, 18a, 19b, 19d, 20, 21 AufenthG)
            if($aufenthaltsstatustn == 9) $aufenthaltsstatus = 8; // ok - Aufenthalt zum Zwecke einer Anpassungsqualifizierung oder einer Kenntnisprüfung (§ 17a AufenthG)
            if($aufenthaltsstatustn == 2) $aufenthaltsstatus = 1; // ok - Blaue Karte EU (§19a AufenthG)
            if($aufenthaltsstatustn == 13) $aufenthaltsstatus = 12; // ok - Duldung (§60a Abs. 4 AufenthG)
            if($aufenthaltsstatustn == 10) $aufenthaltsstatus = 9; // ok - Niederlassungserlaubnis (§9 AufenthG)
            if($aufenthaltsstatustn == 14) $aufenthaltsstatus = 20; // ok - Staatsbürger-/in EU/EWR/Schweiz oder Freizügigkeit (§2-5, §§12-13, §15 FreizüG/EU, §28 AufenthG)
            if($aufenthaltsstatustn == 17) $aufenthaltsstatus = 19; // ok - kein Aufenthaltstitel, da Wohnistz im Ausland 
            if($aufenthaltsstatustn == 15) $aufenthaltsstatus = -1; // ok - Fiktionsbescheinigung (nicht in NIQ)
            if($aufenthaltsstatustn == 18) $aufenthaltsstatus = -1; // Anmerkung (nicht in NIQ)
            
            // Muttersprache
            $deutschkenntnisse = $teilnehmer->getDeutschkenntnisse();
            $zertifikatSprachniveau = $teilnehmer->getZertifikatSprachniveau();
            if($deutschkenntnisse == 0) $deutschkenntnisse = -1000; // NICHTS AUSGEWÄHLT
            if($deutschkenntnisse == 1) $deutschkenntnisse = 1; // ja
            if($deutschkenntnisse == 2) $deutschkenntnisse = 99; // gibt es in NIQ nicht!
            if($deutschkenntnisse == -1) $deutschkenntnisse = -1; // k.A.
            if($zertifikatSprachniveau == 0) $zertifikat = 0; // ohne Zertifikat
            if($zertifikatSprachniveau == 1) $zertifikat = -1000; // nichts ausgewählt
            if($zertifikatSprachniveau > 1) { 
                $deutschkenntnisse = 2; // ja, als Fremdsprache
                $zertifikat = 1; // ja, Zertifikat vorhanden
                $sprachniveau = $zertifikatSprachniveau - 1; // Sprachniveau A1,A2,B1,B2,C1,C2
            }
            
            // Erwerbsstatus
            $erwerbsstatustn = $teilnehmer->getErwerbsstatus();
            if($erwerbsstatustn == -1) $erwerbsstatus = -1; // k.A.
            if($erwerbsstatustn == 0) $erwerbsstatus = -1000; // NICHTS AUSGEWÄHLT
            if($erwerbsstatustn == 8) $erwerbsstatus = -1; // sonstiges (nicht in NIQ)
            if($erwerbsstatustn == 1 || $erwerbsstatustn == 2 || $erwerbsstatustn == 3) $erwerbsstatus = 1; // erwerbstätig in Deutschland
            if($erwerbsstatustn == 6) $erwerbsstatus = 2; // im Ausland erwerbstätig
            if($erwerbsstatustn == 5 || $erwerbsstatustn == 7) $erwerbsstatus = 3; // nicht erwerbstätig
            if($erwerbsstatustn == 4) $erwerbsstatus = 4; // in Aus-/Weiterbildung/Qualifizierung
            // Art Erwerb
            if($erwerbsstatustn == 1) $arterwerb = 1; // beitragspflichtig beschäftigt
            if($erwerbsstatustn == 2) $arterwerb = 2; // geringfügig beschäftigt
            if($erwerbsstatustn == 3) $arterwerb = 3; // selbständig
            
            // Leistungsbezug
            $leistungsbezug = $teilnehmer->getLeistungsbezug();
            $leistungsbezugjanein = $teilnehmer->getLeistungsbezugjanein();
            if($leistungsbezug > 1) $leistungsbezug = $leistungsbezug; // 2 = mit (ergänzendem) SGB II-Leistungsbezug, 3 = mit SGB III-Leistungsbezug, 4 = mit SGB III- und SGB II-Leistungsbezug, 5 = mit (ergänzendem) Asylbewerberleistungsbezug   
            if($leistungsbezugjanein == 2) $leistungsbezug = 1; // ohne Leistungsbezug
            if($leistungsbezugjanein == 3) $leistungsbezug = -1; // k.A. ob Leistungsbezug
            if($leistungsbezugjanein == '') $leistungsbezug = -1000; // NICHTS AUSGEWÄHLT
     
            
            // Beratungsart
            //  1 = face-to-face, 2 = Telefon, 3 = E-Mail, 5 = Video
            $beratungsarttn = $teilnehmer->getBeratungsart();
            if(is_array($beratungsarttn)) {
                if($beratungsarttn[0] == 2 && $beratungsarttn[1] == 3 && $beratungsarttn[2] == 5) {
                    $beratungsart = 4; // wenn Webapp Telefon, E-Mail und Video, dann NIQ "Digitale Beratung"
                } elseif($beratungsarttn[0] == 3 && $beratungsarttn[1] == 5) {
                    $beratungsart = 4; // wenn Webapp E-Mail und Video, dann NIQ "Digitale Beratung"
                } elseif($beratungsarttn[0] == 2 && $beratungsarttn[1] == 3) {
                    $beratungsart = 3; // wenn Webapp Telefon und E-Mail, dann NIQ "Telefon"
                } elseif($beratungsarttn[0] == 1) {
                    $beratungsart = 1; // wenn persönliche Beratung / face-to-face
                } elseif($beratungsarttn[0] == 2) {
                    $beratungsart = 3; // wenn mind. Telefon
                } elseif($beratungsarttn[0] == 3 || $beratungsarttn[0] == 5) {
                    $beratungsart = 4; // wenn Webapp E-Mail oder Video, dann NIQ "Digitale Beratung"
                } else {
                    $beratungsart = 1; // alles andere zählt als face-to-face
                }
            } else {
                if($beratungsarttn == 0) $beratungsart = 0; // k.A.
                if($beratungsarttn == 1) $beratungsart = 1; // persönliche Beratung / face-to-face
                if($beratungsarttn == 2) $beratungsart = 3; // Telefon
                if($beratungsarttn == 3 || $beratungsarttn == 5) $beratungsart = 4; // "Digitale Beratung"
            }
            
            // Wege zur Beratung / Wie haben Sie uns gefunden? / Beratungsstelle
            $nameBeratungsstelletn = $teilnehmer->getNameBeratungsstelle();
            if($nameBeratungsstelletn == 1) $nameBeratungsstelle = 1; // Agentur für Arbeit oder Jobcenter
            if($nameBeratungsstelletn == 12) $nameBeratungsstelle = 2; // Arbeitgeber, Betriebe
            if($nameBeratungsstelletn == 13) $nameBeratungsstelle = 3; // Ausländerbehörde
            if($nameBeratungsstelletn == 14) $nameBeratungsstelle = 4; // Bildungsdienstleister
            if($nameBeratungsstelletn == 15) $nameBeratungsstelle = 5; // Ehrenamtler*innen
            if($nameBeratungsstelletn == 16) $nameBeratungsstelle = 6; // eigene Öffentlichkeitsarbeit (z.B. Projektwebseite, Flyer, Infoveranstaltung)
            if($nameBeratungsstelletn == 21) $nameBeratungsstelle = 9; // IQ externe Öffentlichkeitsarbeit (Internet, Presse, Anerkennungsportal BIBB, Social Media)
            if($nameBeratungsstelletn == 22) $nameBeratungsstelle = 8; // IQ interner Verweis
            if($nameBeratungsstelletn == 17) $nameBeratungsstelle = 7; // IQ externe Beratung (auch MBE, JMD, MO, Hotl. Arb. u. Leb. in D)
            if($nameBeratungsstelletn == 10) $nameBeratungsstelle = 10; // Persönliche Empfehlung
            if($nameBeratungsstelletn == 19) $nameBeratungsstelle = 11; // Zuständige Stellen
            if($nameBeratungsstelletn == 18) $nameBeratungsstelle = 13; // Zentrale Servicestelle Berufsanerkennung (ZSBA)
            if($nameBeratungsstelletn == 11) $nameBeratungsstelle = 12; // Sonstiges
            if($nameBeratungsstelletn == 20) $nameBeratungsstelle = -1; // k.A.
            if($nameBeratungsstelletn == '') $nameBeratungsstelle = -1000; // NICHTS AUSGEWÄHLT
            
            // Beratungsstellen-ID
            $post['niqidberatungsstelle'] = $niqidberatungsstelle;
            
            // --------------------------------------
            // Reiter „persönliche Daten“
            $post['id'] = $teilnehmer->getUid();
            $post['name'] = $teilnehmer->getNachname();
            $post['vorname'] = $teilnehmer->getVorname();
            $post['ort'] = $teilnehmer->getBeratungsort();  // Ort der Beratung
            $post['geschlecht'] = $geschlecht;
            $post['wohnsitz'] = $wohnsitzdeutschland;
            $post['age'] = is_numeric($teilnehmer->getLebensalter()) ? $teilnehmer->getLebensalter() : -1000;
            
            // übertragene Staatsangehörigkeit ist standardmäßig die Erste
            $staatsangehoerigkeit = $teilnehmer->getErsteStaatsangehoerigkeit();
            // Wenn die erste Stattsangehörigkeit Deutsch ist, nimm die Zweite.
            if($teilnehmer->getErsteStaatsangehoerigkeit() == 37) $staatsangehoerigkeit = $teilnehmer->getZweiteStaatsangehoerigkeit() != -1000 ? $teilnehmer->getZweiteStaatsangehoerigkeit() : $teilnehmer->getErsteStaatsangehoerigkeit();
                        
            $post['land'] = $staatsangehoerigkeit;
            $post['aufenthaltsstatus'] = $aufenthaltsstatus;
            if($wohnsitzdeutschland == 1) {
                if($teilnehmer->getEinreisejahr() == '') {
                    $post['einreise'] = -1;
                } else {
                    $post['einreise'] = is_numeric($teilnehmer->getEinreisejahr()) ? $teilnehmer->getEinreisejahr() : -1000;
                }                
            }
            if($wohnsitzdeutschland == 0) $post['wohnstaat'] = $teilnehmer->getWohnsitzNeinIn();
            $post['deutschkenntnisse'] = $deutschkenntnisse;
            if($deutschkenntnisse == 2) $post['zertifikat'] = $zertifikat;
            if($zertifikat == 1) $post['sprachniveau'] = $sprachniveau;
            $post['erwerb'] = $erwerbsstatus;
            if($erwerbsstatus == 1 || $erwerbsstatus == 2 || $erwerbsstatus == 3 ) $post['arterwerb'] = $arterwerb;
            $post['leistungsbezug'] = $leistungsbezug;
            
            // Reiter „Zugangswege zur Beratung“
            $post['aufmerksam'] = $nameBeratungsstelle;
            $post['anmerkungen'] = ""; // Wird nicht übertragen, da kein vergleichbares Feld in Webapp
            
            
            // Reiter "Berufsabschluss" und „Anerkennungsverfahren“
            //
            // Abschluss mit Anerkennungsverfahren zuerst übertragen
            //
            $acnt = 1;
            foreach ($abschluesse as $oneabschluss) {
                if($oneabschluss->getAntragstellungerfolgt() != 0 && $oneabschluss->getNiquebertragung() == 1) {
                    $post['berufwahl' . $acnt] = $oneabschluss->getReferenzberufzugewiesen();
                    $post['sonstigerberuf' . $acnt] = $oneabschluss->getSonstigerberuf();
                    $post['nregberuf' . $acnt] = $oneabschluss->getNregberuf();
                    $abschlussartarray = $oneabschluss->getAbschlussart();
                    $post['berufsart' . $acnt] = $abschlussartarray[0];
                    $post['erwerbslandID' . $acnt] = $oneabschluss->getErwerbsland();
                    $berufserfahrung = $oneabschluss->getBerufserfahrung();
                    $post['berufserfahrung' . $acnt] = $berufserfahrung;
                    $post['antrag' . $acnt] = $oneabschluss->getAntragstellungvorher();
                    $post['gleichwertig' . $acnt] = $oneabschluss->getAntragstellunggwpvorher();
                    $post['zabzeugnis' . $acnt] = $oneabschluss->getAntragstellungzabvorher();
                    $acnt++;
                    
                    // Daten Anerkennungsverfahren
                    $post['berufwahlav'] = $oneabschluss->getReferenzberufzugewiesen();
                    $post['niqantrag'] = $oneabschluss->getAntragstellungerfolgt();
                    $post['gwdatum'] = $oneabschluss->getAntragstellunggwpdatum();
                    $post['niqgleichwertig'] = $oneabschluss->getAntragstellunggwpergebnis();
                    $post['zabdatum'] = $oneabschluss->getAntragstellungzabdatum();
                    $post['niqzab'] = $oneabschluss->getAntragstellungzabergebnis();
                }
            }
            foreach($abschluesse as $oneabschluss) {
                if($oneabschluss->getAntragstellungerfolgt() == 0 && $oneabschluss->getNiquebertragung() == 1) {
                    $post['berufwahl' . $acnt] = $oneabschluss->getReferenzberufzugewiesen();
                    $post['sonstigerberuf' . $acnt] = $oneabschluss->getSonstigerberuf();
                    $post['nregberuf' . $acnt] = $oneabschluss->getNregberuf();
                    $abschlussartarray = $oneabschluss->getAbschlussart();
                    
                    $post['berufsart' . $acnt] = $abschlussartarray[0];
                    $post['erwerbslandID' . $acnt] = $oneabschluss->getErwerbsland();
                    // Berufserfahrung
                    $berufserfahrung = $oneabschluss->getBerufserfahrung();
                    $post['berufserfahrung' . $acnt] = $berufserfahrung;
                    $post['antrag' . $acnt] = $oneabschluss->getAntragstellungvorher();
                    $post['gleichwertig' . $acnt] = $oneabschluss->getAntragstellunggwpvorher();
                    $post['zabzeugnis' . $acnt] = $oneabschluss->getAntragstellungzabvorher();
                    $acnt++;
                }
            }
            
            // Reiter „Beratungsprozess“
            $beratungdatum = date_create_from_format('Y-m-d', $teilnehmer->getBeratungdatum());
            $post['erstberatung'] = $beratungdatum->format('d.m.Y'); // Datum Erstberatung
            $post['beratungsform'] = $beratungsart; // Beratungsform Erstberatung
            $anerkennungsberatung = $teilnehmer->getAnerkennungsberatung();
            foreach($anerkennungsberatung as $aber) {
                if($aber == '6') {
                    $aber = '7';
                } elseif($aber == '7'){
                    $aber = '6';
                }
                $post['acheck' . $aber] = $aber;
            }
            $qualifizierungsberatung = $teilnehmer->getQualifizierungsberatung();
            foreach($qualifizierungsberatung as $qber) {
                if($qber == '6') {
                    $qber = '7';
                } elseif($qber == '7'){
                    $qber = '6';
                }
                $post['qcheck' . $qber] = $qber;
            }
            $post['asonstiges'] = $teilnehmer->getAnerkennungsberatungfreitext();
            $post['qsonstiges'] = $teilnehmer->getQualifizierungsberatungfreitext();
            
            $i = 1;
            foreach ($folgekontakte as $folgekontakt) {
                $post['pickfolgedatum' . $i] = $folgekontakt->getDatum();
                $post['beratungsform' . $i] = $folgekontakt->getBeratungsform();
                $i++;
            }
            
            // Sende Daten per curl
            $retval = $this->post_curl(http_build_query($post), $niqapiurl);
            
            // Checke den Rückgabewert
            if($retval['status'] == 'Update OK') {  // Update erfolgreich?
                $teilnehmer->setNiqtstamp(new \DateTime);
                $retstring = 'NIQ Update erfolgreich.';
                return array($teilnehmer, $retstring);
            } elseif((strpos($retval['status'], 'OK') != FALSE) && $retval['niqchiffre'] != NULL) {  // Upload erfolgreich?
                $teilnehmer->setNiqchiffre($retval['niqchiffre']);
                $teilnehmer->setNiqtstamp(new \DateTime);
                $teilnehmer->setBeratungsstatus(4);
                $retstring = 'NIQ Upload erfolgreich.';
                return array($teilnehmer, $retstring);
            } else {
                $retstring = 'FEHLER: NIQ Upload nicht erfolgreich.';
                return array($post, $retstring);
                //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($post);
                //die();
            }
        }
    }
    
    /*
     *
     * NIQ Server Verbindung
     * Check Verbindung
     *
     */
    public function check_curl($niqapiurl) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$niqapiurl); curl_setopt($ch, CURLOPT_POST, 1);
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, "id=2&do=nothing");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);  
        
        return (strpos($server_output, 'ok') != FALSE);
    }
    
    /*
     *
     * NIQ Server Verbindung
     * Übertrage Daten
     *
     */
    public function post_curl($post, $niqapiurl) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$niqapiurl); curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $server_output = curl_exec($ch);
        
        curl_close ($ch);
        $returnvalue = json_decode($server_output, true);
        return $returnvalue;
    }
    
    function validateDateYmd($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
