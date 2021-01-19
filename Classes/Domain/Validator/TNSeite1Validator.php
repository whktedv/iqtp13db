<?php 

namespace Ud\Iqtp13db\Domain\Validator;

class TNSeite1Validator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
    public function isValid($tnseite1)
	{
	    if (! $tnseite1 instanceof \Ud\Iqtp13db\Domain\Model\TNSeite1) {
	        $this->addError('The given Object is not a Teilnehmer.', 1262341470);
	    }
	    if ($tnseite1->getEmail() !== $tnseite1->getConfirmemail()) {
	       
	        $this->addError('Die E-Mail-Bestätigung ist nicht korrekt. Bitte E-Mail-Adresse prüfen. / E-Mail addresses do not match. Please Check addresses.', 1262341707);
	        return FALSE;
	    }
	}
}

?>