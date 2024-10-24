<?php 

namespace Ud\Iqtp13db\Domain\Validator;

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

class EinwilligungValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
	public function isValid($value)
	{
		if($value != true)
		{
		    $errormsg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('errorconsent', 'iqtp13db');
		    $this->addError($errormsg, 40213131);
		    return FALSE;
		}
		return true;
	}
}

?>