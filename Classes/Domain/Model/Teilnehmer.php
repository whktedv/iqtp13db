<?php
namespace Ud\Iqtp13db\Domain\Model;

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
 * Teilnehmer
 */
class Teilnehmer extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    
    /**
     * niqidberatungsstelle
     *
     * @var int
     */
    protected $niqidberatungsstelle = 0;
    
    /**
     * tstamp
     *
     * @var int
     */
    protected $tstamp = 0;
    
    /**
     * beratungsstatus
     *
     * @var int
     */
    protected $beratungsstatus = 0;
    
    /**
     * niqchiffre
     *
     * @var string
     */
    protected $niqchiffre = '';
    
    /**
     * niqtstamp
     *
     * @var int
     */
    protected $niqtstamp = 0;
        
    /**
     * schonberaten
     *
     * @var int
     */
    protected $schonberaten = 0;
    
    /**
     * schonberatenvon
     *
     * @var string
     */
    protected $schonberatenvon = '';
    
    /**
     * nachname
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     * @var string
     */
    protected $nachname = '';
    
    /**
     * vorname
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     * @var string
     */
    protected $vorname = '';
    
    /**
     * plz
     *
     * @var string
     */
    protected $plz = '';
    
    /**
     * ort
     *
     * @var string
     */
    protected $ort = '';
    
    /**
     * email
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty") 
     * @TYPO3\CMS\Extbase\Annotation\Validate("EmailAddress")
     * @var string
     */
    protected $email = '';
    
    /**
     * E-Mail bestätigung
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     * @TYPO3\CMS\Extbase\Annotation\Validate("EmailAddress")
     */
    protected $confirmemail = '';
    
    /**
     * telefon
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     * @var string
     */
    protected $telefon = '';
    
    /**
     * lebensalter
     * 
     * @var string
     */
    protected $lebensalter = '';
    
    /**
     * geburtsland
     * 
     * @var string
     */
    protected $geburtsland = '';
    
    /**
     * geschlecht
     * @var int
     */
    protected $geschlecht = 0;
    
    /**
     * ersteStaatsangehoerigkeit
     *
     * @var string
     */
    protected $ersteStaatsangehoerigkeit = '';
    
    /**
     * zweiteStaatsangehoerigkeit
     *
     * @var string
     */
    protected $zweiteStaatsangehoerigkeit = '';
    
    /**
     * einreisejahr
     *
     * @var string
     */
    protected $einreisejahr = '';
    
    /**
     * wohnsitzDeutschland
     * @var int
     */
    protected $wohnsitzDeutschland = 0;
        
    /**
     * wohnsitzNeinIn
     *
     * @var string
     */
    protected $wohnsitzNeinIn = '';
    
    /**
     * sonstigerstatus
     *
     * @var string
     */
    protected $sonstigerstatus = '';
    
    /**
     * deutschkenntnisse
     *
     * @var int
     */
    protected $deutschkenntnisse = 0;
    
    /**
     * zertifikatdeutsch
     *
     * @var int
     */
    protected $zertifikatdeutsch = 0;
    
    /**
     * zertifikatSprachniveau
     *
     * @var string
     */
    protected $zertifikatSprachniveau = '';
    
    /**
     * erwerbsstatus
     *
     * @var int
     */
    protected $erwerbsstatus = 0;
    
    /**
     * leistungsbezugjanein
     *
     * @var int
     */
    protected $leistungsbezugjanein = 0;
    
    /**
     * leistungsbezug
     *
     * @var string
     */
    protected $leistungsbezug = '';
    
    /**
     * einwilligungdatenanAA
     *
     * @var int
     */
    protected $einwilligungdatenanAA = 0;
    
    /**
     * einwilligungdatenanAAdatum
     *
     * @var string
     */
    protected $einwilligungdatenanAAdatum = '';
    
    /**
     * einwilligungdatenanAAmedium
     *
     * @var string
     */
    protected $einwilligungdatenanAAmedium = '';
    
    /**
     * nameBeraterAA
     *
     * @var string
     */
    protected $nameBeraterAA = '';
    
    /**
     * kontaktBeraterAA
     *
     * @var string
     */
    protected $kontaktBeraterAA = '';
    
    /**
     * einwAnerkstelle
     *
     * @var int
     */
    protected $einwAnerkstelle = 0;
    
    /**
     * einwAnerkstelledatum
     *
     * @var string
     */
    protected $einwAnerkstelledatum = '';
    
    /**
     * einwAnerkstellemedium
     *
     * @var string
     */
    protected $einwAnerkstellemedium = '';
    
    /**
     * einwAnerkstellename
     *
     * @var string
     */
    protected $einwAnerkstellename = '';
    
    /**
     * einwAnerkstellekontakt
     *
     * @var string
     */
    protected $einwAnerkstellekontakt = '';
    
    /**
     * einwPerson
     *
     * @var int
     */
    protected $einwPerson = 0;
    
    /**
     * einwPersondatum
     *
     * @var string
     */
    protected $einwPersondatum = '';
    
    /**
     * einwPersonmedium
     *
     * @var string
     */
    protected $einwPersonmedium = '';
    
    /**
     * einwPersonname
     *
     * @var string
     */
    protected $einwPersonname = '';
    
    /**
     * einwPersonkontakt
     *
     * @var string
     */
    protected $einwPersonkontakt = '';
    
    /**
     * kundennummerAA
     *
     * @var string
     */
    protected $kundennummerAA = '';
    
    /**
     * aufenthaltsstatus
     *
     * @var int
     */
    protected $aufenthaltsstatus = 0;

    /**
     * aufenthaltsstatusfreitext
     *
     * @var string
     */
    protected $aufenthaltsstatusfreitext = '';
           
    /**
     * nameBeratungsstelle
     *
     * @var string
     */
    protected $nameBeratungsstelle = '';
    
    /**
     * notizen
     *
     * @var string
     */
    protected $notizen = '';
   
    /**
     * Einwilligung Datenübermittlung
     *
     * @var bool
     * @TYPO3\CMS\Extbase\Annotation\Validate("\Ud\Iqtp13db\Domain\Validator\EinwilligungValidator")
     */
    protected $einwilligung = NULL;
    
    /**
     * verificationCode
     *
     * @var string
     */
    protected $verificationCode = '';
    
    /**
     * verificationDate
     *
     * @var int
     */
    protected $verificationDate = 0;
    
    /**
     * verificationIp
     *
     * @var string
     */
    protected $verificationIp = '';
    
    /**
     * anerkennungszuschussbeantragt
     *
     * @var string
     */
    protected $anerkennungszuschussbeantragt = '';
    
    /**
     * wieberaten
     *
     * @var string
     */
    protected $wieberaten = '';
    
    /**
     * kooperationgruppe
     *
     * @var string
     */
    protected $kooperationgruppe = '';
    
    /**
     * beratungdatum
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("\Ud\Iqtp13db\Domain\Validator\DatumValidator")
     */
    protected $beratungdatum = '';
    
    /**
     * beratungsart
     *
     * @var string
     */
    protected $beratungsart = 0;
    
    /**
     * beratungsartfreitext
     *
     * @var string
     */
    protected $beratungsartfreitext = '';
    
    /**
     * beratungsort
     *
     * @var string
     */
    protected $beratungsort = '';
    
    /**
     * beratungsdauer
     *
     * @var string
     */
    protected $beratungsdauer = '';
    
    /**
     * beratungzu
     *
     * @var string
     */
    protected $beratungzu = '';
        
    /**
     * anerkennendestellen
     *
     * @var string
     */
    protected $anerkennendestellen = '';
    
    /**
     * anerkennungsberatung
     *
     * @var string
     */
    protected $anerkennungsberatung = '';
    
    /**
     * anerkennungsberatungfreitext
     *
     * @var string
     */
    protected $anerkennungsberatungfreitext = '';
    
    /**
     * qualifizierungsberatung
     *
     * @var string
     */
    protected $qualifizierungsberatung = '';
    
    /**
     * qualifizierungsberatungfreitext
     *
     * @var string
     */
    protected $qualifizierungsberatungfreitext = '';
    
    /**
     * beratungnotizen
     *
     * @var string
     */
    protected $beratungnotizen = '';
    
    /**
     * erstberatungabgeschlossen
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("\Ud\Iqtp13db\Domain\Validator\DatumValidator")
     */
    protected $erstberatungabgeschlossen = '';
    
    /**
     * berater
     *
     * @var \Ud\Iqtp13db\Domain\Model\Berater
     */
    protected $berater = null;
    
    /**
     * dublette
     *
     * @var boolean
     */
    protected $dublette = FALSE;
    
    /**
     * @var boolean
     */
    protected $hidden;
  
    /**
     * initializes this object
     *
     * @param array $sonstigerstatus
     * @param array $einwilligungdatenanAAmedium
     * @param array $einwAnerkstellemedium
     * @param array $einwPersonmedium
     * @param array $beratungsart
     * @param array $anerkennungsberatung
     * @param array $qualifizierungsberatung
     * @param array $wieberaten
     */
    public function __construct(array $sonstigerstatus = array(), array $einwilligungdatenanAAmedium = array(), array $einwAnerkstellemedium = array(), array $einwPersonmedium = array(), array $beratungsart = array(), array $anerkennungsberatung = array(), array $qualifizierungsberatung = array(), array $wieberaten = array()) {
        $this->setSonstigerstatus($sonstigerstatus);
    	$this->setEinwilligungdatenanAAmedium($einwilligungdatenanAAmedium);    	
    	$this->setEinwAnerkstellemedium($einwAnerkstellemedium);
    	$this->setEinwPersonmedium($einwPersonmedium);
    	$this->initVerificationCode();
    	$this->setBeratungsart($beratungsart);
    	$this->setAnerkennungsberatung($anerkennungsberatung);
    	$this->setQualifizierungsberatung($qualifizierungsberatung);
    	$this->setWieberaten($wieberaten);
    }
    
    /**
     * Returns the niqidberatungsstelle
     *
     * @return int $niqidberatungsstelle
     */
    public function getNiqidberatungsstelle()
    {
        return $this->niqidberatungsstelle;
    }
    
    /**
     * Sets the niqidberatungsstelle
     *
     * @param int $niqidberatungsstelle
     * @return void
     */
    public function setNiqidberatungsstelle($niqidberatungsstelle)
    {
        $this->niqidberatungsstelle = $niqidberatungsstelle;
    }
    
    /**
     * Returns the tstamp
     *
     * @return int $tstamp
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }
    
    /**
     * Sets the tstamp
     *
     * @param int $tstamp
     * @return void
     */
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;
    }
    
     /**
     * Returns the beratungsstatus
     * 
     * @return int $beratungsstatus
     */
    public function getBeratungsstatus()
    {
        return $this->beratungsstatus;
    }

    /**
     * Sets the beratungsstatus
     * 
     * @param int $beratungsstatus
     * @return void
     */
    public function setBeratungsstatus($beratungsstatus)
    {
        $this->beratungsstatus = $beratungsstatus;
    }

    /**
     * Returns the niqchiffre
     * 
     * @return string $niqchiffre
     */
    public function getNiqchiffre()
    {
        return $this->niqchiffre;
    }

    /**
     * Sets the niqchiffre
     * 
     * @param string $niqchiffre
     * @return void
     */
    public function setNiqchiffre($niqchiffre)
    {
        $this->niqchiffre = $niqchiffre;
    }

    /**
     * Returns the niqtstamp
     *
     * @return int $niqtstamp
     */
    public function getNiqtstamp()
    {
        return $this->niqtstamp;
    }
    
    /**
     * Sets the niqtstamp
     *
     * @param int $niqtstamp
     * @return void
     */
    public function setNiqtstamp($niqtstamp)
    {
        $this->niqtstamp = $niqtstamp;
    }
    
    /**
     * Returns the schonberaten
     * 
     * @return int $schonberaten
     */
    public function getSchonberaten()
    {
        return $this->schonberaten;
    }

    /**
     * Sets the schonberaten
     * 
     * @param int $schonberaten
     * @return void
     */
    public function setSchonberaten($schonberaten)
    {
        $this->schonberaten = $schonberaten;
    }

    /**
     * Returns the schonberatenvon
     * 
     * @return string $schonberatenvon
     */
    public function getSchonberatenvon()
    {
        return $this->schonberatenvon;
    }

    /**
     * Sets the schonberatenvon
     * 
     * @param string $schonberatenvon
     * @return void
     */
    public function setSchonberatenvon($schonberatenvon)
    {
        $this->schonberatenvon = $schonberatenvon;
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
     * Returns the confirmemail
     *
     * @return string $confirmemail
     */
    public function getConfirmemail() {
        return $this->confirmemail;
    }
    
    /**
     * Sets the confirmemail
     *
     * @param string $confirmemail
     * @return void
     */
    public function setConfirmemail($confirmemail) {
        $this->confirmemail = $confirmemail;
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
     * Returns the lebensalter
     * 
     * @return string $lebensalter
     */
    public function getLebensalter()
    {
        return $this->lebensalter;
    }

    /**
     * Sets the lebensalter
     * 
     * @param string $lebensalter
     * @return void
     */
    public function setLebensalter($lebensalter)
    {
        $this->lebensalter = $lebensalter;
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
     * Returns the sonstigerstatus
     *
     * @return array $sonstigerstatus
     */
    public function getSonstigerstatus()
    {
        return explode(',', $this->sonstigerstatus);
    }
    
    /**
     * Sets the sonstigerstatus
     *
     * @param array $sonstigerstatus
     * @return void
     */
    public function setSonstigerstatus(array $sonstigerstatus)
    {
        $this->sonstigerstatus = implode(',', $sonstigerstatus);
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
     * Returns the leistungsbezugjanein
     * 
     * @return int $leistungsbezugjanein
     */
    public function getLeistungsbezugjanein()
    {
        return $this->leistungsbezugjanein;
    }

    /**
     * Sets the leistungsbezugjanein
     * 
     * @param int $leistungsbezugjanein
     * @return void
     */
    public function setLeistungsbezugjanein($leistungsbezugjanein)
    {
        $this->leistungsbezugjanein = $leistungsbezugjanein;
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
     * Returns the einwilligungdatenanAA
     * 
     * @return int $einwilligungdatenanAA
     */
    public function getEinwilligungdatenanAA()
    {
        return $this->einwilligungdatenanAA;
    }

    /**
     * Sets the einwilligungdatenanAA
     * 
     * @param int $einwilligungdatenanAA
     * @return void
     */
    public function setEinwilligungdatenanAA($einwilligungdatenanAA)
    {
        $this->einwilligungdatenanAA = $einwilligungdatenanAA;
    }

    /**
     * Returns the einwilligungdatenanAAdatum
     * 
     * @return string $einwilligungdatenanAAdatum
     */
    public function getEinwilligungdatenanAAdatum()
    {
        return $this->einwilligungdatenanAAdatum;
    }

    /**
     * Sets the einwilligungdatenanAAdatum
     * 
     * @param string $einwilligungdatenanAAdatum
     * @return void
     */
    public function setEinwilligungdatenanAAdatum($einwilligungdatenanAAdatum)
    {
        $this->einwilligungdatenanAAdatum = $einwilligungdatenanAAdatum;
    }

    /**
     * Returns the einwilligungdatenanAAmedium
     * 
     * @return array $einwilligungdatenanAAmedium
     */
    public function getEinwilligungdatenanAAmedium()
    {
        return explode(',', $this->einwilligungdatenanAAmedium);
    }

    /**
     * Sets the einwilligungdatenanAAmedium
     * 
     * @param array $einwilligungdatenanAAmedium
     * @return void
     */
    public function setEinwilligungdatenanAAmedium(array $einwilligungdatenanAAmedium)
    {
        $this->einwilligungdatenanAAmedium = implode(',', $einwilligungdatenanAAmedium);
    }

    /**
     * Returns the nameBeraterAA
     * 
     * @return string $nameBeraterAA
     */
    public function getNameBeraterAA()
    {
        return $this->nameBeraterAA;
    }

    /**
     * Sets the nameBeraterAA
     * 
     * @param string $nameBeraterAA
     * @return void
     */
    public function setNameBeraterAA($nameBeraterAA)
    {
        $this->nameBeraterAA = $nameBeraterAA;
    }

    /**
     * Returns the kontaktBeraterAA
     * 
     * @return string $kontaktBeraterAA
     */
    public function getKontaktBeraterAA()
    {
        return $this->kontaktBeraterAA;
    }

    /**
     * Sets the kontaktBeraterAA
     * 
     * @param string $kontaktBeraterAA
     * @return void
     */
    public function setKontaktBeraterAA($kontaktBeraterAA)
    {
        $this->kontaktBeraterAA = $kontaktBeraterAA;
    }

    /**
     * Returns the einwAnerkstelle
     *
     * @return int $einwAnerkstelle
     */
    public function getEinwAnerkstelle()
    {
        return $this->einwAnerkstelle;
    }
    
    /**
     * Sets the einwAnerkstelle
     *
     * @param int $einwAnerkstelle
     * @return void
     */
    public function setEinwAnerkstelle($einwAnerkstelle)
    {
        $this->einwAnerkstelle = $einwAnerkstelle;
    }
    
    /**
     * Returns the einwAnerkstelledatum
     *
     * @return string $einwAnerkstelledatum
     */
    public function getEinwAnerkstelledatum()
    {
        return $this->einwAnerkstelledatum;
    }
    
    /**
     * Sets the einwAnerkstelledatum
     *
     * @param string $einwAnerkstelledatum
     * @return void
     */
    public function setEinwAnerkstelledatum($einwAnerkstelledatum)
    {
        $this->einwAnerkstelledatum = $einwAnerkstelledatum;
    }
    
    /**
     * Returns the einwAnerkstellemedium
     *
     * @return array $einwAnerkstellemedium
     */
    public function getEinwAnerkstellemedium()
    {
        return explode(',', $this->einwAnerkstellemedium);
    }
    
    /**
     * Sets the einwAnerkstellemedium
     *
     * @param array $einwAnerkstellemedium
     * @return void
     */
    public function setEinwAnerkstellemedium(array $einwAnerkstellemedium)
    {
        $this->einwAnerkstellemedium = implode(',', $einwAnerkstellemedium);
    }
    
    /**
     * Returns the einwAnerkstellename
     *
     * @return string $einwAnerkstellename
     */
    public function getEinwAnerkstellename()
    {
        return $this->einwAnerkstellename;
    }
    
    /**
     * Sets the einwAnerkstellename
     *
     * @param string $einwAnerkstellename
     * @return void
     */
    public function setEinwAnerkstellename($einwAnerkstellename)
    {
        $this->einwAnerkstellename = $einwAnerkstellename;
    }
    
    /**
     * Returns the einwAnerkstellekontakt
     *
     * @return string $einwAnerkstellekontakt
     */
    public function getEinwAnerkstellekontakt()
    {
        return $this->einwAnerkstellekontakt;
    }
    
    /**
     * Sets the einwAnerkstellekontakt
     *
     * @param string $einwAnerkstellekontakt
     * @return void
     */
    public function setEinwAnerkstellekontakt($einwAnerkstellekontakt)
    {
        $this->einwAnerkstellekontakt = $einwAnerkstellekontakt;
    }
    
    /**
     * Returns the einwPerson
     *
     * @return int $einwPerson
     */
    public function getEinwPerson()
    {
        return $this->einwPerson;
    }
    
    /**
     * Sets the einwPerson
     *
     * @param int $einwPerson
     * @return void
     */
    public function setEinwPerson($einwPerson)
    {
        $this->einwPerson = $einwPerson;
    }
    
    /**
     * Returns the einwPersondatum
     *
     * @return string $einwPersondatum
     */
    public function getEinwPersondatum()
    {
        return $this->einwPersondatum;
    }
    
    /**
     * Sets the einwPersondatum
     *
     * @param string $einwPersondatum
     * @return void
     */
    public function setEinwPersondatum($einwPersondatum)
    {
        $this->einwPersondatum = $einwPersondatum;
    }
    
    /**
     * Returns the einwPersonmedium
     *
     * @return array $einwPersonmedium
     */
    public function getEinwPersonmedium()
    {
        return explode(',', $this->einwPersonmedium);
    }
    
    /**
     * Sets the einwPersonmedium
     *
     * @param array $einwPersonmedium
     * @return void
     */
    public function setEinwPersonmedium(array $einwPersonmedium)
    {
        $this->einwPersonmedium = implode(',', $einwPersonmedium);
    }
    
    /**
     * Returns the einwPersonname
     *
     * @return string $einwPersonname
     */
    public function getEinwPersonname()
    {
        return $this->einwPersonname;
    }
    
    /**
     * Sets the einwPersonname
     *
     * @param string $einwPersonname
     * @return void
     */
    public function setEinwPersonname($einwPersonname)
    {
        $this->einwPersonname = $einwPersonname;
    }
    
    /**
     * Returns the einwPersonkontakt
     *
     * @return string $einwPersonkontakt
     */
    public function getEinwPersonkontakt()
    {
        return $this->einwPersonkontakt;
    }
    
    /**
     * Sets the einwPersonkontakt
     *
     * @param string $einwPersonkontakt
     * @return void
     */
    public function setEinwPersonkontakt($einwPersonkontakt)
    {
        $this->einwPersonkontakt = $einwPersonkontakt;
    }
    
    /**
     * Returns the kundennummerAA
     * 
     * @return string $kundennummerAA
     */
    public function getKundennummerAA()
    {
        return $this->kundennummerAA;
    }

    /**
     * Sets the kundennummerAA
     * 
     * @param string $kundennummerAA
     * @return void
     */
    public function setKundennummerAA($kundennummerAA)
    {
        $this->kundennummerAA = $kundennummerAA;
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
     * Returns the aufenthaltsstatusfreitext
     *
     * @return string $aufenthaltsstatusfreitext
     */
    public function getAufenthaltsstatusfreitext()
    {
    	return $this->aufenthaltsstatusfreitext;
    }
    
    /**
     * Sets the aufenthaltsstatusfreitext
     *
     * @param string $aufenthaltsstatusfreitext
     * @return void
     */
    public function setAufenthaltsstatusfreitext($aufenthaltsstatusfreitext)
    {
    	$this->aufenthaltsstatusfreitext = $aufenthaltsstatusfreitext;
    }

    /**
     * Returns the nameBeratungsstelle
     * 
     * @return string $nameBeratungsstelle
     */
    public function getNameBeratungsstelle()
    {
        return $this->nameBeratungsstelle;
    }

    /**
     * Sets the nameBeratungsstelle
     * 
     * @param string $nameBeratungsstelle
     * @return void
     */
    public function setNameBeratungsstelle($nameBeratungsstelle)
    {
        $this->nameBeratungsstelle = $nameBeratungsstelle;
    }

    /**
     * Returns the notizen
     *
     * @return string $notizen
     */
    public function getNotizen()
    {
        return $this->notizen;
    }
    
    /**
     * Sets the notizen
     *
     * @param string $notizen
     * @return void
     */
    public function setNotizen($notizen)
    {
        $this->notizen = $notizen;
    }
    
    /**
     * Returns the einwilligung
     * 
     * @return int $einwilligung
     */
    public function getEinwilligung()
    {
        return $this->einwilligung;
    }

    /**
     * Sets the einwilligung
     * 
     * @param int $einwilligung
     * @return void
     */
    public function setEinwilligung($einwilligung)
    {
        $this->einwilligung = $einwilligung;
    }    

    /**
     * @return boolean $einwilligung
     */
    public function isEinwilligung() {
    	return $this->getEinwilligung();
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
     * Sets the anerkennungszuschussbeantragt
     *
     * @param string $anerkennungszuschussbeantragt
     * @return void
     */
    public function setAnerkennungszuschussbeantragt($anerkennungszuschussbeantragt)
    {
        $this->anerkennungszuschussbeantragt = $anerkennungszuschussbeantragt;
    }
    
    /**
     * Returns the anerkennungszuschussbeantragt
     *
     * @return string $anerkennungszuschussbeantragt
     */
    public function getAnerkennungszuschussbeantragt()
    {
        return $this->anerkennungszuschussbeantragt;
    }
    
    /**
     * Sets the wieberaten
     *
     * @param array $wieberaten
     * @return void
     */
    public function setWieberaten(array $wieberaten)
    {
        $this->wieberaten = implode(',', $wieberaten);
    }
    
    /**
     * Returns the wieberaten
     *
     * @return array $wieberaten
     */
    public function getWieberaten()
    {
        return explode(',', $this->wieberaten);
    }
    
    /**
     * Sets the kooperationgruppe
     *
     * @param string $kooperationgruppe
     * @return void
     */
    public function setKooperationgruppe($kooperationgruppe)
    {
        $this->kooperationgruppe = $kooperationgruppe;
    }
    
    /**
     * Returns the kooperationgruppe
     *
     * @return string $kooperationgruppe
     */
    public function getKooperationgruppe()
    {
        return $this->kooperationgruppe;
    }
    
    
   //****** BERATUNG *****
    
    /**
     * Returns the beratungdatum
     *
     * @return string $beratungdatum
     */
    public function getBeratungdatum()
    {
        return $this->beratungdatum;
    }
    
    /**
     * Sets the beratungdatum
     *
     * @param string $beratungdatum
     * @return void
     */
    public function setBeratungdatum($beratungdatum)
    {
        $this->beratungdatum = $beratungdatum;
    }
    
    /**
     * Returns the beratungsart
     *
     * @return array $beratungsart
     */
    public function getBeratungsart()
    {
        return explode(',', $this->beratungsart);
    }
    
    /**
     * Sets the beratungsart
     *
     * @param array $beratungsart
     * @return void
     */
    public function setBeratungsart(array $beratungsart)
    {
        $this->beratungsart = implode(',', $beratungsart);
    }
    
    /**
     * Returns the beratungsartfreitext
     *
     * @return string $beratungsartfreitext
     */
    public function getBeratungsartfreitext()
    {
        return $this->beratungsartfreitext;
    }
    
    /**
     * Sets the beratungsartfreitext
     *
     * @param string $beratungsartfreitext
     * @return void
     */
    public function setBeratungsartfreitext($beratungsartfreitext)
    {
        $this->beratungsartfreitext = $beratungsartfreitext;
    }
    
    /**
     * Returns the beratungsort
     *
     * @return string $beratungsort
     */
    public function getBeratungsort()
    {
        return $this->beratungsort;
    }
    
    /**
     * Sets the beratungsort
     *
     * @param string $beratungsort
     * @return void
     */
    public function setBeratungsort($beratungsort)
    {
        $this->beratungsort = $beratungsort;
    }
    
    /**
     * Returns the beratungsdauer
     *
     * @return string $beratungsdauer
     */
    public function getBeratungsdauer()
    {
        return $this->beratungsdauer;
    }
    
    /**
     * Sets the beratungsdauer
     *
     * @param string $beratungsdauer
     * @return void
     */
    public function setBeratungsdauer($beratungsdauer)
    {
        $this->beratungsdauer = $beratungsdauer;
    }
    
    /**
     * Returns the beratungzu
     *
     * @return string $beratungzu
     */
    public function getBeratungzu()
    {
        return $this->beratungzu;
    }
    
    /**
     * Sets the beratungzu
     *
     * @param string $beratungzu
     * @return void
     */
    public function setBeratungzu($beratungzu)
    {
        $this->beratungzu = $beratungzu;
    }
    
    /**
     * Returns the referenzberufe
     *
     * @return string $referenzberufe
     */
    public function getReferenzberufe()
    {
        return $this->referenzberufe;
    }
    
    /**
     * Sets the referenzberufe
     *
     * @param string $referenzberufe
     * @return void
     */
    public function setReferenzberufe($referenzberufe)
    {
        $this->referenzberufe = $referenzberufe;
    }
    
    /**
     * Returns the anerkennendestellen
     *
     * @return string $anerkennendestellen
     */
    public function getAnerkennendestellen()
    {
        return $this->anerkennendestellen;
    }
    
    /**
     * Sets the anerkennendestellen
     *
     * @param string $anerkennendestellen
     * @return void
     */
    public function setAnerkennendestellen($anerkennendestellen)
    {
        $this->anerkennendestellen = $anerkennendestellen;
    }
    
    /**
     * Returns the anerkennungsberatung
     *
     * @return array $anerkennungsberatung
     */
    public function getAnerkennungsberatung()
    {
        return explode(',', $this->anerkennungsberatung);
    }
    
    /**
     * Sets the anerkennungsberatung
     *
     * @param array $anerkennungsberatung
     * @return void
     */
    public function setAnerkennungsberatung(array $anerkennungsberatung)
    {
        $this->anerkennungsberatung = implode(',', $anerkennungsberatung);
    }
    
    /**
     * Returns the anerkennungsberatungfreitext
     *
     * @return string $anerkennungsberatungfreitext
     */
    public function getAnerkennungsberatungfreitext()
    {
        return $this->anerkennungsberatungfreitext;
    }
    
    /**
     * Sets the anerkennungsberatungfreitext
     *
     * @param string $anerkennungsberatungfreitext
     * @return void
     */
    public function setAnerkennungsberatungfreitext($anerkennungsberatungfreitext)
    {
        $this->anerkennungsberatungfreitext = $anerkennungsberatungfreitext;
    }
    
    /**
     * Returns the qualifizierungsberatung
     *
     * @return array $qualifizierungsberatung
     */
    public function getQualifizierungsberatung()
    {
        return explode(',', $this->qualifizierungsberatung);
    }
    
    /**
     * Sets the qualifizierungsberatung
     *
     * @param array $qualifizierungsberatung
     * @return void
     */
    public function setQualifizierungsberatung(array $qualifizierungsberatung)
    {
        $this->qualifizierungsberatung = implode(',', $qualifizierungsberatung);
    }
    
    /**
     * Returns the qualifizierungsberatungfreitext
     *
     * @return string $qualifizierungsberatungfreitext
     */
    public function getQualifizierungsberatungfreitext()
    {
        return $this->qualifizierungsberatungfreitext;
    }
    
    /**
     * Sets the qualifizierungsberatungfreitext
     *
     * @param string $qualifizierungsberatungfreitext
     * @return void
     */
    public function setQualifizierungsberatungfreitext($qualifizierungsberatungfreitext)
    {
        $this->qualifizierungsberatungfreitext = $qualifizierungsberatungfreitext;
    }
    
    /**
     * Returns the beratungnotizen
     *
     * @return string $beratungnotizen
     */
    public function getBeratungnotizen()
    {
        return $this->beratungnotizen;
    }
    
    /**
     * Sets the beratungnotizen
     *
     * @param string $beratungnotizen
     * @return void
     */
    public function setBeratungnotizen($beratungnotizen)
    {
        $this->beratungnotizen = $beratungnotizen;
    }
    
    /**
     * Returns the erstberatungabgeschlossen
     *
     * @return string $erstberatungabgeschlossen
     */
    public function getErstberatungabgeschlossen()
    {
        return $this->erstberatungabgeschlossen;
    }
    
    /**
     * Sets the erstberatungabgeschlossen
     *
     * @param string $erstberatungabgeschlossen
     * @return void
     */
    public function setErstberatungabgeschlossen($erstberatungabgeschlossen)
    {
        $this->erstberatungabgeschlossen = $erstberatungabgeschlossen;
    }
    
    /**
     * Returns the berater
     *
     * @return \Ud\Iqtp13db\Domain\Model\Berater $berater
     */
    public function getBerater()
    {
        return $this->berater;
    }
    
    /**
     * Sets the berater
     *
     * @param \Ud\Iqtp13db\Domain\Model\Berater $berater
     * @return void
     */
    public function setBerater(\Ud\Iqtp13db\Domain\Model\Berater $berater)
    {
        $this->berater = $berater;
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
     * Returns the dublette
     *
     * @return boolean $dublette
     */
    public function getDublette()
    {
        return $this->dublette;
    }
    
    /**
     * Sets the dublette
     *
     * @param boolean $dublette
     * @return void
     */
    public function setDublette($dublette) 
    {
        $this->dublette = $dublette;
    }
     
	
	/**
	 * @return boolean $hidden
	 */
	public function getHidden() {
	    return $this->hidden;
	}
	
	/**
	 * @return boolean $hidden
	 */
	public function isHidden() {
	    return $this->getHidden();
	}
	
	/**
	 * @param boolean $hidden
	 * @return void
	 */
	public function setHidden($hidden) {
	    $this->hidden = $hidden;
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
