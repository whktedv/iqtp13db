<?php 

namespace Ud\Iqtp13db\Domain\Validator;

class DatumValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
	public function isValid($value)
	{
	    if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$value)) {
	        return true;
	    } else {
	        $this->addError('Das Datum muss im Format JJJJ-MM-TT eingegeben werden.', 40213131);
	        return false;
	    }		
	}
}

?>