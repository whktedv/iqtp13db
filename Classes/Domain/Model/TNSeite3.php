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
 * TNSeite3
 */
class TNSeite3 extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Erwerbsstatus
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
	 * Leistungsbezug
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
	 * kundennummerAA
	 *
	 * @var string
	 */
	protected $kundennummerAA = '';
	
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
	 * nameBeratungsstelle
	 *
	 * @var string
	 */
	protected $nameBeratungsstelle = '';
		
	/**
	 * wieberaten
	 *
	 * @var string
	 */
	protected $wieberaten = '';
	
	/**
	 * notizen
	 *
	 * @var string
	 */
	protected $notizen = '';
	
	/**
	 * initializes this object
	 *
	 * @param array $wieberaten
	 */
	public function __construct(array $wieberaten = array()) {
	    $this->setWieberaten($wieberaten);
	}
	
	/**
	 * Returns the erwerbsstatus
	 *
	 * @return int $erwerbsstatus
	 */
	public function getErwerbsstatus() {
		return $this->erwerbsstatus;
	}

	/**
	 * Sets the erwerbsstatus
	 *
	 * @param int $erwerbsstatus
	 * @return void
	 */
	public function setErwerbsstatus($erwerbsstatus) {
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
	public function getLeistungsbezug() {
		return $this->leistungsbezug;
	}

	/**
	 * Sets the leistungsbezug
	 *
	 * @param string $leistungsbezug
	 * @return void
	 */
	public function setLeistungsbezug($leistungsbezug) {
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
	
	
}