<?php
declare(strict_types = 1);

namespace Ud\Iqtp13db\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

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
 * Class InstringViewHelper
 */
class InstringViewHelper extends AbstractConditionViewHelper 
{
    public function initializeArguments() {
        parent::initializeArguments();
        $this->registerArgument('haystack', 'mixed', 'View helper haystack ', TRUE);
        $this->registerArgument('needle', 'string', 'View helper needle', TRUE);
    }
    
    
    /**
     * @param array $arguments
     * @return bool
     */
    protected static function evaluateCondition($arguments = null)
    {
        $haystack = (string)$arguments['haystack'];
        $needle = (string)$arguments['needle'];
        
        if(str_contains($haystack, $needle)) {
            return true;
        } else {
            return false;
        }
    }
  
}
