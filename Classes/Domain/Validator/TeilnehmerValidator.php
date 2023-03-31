<?php 

namespace Ud\Iqtp13db\Domain\Validator;

class TeilnehmerValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
    public function isValid($teilnehmer)
	{
	    if (! $teilnehmer instanceof \Ud\Iqtp13db\Domain\Model\Teilnehmer) {
	        $errormsg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('errortndatanotvalid', 'iqtp13db');
	        $this->addError($errormsg, 1262341470);
	    } 	    
	}
}

?>