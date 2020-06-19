<?php
namespace Ud\Iqtp13db\Domain\Model;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Uli Dohmen <edv@whkt.de>, WHKT
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/***
 *
 * This file is part of the "IQ TP13 Datenbank Anerkennungserstberatung NRW" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Uli Dohmen <edv@whkt.de>, WHKT
 *
 ***/

/**
 * Schulung
 */
class Schulung extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Datum
     *
     * @var \DateTime
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty"),DateTime
     */
    protected $datum = NULL;

    /**
     * Institution
     *
     * @var string
     */
    protected $institution = '';

    /**
     * Maßnahme Bereich
     *
     * @var int
     */
    protected $massnahmebereich = 0;

    /**
     * Art der Schulung
     *
     * @var int
     */
    protected $art = 0;

    /**
     * Falls andere Maßnahme Art
     *
     * @var string
     */
    protected $andereArt = '';

    /**
     * Falls Schulung mit modularem Aufbau
     *
     * @var int
     */
    protected $modularerAufbau = 0;

    /**
     * Durchführung in Kooperation mit
     *
     * @var int
     */
    protected $kooperation = 0;

    /**
     * Kooperation Sonstige
     *
     * @var string
     */
    protected $kooperationSonstige = '';

    /**
     * Zeitlicher Umfang
     *
     * @var int
     */
    protected $zeitUmfang = 0;

    /**
     * Organisation
     *
     * @var int
     */
    protected $organisation = 0;

    /**
     * Teilnahmeart
     *
     * @var int
     */
    protected $teilnahmeart = 0;

    /**
     * Teilnehmerkreis
     *
     * @var string
     */
    protected $teilnehmerkreis = '';

    /**
     * Themen
     *
     * @var int
     */
    protected $themen = 0;

    /**
     * Anderes Thema
     *
     * @var string
     */
    protected $themenAnderes = '';

    /**
     * Institution
     *
     * @var int
     */
    protected $institutionAuswahl = 0;

    /**
     * Andere Institution
     *
     * @var string
     */
    protected $institutionAndere = '';

    /**
     * Betriebsgröße bei KMU
     *
     * @var int
     */
    protected $betriebsgroesse = 0;

    /**
     * Weitere Planung
     *
     * @var int
     */
    protected $weiterePlanung = 0;

    /**
     * andere Planung
     *
     * @var string
     */
    protected $weiterePlanungAndere = '';

    /**
     * anzDokumente
     *
     * @var int
     */
    protected $anzDokumente = 0;

    /**
     * Berater
     *
     * @var \Ud\Iqtp13db\Domain\Model\Berater
     */
    protected $berater = NULL;

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
     * Returns the institution
     *
     * @return string $institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * Sets the institution
     *
     * @param string $institution
     * @return void
     */
    public function setInstitution($institution)
    {
        $this->institution = $institution;
    }

    /**
     * Returns the massnahmebereich
     *
     * @return int $massnahmebereich
     */
    public function getMassnahmebereich()
    {
        return $this->massnahmebereich;
    }

    /**
     * Sets the massnahmebereich
     *
     * @param int $massnahmebereich
     * @return void
     */
    public function setMassnahmebereich($massnahmebereich)
    {
        $this->massnahmebereich = $massnahmebereich;
    }

    /**
     * Returns the art
     *
     * @return int $art
     */
    public function getArt()
    {
        return $this->art;
    }

    /**
     * Sets the art
     *
     * @param int $art
     * @return void
     */
    public function setArt($art)
    {
        $this->art = $art;
    }

    /**
     * Returns the andereArt
     *
     * @return string $andereArt
     */
    public function getAndereArt()
    {
        return $this->andereArt;
    }

    /**
     * Sets the andereArt
     *
     * @param string $andereArt
     * @return void
     */
    public function setAndereArt($andereArt)
    {
        $this->andereArt = $andereArt;
    }

    /**
     * Returns the modularerAufbau
     *
     * @return int $modularerAufbau
     */
    public function getModularerAufbau()
    {
        return $this->modularerAufbau;
    }

    /**
     * Sets the modularerAufbau
     *
     * @param int $modularerAufbau
     * @return void
     */
    public function setModularerAufbau($modularerAufbau)
    {
        $this->modularerAufbau = $modularerAufbau;
    }

    /**
     * Returns the kooperation
     *
     * @return int $kooperation
     */
    public function getKooperation()
    {
        return $this->kooperation;
    }

    /**
     * Sets the kooperation
     *
     * @param int $kooperation
     * @return void
     */
    public function setKooperation($kooperation)
    {
        $this->kooperation = $kooperation;
    }

    /**
     * Returns the kooperationSonstige
     *
     * @return string $kooperationSonstige
     */
    public function getKooperationSonstige()
    {
        return $this->kooperationSonstige;
    }

    /**
     * Sets the kooperationSonstige
     *
     * @param string $kooperationSonstige
     * @return void
     */
    public function setKooperationSonstige($kooperationSonstige)
    {
        $this->kooperationSonstige = $kooperationSonstige;
    }

    /**
     * Returns the zeitUmfang
     *
     * @return int $zeitUmfang
     */
    public function getZeitUmfang()
    {
        return $this->zeitUmfang;
    }

    /**
     * Sets the zeitUmfang
     *
     * @param int $zeitUmfang
     * @return void
     */
    public function setZeitUmfang($zeitUmfang)
    {
        $this->zeitUmfang = $zeitUmfang;
    }

    /**
     * Returns the organisation
     *
     * @return int $organisation
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * Sets the organisation
     *
     * @param int $organisation
     * @return void
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * Returns the teilnahmeart
     *
     * @return int $teilnahmeart
     */
    public function getTeilnahmeart()
    {
        return $this->teilnahmeart;
    }

    /**
     * Sets the teilnahmeart
     *
     * @param int $teilnahmeart
     * @return void
     */
    public function setTeilnahmeart($teilnahmeart)
    {
        $this->teilnahmeart = $teilnahmeart;
    }

    /**
     * Returns the teilnehmerkreis
     *
     * @return string $teilnehmerkreis
     */
    public function getTeilnehmerkreis()
    {
        return $this->teilnehmerkreis;
    }

    /**
     * Sets the teilnehmerkreis
     *
     * @param string $teilnehmerkreis
     * @return void
     */
    public function setTeilnehmerkreis($teilnehmerkreis)
    {
        $this->teilnehmerkreis = $teilnehmerkreis;
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

    /**
     * Returns the themen
     *
     * @return int $themen
     */
    public function getThemen()
    {
        return $this->themen;
    }

    /**
     * Sets the themen
     *
     * @param int $themen
     * @return void
     */
    public function setThemen($themen)
    {
        $this->themen = $themen;
    }

    /**
     * Returns the themenAnderes
     *
     * @return string $themenAnderes
     */
    public function getThemenAnderes()
    {
        return $this->themenAnderes;
    }

    /**
     * Sets the themenAnderes
     *
     * @param string $themenAnderes
     * @return void
     */
    public function setThemenAnderes($themenAnderes)
    {
        $this->themenAnderes = $themenAnderes;
    }

    /**
     * Returns the institutionAuswahl
     *
     * @return int $institutionAuswahl
     */
    public function getInstitutionAuswahl()
    {
        return $this->institutionAuswahl;
    }

    /**
     * Sets the institutionAuswahl
     *
     * @param int $institutionAuswahl
     * @return void
     */
    public function setInstitutionAuswahl($institutionAuswahl)
    {
        $this->institutionAuswahl = $institutionAuswahl;
    }

    /**
     * Returns the institutionAndere
     *
     * @return string $institutionAndere
     */
    public function getInstitutionAndere()
    {
        return $this->institutionAndere;
    }

    /**
     * Sets the institutionAndere
     *
     * @param string $institutionAndere
     * @return void
     */
    public function setInstitutionAndere($institutionAndere)
    {
        $this->institutionAndere = $institutionAndere;
    }

    /**
     * Returns the betriebsgroesse
     *
     * @return int $betriebsgroesse
     */
    public function getBetriebsgroesse()
    {
        return $this->betriebsgroesse;
    }

    /**
     * Sets the betriebsgroesse
     *
     * @param int $betriebsgroesse
     * @return void
     */
    public function setBetriebsgroesse($betriebsgroesse)
    {
        $this->betriebsgroesse = $betriebsgroesse;
    }

    /**
     * Returns the weiterePlanung
     *
     * @return int $weiterePlanung
     */
    public function getWeiterePlanung()
    {
        return $this->weiterePlanung;
    }

    /**
     * Sets the weiterePlanung
     *
     * @param int $weiterePlanung
     * @return void
     */
    public function setWeiterePlanung($weiterePlanung)
    {
        $this->weiterePlanung = $weiterePlanung;
    }

    /**
     * Returns the weiterePlanungAndere
     *
     * @return string $weiterePlanungAndere
     */
    public function getWeiterePlanungAndere()
    {
        return $this->weiterePlanungAndere;
    }

    /**
     * Sets the weiterePlanungAndere
     *
     * @param string $weiterePlanungAndere
     * @return void
     */
    public function setWeiterePlanungAndere($weiterePlanungAndere)
    {
        $this->weiterePlanungAndere = $weiterePlanungAndere;
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
}
