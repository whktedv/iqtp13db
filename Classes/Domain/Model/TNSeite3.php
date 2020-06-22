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
	 * bescheidfruehererAnerkennungsantrag
	 *
	 * @var bool
	 */
	protected $bescheidfruehererAnerkennungsantrag = FALSE;
	
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
	
}