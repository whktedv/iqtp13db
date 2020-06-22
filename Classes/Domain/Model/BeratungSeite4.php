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
 * BeratungSeite4
 */
class BeratungSeite4 extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Datum Erstkontakt
	 *
	 * @var \DateTime
	 * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty"), @TYPO3\CMS\Extbase\Annotation\Validate("DateTime")
	 */
	protected $datum = '';
	
	/**
	 * Weg zur Beratungsstelle über
	 *
	 * @var int
	 */
	protected $wegBeratungsstelle = 0;

	/**
	 * Berater
	 *
	 * @var \Ud\Iqtp13db\Domain\Model\Berater
	 */
	protected $berater = NULL;

	/**
	 * Returns the datum
	 *
	 * @return \DateTime $datum
	 */
	public function getDatum() {
		return $this->datum;
	}
	
	/**
	 * Sets the datum
	 *
	 * @param \DateTime $datum
	 * @return void
	 */
	public function setDatum(\DateTime $datum) {
		$this->datum = $datum;
	}
	
	/**
	 * Returns the wegBeratungsstelle
	 *
	 * @return int $wegBeratungsstelle
	 */
	public function getWegBeratungsstelle() {
		return $this->wegBeratungsstelle;
	}

	/**
	 * Sets the wegBeratungsstelle
	 *
	 * @param int $wegBeratungsstelle
	 * @return void
	 */
	public function setWegBeratungsstelle($wegBeratungsstelle) {
		$this->wegBeratungsstelle = $wegBeratungsstelle;
	}

	/**
	 * Returns the berater
	 *
	 * @return \Ud\Iqtp13db\Domain\Model\Berater $berater
	 */
	public function getBerater() {
		return $this->berater;
	}

	/**
	 * Sets the berater
	 *
	 * @param \Ud\Iqtp13db\Domain\Model\Berater $berater
	 * @return void
	 */
	public function setBerater(\Ud\Iqtp13db\Domain\Model\Berater $berater) {
		$this->berater = $berater;
	}

}