<?php
namespace Ud\Iqtp13db\Domain\Model;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Uli Dohmen <edv@whkt.de>, WHKT
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/***
 *
 * This file is part of the "IQ TP13 Datenbank Anerkennungserstberatung NRW" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Uli Dohmen <edv@whkt.de>, WHKT
 *
 ***/

/**
 * Teilnehmer
 */
class Teilnehmer extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * @var boolean
     */
    protected $hidden = NULL;

    /**
     * tstamp
     *
     * @var string
     */
    protected $crdate = NULL;

    /**
     * Nachname
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $nachname = '';

    /**
     * Vorname
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $vorname = '';

    /**
     * Straße
     *
     * @var string
     */
    protected $strasse = '';

    /**
     * PLZ
     *
     * @var string
     */
    protected $plz = '';

    /**
     * Ort
     *
     * @var string
     */
    protected $ort = '';

    /**
     * E-Mail
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty"), @TYPO3\CMS\Extbase\Annotation\Validate("EmailAddress")
     */
    protected $email = '';

    /**
     * Telefon
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $telefon = '';

    /**
     * Geburtsjahr
     *
     * @var string
     */
    protected $geburtsjahr = '';

    /**
     * Geburtsland
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $geburtsland = '';

    /**
     * Geschlecht
     *
     * @var int
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $geschlecht = 0;

    /**
     * Erste Staatsangehörigkeit
     *
     * @var string
     */
    protected $ersteStaatsangehoerigkeit = '';

    /**
     * Zweite Staatsangehörigkeit
     *
     * @var string
     */
    protected $zweiteStaatsangehoerigkeit = '';

    /**
     * Einreisejahr
     *
     * @var string
     */
    protected $einreisejahr = '';

    /**
     * Wohnsitz in Deutschland
     *
     * @var int
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $wohnsitzDeutschland = 0;

    /**
     * In welchem Bundesland?
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $wohnsitzJaBundesland = '';

    /**
     * Wohnsitz in
     *
     * @var string
     */
    protected $wohnsitzNeinIn = '';

    /**
     * Geplante Einreise
     *
     * @var string
     */
    protected $geplanteEinreise = '';
    
    /**
     * Kontakt Visastelle
     *
     * @var int
     */
    protected $kontaktVisastelle = 0;
    
    /**
     * Welcher Visumsantrag
     *
     * @var string
     */
    protected $visumsantrag = '';
    
    /**
     * Deutschkenntnisse
     *
     * @var int
     */
    protected $deutschkenntnisse = 0;

    /**
     * Zertifikat Deutschkenntnisse vorhanden?
     *
     * @var int
     */
    protected $zertifikatdeutsch = 0;

    /**
     * Welches Sprachniveau?
     *
     * @var string
     */
    protected $zertifikatSprachniveau = '';

    /**
     * Beratungsgespräch auf Deutsch?
     *
     * @var int
     */
    protected $beratungsgespraechDeutsch = 0;

    /**
     * Sprache Beratungsgespräch
     *
     * @var string
     */
    protected $beratungsgespraechSprache = '';

    /**
     * Ausbildungsabschluss
     *
     * @var bool
     */
    protected $abschlussartA = FALSE;

    /**
     * Hochschulabschluss
     *
     * @var bool
     */
    protected $abschlussartH = FALSE;

    /**
     * Erwerbsland
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $erwerbsland1 = '';

    /**
     * Dauer der Berufsausbildung
     *
     * @var string
     */
    protected $dauerBerufsausbildung1 = '';

    /**
     * Abschlussjahr
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $abschlussjahr1 = '';

    /**
     * Ausbildungsinstitution
     *
     * @var string
     */
    protected $ausbildungsinstitution1 = '';

    /**
     * Ausbildungsort
     *
     * @var string
     */
    protected $ausbildungsort1 = '';

    /**
     * Abschluss
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $abschluss1 = '';

    /**
     * Deutsche Übersetzung des Abschlusstitels
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $deutschAbschlusstitel1 = '';

    /**
     * Berufserfahrung
     *
     * @var string
     */
    protected $berufserfahrung1 = '';

    /**
     * Möglicher deutscher Referenzberuf
     *
     * @var string
     */
    protected $deutscherReferenzberuf1 = '';

    /**
     * Wunschberuf
     *
     * @var string
     */
    protected $wunschberuf1 = '';

    /**
     * Erwerbsstatus
     *
     * @var int
     */
    protected $erwerbsstatus = 0;

    /**
     * Leistungsbezug
     *
     * @var string
     */
    protected $leistungsbezug = '';

    /**
     * Aufenthaltsstatus des/der Teilnehmers/in bei Beginn der Maßnahme
     *
     * @var int
     */
    protected $aufenthaltsstatus = 0;

    /**
     * Wurde früher ein Antrag auf Anerkennung gestellt?
     *
     * @var int
     */
    protected $fruehererAntrag = 0;

    /**
     * Zu welchem Referenzberuf?
     *
     * @var string
     */
    protected $fruehererAntragReferenzberuf = '';

    /**
     * Bei welcher Institution?
     *
     * @var string
     */
    protected $fruehererAntragInstitution = '';

    /**
     * Anzahl Beratungen
     *
     * @var int
     */
    protected $anzBeratungen = 0;

    /**
     * Weitere Sprachkenntnisse vorhanden?
     *
     * @var int
     */
    protected $weitereSprachkenntnisse = 0;

    /**
     * Wenn ja, welche Sprache(n)?
     *
     * @var string
     */
    protected $sprachen = '';

    /**
     * Einwilligung Datenübermittlung
     *
     * @var bool
     * @TYPO3\CMS\Extbase\Annotation\Validate("\Ud\Iqtp13db\Validation\Validator\EinwilligungValidator")
     */
    protected $einwilligung = NULL;

    /**
     * Erwerbsland
     *
     * @var string
     */
    protected $erwerbsland2 = '';

    /**
     * Dauer Berufsausbildung
     *
     * @var string
     */
    protected $dauerBerufsausbildung2 = '';

    /**
     * Abschlussjahr
     *
     * @var string
     */
    protected $abschlussjahr2 = '';

    /**
     * Ausbildungsinstitution
     *
     * @var string
     */
    protected $ausbildungsinstitution2 = '';

    /**
     * Ausbildungsort
     *
     * @var string
     */
    protected $ausbildungsort2 = '';

    /**
     * Abschluss
     *
     * @var string
     */
    protected $abschluss2 = '';

    /**
     * Deutsche Übersetzung des Abschlusstitels
     *
     * @var string
     */
    protected $deutschAbschlusstitel2 = '';

    /**
     * Berufserfahrung
     *
     * @var string
     */
    protected $berufserfahrung2 = '';

    /**
     * Möglicher deutscher Beruf
     *
     * @var string
     */
    protected $deutscherReferenzberuf2 = '';

    /**
     * Wunschberuf
     *
     * @var string
     */
    protected $wunschberuf2 = '';

    /**
     * Liegen Original-Dokumente des Abschlusses vor?
     *
     * @var int
     */
    protected $originalDokumenteAbschluss1 = 0;

    /**
     * Liegen Original-Dokumente des Abschlusses vor?
     *
     * @var int
     */
    protected $originalDokumenteAbschluss2 = 0;

    /**
     * bescheidfruehererAnerkennungsantrag
     *
     * @var bool
     */
    protected $bescheidfruehererAnerkennungsantrag = FALSE;

    /**
     * verificationCode
     *
     * @var string
     */
    protected $verificationCode = '';
    
    /**
     * verificationDate
     *
     * @var \DateTime
     */
    protected $verificationDate = null;
    
    /**
     * verificationIp
     *
     * @var string
     */
    protected $verificationIp = '';
    
    /**
     * __construct
     */
    public function __construct()
    {
        $this->initVerificationCode();
    }
    
    /**
     * Returns the nachname
     *
     * @return string $nachname
     */
    public function getNachname()
    {
        return $this->nachname;
    }

    /**
     * Sets the nachname
     *
     * @param string $nachname
     * @return void
     */
    public function setNachname($nachname)
    {
        $this->nachname = $nachname;
    }

    /**
     * Returns the vorname
     *
     * @return string $vorname
     */
    public function getVorname()
    {
        return $this->vorname;
    }

    /**
     * Sets the vorname
     *
     * @param string $vorname
     * @return void
     */
    public function setVorname($vorname)
    {
        $this->vorname = $vorname;
    }

    /**
     * Returns the strasse
     *
     * @return string $strasse
     */
    public function getStrasse()
    {
        return $this->strasse;
    }

    /**
     * Sets the strasse
     *
     * @param string $strasse
     * @return void
     */
    public function setStrasse($strasse)
    {
        $this->strasse = $strasse;
    }

    /**
     * Returns the plz
     *
     * @return string $plz
     */
    public function getPlz()
    {
        return $this->plz;
    }

    /**
     * Sets the plz
     *
     * @param string $plz
     * @return void
     */
    public function setPlz($plz)
    {
        $this->plz = $plz;
    }

    /**
     * Returns the ort
     *
     * @return string $ort
     */
    public function getOrt()
    {
        return $this->ort;
    }

    /**
     * Sets the ort
     *
     * @param string $ort
     * @return void
     */
    public function setOrt($ort)
    {
        $this->ort = $ort;
    }

    /**
     * Returns the email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email
     *
     * @param string $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Returns the telefon
     *
     * @return string $telefon
     */
    public function getTelefon()
    {
        return $this->telefon;
    }

    /**
     * Sets the telefon
     *
     * @param string $telefon
     * @return void
     */
    public function setTelefon($telefon)
    {
        $this->telefon = $telefon;
    }

    /**
     * Returns the geburtsjahr
     *
     * @return string $geburtsjahr
     */
    public function getGeburtsjahr()
    {
        return $this->geburtsjahr;
    }

    /**
     * Sets the geburtsjahr
     *
     * @param string $geburtsjahr
     * @return void
     */
    public function setGeburtsjahr($geburtsjahr)
    {
        $this->geburtsjahr = $geburtsjahr;
    }

    /**
     * Returns the geburtsland
     *
     * @return string $geburtsland
     */
    public function getGeburtsland()
    {
        return $this->geburtsland;
    }

    /**
     * Sets the geburtsland
     *
     * @param string $geburtsland
     * @return void
     */
    public function setGeburtsland($geburtsland)
    {
        $this->geburtsland = $geburtsland;
    }

    /**
     * Returns the geschlecht
     *
     * @return int $geschlecht
     */
    public function getGeschlecht()
    {
        return $this->geschlecht;
    }

    /**
     * Sets the geschlecht
     *
     * @param int $geschlecht
     * @return void
     */
    public function setGeschlecht($geschlecht)
    {
        $this->geschlecht = $geschlecht;
    }

    /**
     * Returns the ersteStaatsangehoerigkeit
     *
     * @return string $ersteStaatsangehoerigkeit
     */
    public function getErsteStaatsangehoerigkeit()
    {
        return $this->ersteStaatsangehoerigkeit;
    }

    /**
     * Sets the ersteStaatsangehoerigkeit
     *
     * @param string $ersteStaatsangehoerigkeit
     * @return void
     */
    public function setErsteStaatsangehoerigkeit($ersteStaatsangehoerigkeit)
    {
        $this->ersteStaatsangehoerigkeit = $ersteStaatsangehoerigkeit;
    }

    /**
     * Returns the zweiteStaatsangehoerigkeit
     *
     * @return string $zweiteStaatsangehoerigkeit
     */
    public function getZweiteStaatsangehoerigkeit()
    {
        return $this->zweiteStaatsangehoerigkeit;
    }

    /**
     * Sets the zweiteStaatsangehoerigkeit
     *
     * @param string $zweiteStaatsangehoerigkeit
     * @return void
     */
    public function setZweiteStaatsangehoerigkeit($zweiteStaatsangehoerigkeit)
    {
        $this->zweiteStaatsangehoerigkeit = $zweiteStaatsangehoerigkeit;
    }

    /**
     * Returns the einreisejahr
     *
     * @return string $einreisejahr
     */
    public function getEinreisejahr()
    {
        return $this->einreisejahr;
    }

    /**
     * Sets the einreisejahr
     *
     * @param string $einreisejahr
     * @return void
     */
    public function setEinreisejahr($einreisejahr)
    {
        $this->einreisejahr = $einreisejahr;
    }

    /**
     * Returns the wohnsitzDeutschland
     *
     * @return int $wohnsitzDeutschland
     */
    public function getWohnsitzDeutschland()
    {
        return $this->wohnsitzDeutschland;
    }

    /**
     * Sets the wohnsitzDeutschland
     *
     * @param int $wohnsitzDeutschland
     * @return void
     */
    public function setWohnsitzDeutschland($wohnsitzDeutschland)
    {
        $this->wohnsitzDeutschland = $wohnsitzDeutschland;
    }

    /**
     * Returns the wohnsitzJaBundesland
     *
     * @return string $wohnsitzJaBundesland
     */
    public function getWohnsitzJaBundesland()
    {
        return $this->wohnsitzJaBundesland;
    }

    /**
     * Sets the wohnsitzJaBundesland
     *
     * @param string $wohnsitzJaBundesland
     * @return void
     */
    public function setWohnsitzJaBundesland($wohnsitzJaBundesland)
    {
        $this->wohnsitzJaBundesland = $wohnsitzJaBundesland;
    }

    /**
     * Returns the wohnsitzNeinIn
     *
     * @return string $wohnsitzNeinIn
     */
    public function getWohnsitzNeinIn()
    {
        return $this->wohnsitzNeinIn;
    }

    /**
     * Sets the wohnsitzNeinIn
     *
     * @param string $wohnsitzNeinIn
     * @return void
     */
    public function setWohnsitzNeinIn($wohnsitzNeinIn)
    {
        $this->wohnsitzNeinIn = $wohnsitzNeinIn;
    }

    /**
     * Returns the geplanteEinreise
     *
     * @return string $geplanteEinreise
     */
    public function getGeplanteEinreise() {
        return $this->geplanteEinreise;
    }
    
    /**
     * Sets the geplanteEinreise
     *
     * @param string $geplanteEinreise
     * @return void
     */
    public function setGeplanteEinreise($geplanteEinreise) {
        $this->geplanteEinreise = $geplanteEinreise;
    }
    
    /**
     * Returns the kontaktVisastelle
     *
     * @return string $kontaktVisastelle
     */
    public function getKontaktVisastelle() {
        return $this->kontaktVisastelle;
    }
    
    /**
     * Sets the kontaktVisastelle
     *
     * @param string $kontaktVisastelle
     * @return void
     */
    public function setKontaktVisastelle($kontaktVisastelle) {
        $this->kontaktVisastelle = $kontaktVisastelle;
    }
    
    /**
     * Returns the visumsantrag
     *
     * @return string $visumsantrag
     */
    public function getVisumsantrag() {
        return $this->visumsantrag;
    }
    
    /**
     * Sets the visumsantrag
     *
     * @param string $visumsantrag
     * @return void
     */
    public function setVisumsantrag($visumsantrag) {
        $this->visumsantrag = $visumsantrag;
    }
    
    
    /**
     * Returns the deutschkenntnisse
     *
     * @return int $deutschkenntnisse
     */
    public function getDeutschkenntnisse()
    {
        return $this->deutschkenntnisse;
    }

    /**
     * Sets the deutschkenntnisse
     *
     * @param int $deutschkenntnisse
     * @return void
     */
    public function setDeutschkenntnisse($deutschkenntnisse)
    {
        $this->deutschkenntnisse = $deutschkenntnisse;
    }

    /**
     * Returns the zertifikatdeutsch
     *
     * @return int $zertifikatdeutsch
     */
    public function getZertifikatdeutsch()
    {
        return $this->zertifikatdeutsch;
    }

    /**
     * Sets the zertifikatdeutsch
     *
     * @param int $zertifikatdeutsch
     * @return void
     */
    public function setZertifikatdeutsch($zertifikatdeutsch)
    {
        $this->zertifikatdeutsch = $zertifikatdeutsch;
    }

    /**
     * Returns the zertifikatSprachniveau
     *
     * @return string $zertifikatSprachniveau
     */
    public function getZertifikatSprachniveau()
    {
        return $this->zertifikatSprachniveau;
    }

    /**
     * Sets the zertifikatSprachniveau
     *
     * @param string $zertifikatSprachniveau
     * @return void
     */
    public function setZertifikatSprachniveau($zertifikatSprachniveau)
    {
        $this->zertifikatSprachniveau = $zertifikatSprachniveau;
    }

    /**
     * Returns the beratungsgespraechDeutsch
     *
     * @return int $beratungsgespraechDeutsch
     */
    public function getBeratungsgespraechDeutsch()
    {
        return $this->beratungsgespraechDeutsch;
    }

    /**
     * Sets the beratungsgespraechDeutsch
     *
     * @param int $beratungsgespraechDeutsch
     * @return void
     */
    public function setBeratungsgespraechDeutsch($beratungsgespraechDeutsch)
    {
        $this->beratungsgespraechDeutsch = $beratungsgespraechDeutsch;
    }

    /**
     * Returns the beratungsgespraechSprache
     *
     * @return string $beratungsgespraechSprache
     */
    public function getBeratungsgespraechSprache()
    {
        return $this->beratungsgespraechSprache;
    }

    /**
     * Sets the beratungsgespraechSprache
     *
     * @param string $beratungsgespraechSprache
     * @return void
     */
    public function setBeratungsgespraechSprache($beratungsgespraechSprache)
    {
        $this->beratungsgespraechSprache = $beratungsgespraechSprache;
    }

    /**
     * Returns the erwerbsstatus
     *
     * @return int $erwerbsstatus
     */
    public function getErwerbsstatus()
    {
        return $this->erwerbsstatus;
    }

    /**
     * Sets the erwerbsstatus
     *
     * @param int $erwerbsstatus
     * @return void
     */
    public function setErwerbsstatus($erwerbsstatus)
    {
        $this->erwerbsstatus = $erwerbsstatus;
    }

    /**
     * Returns the leistungsbezug
     *
     * @return string $leistungsbezug
     */
    public function getLeistungsbezug()
    {
        return $this->leistungsbezug;
    }

    /**
     * Sets the leistungsbezug
     *
     * @param string $leistungsbezug
     * @return void
     */
    public function setLeistungsbezug($leistungsbezug)
    {
        $this->leistungsbezug = $leistungsbezug;
    }

    /**
     * Returns the aufenthaltsstatus
     *
     * @return int $aufenthaltsstatus
     */
    public function getAufenthaltsstatus()
    {
        return $this->aufenthaltsstatus;
    }

    /**
     * Sets the aufenthaltsstatus
     *
     * @param int $aufenthaltsstatus
     * @return void
     */
    public function setAufenthaltsstatus($aufenthaltsstatus)
    {
        $this->aufenthaltsstatus = $aufenthaltsstatus;
    }

    /**
     * Returns the fruehererAntrag
     *
     * @return int $fruehererAntrag
     */
    public function getFruehererAntrag()
    {
        return $this->fruehererAntrag;
    }

    /**
     * Sets the fruehererAntrag
     *
     * @param int $fruehererAntrag
     * @return void
     */
    public function setFruehererAntrag($fruehererAntrag)
    {
        $this->fruehererAntrag = $fruehererAntrag;
    }

    /**
     * Returns the fruehererAntragReferenzberuf
     *
     * @return string $fruehererAntragReferenzberuf
     */
    public function getFruehererAntragReferenzberuf()
    {
        return $this->fruehererAntragReferenzberuf;
    }

    /**
     * Sets the fruehererAntragReferenzberuf
     *
     * @param string $fruehererAntragReferenzberuf
     * @return void
     */
    public function setFruehererAntragReferenzberuf($fruehererAntragReferenzberuf)
    {
        $this->fruehererAntragReferenzberuf = $fruehererAntragReferenzberuf;
    }

    /**
     * Returns the fruehererAntragInstitution
     *
     * @return string $fruehererAntragInstitution
     */
    public function getFruehererAntragInstitution()
    {
        return $this->fruehererAntragInstitution;
    }

    /**
     * Sets the fruehererAntragInstitution
     *
     * @param string $fruehererAntragInstitution
     * @return void
     */
    public function setFruehererAntragInstitution($fruehererAntragInstitution)
    {
        $this->fruehererAntragInstitution = $fruehererAntragInstitution;
    }

    /**
     * Returns the anzBeratungen
     *
     * @return int $anzBeratungen
     */
    public function getAnzBeratungen()
    {
        return $this->anzBeratungen;
    }

    /**
     * Sets the anzBeratungen
     *
     * @param int $anzBeratungen
     * @return void
     */
    public function setAnzBeratungen($anzBeratungen)
    {
        $this->anzBeratungen = $anzBeratungen;
    }

    /**
     * Returns the weitereSprachkenntnisse
     *
     * @return int $weitereSprachkenntnisse
     */
    public function getWeitereSprachkenntnisse()
    {
        return $this->weitereSprachkenntnisse;
    }

    /**
     * Sets the weitereSprachkenntnisse
     *
     * @param int $weitereSprachkenntnisse
     * @return void
     */
    public function setWeitereSprachkenntnisse($weitereSprachkenntnisse)
    {
        $this->weitereSprachkenntnisse = $weitereSprachkenntnisse;
    }

    /**
     * Returns the sprachen
     *
     * @return string $sprachen
     */
    public function getSprachen()
    {
        return $this->sprachen;
    }

    /**
     * Sets the sprachen
     *
     * @param string $sprachen
     * @return void
     */
    public function setSprachen($sprachen)
    {
        $this->sprachen = $sprachen;
    }

    /**
     * @return boolean $hidden
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * @return boolean $hidden
     */
    public function isHidden()
    {
        return $this->getHidden();
    }

    /**
     * @param boolean $hidden
     * @return void
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * Returns the crdate
     *
     * @return string $crdate
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * Sets the crdate
     *
     * @param string $crdate
     * @return void
     */
    public function setCrdate($crdate)
    {
        $this->crdate = $crdate;
    }

    /**
     * Returns the einwilligung
     *
     * @return boolean $einwilligung
     */
    public function getEinwilligung()
    {
        return $this->einwilligung;
    }

    /**
     * @return boolean $einwilligung
     */
    public function isEinwilligung()
    {
        return $this->getEinwilligung();
    }

    /**
     * Sets the einwilligung
     *
     * @param boolean $einwilligung
     * @return void
     */
    public function setEinwilligung($einwilligung)
    {
        $this->einwilligung = $einwilligung;
    }

    /**
     * Returns the abschlussartA
     *
     * @return bool abschlussartA
     */
    public function getAbschlussartA()
    {
        return $this->abschlussartA;
    }

    /**
     * Sets the abschlussartA
     *
     * @param int $abschlussartA
     * @return void
     */
    public function setAbschlussartA($abschlussartA)
    {
        $this->abschlussartA = $abschlussartA;
    }

    /**
     * Returns the erwerbsland1
     *
     * @return string erwerbsland1
     */
    public function getErwerbsland1()
    {
        return $this->erwerbsland1;
    }

    /**
     * Sets the erwerbsland1
     *
     * @param string $erwerbsland1
     * @return void
     */
    public function setErwerbsland1($erwerbsland1)
    {
        $this->erwerbsland1 = $erwerbsland1;
    }

    /**
     * Returns the dauerBerufsausbildung1
     *
     * @return string dauerBerufsausbildung1
     */
    public function getDauerBerufsausbildung1()
    {
        return $this->dauerBerufsausbildung1;
    }

    /**
     * Sets the dauerBerufsausbildung1
     *
     * @param string $dauerBerufsausbildung1
     * @return void
     */
    public function setDauerBerufsausbildung1($dauerBerufsausbildung1)
    {
        $this->dauerBerufsausbildung1 = $dauerBerufsausbildung1;
    }

    /**
     * Returns the abschlussjahr1
     *
     * @return string abschlussjahr1
     */
    public function getAbschlussjahr1()
    {
        return $this->abschlussjahr1;
    }

    /**
     * Sets the abschlussjahr1
     *
     * @param string $abschlussjahr1
     * @return void
     */
    public function setAbschlussjahr1($abschlussjahr1)
    {
        $this->abschlussjahr1 = $abschlussjahr1;
    }

    /**
     * Returns the ausbildungsinstitution1
     *
     * @return string ausbildungsinstitution1
     */
    public function getAusbildungsinstitution1()
    {
        return $this->ausbildungsinstitution1;
    }

    /**
     * Sets the ausbildungsinstitution1
     *
     * @param string $ausbildungsinstitution1
     * @return void
     */
    public function setAusbildungsinstitution1($ausbildungsinstitution1)
    {
        $this->ausbildungsinstitution1 = $ausbildungsinstitution1;
    }

    /**
     * Returns the ausbildungsort1
     *
     * @return string ausbildungsort1
     */
    public function getAusbildungsort1()
    {
        return $this->ausbildungsort1;
    }

    /**
     * Sets the ausbildungsort1
     *
     * @param string $ausbildungsort1
     * @return void
     */
    public function setAusbildungsort1($ausbildungsort1)
    {
        $this->ausbildungsort1 = $ausbildungsort1;
    }

    /**
     * Returns the abschluss1
     *
     * @return string abschluss1
     */
    public function getAbschluss1()
    {
        return $this->abschluss1;
    }

    /**
     * Sets the abschluss1
     *
     * @param string $abschluss1
     * @return void
     */
    public function setAbschluss1($abschluss1)
    {
        $this->abschluss1 = $abschluss1;
    }

    /**
     * Returns the deutschAbschlusstitel1
     *
     * @return string deutschAbschlusstitel1
     */
    public function getDeutschAbschlusstitel1()
    {
        return $this->deutschAbschlusstitel1;
    }

    /**
     * Sets the deutschAbschlusstitel1
     *
     * @param string $deutschAbschlusstitel1
     * @return void
     */
    public function setDeutschAbschlusstitel1($deutschAbschlusstitel1)
    {
        $this->deutschAbschlusstitel1 = $deutschAbschlusstitel1;
    }

    /**
     * Returns the deutscherReferenzberuf1
     *
     * @return string deutscherReferenzberuf1
     */
    public function getDeutscherReferenzberuf1()
    {
        return $this->deutscherReferenzberuf1;
    }

    /**
     * Sets the deutscherReferenzberuf1
     *
     * @param string $deutscherReferenzberuf1
     * @return void
     */
    public function setDeutscherReferenzberuf1($deutscherReferenzberuf1)
    {
        $this->deutscherReferenzberuf1 = $deutscherReferenzberuf1;
    }

    /**
     * Returns the berufserfahrung1
     *
     * @return string berufserfahrung1
     */
    public function getBerufserfahrung1()
    {
        return $this->berufserfahrung1;
    }

    /**
     * Sets the berufserfahrung1
     *
     * @param string $berufserfahrung1
     * @return void
     */
    public function setBerufserfahrung1($berufserfahrung1)
    {
        $this->berufserfahrung1 = $berufserfahrung1;
    }

    /**
     * Returns the wunschberuf1
     *
     * @return string wunschberuf1
     */
    public function getWunschberuf1()
    {
        return $this->wunschberuf1;
    }

    /**
     * Sets the wunschberuf1
     *
     * @param string $wunschberuf1
     * @return void
     */
    public function setWunschberuf1($wunschberuf1)
    {
        $this->wunschberuf1 = $wunschberuf1;
    }

    /**
     * Returns the abschlussartH
     *
     * @return bool $abschlussartH
     */
    public function getAbschlussartH()
    {
        return $this->abschlussartH;
    }

    /**
     * Sets the abschlussartH
     *
     * @param bool $abschlussartH
     * @return void
     */
    public function setAbschlussartH($abschlussartH)
    {
        $this->abschlussartH = $abschlussartH;
    }

    /**
     * Returns the boolean state of abschlussartH
     *
     * @return bool
     */
    public function isAbschlussartH()
    {
        return $this->abschlussartH;
    }

    /**
     * Returns the erwerbsland2
     *
     * @return string $erwerbsland2
     */
    public function getErwerbsland2()
    {
        return $this->erwerbsland2;
    }

    /**
     * Sets the erwerbsland2
     *
     * @param string $erwerbsland2
     * @return void
     */
    public function setErwerbsland2($erwerbsland2)
    {
        $this->erwerbsland2 = $erwerbsland2;
    }

    /**
     * Returns the dauerBerufsausbildung2
     *
     * @return string $dauerBerufsausbildung2
     */
    public function getDauerBerufsausbildung2()
    {
        return $this->dauerBerufsausbildung2;
    }

    /**
     * Sets the dauerBerufsausbildung2
     *
     * @param string $dauerBerufsausbildung2
     * @return void
     */
    public function setDauerBerufsausbildung2($dauerBerufsausbildung2)
    {
        $this->dauerBerufsausbildung2 = $dauerBerufsausbildung2;
    }

    /**
     * Returns the abschlussjahr2
     *
     * @return string $abschlussjahr2
     */
    public function getAbschlussjahr2()
    {
        return $this->abschlussjahr2;
    }

    /**
     * Sets the abschlussjahr2
     *
     * @param string $abschlussjahr2
     * @return void
     */
    public function setAbschlussjahr2($abschlussjahr2)
    {
        $this->abschlussjahr2 = $abschlussjahr2;
    }

    /**
     * Returns the ausbildungsinstitution2
     *
     * @return string $ausbildungsinstitution2
     */
    public function getAusbildungsinstitution2()
    {
        return $this->ausbildungsinstitution2;
    }

    /**
     * Sets the ausbildungsinstitution2
     *
     * @param string $ausbildungsinstitution2
     * @return void
     */
    public function setAusbildungsinstitution2($ausbildungsinstitution2)
    {
        $this->ausbildungsinstitution2 = $ausbildungsinstitution2;
    }

    /**
     * Returns the ausbildungsort2
     *
     * @return string $ausbildungsort2
     */
    public function getAusbildungsort2()
    {
        return $this->ausbildungsort2;
    }

    /**
     * Sets the ausbildungsort2
     *
     * @param string $ausbildungsort2
     * @return void
     */
    public function setAusbildungsort2($ausbildungsort2)
    {
        $this->ausbildungsort2 = $ausbildungsort2;
    }

    /**
     * Returns the abschluss2
     *
     * @return string $abschluss2
     */
    public function getAbschluss2()
    {
        return $this->abschluss2;
    }

    /**
     * Sets the abschluss2
     *
     * @param string $abschluss2
     * @return void
     */
    public function setAbschluss2($abschluss2)
    {
        $this->abschluss2 = $abschluss2;
    }

    /**
     * Returns the deutschAbschlusstitel2
     *
     * @return string $deutschAbschlusstitel2
     */
    public function getDeutschAbschlusstitel2()
    {
        return $this->deutschAbschlusstitel2;
    }

    /**
     * Sets the deutschAbschlusstitel2
     *
     * @param string $deutschAbschlusstitel2
     * @return void
     */
    public function setDeutschAbschlusstitel2($deutschAbschlusstitel2)
    {
        $this->deutschAbschlusstitel2 = $deutschAbschlusstitel2;
    }

    /**
     * Returns the berufserfahrung2
     *
     * @return string $berufserfahrung2
     */
    public function getBerufserfahrung2()
    {
        return $this->berufserfahrung2;
    }

    /**
     * Sets the berufserfahrung2
     *
     * @param string $berufserfahrung2
     * @return void
     */
    public function setBerufserfahrung2($berufserfahrung2)
    {
        $this->berufserfahrung2 = $berufserfahrung2;
    }

    /**
     * Returns the deutscherReferenzberuf2
     *
     * @return string $deutscherReferenzberuf2
     */
    public function getDeutscherReferenzberuf2()
    {
        return $this->deutscherReferenzberuf2;
    }

    /**
     * Sets the deutscherReferenzberuf2
     *
     * @param string $deutscherReferenzberuf2
     * @return void
     */
    public function setDeutscherReferenzberuf2($deutscherReferenzberuf2)
    {
        $this->deutscherReferenzberuf2 = $deutscherReferenzberuf2;
    }

    /**
     * Returns the wunschberuf2
     *
     * @return string $wunschberuf2
     */
    public function getWunschberuf2()
    {
        return $this->wunschberuf2;
    }

    /**
     * Sets the wunschberuf2
     *
     * @param string $wunschberuf2
     * @return void
     */
    public function setWunschberuf2($wunschberuf2)
    {
        $this->wunschberuf2 = $wunschberuf2;
    }

    /**
     * Returns the originalDokumenteAbschluss1
     *
     * @return int $originalDokumenteAbschluss1
     */
    public function getOriginalDokumenteAbschluss1()
    {
        return $this->originalDokumenteAbschluss1;
    }

    /**
     * Sets the originalDokumenteAbschluss1
     *
     * @param int $originalDokumenteAbschluss1
     * @return void
     */
    public function setOriginalDokumenteAbschluss1($originalDokumenteAbschluss1)
    {
        $this->originalDokumenteAbschluss1 = $originalDokumenteAbschluss1;
    }

    /**
     * Returns the originalDokumenteAbschluss2
     *
     * @return int $originalDokumenteAbschluss2
     */
    public function getOriginalDokumenteAbschluss2()
    {
        return $this->originalDokumenteAbschluss2;
    }

    /**
     * Sets the originalDokumenteAbschluss2
     *
     * @param int $originalDokumenteAbschluss2
     * @return void
     */
    public function setOriginalDokumenteAbschluss2($originalDokumenteAbschluss2)
    {
        $this->originalDokumenteAbschluss2 = $originalDokumenteAbschluss2;
    }

    /**
     * Returns the bescheidfruehererAnerkennungsantrag
     *
     * @return bool $bescheidfruehererAnerkennungsantrag
     */
    public function getBescheidfruehererAnerkennungsantrag()
    {
        return $this->bescheidfruehererAnerkennungsantrag;
    }

    /**
     * Sets the bescheidfruehererAnerkennungsantrag
     *
     * @param bool $bescheidfruehererAnerkennungsantrag
     * @return void
     */
    public function setBescheidfruehererAnerkennungsantrag($bescheidfruehererAnerkennungsantrag)
    {
        $this->bescheidfruehererAnerkennungsantrag = $bescheidfruehererAnerkennungsantrag;
    }

    /**
     * Returns the boolean state of bescheidfruehererAnerkennungsantrag
     *
     * @return bool
     */
    public function isBescheidfruehererAnerkennungsantrag()
    {
        return $this->bescheidfruehererAnerkennungsantrag;
    }
    
    /**
     * Returns the verificationCode
     *
     * @return string $verificationCode
     */
    public function getVerificationCode()
    {
        return $this->verificationCode;
    }
    
    /**
     * Sets the verificationCode
     *
     * @param int $verificationCode
     * @return void
     */
    public function setVerificationCode($verificationCode)
    {
        $this->verificationCode = $verificationCode;
    }
    
    /**
     * Returns the verificationDate
     *
     * @return \DateTime $verificationDate
     */
    public function getVerificationDate()
    {
        return $this->verificationDate;
    }
    
    /**
     * Sets the verificationDate
     *
     * @param \DateTime $verificationDate
     * @return void
     */
    public function setVerificationDate(\DateTime $verificationDate)
    {
        $this->verificationDate = $verificationDate;
    }
    
    /**
     * Sets the verificationIp
     *
     * @param string $verificationIp
     * @return void
     */
    public function setVerificationIp($verificationIp)
    {
        $this->verificationIp = $verificationIp;
    }
    
    /**
     * Returns the verificationIp
     *
     * @return string $verificationIp
     */
    public function getVerificationIp()
    {
        return $this->verificationIp;
    }
    
    /**
     * Initializes the verificationCode
     *
     * @return string $randomString
     */
    private function initVerificationCode() {
        if(!$this->verificationCode){
            $this->verificationCode = $this->getRandomString();
        }
    }
    
    /**
     * Returns random string
     *
     * @return string $randomString
     */
    private function getRandomString($length = 64)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    
}
