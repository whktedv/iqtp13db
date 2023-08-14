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
 * Staaten
 */
class Staaten extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Staatid
     *
     * @var string
     */
    protected $staatid = '';
    
    /**
     * Titel
     *
     * @var string
     */
    protected $titel = '';
    
    /**
     * Langisocode
     *
     * @var string
     */
    protected $langisocode = '';
    
    
    /**
     * Returns the staatid
     *
     * @return string $staatid
     */
    public function getStaatid()
    {
        return $this->staatid;
    }
    
    /**
     * Sets the staatid
     *
     * @param string $staatid
     * @return void
     */
    public function setStaatid($staatid)
    {
        $this->staatid = $staatid;
    }
    
    /**
     * Returns the titel
     *
     * @return string $titel
     */
    public function getTitel()
    {
        return $this->titel;
    }
    
    /**
     * Sets the titel
     *
     * @param string $titel
     * @return void
     */
    public function setTitel($titel)
    {
        $this->titel = $titel;
    }
    
    /**
     * Returns the langisocode
     *
     * @return string $langisocode
     */
    public function getLangisocode()
    {
        return $this->langisocode;
    }
    
    /**
     * Sets the langisocode
     *
     * @param string $langisocode
     * @return void
     */
    public function setLangisocode($langisocode)
    {
        $this->langisocode = $langisocode;
    }
    
}
