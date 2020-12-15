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
	 * bescheidfruehererAnerkennungsantrag
	 *
	 * @var bool
	 */
	protected $bescheidfruehererAnerkennungsantrag = FALSE;
	
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
	 * Returns the aufenthaltsstatus
	 *
	 * @return int $aufenthaltsstatus
	 */
	public function getAufenthaltsstatus() {
		return $this->aufenthaltsstatus;
	}

	/**
	 * Sets the aufenthaltsstatus
	 *
	 * @param int $aufenthaltsstatus
	 * @return void
	 */
	public function setAufenthaltsstatus($aufenthaltsstatus) {
		$this->aufenthaltsstatus = $aufenthaltsstatus;
	}

	/**
	 * Returns the fruehererAntrag
	 *
	 * @return int $fruehererAntrag
	 */
	public function getFruehererAntrag() {
		return $this->fruehererAntrag;
	}

	/**
	 * Sets the fruehererAntrag
	 *
	 * @param int $fruehererAntrag
	 * @return void
	 */
	public function setFruehererAntrag($fruehererAntrag) {
		$this->fruehererAntrag = $fruehererAntrag;
	}

	/**
	 * Returns the fruehererAntragReferenzberuf
	 *
	 * @return string $fruehererAntragReferenzberuf
	 */
	public function getFruehererAntragReferenzberuf() {
		return $this->fruehererAntragReferenzberuf;
	}

	/**
	 * Sets the fruehererAntragReferenzberuf
	 *
	 * @param string $fruehererAntragReferenzberuf
	 * @return void
	 */
	public function setFruehererAntragReferenzberuf($fruehererAntragReferenzberuf) {
		$this->fruehererAntragReferenzberuf = $fruehererAntragReferenzberuf;
	}

	/**
	 * Returns the fruehererAntragInstitution
	 *
	 * @return string $fruehererAntragInstitution
	 */
	public function getFruehererAntragInstitution() {
		return $this->fruehererAntragInstitution;
	}
	
	/**
	 * Sets the fruehererAntragInstitution
	 *
	 * @param string $fruehererAntragInstitution
	 * @return void
	 */
	public function setFruehererAntragInstitution($fruehererAntragInstitution) {
		$this->fruehererAntragInstitution = $fruehererAntragInstitution;
	}
	
	/**
	 * Returns the bescheidfruehererAnerkennungsantrag
	 *
	 * @return bool $bescheidfruehererAnerkennungsantrag
	 */
	public function getBescheidfruehererAnerkennungsantrag() {
		return $this->bescheidfruehererAnerkennungsantrag;
	}
	
	/**
	 * Sets the bescheidfruehererAnerkennungsantrag
	 *
	 * @param bool $bescheidfruehererAnerkennungsantrag
	 * @return void
	 */
	public function setBescheidfruehererAnerkennungsantrag($bescheidfruehererAnerkennungsantrag) {
		$this->bescheidfruehererAnerkennungsantrag = $bescheidfruehererAnerkennungsantrag;
	}
	
	/**
	 * Returns the boolean state of bescheidfruehererAnerkennungsantrag
	 *
	 * @return bool
	 */
	public function isBescheidfruehererAnerkennungsantrag() {
		return $this->bescheidfruehererAnerkennungsantrag;
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
	
	
}