<?php 

namespace Ud\Iqtp13db\Domain\Validator;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class WebappMailValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
    /**
     * @var bool
     */
    protected $acceptsEmptyValues = false;
    
    public function isValid(mixed $value):void
	{
        if (!is_object($value)) {
	        $this->addError('Der übergebene Wert ist kein Objekt.', 1234567890);
	        return;
	    }
	    
	    // Prüfe, ob die Getter-Methoden existieren
	    if (!method_exists($value, 'getEmail') || !method_exists($value, 'getConfirmEmail')) {
	        $this->addError('Das Objekt hat nicht die erforderlichen E-Mail-Methoden.', 1234567891);
	        return;
	    }
	    
	    $email = $value->getEmail();
	    $confirmEmail = $value->getConfirmEmail();
	    
	    // Prüfe, ob beide Felder ausgefüllt sind
	    if (empty($email) || empty($confirmEmail)) {
	        $this->addError('Beide E-Mail-Felder müssen ausgefüllt werden.', 1234567892);
	        return;
	    }

	    // Prüfe ob gültige E-Mail-Adresse 
	    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	        $errormsg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('errormailnotvalid', 'iqtp13db');
	        $this->addError($errormsg, 1262341707);
	        return;
	    }
	    
	    // Prüfe, ob die E-Mail-Adressen identisch sind
	    if ($email !== $confirmEmail) {
	        $errormsg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('errormailnotmatch', 'iqtp13db');
	        $this->addError('Die E-Mail-Adressen stimmen nicht überein.', 1234567893);
	        return;
	    }
	    
	    // Prüfe die Domains
	    $atPos = mb_strpos($email, '@');
	    $domain = mb_substr($email, $atPos + 1);
	    if (!checkdnsrr($domain . '.', 'MX') || $domain == 'gmial.com' || $domain == 'gamil.com' || $domain == 'gmail.co' ) {
	        $errormsg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('errordomainnotvalid', 'iqtp13db');
	        $this->addError('@'.$domain.': '.$errormsg, 1262341707);
	        return;
	    }
	    
	}
}

?>
