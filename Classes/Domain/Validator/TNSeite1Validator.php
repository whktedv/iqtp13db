<?php 

namespace Ud\Iqtp13db\Domain\Validator;

class TNSeite1Validator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
    public function isValid($tnseite1)
	{
	    if (! $tnseite1 instanceof \Ud\Iqtp13db\Domain\Model\TNSeite1) {
	       $this->addError('The given object is not a teilnehmer.', 1262341470);
	    }
	    if ($tnseite1->getEmail() !== $tnseite1->getConfirmemail()) {
	       
	        $errormsg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('errormailnotmatch', 'iqtp13db');
	        $this->addError($errormsg, 1262341707);
	        return FALSE;
	    }
	    	    
	    /* Validate the E-mail-address. */
	    if (!filter_var($tnseite1->getEmail(), FILTER_VALIDATE_EMAIL)) {
	        $errormsg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('errormailnotvalid', 'iqtp13db');
	        $this->addError($errormsg, 1262341707);
	        return FALSE;
	    }
	    /* Check the domain. */
	    $atPos = mb_strpos($tnseite1->getEmail(), '@');
	    $domain = mb_substr($tnseite1->getEmail(), $atPos + 1);
	    if (!checkdnsrr($domain . '.', 'MX')) {
	        $errormsg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('errordomainnotvalid', 'iqtp13db');
	        $this->addError('@'.$domain.': '.$errormsg, 1262341707);
	        return FALSE;
	    }
	}
}

?>