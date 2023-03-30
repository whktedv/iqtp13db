<?php
declare(strict_types = 1);
namespace Ud\Iqtp13db\Domain\Model;

/**
 * Class UserGroup
 */
class UserGroup extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * @var string
     */
    protected $title = '';
    
    /**
     * niqbid
     *
     * @var string
     */
    protected $niqbid = '';
        
    /**
     * nichtiq
     * 
     * @var int
     */
    protected $nichtiq = 0;
        
    /**
     * bundesland 
     * 
     * @var string
     */
    protected $bundesland = '';
    
    /**
     * generalmail
     *
     * @var string
     */
    protected $generalmail = '';
    
    /**
     * plzlist
     *
     * @var string
     */
    protected $plzlist = '';
    
    /**
     * keywordlist
     *
     * @var string
     */
    protected $keywordlist = '';
    
    /**
     * beratungsarten
     *
     * @var string
     */
    protected $beratungsarten = '';
    
    /**
     * description
     * 
     * @var string
     */
    protected $description = '';
    
    
    /**
     * einwilligungserklaerungsseite
     * 
     *  @var int
     */
    protected $einwilligungserklaerungsseite = 0;
    
    /**
     * initializes this object
     *
     * @param string $title
     * @param array $plzlist
     * @param array $keywordlist
     * @param array $beratungsarten
     * @param array $description
     */
    public function __construct($title = '', array $plzlist = array(), array $keywordlist = array(), array $beratungsarten = array(), $description = '') {
        $this->setTitle($title);
        $this->setPlzlist($plzlist);
        $this->setKeywordlist($keywordlist);
        $this->setBeratungsarten($beratungsarten);
        $this->setDescription($description);
    }
    
    /**
     * Sets the title value
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    /**
     * Returns the title value
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * Returns the nichtiq
     *
     * @return int $nichtiq
     */
    public function getNichtiq()
    {
        return $this->nichtiq;
    }
    
    /**
     * Sets the nichtiq
     *
     * @param int $nichtiq
     * @return void
     */
    public function setNichtiq($nichtiq)
    {
        $this->nichtiq = $nichtiq;
    }
    
    /**
     * Sets the bundesland value
     *
     * @param string $bundesland
     */
    public function setBundesland($bundesland)
    {
        $this->title = $bundesland;
    }
    
    /**
     * Returns the bundesland value
     *
     * @return string
     */
    public function getBundesland()
    {
        return $this->bundesland;
    }
    
    /**
     * Returns the generalmail
     *
     * @return string $generalmail
     */
    public function getGeneralmail()
    {
        return $this->generalmail;
    }
    
    /**
     * Sets the generalmail
     *
     * @param string $generalmail
     * @return void
     */
    public function setGeneralmail($generalmail)
    {
        $this->generalmail = $generalmail;
    }
    
    /**
     * Returns the plzlist
     *
     * @return array $plzlist
     */
    public function getPlzlist()
    {
        return explode(',', $this->plzlist);
    }
    
    /**
     * Sets the plzlist
     *
     * @param array $plzlist
     * @return void
     */
    public function setPlzlist(array $plzlist)
    {
        $this->plzlist = implode(',', $plzlist);
    }
    
    /**
     * Returns the keywordlist
     *
     * @return array $keywordlist
     */
    public function getKeywordlist()
    {
        return explode(',', $this->keywordlist);
    }
    
    /**
     * Sets the keywordlist
     *
     * @param array $keywordlist
     * @return void
     */
    public function setKeywordlist(array $keywordlist)
    {
        $this->keywordlist = implode(',', $keywordlist);
    }
    
    /**
     * Returns the beratungsarten
     *
     * @return array $beratungsarten
     */
    public function getBeratungsarten()
    {
        return explode(',', $this->beratungsarten);
    }
    
    /**
     * Sets the beratungsarten
     *
     * @param array $beratungsarten
     * @return void
     */
    public function setBeratungsarten(array $beratungsarten)
    {
        $this->beratungsarten = implode(',', $beratungsarten);
    }
    
    /**
     * Sets the description value
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    /**
     * Returns the description value
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Returns the einwilligungserklaerungsseite
     *
     * @return int $einwilligungserklaerungsseite
     */
    public function getEinwilligungserklaerungsseite()
    {
        return $this->einwilligungserklaerungsseite;
    }
    
    /**
     * Sets the einwilligungserklaerungsseite
     *
     * @param int $einwilligungserklaerungsseite
     * @return void
     */
    public function setEinwilligungserklaerungsseite($einwilligungserklaerungsseite)
    {
        $this->einwilligungserklaerungsseite = $einwilligungserklaerungsseite;
    }
    
}
