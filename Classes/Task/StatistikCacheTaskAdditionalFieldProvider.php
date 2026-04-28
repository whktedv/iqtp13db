<?php

declare(strict_types=1);

namespace Ud\Iqtp13db\Task;

use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Scheduler\AbstractAdditionalFieldProvider;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Ermöglicht die Konfiguration des Storage-PIDs direkt im Scheduler-Backend.
 *
 * TYPO3 13 hat die Signatur von AbstractAdditionalFieldProvider geändert:
 * - SchedulerModuleController wird nicht mehr übergeben
 * - $task ist nicht mehr nullable
 * - validateAdditionalFields() erhält keinen Controller-Parameter mehr
 */
class StatistikCacheTaskAdditionalFieldProvider extends AbstractAdditionalFieldProvider
{
    private const FIELD_STORAGE_PID = 'tx_iqtp13db_statistik_storage_pid';

    /**
     * @param array<string, mixed> $taskInfo
     * @return array<string, array<string, string>>
     */
    public function getAdditionalFields(array &$taskInfo, $task, SchedulerModuleController $schedulerModule): array
    {
        if ($task instanceof StatistikCacheTask) {
            $taskInfo[self::FIELD_STORAGE_PID] = $task->storagePid;
        }

        if (!isset($taskInfo[self::FIELD_STORAGE_PID])) {
            $taskInfo[self::FIELD_STORAGE_PID] = 0;
        }

        $fieldHtml = sprintf(
            '<input type="number" min="0" class="form-control" name="tx_scheduler[%s]" id="%s" value="%d" />',
            self::FIELD_STORAGE_PID,
            self::FIELD_STORAGE_PID,
            (int)$taskInfo[self::FIELD_STORAGE_PID]
        );

        return [
            self::FIELD_STORAGE_PID => [
                'code'     => $fieldHtml,
                'label'    => 'Storage-PID für Statistik-Datensätze',
                'cshKey'   => '',
                'cshLabel' => '',
            ],
        ];
    }

    /**
     * @param array<string, mixed> $submittedData
     */
    public function validateAdditionalFields(array &$submittedData, mixed $schedulerModule): bool
    {
        $pid = (int)($submittedData[self::FIELD_STORAGE_PID] ?? 0);

        if ($pid < 0) {
            $this->addMessage(
                'Storage-PID muss 0 oder größer sein.',
                ContextualFeedbackSeverity::ERROR
            );
            return false;
        }

        $submittedData[self::FIELD_STORAGE_PID] = $pid;
        return true;
    }

    public function saveAdditionalFields(array $submittedData, AbstractTask $task): void
    {
        if ($task instanceof StatistikCacheTask) {
            $task->storagePid = (int)($submittedData[self::FIELD_STORAGE_PID] ?? 0);
        }
    }
}
