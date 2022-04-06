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
 * Historie
 */
class Historie extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

	/**
	 * teilnehmer
	 *
	 * @var \Ud\Iqtp13db\Domain\Model\Teilnehmer
	 */
	protected $teilnehmer = null;
	
	/**
	 * property
	 *
	 * @var string
	 */
	protected $property = '';

	/**
	 * oldvalue
	 *
	 * @var string
	 */
	protected $oldvalue = '';

	/**
	 * newvalue
	 *
	 * @var string
	 */
	protected $newvalue = '';

	/**
	 * berater
	 *
	 * @var \Ud\Iqtp13db\Domain\Model\Berater
	 */
	protected $berater = null;

	/**
	 * tstamp
	 *
	 * @var int
	 */
	protected $tstamp = 0;
	
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
	 * Returns the property
	 *
	 * @return string $property
	 */
	public function getProperty()
	{
		return $this->property;
	}

	/**
	 * Sets the property
	 *
	 * @param string $property
	 * @return void
	 */
	public function setProperty($property)
	{
		$this->property = $property;
	}

	/**
	 * Returns the oldvalue
	 *
	 * @return string $oldvalue
	 */
	public function getOldvalue()
	{
		return $this->oldvalue;
	}

	/**
	 * Sets the oldvalue
	 *
	 * @param string $oldvalue
	 * @return void
	 */
	public function setOldvalue($oldvalue)
	{
		$this->oldvalue = $oldvalue;
	}

	/**
	 * Returns the newvalue
	 *
	 * @return string $newvalue
	 */
	public function getNewvalue()
	{
		return $this->newvalue;
	}

	/**
	 * Sets the newvalue
	 *
	 * @param string $newvalue
	 * @return void
	 */
	public function setNewvalue($newvalue)
	{
		$this->newvalue = $newvalue;
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
	 * Returns the tstamp
	 *
	 * @return int $tstamp
	 */
	public function getTstamp()
	{
		return $this->tstamp;
	}
	
	/**
	 * Sets the tstamp
	 *
	 * @param int $tstamp
	 * @return void
	 */
	public function setTstamp($tstamp)
	{
		$this->tstamp = $tstamp;
	}
}
