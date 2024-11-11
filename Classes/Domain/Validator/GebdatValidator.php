<?php 

namespace Ud\Iqtp13db\Domain\Validator;

class GebdatValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
	public function isValid(mixed $value):void
	{
	    
	    // Mit Hilfe von ChatGPT erstellt:
	    
	    // Überprüfen, ob das Datum im Format YYYY-MM-DD vorliegt
	    if (!is_string($value) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
	        $this->addError('Das Datum muss im Format JJJJ-MM-TT angegeben werden.', 161123);
	        return;
	    }
	    
	    // Datum in ein DateTime-Objekt umwandeln
	    $birthdate = \DateTime::createFromFormat('Y-m-d', $value);
	    if (!$birthdate || $birthdate->format('Y-m-d') !== $value) {
	        $this->addError('Das Datum ist ungültig.', 161124);
	        return;
	    }
	    
	    // Alter berechnen
	    $today = new \DateTime();
	    $age = $today->diff($birthdate)->y;
	    
	    // Überprüfen, ob das Alter im zulässigen Bereich liegt
	    if ($age < 15) {
	        $this->addError('Das Geburtsdatum muss mindestens 15 Jahre zurückliegen.', 161125);
	    } elseif ($age > 90) {
	        $this->addError('Das Geburtsdatum darf maximal 90 Jahre zurückliegen.', 161126);
	    }
	}
}

?>