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
 * Abschluss
 */
class Abschluss extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
       
    /**
     * abschlussart
     *
     * @var string
     */
    protected $abschlussart = '';
    
    /**
     * erwerbsland
     *
     * @var string
     */
    protected $erwerbsland = '';
    
    /**
     * dauerBerufsausbildung
     *
     * @var string
     */
    protected $dauerBerufsausbildung = '';
    
    /**
     * abschlussjahr
     *
     * @var string
     */
    protected $abschlussjahr = '';
    
    /**
     * ausbildungsinstitution
     *
     * @var string
     */
    protected $ausbildungsinstitution = '';
    
    /**
     * ausbildungsort
     *
     * @var string
     */
    protected $ausbildungsort = '';
    
    /**
     * abschluss
     *
     * @var string
     */
    protected $abschluss = '';
    
    /**
     * berufserfahrung
     *
     * @var int
     */
    protected $berufserfahrung = 0;
           
    /**
     * deutscherReferenzberuf
     *
     * @var string
     */
    protected $deutscherReferenzberuf = '';
    
    /**
     * referenzberufzugewiesen
     *
     * @var string
     */
    protected $referenzberufzugewiesen = '';
    
    /**
     * wunschberuf
     *
     * @var string
     */
    protected $wunschberuf = '';
    
    /**
     * sonstigerberuf
     *
     * @var string
     */
    protected $sonstigerberuf = '';
    
    /**
     * nregberuf
     *
     * @var string
     */
    protected $nregberuf = '';
    
    /**
     * antragstellungvorher
     *
     * @var int
     */
    protected $antragstellungvorher = 0;
    
    /**
     * antragstellunggwpvorher
     *
     * @var int
     */
    protected $antragstellunggwpvorher = 0;
    
    /**
     * antragstellungzabvorher
     *
     * @var int
     */
    protected $antragstellungzabvorher = 0;
    
    /**
     * antragstellungerfolgt
     *
     * @var int
     */
    protected $antragstellungerfolgt = 0;
    
    /**
     * antragstellunggwpdatum
     *
     * @var string
     */
    protected $antragstellunggwpdatum = '';
    
    /**
     * antragstellunggwpergebnis
     *
     * @var int
     */
    protected $antragstellunggwpergebnis = 0;
    
    /**
     * antragstellungzabdatum
     *
     * @var string
     */
    protected $antragstellungzabdatum = '';
    
    /**
     * antragstellungzabergebnis
     *
     * @var int
     */
    protected $antragstellungzabergebnis = 0;
    
    /**
     * niquebertragung
     *
     * @var string
     */
    protected $niquebertragung = '';
    
    /**
     * teilnehmer
     *
     * @var \Ud\Iqtp13db\Domain\Model\Teilnehmer
     */
    protected $teilnehmer = null;
    
    /**
     * initializes this object
     *
     * @param array $abschlussart
     */
    public function __construct(array $abschlussart = array()) {
        $this->setAbschlussart($abschlussart);
    }
    
    
    /**
     * Returns the abschlussart
     *
     * @return array $abschlussart
     */
    public function getAbschlussart()
    {
        return explode(',', $this->abschlussart);
    }
    
    /**
     * Sets the abschlussart
     *
     * @param array $abschlussart
     * @return void
     */
    public function setAbschlussart(array $abschlussart)
    {
        $this->abschlussart =  implode(',', $abschlussart);
    }
    
    /**
     * Returns the erwerbsland
     *
     * @return string $erwerbsland
     */
    public function getErwerbsland()
    {
        return $this->erwerbsland;
    }
    
    /**
     * Sets the erwerbsland
     *
     * @param string $erwerbsland
     * @return void
     */
    public function setErwerbsland(string $erwerbsland)
    {
        $this->erwerbsland = $erwerbsland;
    }
    
    /**
     * Returns the dauerBerufsausbildung
     *
     * @return string $dauerBerufsausbildung
     */
    public function getDauerBerufsausbildung()
    {
        return $this->dauerBerufsausbildung;
    }
    
    /**
     * Sets the dauerBerufsausbildung
     *
     * @param string $dauerBerufsausbildung
     * @return void
     */
    public function setDauerBerufsausbildung(string $dauerBerufsausbildung)
    {
        $this->dauerBerufsausbildung = $dauerBerufsausbildung;
    }
    
    /**
     * Returns the abschlussjahr
     *
     * @return string $abschlussjahr
     */
    public function getAbschlussjahr()
    {
        return $this->abschlussjahr;
    }
    
    /**
     * Sets the abschlussjahr
     *
     * @param string $abschlussjahr
     * @return void
     */
    public function setAbschlussjahr(string $abschlussjahr)
    {
        $this->abschlussjahr = $abschlussjahr;
    }
    
    /**
     * Returns the ausbildungsinstitution
     *
     * @return string $ausbildungsinstitution
     */
    public function getAusbildungsinstitution()
    {
        return $this->ausbildungsinstitution;
    }
    
    /**
     * Sets the ausbildungsinstitution
     *
     * @param string $ausbildungsinstitution
     * @return void
     */
    public function setAusbildungsinstitution(string $ausbildungsinstitution)
    {
        $this->ausbildungsinstitution = $ausbildungsinstitution;
    }
    
    /**
     * Returns the ausbildungsort
     *
     * @return string $ausbildungsort
     */
    public function getAusbildungsort()
    {
        return $this->ausbildungsort;
    }
    
    /**
     * Sets the ausbildungsort
     *
     * @param string $ausbildungsort
     * @return void
     */
    public function setAusbildungsort(string $ausbildungsort)
    {
        $this->ausbildungsort = $ausbildungsort;
    }
    
    /**
     * Returns the abschluss
     *
     * @return string $abschluss
     */
    public function getAbschluss()
    {
        return $this->abschluss;
    }
    
    /**
     * Sets the abschluss
     *
     * @param string $abschluss
     * @return void
     */
    public function setAbschluss(string $abschluss)
    {
        $this->abschluss = $abschluss;
    }
    
    /**
     * Returns the berufserfahrung
     *
     * @return int $berufserfahrung
     */
    public function getBerufserfahrung()
    {
        return $this->berufserfahrung;
    }
    
    /**
     * Sets the berufserfahrung
     *
     * @param int $berufserfahrung
     * @return void
     */
    public function setBerufserfahrung(string $berufserfahrung)
    {
        $this->berufserfahrung = $berufserfahrung;
    }
        
    /**
     * Returns the deutscherReferenzberuf
     *
     * @return string $deutscherReferenzberuf
     */
    public function getDeutscherReferenzberuf()
    {
        return $this->deutscherReferenzberuf;
    }
    
    /**
     * Sets the deutscherReferenzberuf
     *
     * @param string $deutscherReferenzberuf
     * @return void
     */
    public function setDeutscherReferenzberuf(string $deutscherReferenzberuf)
    {
        $this->deutscherReferenzberuf = $deutscherReferenzberuf;
    }
    
    /**
     * Returns the referenzberufzugewiesen
     *
     * @return string $referenzberufzugewiesen
     */
    public function getReferenzberufzugewiesen()
    {
        return $this->referenzberufzugewiesen;
    }
    
    /**
     * Sets the referenzberufzugewiesen
     *
     * @param string $referenzberufzugewiesen
     * @return void
     */
    public function setReferenzberufzugewiesen(string $referenzberufzugewiesen)
    {
        $this->referenzberufzugewiesen = $referenzberufzugewiesen;
    }
      
    
    /**
     * Returns the wunschberuf
     *
     * @return string $wunschberuf
     */
    public function getWunschberuf()
    {
        return $this->wunschberuf;
    }
    
    /**
     * Sets the wunschberuf
     *
     * @param string $wunschberuf
     * @return void
     */
    public function setWunschberuf(string $wunschberuf)
    {
        $this->wunschberuf = $wunschberuf;
    }
    
    /**
     * Returns the sonstigerberuf
     *
     * @return string $sonstigerberuf
     */
    public function getSonstigerberuf()
    {
        return $this->sonstigerberuf;
    }
    
    /**
     * Sets the sonstigerberuf
     *
     * @param string $sonstigerberuf
     * @return void
     */
    public function setSonstigerberuf(string $sonstigerberuf)
    {
        $this->sonstigerberuf = $sonstigerberuf;
    }
    
    /**
     * Returns the nregberuf
     *
     * @return string $nregberuf
     */
    public function getNregberuf()
    {
        return $this->nregberuf;
    }
    
    /**
     * Sets the nregberuf
     *
     * @param string $nregberuf
     * @return void
     */
    public function setNregberuf(string $nregberuf)
    {
        $this->nregberuf = $nregberuf;
    }
    
    /**
     * Returns the antragstellungvorher
     *
     * @return int $antragstellungvorher
     */
    public function getAntragstellungvorher()
    {
        return $this->antragstellungvorher;
    }
    
    /**
     * Sets the antragstellungvorher
     *
     * @param int $antragstellungvorher
     * @return void
     */
    public function setAntragstellungvorher(int $antragstellungvorher)
    {
        $this->antragstellungvorher = $antragstellungvorher;
    }
    
    /**
     * Returns the antragstellunggwpvorher
     *
     * @return int $antragstellunggwpvorher
     */
    public function getAntragstellunggwpvorher()
    {
        return $this->antragstellunggwpvorher;
    }
    
    /**
     * Sets the antragstellunggwpvorher
     *
     * @param int $antragstellunggwpvorher
     * @return void
     */
    public function setAntragstellunggwpvorher(int $antragstellunggwpvorher)
    {
        $this->antragstellunggwpvorher = $antragstellunggwpvorher;
    }
    
    /**
     * Returns the antragstellungzabvorher
     *
     * @return int $antragstellungzabvorher
     */
    public function getAntragstellungzabvorher()
    {
        return $this->antragstellungzabvorher;
    }
    
    /**
     * Sets the antragstellungzabvorher
     *
     * @param int $antragstellungzabvorher
     * @return void
     */
    public function setAntragstellungzabvorher(int $antragstellungzabvorher)
    {
        $this->antragstellungzabvorher = $antragstellungzabvorher;
    }
    
    /**
     * Returns the antragstellungerfolgt
     *
     * @return int $antragstellungerfolgt
     */
    public function getAntragstellungerfolgt()
    {
        return $this->antragstellungerfolgt;
    }
    
    /**
     * Sets the antragstellungerfolgt
     *
     * @param int $antragstellungerfolgt
     * @return void
     */
    public function setAntragstellungerfolgt(int $antragstellungerfolgt)
    {
        $this->antragstellungerfolgt = $antragstellungerfolgt;
    }
    
    /**
     * Returns the antragstellunggwpdatum
     *
     * @return string $antragstellunggwpdatum
     */
    public function getAntragstellunggwpdatum()
    {
        return $this->antragstellunggwpdatum;
    }
    
    /**
     * Sets the antragstellunggwpdatum
     *
     * @param string $antragstellunggwpdatum
     * @return void
     */
    public function setAntragstellunggwpdatum(string $antragstellunggwpdatum)
    {
        $this->antragstellunggwpdatum = $antragstellunggwpdatum;
    }
    
    /**
     * Returns the antragstellunggwpergebnis
     *
     * @return int $antragstellunggwpergebnis
     */
    public function getAntragstellunggwpergebnis()
    {
        return $this->antragstellunggwpergebnis;
    }
    
    /**
     * Sets the antragstellunggwpergebnis
     *
     * @param int $antragstellunggwpergebnis
     * @return void
     */
    public function setAntragstellunggwpergebnis(int $antragstellunggwpergebnis)
    {
        $this->antragstellunggwpergebnis = $antragstellunggwpergebnis;
    }
    
    /**
     * Returns the antragstellungzabdatum
     *
     * @return string $antragstellungzabdatum
     */
    public function getAntragstellungzabdatum()
    {
        return $this->antragstellungzabdatum;
    }
    
    /**
     * Sets the antragstellungzabdatum
     *
     * @param string $antragstellungzabdatum
     * @return void
     */
    public function setAntragstellungzabdatum(string $antragstellungzabdatum)
    {
        $this->antragstellungzabdatum = $antragstellungzabdatum;
    }
    
    /**
     * Returns the antragstellungzabergebnis
     *
     * @return int $antragstellungzabergebnis
     */
    public function getAntragstellungzabergebnis()
    {
        return $this->antragstellungzabergebnis;
    }
    
    /**
     * Sets the antragstellungzabergebnis
     *
     * @param int $antragstellungzabergebnis
     * @return void
     */
    public function setAntragstellungzabergebnis(int $antragstellungzabergebnis)
    {
        $this->antragstellungzabergebnis = $antragstellungzabergebnis;
    }
    
    /**
     * Returns the niquebertragung
     *
     * @return string $niquebertragung
     */
    public function getNiquebertragung()
    {
        return $this->niquebertragung;
    }
    
    /**
     * Sets the niquebertragung
     *
     * @param string $niquebertragung
     * @return void
     */
    public function setNiquebertragung(string $niquebertragung)
    {
        $this->niquebertragung = $niquebertragung;
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
}

