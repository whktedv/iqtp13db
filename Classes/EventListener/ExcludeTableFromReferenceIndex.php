<?php
namespace Ud\Iqtp13db\EventListener;

use TYPO3\CMS\Core\DataHandling\Event\IsTableExcludedFromReferenceIndexEvent;

class ExcludeTableFromReferenceIndex
{
    public function __invoke(IsTableExcludedFromReferenceIndexEvent $event): void
    {
        if ($event->getTableName() === 'tx_iqtp13db_domain_model_historie') {
            $event->setExcluded(true);
        }
    }
}