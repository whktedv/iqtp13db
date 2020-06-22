<?php
namespace Ud\Iqtp13db\Domain\Model;

/***
 *
 * This file is part of the "IQ TP13 Datenbank Anerkennungserstberatung NRW" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Uli Dohmen <edv@whkt.de>, WHKT
 *
 ***/

/**
 * Beratung
 */
class Beratung extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * NIQ Chiffre
     *
     * @var string
     */
    protected $chiffre = '';

    /**
     * Beratungsprozess
     *
     * @var int
     */
    protected $prozess = 0;

    /**
     * Datum Erstkontakt
     *
     * @var \DateTime
     * @TYPO3\CMS\Extbase\Annotation\Validate("DateTime")
     */
    protected $datum = '';

    /**
     * Folgekontakt
     *
     * @var string
     */
    protected $folgekontakt = '';

    /**
     * Durchführungsort
     *
     * @var string
     */
    protected $ort = '';

    /**
     * Beratungsart
     *
     * @var int
     */
    protected $beratungsart = '';

    /**
     * Anfrage durch
     *
     * @var int
     */
    protected $anfrageDurch = 0;

    /**
     * Anmerkung
     *
     * @var string
     */
    protected $anmerkung = '';

    /**
     * Weiterleitung an "prioritär" anerkennende Stellen
     *
     * @var string
     */
    protected $ergebnisWeiterleitung = '';

    /**
     * Anmerkungen zum aktuellen Verfahren
     *
     * @var string
     */
    protected $anmerkungVerfahren = '';

    /**
     * Angaben zu Vereinbarungen, Empfehlungen, Kontakten
     *
     * @var string
     */
    protected $angabenVereinbarungen = '';

    /**
     * Umfang der Beratung
     *
     * @var string
     */
    protected $umfang = '';

    /**
     * Beratung abgeschlossen
     *
     * @var string
     */
    protected $beratungAbgeschlossen = '';

    /**
     * Übertragung in NIQ-Datenbank erfolgt
     *
     * @var string
     */
    protected $uebertragNIQ = '';

    /**
     * Dokumente Ratsuchender
     *
     * @var string
     */
    protected $dokumenteRatsuchender = '';

    /**
     * Dokumente anhängen
     *
     * @var string
     */
    protected $dokumenteAnhaengen = '';

    /**
     * Folgekontakte
     *
     * @var int
     */
    protected $folgekontakte = 0;

    /**
     * Liegt ein Bescheid über eine Gleichwertigkeitsprüfung vor?
     *
     * @var int
     */
    protected $bescheidGleichwertigkeitspruefung = 0;

    /**
     * Falls ja, welches Ergebnis hatte das Verfahren der Gleichwertigkeitsfeststellung
     *
     * @var int
     */
    protected $ergebnisGleichwertigkeitsfeststellung = 0;

    /**
     * Falls nicht-reglementierter akademischer Beruf: Liegt eine ZAB-Bewertung vor?
     *
     * @var int
     */
    protected $zabBewertung = 0;

    /**
     * Wurde an einen IQ-internen Bildungsdienstleister weiterverwiesen?
     *
     * @var int
     */
    protected $verweisAnBildungsdienstleister = 0;

    /**
     * Falls ja, welche Qualifizierungsmaßnahme haben Sie empfohlen?
     *
     * @var string
     */
    protected $empfohleneQualimassnahme = '';

    /**
     * An welchen Bildungsdienstleister haben Sie verwiesen?
     *
     * @var int
     */
    protected $welcherBildungsdienstleister = 0;

    /**
     * Zu welchem Modul wird die Qualifizierungsmaßnahme zugeordnet?
     *
     * @var int
     */
    protected $modulZuordnungQualimassnahme = 0;

    /**
     * In welchem Bundesland findet die Qualifizierungsmaßnahme statt?
     *
     * @var string
     */
    protected $bundeslandQualimassnahme = '';

    /**
     * Anzahl Dokumente
     *
     * @var int
     */
    protected $anzDokumente = 0;

    /**
     * Weg zur Beratungsstelle über
     *
     * @var int
     */
    protected $wegBeratungsstelle = 0;

    /**
     * Name Beratungsstelle
     *
     * @var string
     */
    protected $nameBeratungsstelle = '';

    /**
     * Teilnehmer
     *
     * @var \Ud\Iqtp13db\Domain\Model\Teilnehmer
     */
    protected $teilnehmer = NULL;

    /**
     * Berater
     *
     * @var \Ud\Iqtp13db\Domain\Model\Berater
     */
    protected $berater = NULL;

    /**
     * Returns the chiffre
     *
     * @return string $chiffre
     */
    public function getChiffre()
    {
        return $this->chiffre;
    }

    /**
     * Sets the chiffre
     *
     * @param string $chiffre
     * @return void
     */
    public function setChiffre($chiffre)
    {
        $this->chiffre = $chiffre;
    }

    /**
     * Returns the datum
     *
     * @return \DateTime $datum
     */
    public function getDatum()
    {
        return $this->datum;
    }

    /**
     * Sets the datum
     *
     * @param \DateTime $datum
     * @return void
     */
    public function setDatum(\DateTime $datum)
    {
        $this->datum = $datum;
    }

    /**
     * Returns the ort
     *
     * @return string $ort
     */
    public function getOrt()
    {
        return $this->ort;
    }

    /**
     * Sets the ort
     *
     * @param string $ort
     * @return void
     */
    public function setOrt($ort)
    {
        $this->ort = $ort;
    }

    /**
     * Returns the beratungsart
     *
     * @return int beratungsart
     */
    public function getBeratungsart()
    {
        return $this->beratungsart;
    }

    /**
     * Sets the beratungsart
     *
     * @param string $beratungsart
     * @return void
     */
    public function setBeratungsart($beratungsart)
    {
        $this->beratungsart = $beratungsart;
    }

    /**
     * Returns the prozess
     *
     * @return int $prozess
     */
    public function getProzess()
    {
        return $this->prozess;
    }

    /**
     * Sets the prozess
     *
     * @param int $prozess
     * @return void
     */
    public function setProzess($prozess)
    {
        $this->prozess = $prozess;
    }

    /**
     * Returns the folgekontakt
     *
     * @return string $folgekontakt
     */
    public function getFolgekontakt()
    {
        return $this->folgekontakt;
    }

    /**
     * Sets the folgekontakt
     *
     * @param string $folgekontakt
     * @return void
     */
    public function setFolgekontakt($folgekontakt)
    {
        $this->folgekontakt = $folgekontakt;
    }

    /**
     * Returns the anfrageDurch
     *
     * @return int $anfrageDurch
     */
    public function getAnfrageDurch()
    {
        return $this->anfrageDurch;
    }

    /**
     * Sets the anfrageDurch
     *
     * @param int $anfrageDurch
     * @return void
     */
    public function setAnfrageDurch($anfrageDurch)
    {
        $this->anfrageDurch = $anfrageDurch;
    }

    /**
     * Returns the anmerkung
     *
     * @return string $anmerkung
     */
    public function getAnmerkung()
    {
        return $this->anmerkung;
    }

    /**
     * Sets the anmerkung
     *
     * @param string $anmerkung
     * @return void
     */
    public function setAnmerkung($anmerkung)
    {
        $this->anmerkung = $anmerkung;
    }

    /**
     * Returns the ergebnisWeiterleitung
     *
     * @return string $ergebnisWeiterleitung
     */
    public function getErgebnisWeiterleitung()
    {
        return $this->ergebnisWeiterleitung;
    }

    /**
     * Sets the ergebnisWeiterleitung
     *
     * @param string $ergebnisWeiterleitung
     * @return void
     */
    public function setErgebnisWeiterleitung($ergebnisWeiterleitung)
    {
        $this->ergebnisWeiterleitung = $ergebnisWeiterleitung;
    }

    /**
     * Returns the anmerkungVerfahren
     *
     * @return string $anmerkungVerfahren
     */
    public function getAnmerkungVerfahren()
    {
        return $this->anmerkungVerfahren;
    }

    /**
     * Sets the anmerkungVerfahren
     *
     * @param string $anmerkungVerfahren
     * @return void
     */
    public function setAnmerkungVerfahren($anmerkungVerfahren)
    {
        $this->anmerkungVerfahren = $anmerkungVerfahren;
    }

    /**
     * Returns the angabenVereinbarungen
     *
     * @return string $angabenVereinbarungen
     */
    public function getAngabenVereinbarungen()
    {
        return $this->angabenVereinbarungen;
    }

    /**
     * Sets the angabenVereinbarungen
     *
     * @param string $angabenVereinbarungen
     * @return void
     */
    public function setAngabenVereinbarungen($angabenVereinbarungen)
    {
        $this->angabenVereinbarungen = $angabenVereinbarungen;
    }

    /**
     * Returns the umfang
     *
     * @return string $umfang
     */
    public function getUmfang()
    {
        return $this->umfang;
    }

    /**
     * Sets the umfang
     *
     * @param string $umfang
     * @return void
     */
    public function setUmfang($umfang)
    {
        $this->umfang = $umfang;
    }

    /**
     * Returns the beratungAbgeschlossen
     *
     * @return string $beratungAbgeschlossen
     */
    public function getBeratungAbgeschlossen()
    {
        return $this->beratungAbgeschlossen;
    }

    /**
     * Sets the beratungAbgeschlossen
     *
     * @param string $beratungAbgeschlossen
     * @return void
     */
    public function setBeratungAbgeschlossen($beratungAbgeschlossen)
    {
        $this->beratungAbgeschlossen = $beratungAbgeschlossen;
    }

    /**
     * Returns the uebertragNIQ
     *
     * @return string $uebertragNIQ
     */
    public function getUebertragNIQ()
    {
        return $this->uebertragNIQ;
    }

    /**
     * Sets the uebertragNIQ
     *
     * @param string $uebertragNIQ
     * @return void
     */
    public function setUebertragNIQ($uebertragNIQ)
    {
        $this->uebertragNIQ = $uebertragNIQ;
    }

    /**
     * Returns the dokumenteRatsuchender
     *
     * @return string $dokumenteRatsuchender
     */
    public function getDokumenteRatsuchender()
    {
        return $this->dokumenteRatsuchender;
    }

    /**
     * Sets the dokumenteRatsuchender
     *
     * @param string $dokumenteRatsuchender
     * @return void
     */
    public function setDokumenteRatsuchender($dokumenteRatsuchender)
    {
        $this->dokumenteRatsuchender = $dokumenteRatsuchender;
    }

    /**
     * Returns the dokumenteAnhaengen
     *
     * @return string $dokumenteAnhaengen
     */
    public function getDokumenteAnhaengen()
    {
        return $this->dokumenteAnhaengen;
    }

    /**
     * Sets the dokumenteAnhaengen
     *
     * @param string $dokumenteAnhaengen
     * @return void
     */
    public function setDokumenteAnhaengen($dokumenteAnhaengen)
    {
        $this->dokumenteAnhaengen = $dokumenteAnhaengen;
    }

    /**
     * Returns the folgekontakte
     *
     * @return int $folgekontakte
     */
    public function getFolgekontakte()
    {
        return $this->folgekontakte;
    }

    /**
     * Sets the folgekontakte
     *
     * @param int $folgekontakte
     * @return void
     */
    public function setFolgekontakte($folgekontakte)
    {
        $this->folgekontakte = $folgekontakte;
    }

    /**
     * Returns the bescheidGleichwertigkeitspruefung
     *
     * @return int $bescheidGleichwertigkeitspruefung
     */
    public function getBescheidGleichwertigkeitspruefung()
    {
        return $this->bescheidGleichwertigkeitspruefung;
    }

    /**
     * Sets the bescheidGleichwertigkeitspruefung
     *
     * @param int $bescheidGleichwertigkeitspruefung
     * @return void
     */
    public function setBescheidGleichwertigkeitspruefung($bescheidGleichwertigkeitspruefung)
    {
        $this->bescheidGleichwertigkeitspruefung = $bescheidGleichwertigkeitspruefung;
    }

    /**
     * Returns the ergebnisGleichwertigkeitsfeststellung
     *
     * @return int $ergebnisGleichwertigkeitsfeststellung
     */
    public function getErgebnisGleichwertigkeitsfeststellung()
    {
        return $this->ergebnisGleichwertigkeitsfeststellung;
    }

    /**
     * Sets the ergebnisGleichwertigkeitsfeststellung
     *
     * @param int $ergebnisGleichwertigkeitsfeststellung
     * @return void
     */
    public function setErgebnisGleichwertigkeitsfeststellung($ergebnisGleichwertigkeitsfeststellung)
    {
        $this->ergebnisGleichwertigkeitsfeststellung = $ergebnisGleichwertigkeitsfeststellung;
    }

    /**
     * Returns the zabBewertung
     *
     * @return int $zabBewertung
     */
    public function getZabBewertung()
    {
        return $this->zabBewertung;
    }

    /**
     * Sets the zabBewertung
     *
     * @param int $zabBewertung
     * @return void
     */
    public function setZabBewertung($zabBewertung)
    {
        $this->zabBewertung = $zabBewertung;
    }

    /**
     * Returns the verweisAnBildungsdienstleister
     *
     * @return int $verweisAnBildungsdienstleister
     */
    public function getVerweisAnBildungsdienstleister()
    {
        return $this->verweisAnBildungsdienstleister;
    }

    /**
     * Sets the verweisAnBildungsdienstleister
     *
     * @param int $verweisAnBildungsdienstleister
     * @return void
     */
    public function setVerweisAnBildungsdienstleister($verweisAnBildungsdienstleister)
    {
        $this->verweisAnBildungsdienstleister = $verweisAnBildungsdienstleister;
    }

    /**
     * Returns the empfohleneQualimassnahme
     *
     * @return string $empfohleneQualimassnahme
     */
    public function getEmpfohleneQualimassnahme()
    {
        return $this->empfohleneQualimassnahme;
    }

    /**
     * Sets the empfohleneQualimassnahme
     *
     * @param string $empfohleneQualimassnahme
     * @return void
     */
    public function setEmpfohleneQualimassnahme($empfohleneQualimassnahme)
    {
        $this->empfohleneQualimassnahme = $empfohleneQualimassnahme;
    }

    /**
     * Returns the welcherBildungsdienstleister
     *
     * @return int $welcherBildungsdienstleister
     */
    public function getWelcherBildungsdienstleister()
    {
        return $this->welcherBildungsdienstleister;
    }

    /**
     * Sets the welcherBildungsdienstleister
     *
     * @param int $welcherBildungsdienstleister
     * @return void
     */
    public function setWelcherBildungsdienstleister($welcherBildungsdienstleister)
    {
        $this->welcherBildungsdienstleister = $welcherBildungsdienstleister;
    }

    /**
     * Returns the modulZuordnungQualimassnahme
     *
     * @return int $modulZuordnungQualimassnahme
     */
    public function getModulZuordnungQualimassnahme()
    {
        return $this->modulZuordnungQualimassnahme;
    }

    /**
     * Sets the modulZuordnungQualimassnahme
     *
     * @param int $modulZuordnungQualimassnahme
     * @return void
     */
    public function setModulZuordnungQualimassnahme($modulZuordnungQualimassnahme)
    {
        $this->modulZuordnungQualimassnahme = $modulZuordnungQualimassnahme;
    }

    /**
     * Returns the bundeslandQualimassnahme
     *
     * @return string $bundeslandQualimassnahme
     */
    public function getBundeslandQualimassnahme()
    {
        return $this->bundeslandQualimassnahme;
    }

    /**
     * Sets the bundeslandQualimassnahme
     *
     * @param string $bundeslandQualimassnahme
     * @return void
     */
    public function setBundeslandQualimassnahme($bundeslandQualimassnahme)
    {
        $this->bundeslandQualimassnahme = $bundeslandQualimassnahme;
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
     * Returns the anzDokumente
     *
     * @return int $anzDokumente
     */
    public function getAnzDokumente()
    {
        return $this->anzDokumente;
    }

    /**
     * Sets the anzDokumente
     *
     * @param int $anzDokumente
     * @return void
     */
    public function setAnzDokumente($anzDokumente)
    {
        $this->anzDokumente = $anzDokumente;
    }

    /**
     * Returns the wegBeratungsstelle
     *
     * @return int $wegBeratungsstelle
     */
    public function getWegBeratungsstelle()
    {
        return $this->wegBeratungsstelle;
    }

    /**
     * Sets the wegBeratungsstelle
     *
     * @param int $wegBeratungsstelle
     * @return void
     */
    public function setWegBeratungsstelle($wegBeratungsstelle)
    {
        $this->wegBeratungsstelle = $wegBeratungsstelle;
    }

    /**
     * Returns the nameBeratungsstelle
     *
     * @return string $nameBeratungsstelle
     */
    public function getNameBeratungsstelle()
    {
        return $this->nameBeratungsstelle;
    }

    /**
     * Sets the nameBeratungsstelle
     *
     * @param string $nameBeratungsstelle
     * @return void
     */
    public function setNameBeratungsstelle($nameBeratungsstelle)
    {
        $this->nameBeratungsstelle = $nameBeratungsstelle;
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
