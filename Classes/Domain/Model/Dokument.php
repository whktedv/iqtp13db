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

}
