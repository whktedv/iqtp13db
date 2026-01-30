<?php
namespace Ud\Iqtp13db\Domain\Model;

use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Domain\Model\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

use TYPO3\CMS\Core\Utility\GeneralUtility;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
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
     * datum
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $datum = '';
    
    /**
     * @var string
     */
    protected string $ort = '';
    
    /**
     * @var int
     */
    protected int $maxTeilnehmer = 0;
    
    /**
     * niqbid
     *
     * @var string
     */
    protected $niqbid = '';
    
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Ud\Iqtp13db\Domain\Model\Teilnehmer>
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $teilnehmer;
    
    protected $beratungdatum = '';
    protected int $berater = 0;
    protected $beratungsart = 0;
    protected $beratungsdauer = '';
    protected $beratungzu = '';
    protected $anerkennungsberatung = '';
    protected $anerkennungsberatungfreitext = '';
    protected $qualifizierungsberatung = '';
    protected $qualifizierungsberatungfreitext = '';
    protected $erstberatungabgeschlossen = '';
    
    
    //public function __construct(array $beratungsart = array(), array $anerkennungsberatung = array(), array $qualifizierungsberatung = array())
    public function __construct(array $beratungsart = array())
    {
        
        $this->setBeratungsart($beratungsart);
        /*
        $this->setAnerkennungsberatung($anerkennungsberatung);
        $this->setQualifizierungsberatung($qualifizierungsberatung);
        */
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
    public function setDatum(string $datum)
    {
        $this->datum = $datum;
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
    
    public function getBerater(): int
    {
        return $this->berater;
    }
    
    
    public function setBerater(int $berater): void
    {
        $this->berater = $berater;
    }
    
    
    public function getBeratungsart(): array
    {
        return explode(',', $this->beratungsart);
    }
    public function setBeratungsart(array $beratungsart): void
    {
        $this->beratungsart = implode(',', $beratungsart);
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
    
    
    public function getAnerkennungsberatung(): array
    {
        //return $this->anerkennungsberatung;
        return explode(',', $this->anerkennungsberatung);
    }
    public function setAnerkennungsberatung(array $anerkennungsberatung): void
    {
        $this->anerkennungsberatung = implode(',', $anerkennungsberatung);
    }
    //public function setAnerkennungsberatung(string $anerkennungsberatung): void
    //{
    //    $this->anerkennungsberatung = $anerkennungsberatung;
    //}

    
    public function getAnerkennungsberatungfreitext(): string
    {
        return $this->anerkennungsberatungfreitext;
    }
    public function setAnerkennungsberatungfreitext(string $text): void
    {
        $this->anerkennungsberatungfreitext = $text;
    }
    
    
    public function getQualifizierungsberatung(): array
    {
        //return $this->qualifizierungsberatung;
        return explode(',', $this->qualifizierungsberatung);
    }
    //public function setQualifizierungsberatung(string $qualifizierungsberatung): void
    //{
     //   $this->qualifizierungsberatung = $qualifizierungsberatung;
    //}
    public function setQualifizierungsberatung(array $qualifizierungsberatung): void
    {
        $this->qualifizierungsberatung = implode(',', $qualifizierungsberatung);
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