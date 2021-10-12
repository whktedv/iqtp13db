<?php
namespace Ud\Iqtp13db\Domain\Model;

/***
 *
 * This file is part of the "IQ Webapp Anerkennungsberatung" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2021 Uli Dohmen <edv@whkt.de>, WHKT
 *
 ***/

/**
 * Beratung
 */
class Beratung extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

  /**
     * datum
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     * @var string
     */
    protected $datum = '';

    /**
     * beratungsart
     * 
     * @var string
     */
    protected $beratungsart = 0;

    /**
     * beratungsartfreitext
     *
     * @var string
     */
    protected $beratungsartfreitext = '';
    
    /**
     * beratungsort
     *
     * @var string
     */
    protected $beratungsort = '';
            
    /**
     * beratungzu
     * 
     * @var string
     */
    protected $beratungzu = '';

    /**
     * referenzberufe
     * 
     * @var string
     */
    protected $referenzberufe = '';

    /**
     * anerkennendestellen
     * 
     * @var string
     */
    protected $anerkennendestellen = '';

    /**
     * anerkennungsberatung
     * 
     * @var string
     */
    protected $anerkennungsberatung = '';
    
    /**
     * anerkennungsberatungfreitext
     *
     * @var string
     */
    protected $anerkennungsberatungfreitext = '';

    /**
     * qualifizierungsberatung
     * 
     * @var string
     */
    protected $qualifizierungsberatung = '';

    /**
     * qualifizierungsberatungfreitext
     *
     * @var string
     */
    protected $qualifizierungsberatungfreitext = '';
        
    /**
     * notizen
     * 
     * @var string
     */
    protected $notizen = '';

    /**
     * erstberatungabgeschlossen
     * 
     * @var string
     */
    protected $erstberatungabgeschlossen = '';

    /**
     * teilnehmer
     * 
     * @var \Ud\Iqtp13db\Domain\Model\Teilnehmer
     */
    protected $teilnehmer = null;

    /**
     * berater
     * 
     * @var \Ud\Iqtp13db\Domain\Model\Berater
     */
    protected $berater = null;

    /**
     * initializes this object
     *
     * @param array $beratungsart
     * @param array $beratungzu
     * @param array $anerkennungsberatung
     * @param array $qualifizierungsberatung
     */
    public function __construct(array $beratungsart = array(), array $beratungzu = array(), array $anerkennungsberatung = array(), array $qualifizierungsberatung = array()) {
        $this->setBeratungsart($beratungsart);
        $this->setBeratungzu($beratungzu);
        $this->setAnerkennungsberatung($anerkennungsberatung);
        $this->setQualifizierungsberatung($qualifizierungsberatung);
    }
    
    /**
     * Returns the datum
     * 
     * @return string $datum
     */
    public function getDatum()
    {
        return $this->datum;
    }

    /**
     * Sets the datum
     * 
     * @param string $datum
     * @return void
     */
    public function setDatum($datum)
    {
        $this->datum = $datum;
    }

    /**
     * Returns the beratungsart
     * 
     * @return array $beratungsart
     */
    public function getBeratungsart()
    {
        return explode(',', $this->beratungsart);
    }

    /**
     * Sets the beratungsart
     * 
     * @param array $beratungsart
     * @return void
     */
    public function setBeratungsart(array $beratungsart)
    {
        $this->beratungsart = implode(',', $beratungsart);
    }

    /**
     * Returns the beratungsartfreitext
     *
     * @return string $beratungsartfreitext
     */
    public function getBeratungsartfreitext()
    {
        return $this->beratungsartfreitext;
    }
    
    /**
     * Sets the beratungsartfreitext
     *
     * @param string $beratungsartfreitext
     * @return void
     */
    public function setBeratungsartfreitext($beratungsartfreitext)
    {
        $this->beratungsartfreitext = $beratungsartfreitext;
    }
    
    /**
     * Returns the beratungsort
     *
     * @return string $beratungsort
     */
    public function getBeratungsort()
    {
        return $this->beratungsort;
    }
    
    /**
     * Sets the beratungsort
     *
     * @param string $beratungsort
     * @return void
     */
    public function setBeratungsort($beratungsort)
    {
        $this->beratungsort = $beratungsort;
    }
    
    /**
     * Returns the beratungzu
     * 
     * @return array $beratungzu
     */
    public function getBeratungzu()
    {
        return explode(',', $this->beratungzu);
    }

    /**
     * Sets the beratungzu
     * 
     * @param array $beratungzu
     * @return void
     */
    public function setBeratungzu(array $beratungzu)
    {
        $this->beratungzu = implode(',', $beratungzu);
    }

    /**
     * Returns the referenzberufe
     * 
     * @return string $referenzberufe
     */
    public function getReferenzberufe()
    {
        return $this->referenzberufe;
    }

    /**
     * Sets the referenzberufe
     * 
     * @param string $referenzberufe
     * @return void
     */
    public function setReferenzberufe($referenzberufe)
    {
        $this->referenzberufe = $referenzberufe;
    }

    /**
     * Returns the anerkennendestellen
     * 
     * @return string $anerkennendestellen
     */
    public function getAnerkennendestellen()
    {
        return $this->anerkennendestellen;
    }

    /**
     * Sets the anerkennendestellen
     * 
     * @param string $anerkennendestellen
     * @return void
     */
    public function setAnerkennendestellen($anerkennendestellen)
    {
        $this->anerkennendestellen = $anerkennendestellen;
    }

    /**
     * Returns the anerkennungsberatung
     * 
     * @return array $anerkennungsberatung
     */
    public function getAnerkennungsberatung()
    {
        return explode(',', $this->anerkennungsberatung);
    }

    /**
     * Sets the anerkennungsberatung
     * 
     * @param array $anerkennungsberatung
     * @return void
     */
    public function setAnerkennungsberatung(array $anerkennungsberatung)
    {
        $this->anerkennungsberatung = implode(',', $anerkennungsberatung);
    }
    
    /**
     * Returns the anerkennungsberatungfreitext
     *
     * @return string $anerkennungsberatungfreitext
     */
    public function getAnerkennungsberatungfreitext()
    {
    	return $this->anerkennungsberatungfreitext;
    }
    
    /**
     * Sets the anerkennungsberatungfreitext
     *
     * @param string $anerkennungsberatungfreitext
     * @return void
     */
    public function setAnerkennungsberatungfreitext($anerkennungsberatungfreitext)
    {
    	$this->anerkennungsberatungfreitext = $anerkennungsberatungfreitext;
    }

    /**
     * Returns the qualifizierungsberatung
     * 
     * @return array $qualifizierungsberatung
     */
    public function getQualifizierungsberatung()
    {
        return explode(',', $this->qualifizierungsberatung);
    }

    /**
     * Sets the qualifizierungsberatung
     * 
     * @param array $qualifizierungsberatung
     * @return void
     */
    public function setQualifizierungsberatung(array $qualifizierungsberatung)
    {
        $this->qualifizierungsberatung = implode(',', $qualifizierungsberatung);
    }
    
    /**
     * Returns the qualifizierungsberatungfreitext
     *
     * @return string $qualifizierungsberatungfreitext
     */
    public function getQualifizierungsberatungfreitext()
    {
    	return $this->qualifizierungsberatungfreitext;
    }
    
    /**
     * Sets the qualifizierungsberatungfreitext
     *
     * @param string $qualifizierungsberatungfreitext
     * @return void
     */
    public function setQualifizierungsberatungfreitext($qualifizierungsberatungfreitext)
    {
    	$this->qualifizierungsberatungfreitext = $qualifizierungsberatungfreitext;
    }

    /**
     * Returns the notizen
     * 
     * @return string $notizen
     */
    public function getNotizen()
    {
        return $this->notizen;
    }

    /**
     * Sets the notizen
     * 
     * @param string $notizen
     * @return void
     */
    public function setNotizen($notizen)
    {
        $this->notizen = $notizen;
    }

    /**
     * Returns the erstberatungabgeschlossen
     * 
     * @return string $erstberatungabgeschlossen
     */
    public function getErstberatungabgeschlossen()
    {
        return $this->erstberatungabgeschlossen;
    }

    /**
     * Sets the erstberatungabgeschlossen
     * 
     * @param int $erstberatungabgeschlossen
     * @return void
     */
    public function setErstberatungabgeschlossen($erstberatungabgeschlossen)
    {
        $this->erstberatungabgeschlossen = $erstberatungabgeschlossen;
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
     * Returns the berater
     * 
     * @return \Ud\Iqtp13db\Domain\Model\Berater $berater
     */
    public function getBerater()
    {
        return $this->berater;
    }

    /**
     * Sets the berater
     * 
     * @param \Ud\Iqtp13db\Domain\Model\Berater $berater
     * @return void
     */
    public function setBerater(\Ud\Iqtp13db\Domain\Model\Berater $berater)
    {
        $this->berater = $berater;
    }
}
