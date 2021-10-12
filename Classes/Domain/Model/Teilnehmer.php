<?php
namespace Ud\Iqtp13db\Domain\Model;

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
 * Teilnehmer
 */
class Teilnehmer extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

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
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty"), @TYPO3\CMS\Extbase\Annotation\Validate("EmailAddress")
     * @var string
     */
    protected $email = '';
    
    /**
     * E-Mail bestätigung
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty"), @TYPO3\CMS\Extbase\Annotation\Validate("EmailAddress")
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
     * Ortskraft Afghanistan
     *
     * @var bool
     */
    protected $ortskraftafghanistan = false;
    
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
	 * Abschlussart1
	 *
	 * @var string
	 */
	protected $abschlussart1 = '';

	/**
	 * Abschlussart2
	 *
	 * @var string
	 */
	protected $abschlussart2 = '';
    
	/**
	 * Erwerbsland
	 *
	 * @var string
	 */
	protected $erwerbsland1 = '';
    
    /**
     * dauerBerufsausbildung1
     *
     * @var string
     */
    protected $dauerBerufsausbildung1 = '';
    
	/**
	 * Abschlussjahr
	 *
	 * @var string
	 */
	protected $abschlussjahr1 = '';
    
    /**
     * ausbildungsinstitution1
     *
     * @var string
     */
    protected $ausbildungsinstitution1 = '';
    
    /**
     * ausbildungsort1
     *
     * @var string
     */
    protected $ausbildungsort1 = '';
    
	/**
	 * Abschluss
	 *
	 * @var string
	 */
	protected $abschluss1 = '';
    
    /**
     * berufserfahrung1
     *
     * @var string
     */
    protected $berufserfahrung1 = '';
    
    /**
     * ausbildungsfremdeberufserfahrung1
     *
     * @var string
     */
    protected $ausbildungsfremdeberufserfahrung1 = '';
        
    /**
     * deutscherReferenzberuf1
     *
     * @var string
     */
    protected $deutscherReferenzberuf1 = '';
    
    /**
     * wunschberuf1
     *
     * @var string
     */
    protected $wunschberuf1 = '';
    
    /**
     * erwerbsland2
     *
     * @var string
     */
    protected $erwerbsland2 = '';
    
    /**
     * dauerBerufsausbildung2
     *
     * @var string
     */
    protected $dauerBerufsausbildung2 = '';
    
    /**
     * abschlussjahr2
     *
     * @var string
     */
    protected $abschlussjahr2 = '';
    
    /**
     * ausbildungsinstitution2
     *
     * @var string
     */
    protected $ausbildungsinstitution2 = '';
    
    /**
     * ausbildungsort2
     *
     * @var string
     */
    protected $ausbildungsort2 = '';
    
    /**
     * abschluss2
     *
     * @var string
     */
    protected $abschluss2 = '';
    
    /**
     * berufserfahrung2
     *
     * @var string
     */
    protected $berufserfahrung2 = '';
    
    /**
     * ausbildungsfremdeberufserfahrung2
     *
     * @var string
     */
    protected $ausbildungsfremdeberufserfahrung2 = '';
    
    
    /**
     * deutscherReferenzberuf2
     *
     * @var string
     */
    protected $deutscherReferenzberuf2 = '';
    
    /**
     * wunschberuf2
     *
     * @var string
     */
    protected $wunschberuf2 = '';
    
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
     * fruehererAntrag
     *
     * @var int
     */
    protected $fruehererAntrag = 0;
    
    /**
     * fruehererAntragReferenzberuf
     *
     * @var string
     */
    protected $fruehererAntragReferenzberuf = '';
    
    /**
     * fruehererAntragInstitution
     *
     * @var string
     */
    protected $fruehererAntragInstitution = '';
    
    /**
     * bescheidfruehererAnerkennungsantrag
     *
     * @var int
     */
    protected $bescheidfruehererAnerkennungsantrag = 0;
    
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
     * @TYPO3\CMS\Extbase\Annotation\Validate("\Ud\Iqtp13db\Validation\Validator\EinwilligungValidator")
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
     * @param array $abschlussart1
     * @param array $abschlussart2
     * @param array $einwilligungdatenanAAmedium
     * @param array $einwAnerkstellemedium
     * @param array $einwPersonmedium
     */
    public function __construct(array $abschlussart1 = array(), array $abschlussart2 = array(), array $einwilligungdatenanAAmedium = array(), array $einwAnerkstellemedium = array(), array $einwPersonmedium = array()) {
    	$this->setAbschlussart1($abschlussart1);
    	$this->setAbschlussart2($abschlussart2);
    	$this->setEinwilligungdatenanAAmedium($einwilligungdatenanAAmedium);    	
    	$this->setEinwAnerkstellemedium($einwAnerkstellemedium);
    	$this->setEinwPersonmedium($einwPersonmedium);
    	$this->initVerificationCode();
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
     * Returns the ortskraftafghanistan
     *
     * @return boolean $ortskraftafghanistan
     */
    public function getOrtskraftafghanistan() {
        return $this->ortskraftafghanistan;
    }
    
    /**
     * Sets the ortskraftafghanistan
     *
     * @param boolean $ortskraftafghanistan
     * @return void
     */
    public function setOrtskraftafghanistan($ortskraftafghanistan) {
        $this->ortskraftafghanistan = $ortskraftafghanistan;
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
     * Returns the abschlussart1
     * 
     * @return array $abschlussart1
     */
    public function getAbschlussart1()
    {
        return explode(',', $this->abschlussart1);
    }

    /**
     * Sets the abschlussart1
     * 
     * @param array $abschlussart1
     * @return void
     */
    public function setAbschlussart1(array $abschlussart1)
    {
        $this->abschlussart1 = implode(',', $abschlussart1);
    }

    /**
     * Returns the abschlussart2
     * 
     * @return array $abschlussart2
     */
    public function getAbschlussart2()
    {
        return explode(',', $this->abschlussart2);
    }

    /**
     * Sets the abschlussart2
     * 
     * @param array $abschlussart2
     * @return void
     */
    public function setAbschlussart2(array $abschlussart2)
    {
        $this->abschlussart2 = implode(',', $abschlussart2);
    }

    /**
     * Returns the erwerbsland1
     * 
     * @return string $erwerbsland1
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
     * @return string $dauerBerufsausbildung1
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
     * @return string $abschlussjahr1
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
     * @return string $ausbildungsinstitution1
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
     * @return string $ausbildungsort1
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
     * @return string $abschluss1
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
     * Returns the berufserfahrung1
     *
     * @return string $berufserfahrung1
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
     * Returns the ausbildungsfremdeberufserfahrung1
     *
     * @return string $ausbildungsfremdeberufserfahrung1
     */
    public function getAusbildungsfremdeberufserfahrung1()
    {
        return $this->ausbildungsfremdeberufserfahrung1;
    }
    
    /**
     * Sets the ausbildungsfremdeberufserfahrung1
     *
     * @param string $ausbildungsfremdeberufserfahrung1
     * @return void
     */
    public function setAusbildungsfremdeberufserfahrung1($ausbildungsfremdeberufserfahrung1)
    {
        $this->ausbildungsfremdeberufserfahrung1 = $ausbildungsfremdeberufserfahrung1;
    }
    
    /**
     * Returns the deutscherReferenzberuf1
     * 
     * @return string $deutscherReferenzberuf1
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
     * Returns the wunschberuf1
     * 
     * @return string $wunschberuf1
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
     * Returns the ausbildungsfremdeberufserfahrung2
     *
     * @return string $ausbildungsfremdeberufserfahrung2
     */
    public function getAusbildungsfremdeberufserfahrung2()
    {
        return $this->ausbildungsfremdeberufserfahrung2;
    }
    
    /**
     * Sets the ausbildungsfremdeberufserfahrung2
     *
     * @param string $ausbildungsfremdeberufserfahrung2
     * @return void
     */
    public function setAusbildungsfremdeberufserfahrung2($ausbildungsfremdeberufserfahrung2)
    {
        $this->ausbildungsfremdeberufserfahrung2 = $ausbildungsfremdeberufserfahrung2;
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
     * Returns the bescheidfruehererAnerkennungsantrag
     * 
     * @return int $bescheidfruehererAnerkennungsantrag
     */
    public function getBescheidfruehererAnerkennungsantrag()
    {
        return $this->bescheidfruehererAnerkennungsantrag;
    }

    /**
     * Sets the bescheidfruehererAnerkennungsantrag
     * 
     * @param int $bescheidfruehererAnerkennungsantrag
     * @return void
     */
    public function setBescheidfruehererAnerkennungsantrag($bescheidfruehererAnerkennungsantrag)
    {
        $this->bescheidfruehererAnerkennungsantrag = $bescheidfruehererAnerkennungsantrag;
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
