<?php 

namespace Ud\Iqtp13db\Validation\Validator;

class EinwilligungValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
	public function isValid($value)
	{
		if($value != true)
		{
			$this->addError('Um fortzufahren, müssen Sie in die Übermittlung Ihrer Daten einwilligen. / <i>To proceed, you have to agree to transfer your data.</i>', 40213131);
			return false;
		}
		return true;
	}
}

?>