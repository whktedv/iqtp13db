<?php
namespace Ud\Iqtp13db\Domain\Model;

use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Domain\Model\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Gruppenberatung
 */
class Gruppenberatung extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * @var string
     */
    protected string $titel = '';
    
    /**
     * @var string
     */
    protected string $beschreibung = '';
    
    /**
     * @var string
     */
    protected string $ort = '';
    
    /**
     * @var int
     */
    protected int $maxTeilnehmer = 0;
    
    /**
     * @var string
     */
    protected string $niqbid = '';
    
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Ud\Iqtp13db\Domain\Model\Teilnehmer>
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $teilnehmer;
    
    /*
     * @var string
     */
    protected string $beratungdatum = '';
    
    /**
     * @var string
     */
    protected string $beratungsarten = '';
    
    /*
     * @var int
     */
    protected int $berater = 0;
    
    /*
     * @var string
     */
    protected string $beratungsdauer = '';
    
    /*
     * @var string
     */
    protected string $beratungzu = '';
    
    /**
     * @var string
     */
    protected string $anerkennungsberatung = '';
    
    /**
     * @var string
     */
    protected string $qualifizierungsberatung = '';
    
    /*
     * @var string
     */
    protected string $anerkennungsberatungfreitext = '';
    
    /*
     * @var string
     */
    protected string $qualifizierungsberatungfreitext = '';
    
    /*
     * @var string
     */
    protected string $erstberatungabgeschlossen = '';
    
    
    public function __construct()
    {
        $this->teilnehmer = new ObjectStorage();        
    }
    
    public function getTitel(): string
    {
        return $this->titel;
    }
    
    public function setTitel(string $titel): void
    {
        $this->titel = $titel;
    }
    
    public function getBeschreibung(): string
    {
        return $this->beschreibung;
    }
    
    public function setBeschreibung(string $beschreibung): void
    {
        $this->beschreibung = $beschreibung;
    }
    
    public function getOrt(): string
    {
        return $this->ort;
    }
    
    public function setOrt(string $ort): void
    {
        $this->ort = $ort;
    }
    
    public function getMaxTeilnehmer(): int
    {
        return $this->maxTeilnehmer;
    }
    
    public function setMaxTeilnehmer(int $maxTeilnehmer): void
    {
        $this->maxTeilnehmer = $maxTeilnehmer;
    }
    
    /**
     * Returns the niqbid
     *
     * @return string $niqbid
     */
    public function getNiqbid()
    {
        return $this->niqbid;
    }
    
    /**
     * Sets the niqbid
     *
     * @param string $niqbid
     * @return void
     */
    public function setNiqbid($niqbid)
    {
        $this->niqbid = $niqbid;
    }
    
    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Ud\Iqtp13db\Domain\Model\Teilnehmer>
     */
    public function getTeilnehmer(): ObjectStorage
    {
        return $this->teilnehmer;
    }
    
    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Ud\Iqtp13db\Domain\Model\Teilnehmer> $teilnehmer
     */
    public function setTeilnehmer(ObjectStorage $teilnehmer): void
    {
        $this->teilnehmer = $teilnehmer;
    }
    
    public function addTeilnehmer(Teilnehmer $teilnehmer): void
    {
        $this->teilnehmer->attach($teilnehmer);
    }
    
    public function removeTeilnehmer(Teilnehmer $teilnehmer): void
    {
        $this->teilnehmer->detach($teilnehmer);
    }
    
    public function getAnzahlTeilnehmer(): int
    {
        return $this->teilnehmer->count();
    }
    
    public function istVollBelegt(): bool
    {
        return $this->maxTeilnehmer > 0 && $this->getAnzahlTeilnehmer() >= $this->maxTeilnehmer;
    }
    
    
    public function getBeratungdatum(): string
    {
        return $this->beratungdatum;
    }
    public function setBeratungdatum(string $beratungdatum): void
    {
        $this->beratungdatum = $beratungdatum;
    }
    
    /**
     * Returns the beratungsarten
     *
     * @return string
     */
    public function getBeratungsarten()
    {
        return $this->beratungsarten;
    }
    
    /**
     * Sets the beratungsarten
     *
     * @param string $beratungsarten
     * @return void
     */
    public function setBeratungsarten($beratungsarten)
    {
        $this->beratungsarten = $beratungsarten;
    }
    
    /**
     * Returns beratungsarten as array
     *
     * @return array
     */
    public function getBeratungsartenArray()
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->beratungsarten, true);
    }
    
    public function getBerater(): int
    {
        return $this->berater;
    }
    
    public function setBerater(int $berater): void
    {
        $this->berater = $berater;
    }
    
    public function getBeratungsdauer(): string
    {
        return $this->beratungsdauer;
    }
    public function setBeratungsdauer(string $beratungsdauer): void
    {
        $this->beratungsdauer = $beratungsdauer;
    }
    
    public function getBeratungzu(): string
    {
        return $this->beratungzu;
    }
    public function setBeratungzu(string $beratungzu): void
    {
        $this->beratungzu = $beratungzu;
    }
    
    /**
     * Returns the anerkennungsberatung
     *
     * @return string
     */
    public function getAnerkennungsberatung()
    {
        return $this->anerkennungsberatung;
    }
    
    /**
     * Sets the anerkennungsberatung
     *
     * @param string $anerkennungsberatung
     * @return void
     */
    public function setAnerkennungsberatung($anerkennungsberatung)
    {
        $this->anerkennungsberatung = $anerkennungsberatung;
    }
    
    /**
     * Returns anerkennungsberatung as array
     *
     * @return array
     */
    public function getAnerkennungsberatungArray()
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->anerkennungsberatung, true);
    }
    
    /**
     * Returns the qualifizierungsberatung
     *
     * @return string
     */
    public function getQualifizierungsberatung()
    {
        return $this->qualifizierungsberatung;
    }
    
    /**
     * Sets the qualifizierungsberatung
     *
     * @param string $qualifizierungsberatung
     * @return void
     */
    public function setQualifizierungsberatung($qualifizierungsberatung)
    {
        $this->qualifizierungsberatung = $qualifizierungsberatung;
    }
    
    /**
     * Returns qualifizierungsberatung as array
     *
     * @return array
     */
    public function getQualifizierungsberatungArray()
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->qualifizierungsberatung, true);
    }
    
    public function getAnerkennungsberatungfreitext(): string
    {
        return $this->anerkennungsberatungfreitext;
    }
    public function setAnerkennungsberatungfreitext(string $text): void
    {
        $this->anerkennungsberatungfreitext = $text;
    }
    
    public function getQualifizierungsberatungfreitext(): string
    {
        return $this->qualifizierungsberatungfreitext;
    }
    public function setQualifizierungsberatungfreitext(string $text): void
    {
        $this->qualifizierungsberatungfreitext = $text;
    }
    
    public function getErstberatungabgeschlossen(): string
    {
        return $this->erstberatungabgeschlossen;
    }
    public function setErstberatungabgeschlossen(string $flag): void
    {
        $this->erstberatungabgeschlossen = $flag; // '0' oder '1'
    }
    
}