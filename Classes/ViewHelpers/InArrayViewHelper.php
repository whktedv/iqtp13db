<?php
namespace Ud\Iqtp13db\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class InArrayViewHelper extends AbstractViewHelper
{
    public function initializeArguments()
    {
        $this->registerArgument('needle', 'string', 'The value to search for', true);
        $this->registerArgument('haystack', 'array', 'The array to search in', true);
    }

    public function render()
    {
        return in_array($this->arguments['needle'], $this->arguments['haystack']);
    }
}