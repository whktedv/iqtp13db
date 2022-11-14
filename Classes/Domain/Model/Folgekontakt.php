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
 * Folgekontakt
 */
class Folgekontakt extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

	/**
	 * datum
	 *
	 * @var string
	 */
	protected $datum = '';

	/**
	 * notizen
	 *
	 * @var string
	 */
	protected $notizen = '';

	/**
	 * beratungsform
	 *
	 * @var int
	 */
	protected $beratungsform = 0;
	
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
	 * Returns the beratungsform
	 *
	 * @return int $beratungsform
	 */
	public function getBeratungsform()
	{
	    return $this->beratungsform;
	}
	
	/**
	 * Sets the beratungsform
	 *
	 * @param int $beratungsform
	 * @return void
	 */
	public function setBeratungsform($beratungsform)
	{
	    $this->beratungsform = $beratungsform;
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
