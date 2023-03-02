<?php 

namespace Ud\Iqtp13db\Domain\Validator;

class TeilnehmerValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
    public function isValid($teilnehmer)
	{
	    if (! $teilnehmer instanceof \Ud\Iqtp13db\Domain\Model\Teilnehmer) {
	        $this->addError('Die eingebenen Daten sind ungültig. Bitte prüfen Sie die eingebenenen Daten.', 1262341470);
	    }
	    
	}
}

?>