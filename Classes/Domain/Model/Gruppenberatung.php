<?php
declare(strict_types = 1);
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
     * @var \DateTime|null
     */
    protected ?\DateTime $datum = null;
    
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
    
    public function getDatum(): ?\DateTime
    {
        return $this->datum;
    }
    
    public function setDatum(?\DateTime $datum): void
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
}