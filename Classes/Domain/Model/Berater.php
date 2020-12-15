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
 * Berater
 */
class Berater extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Name
     *
     * @var string
     */
    protected $name = '';

    /**
     * Organisation
     *
     * @var string
     */
    protected $organisation = '';
    
    /**
     * Kürzel
     *
     * @var string
     */
    protected $kuerzel = '';

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
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {

    }

    /**
     * Returns the organisation
     *
     * @return string $organisation
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * Sets the organisation
     *
     * @param string $organisation
     * @return void
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
    }
    
    /**
     * Returns the kuerzel
     *
     * @return string $kuerzel
     */
    public function getKuerzel()
    {
    	return $this->kuerzel;
    }
    
    /**
     * Sets the kuerzel
     *
     * @param string $kuerzel
     * @return void
     */
    public function setKuerzel($kuerzel)
    {
    	$this->kuerzel = $kuerzel;
    }
}
