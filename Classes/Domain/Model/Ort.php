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
 * Ort
 */
class Ort extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * plz
     *
     * @var string
     */
    protected $plz = '';
    
    /**
     * bundesland
     *
     * @var string
     */
    protected $bundesland = '';
    
    /**
     * landkreis
     *
     * @var string
     */
    protected $landkreis = '';
    
    
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
     * Returns the bundesland
     *
     * @return string $bundesland
     */
    public function getBundesland()
    {
        return $this->bundesland;
    }
    
    /**
     * Sets the bundesland
     *
     * @param string $bundesland
     * @return void
     */
    public function setBundesland($bundesland)
    {
        $this->bundesland = $bundesland;
    }
    
    /**
     * Returns the landkreis
     *
     * @return string $landkreis
     */
    public function getLandkreis()
    {
        return $this->landkreis;
    }
    
    /**
     * Sets the landkreis
     *
     * @param string $landkreis
     * @return void
     */
    public function setLandkreis($landkreis)
    {
        $this->landkreis = $landkreis;
    }
    
}
