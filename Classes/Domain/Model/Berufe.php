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
 * Berufe
 */
class Berufe extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Berufid
     *
     * @var string
     */
    protected $berufid = '';
    
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
     * Returns the berufid
     *
     * @return string $berufid
     */
    public function getBerufid()
    {
        return $this->berufid;
    }
    
    /**
     * Sets the berufid
     *
     * @param string $berufid
     * @return void
     */
    public function setBerufid($berufid)
    {
        $this->berufid = $berufid;
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
