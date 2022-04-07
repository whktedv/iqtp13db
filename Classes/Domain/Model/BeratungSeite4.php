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
 * BeratungSeite4
 */
class BeratungSeite4 extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Datum Erstkontakt
	 *
	 * @var \DateTime
	 * @validate NotEmpty,DateTime
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