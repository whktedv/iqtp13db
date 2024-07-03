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
 * Branche
 */
class Branche extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Brancheid
     *
     * @var int
     */
    protected $brancheid = 0;
    
    /**
     * Brancheok
     *
     * @var int
     */
    protected $brancheok = 0;
    
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
     * Returns the brancheid
     *
     * @return int $brancheid
     */
    public function getBrancheid()
    {
        return $this->brancheid;
    }
    
    /**
     * Sets the brancheid
     *
     * @param int $brancheid
     * @return void
     */
    public function setBrancheid($brancheid)
    {
        $this->brancheid = $brancheid;
    }
    
    /**
     * Returns the brancheok
     *
     * @return int $brancheok
     */
    public function getBrancheok()
    {
        return $this->brancheok;
    }
    
    /**
     * Sets the brancheok
     *
     * @param int $brancheok
     * @return void
     */
    public function setBrancheok($brancheok)
    {
        $this->brancheok = $brancheok;
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
