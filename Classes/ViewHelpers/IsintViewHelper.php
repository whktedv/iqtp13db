<?php
declare(strict_types = 1);

namespace Ud\Iqtp13db\ViewHelpers;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
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
 * Class IsintViewHelper
 */
class IsintViewHelper extends AbstractConditionViewHelper 
{
    public function initializeArguments() {
        parent::initializeArguments();        
        $this->registerArgument('field', 'string', 'View helper field', TRUE);
    }
    
    /**
     * @param array $arguments
     * @return bool
     */
    protected static function evaluateCondition($arguments = null)
    {
        $field = (string)$arguments['field'];
        
        //DebuggerUtility::var_dump($field);
        
        if(is_numeric($field)) {
            return true;
        } else {
            return false;
        }
    }
  
}
