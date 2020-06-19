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
 * Dokument
 */
class Dokument extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Name
     *
     * @var string
     */
    protected $name = '';

    /**
     * Pfad
     *
     * @var string
     */
    protected $pfad = '';

    /**
     * beratung
     *
     * @var \Ud\Iqtp13db\Domain\Model\Beratung
     */
    protected $beratung = NULL;

    /**
     * schulung
     *
     * @var \Ud\Iqtp13db\Domain\Model\Schulung
     */
    protected $schulung = NULL;

    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the pfad
     *
     * @return string $pfad
     */
    public function getPfad()
    {
        return $this->pfad;
    }

    /**
     * Sets the pfad
     *
     * @param string $pfad
     * @return void
     */
    public function setPfad($pfad)
    {
        $this->pfad = $pfad;
    }

    /**
     * Returns the beratung
     *
     * @return \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     */
    public function getBeratung()
    {
        return $this->beratung;
    }

    /**
     * Sets the beratung
     *
     * @param \Ud\Iqtp13db\Domain\Model\Beratung $beratung
     * @return void
     */
    public function setBeratung(\Ud\Iqtp13db\Domain\Model\Beratung $beratung)
    {
        $this->beratung = $beratung;
    }

    /**
     * Returns the schulung
     *
     * @return \Ud\Iqtp13db\Domain\Model\Schulung $schulung
     */
    public function getSchulung()
    {
        return $this->schulung;
    }

    /**
     * Sets the schulung
     *
     * @param \Ud\Iqtp13db\Domain\Model\Schulung $schulung
     * @return void
     */
    public function setSchulung(\Ud\Iqtp13db\Domain\Model\Schulung $schulung)
    {
        $this->schulung = $schulung;
    }
}
