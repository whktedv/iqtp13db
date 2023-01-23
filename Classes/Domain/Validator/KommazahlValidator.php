<?php 
 
namespace Ud\Iqtp13db\Domain\Validator;

class KommazahlValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
	public function isValid($value)
	{
	    $value = str_replace(",",".",$value);
	    
	    if (is_numeric($value)) {
	        return true;
	    } else {
	        $this->addError('Die Beratungsdauer in Stunden muss in ganzen Zahlen oder Kommazahlen angegeben werden.', 40213131);
	        return false;
	    }		
	}
}

?>