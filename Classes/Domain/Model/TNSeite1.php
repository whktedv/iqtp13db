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

/**
 * TNSeite1
 */
class TNSeite1 extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

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
	 * E-Mail bestätigung
	 *
	 * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty"), @TYPO3\CMS\Extbase\Annotation\Validate("EmailAddress")
	 */
	protected $confirmemail = '';

	/**
	 * Telefon
	 *
	 * @var string
	 * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
	 */
	protected $telefon = '';

	/**
	 * lebensalter
	 *
	 * @var string
	 */
	protected $lebensalter = '';

	/**
	 * Geburtsland
	 *
	 * @var string	 
	 */
	protected $geburtsland = '';

	/**
	 * Geschlecht
	 *
	 * @var int
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
	 */
	protected $wohnsitzDeutschland = 0;

	/**
	 * Wohnsitz in
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
	 * Einwilligung Datenübermittlung
	 *
	 * @var bool
	 * @TYPO3\CMS\Extbase\Annotation\Validate("\Ud\Iqtp13db\Validation\Validator\EinwilligungValidator")
	 */
	protected $einwilligung = false;

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
	 * Returns the lebensalter
	 *
	 * @return string $lebensalter
	 */
	public function getLebensalter() {
		return $this->lebensalter;
	}

	/**
	 * Sets the lebensalter
	 *
	 * @param string $lebensalter
	 * @return void
	 */
	public function setLebensalter($lebensalter) {
		$this->lebensalter = $lebensalter;
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