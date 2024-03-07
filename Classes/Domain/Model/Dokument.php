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
     * Beschreibung
     *
     * @var string
     */
    protected $beschreibung = '';
    
    /**
     * Pfad
     *
     * @var string
     */
    protected $pfad = '';
    
    /**
     * teilnehmer
     *
     * @var \Ud\Iqtp13db\Domain\Model\Teilnehmer
     */
    protected $teilnehmer = NULL;
    
    /**
     * filesize
     * 
     * @var int
     */
    protected $filesize = 0;    
    
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
     * Returns the beschreibung
     *
     * @return string $beschreibung
     */
    public function getBeschreibung()
    {
        return $this->beschreibung;
    }
    
    /**
     * Sets the beschreibung
     *
     * @param string $beschreibung
     * @return void
     */
    public function setBeschreibung($beschreibung)
    {
        $this->beschreibung = $beschreibung;
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
     * Returns the teilnehmer
     *
     * @return \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     */
    public function getTeilnehmer()
    {
        return $this->teilnehmer;
    }
    
    /**
     * Sets the teilnehmer
     *
     * @param \Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer
     * @return void
     */
    public function setTeilnehmer(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer)
    {
        $this->teilnehmer = $teilnehmer;
    }
    
    /**
     * Get filesize for file
     * 
     * @param string $basepath
     * @return int     
     */
    public function getFilesize($basepath) {
        return filesize($basepath.$this->getPfad().$this->getName());
    }
    
}
