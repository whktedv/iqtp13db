<?php
declare(strict_types = 1);
namespace Ud\Iqtp13db\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup;

/**
 * Class UserGroup
 */
class UserGroup extends FrontendUserGroup
{
    /**
     * niqbid
     *
     * @var string
     */
    protected $niqbid = '';
    
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
     * initializes this object
     *
     * @param array $plzlist
     * @param array $keywordlist
     */
    public function __construct(array $plzlist = array(), array $keywordlist = array(), array $beratungsarten = array()) {
        $this->setPlzlist($plzlist);
        $this->setKeywordlist($keywordlist);
        $this->setBeratungsarten($beratungsarten);
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
}
