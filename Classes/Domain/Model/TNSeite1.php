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
 * TNSeite1
 */
class TNSeite1 extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	
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
	 * @TYPO3\CMS\Extbase\Annotation\Validate("NumberRange", options={"minimum":1, "maximum":3})
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
	 * @TYPO3\CMS\Extbase\Annotation\Validate("NumberRange", options={"minimum":1, "maximum":2})
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
	 * Einwilligung Datenübermittlung
	 *
	 * @var bool
	 * @TYPO3\CMS\Extbase\Annotation\Validate("\Ud\Iqtp13db\Validation\Validator\EinwilligungValidator")
	 */
	protected $einwilligung = NULL;

	/**
	 * Returns the nachname
	 *
	 * @return string $nachname
	 */
	public function getNachname() {
		return $this->nachname;
	}

	/**
	 * Sets the nachname
	 *
	 * @param string $nachname
	 * @return void
	 */
	public function setNachname($nachname) {
		$this->nachname = $nachname;
	}

	/**
	 * Returns the vorname
	 *
	 * @return string $vorname
	 */
	public function getVorname() {
		return $this->vorname;
	}

	/**
	 * Sets the vorname
	 *
	 * @param string $vorname
	 * @return void
	 */
	public function setVorname($vorname) {
		$this->vorname = $vorname;
	}

	/**
	 * Returns the strasse
	 *
	 * @return string $strasse
	 */
	public function getStrasse() {
		return $this->strasse;
	}
	
	/**
	 * Sets the strasse
	 *
	 * @param string $strasse
	 * @return void
	 */
	public function setStrasse($strasse) {
		$this->strasse = $strasse;
	}
	
	/**
	 * Returns the plz
	 *
	 * @return string $plz
	 */
	public function getPlz() {
		return $this->plz;
	}
	
	/**
	 * Sets the plz
	 *
	 * @param string $plz
	 * @return void
	 */
	public function setPlz($plz) {
		$this->plz = $plz;
	}
	
	/**
	 * Returns the ort
	 *
	 * @return string $ort
	 */
	public function getOrt() {
		return $this->ort;
	}
	
	/**
	 * Sets the ort
	 *
	 * @param string $ort
	 * @return void
	 */
	public function setOrt($ort) {
		$this->ort = $ort;
	}
	
	/**
	 * Returns the email
	 *
	 * @return string $email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Sets the email
	 *
	 * @param string $email
	 * @return void
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * Returns the telefon
	 *
	 * @return string $telefon
	 */
	public function getTelefon() {
		return $this->telefon;
	}

	/**
	 * Sets the telefon
	 *
	 * @param string $telefon
	 * @return void
	 */
	public function setTelefon($telefon) {
		$this->telefon = $telefon;
	}

	/**
	 * Returns the geburtsjahr
	 *
	 * @return string $geburtsjahr
	 */
	public function getGeburtsjahr() {
		return $this->geburtsjahr;
	}

	/**
	 * Sets the geburtsjahr
	 *
	 * @param string $geburtsjahr
	 * @return void
	 */
	public function setGeburtsjahr($geburtsjahr) {
		$this->geburtsjahr = $geburtsjahr;
	}

	/**
	 * Returns the geburtsland
	 *
	 * @return string $geburtsland
	 */
	public function getGeburtsland() {
		return $this->geburtsland;
	}

	/**
	 * Sets the geburtsland
	 *
	 * @param string $geburtsland
	 * @return void
	 */
	public function setGeburtsland($geburtsland) {
		$this->geburtsland = $geburtsland;
	}

	/**
	 * Returns the geschlecht
	 *
	 * @return int $geschlecht
	 */
	public function getGeschlecht() {
		return $this->geschlecht;
	}

	/**
	 * Sets the geschlecht
	 *
	 * @param int $geschlecht
	 * @return void
	 */
	public function setGeschlecht($geschlecht) {
		$this->geschlecht = $geschlecht;
	}

	/**
	 * Returns the ersteStaatsangehoerigkeit
	 *
	 * @return string $ersteStaatsangehoerigkeit
	 */
	public function getErsteStaatsangehoerigkeit() {
		return $this->ersteStaatsangehoerigkeit;
	}

	/**
	 * Sets the ersteStaatsangehoerigkeit
	 *
	 * @param string $ersteStaatsangehoerigkeit
	 * @return void
	 */
	public function setErsteStaatsangehoerigkeit($ersteStaatsangehoerigkeit) {
		$this->ersteStaatsangehoerigkeit = $ersteStaatsangehoerigkeit;
	}

	/**
	 * Returns the zweiteStaatsangehoerigkeit
	 *
	 * @return string $zweiteStaatsangehoerigkeit
	 */
	public function getZweiteStaatsangehoerigkeit() {
		return $this->zweiteStaatsangehoerigkeit;
	}

	/**
	 * Sets the zweiteStaatsangehoerigkeit
	 *
	 * @param string $zweiteStaatsangehoerigkeit
	 * @return void
	 */
	public function setZweiteStaatsangehoerigkeit($zweiteStaatsangehoerigkeit) {
		$this->zweiteStaatsangehoerigkeit = $zweiteStaatsangehoerigkeit;
	}

	/**
	 * Returns the einreisejahr
	 *
	 * @return string $einreisejahr
	 */
	public function getEinreisejahr() {
		return $this->einreisejahr;
	}

	/**
	 * Sets the einreisejahr
	 *
	 * @param string $einreisejahr
	 * @return void
	 */
	public function setEinreisejahr($einreisejahr) {
		$this->einreisejahr = $einreisejahr;
	}

	/**
	 * Returns the wohnsitzDeutschland
	 *
	 * @return int $wohnsitzDeutschland
	 */
	public function getWohnsitzDeutschland() {
		return $this->wohnsitzDeutschland;
	}

	/**
	 * Sets the wohnsitzDeutschland
	 *
	 * @param int $wohnsitzDeutschland
	 * @return void
	 */
	public function setWohnsitzDeutschland($wohnsitzDeutschland) {
		$this->wohnsitzDeutschland = $wohnsitzDeutschland;
	}

	/**
	 * Returns the wohnsitzJaBundesland
	 *
	 * @return string $wohnsitzJaBundesland
	 */
	public function getWohnsitzJaBundesland() {
		return $this->wohnsitzJaBundesland;
	}

	/**
	 * Sets the wohnsitzJaBundesland
	 *
	 * @param string $wohnsitzJaBundesland
	 * @return void
	 */
	public function setWohnsitzJaBundesland($wohnsitzJaBundesland) {
		$this->wohnsitzJaBundesland = $wohnsitzJaBundesland;
	}

	/**
	 * Returns the wohnsitzNeinIn
	 *
	 * @return string $wohnsitzNeinIn
	 */
	public function getWohnsitzNeinIn() {
		return $this->wohnsitzNeinIn;
	}

	/**
	 * Sets the wohnsitzNeinIn
	 *
	 * @param string $wohnsitzNeinIn
	 * @return void
	 */
	public function setWohnsitzNeinIn($wohnsitzNeinIn) {
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
	 * Returns the einwilligung
	 *
	 * @return boolean $einwilligung
	 */
	public function getEinwilligung() {
		return $this->einwilligung;
	}

	/**
	 * @return boolean $einwilligung
	 */
	public function isEinwilligung() {
		return $this->getEinwilligung();
	}

	/**
	 * Sets the einwilligung
	 *
	 * @param boolean $einwilligung
	 * @return void
	 */
	public function setEinwilligung($einwilligung) {
		$this->einwilligung = $einwilligung;
	}

}