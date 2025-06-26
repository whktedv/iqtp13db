<?php 

namespace Ud\Iqtp13db\Domain\Validator;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class TeilnehmerValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
    public function isValid(mixed $teilnehmer):void
	{
	    if (! $teilnehmer instanceof \Ud\Iqtp13db\Domain\Model\Teilnehmer) {
	        $errormsg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('errortndatanotvalid', 'iqtp13db');
	        $this->addError($errormsg, 1262341470);
	        return;
	    } 

	    
	    if($teilnehmer->getNachname() == '') {
	        $errormsg = "<b>".\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_iqtp13db_domain_model_teilnehmer.nachname','iqtp13db') . ":</b> Feld muss ausgefüllt sein.";
	        $this->addError($errormsg, 1262341470);
	    }
	    	    
	    if($teilnehmer->getVorname() == '') {
	        $errormsg = "<b>".\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_iqtp13db_domain_model_teilnehmer.vorname','iqtp13db') . ":</b> Feld muss ausgefüllt sein.";
	        $this->addError($errormsg, 1262341470);
	    }
	    
	    if($teilnehmer->getStrasse() == '') {
	        $errormsg = "<b>".\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_iqtp13db_domain_model_teilnehmer.strasse','iqtp13db') . ":</b> Feld muss ausgefüllt sein.";
	        $this->addError($errormsg, 1262341470);
	    }
	    
	    if($teilnehmer->getPlz() == '') {
	        $errormsg = "<b>".\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_iqtp13db_domain_model_teilnehmer.plz','iqtp13db') . ":</b> Feld muss ausgefüllt sein.";
	        $this->addError($errormsg, 1262341470);
	    }

	    if($teilnehmer->getTelefon() == '') {
	        $errormsg = "<b>".\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_iqtp13db_domain_model_teilnehmer.telefon','iqtp13db') . ":</b> Feld muss ausgefüllt sein.";
	        $this->addError($errormsg, 1262341470);
	    }
	    
	    if($teilnehmer->getGebdat() == '') {
	        $errormsg = "<b>".\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_iqtp13db_domain_model_teilnehmer.gebdat','iqtp13db') . ":</b> Feld muss ausgefüllt sein.";
	        $this->addError($errormsg, 1262341470);
	    }
	    return;
	}
}

?>