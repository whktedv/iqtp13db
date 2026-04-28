<?php
declare(strict_types=1);

namespace Ud\Iqtp13db\Task;

use Doctrine\DBAL\Exception;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

use Ud\Iqtp13db\Domain\Repository\TeilnehmerRepository;

/**
 * Scheduler-Task: Statistik-Cache täglich neu berechnen
 *
 */
class StatistikCacheTask extends AbstractTask
{
    protected ?TeilnehmerRepository $teilnehmerRepository = null;

    public function injectTeilnehmerRepository(TeilnehmerRepository $teilnehmerRepository): void
    {
        $this->teilnehmerRepository = $teilnehmerRepository;
    }

    // -----------------------------------------------------------------------
    // Tabellennamen
    // -----------------------------------------------------------------------
    private const TABLE_CACHE        = 'tx_iqtp13db_domain_model_statistik_cache';
    private const TABLE_TN           = 'tx_iqtp13db_domain_model_teilnehmer';
    private const TABLE_ABSCHLUSS    = 'tx_iqtp13db_domain_model_abschluss';
    private const TABLE_FOLGEKONTAKT = 'tx_iqtp13db_domain_model_folgekontakt';
    private const TABLE_FE_GROUPS    = 'fe_groups';

    // -----------------------------------------------------------------------
    // Konfigurierbarer Wert: Storage-PID für neue Cache-Datensätze.
    // Kann im Scheduler-Task-Backend überschrieben werden.
    // -----------------------------------------------------------------------
    public int $storagePid = 0;

    // -----------------------------------------------------------------------
    // execute() – Einstiegspunkt des Scheduler-Tasks
    // -----------------------------------------------------------------------
    public function execute(): bool
    {
        try {
            $niqbids = $this->getAllNiqbids();

            if (empty($niqbids)) {
                $this->logInfo('Keine Beratungsstellen (niqbid) gefunden. Task beendet.');
                return true;
            }

            $generatedAt = time();
            foreach ($niqbids as $niqbid) {
                for($jahr=2023;$jahr<=date('Y');$jahr++){                    
                    $metrics = $this->computeMetrics($niqbid, $jahr);
                    $this->replaceMetrics($niqbid, $jahr, $metrics, $generatedAt);
                }
            }

            $this->logInfo(sprintf(
                'Statistik-Cache erfolgreich aktualisiert. %d Beratungsstellen, Zeitstempel %s.',
                count($niqbids),
                date('Y-m-d H:i:s', $generatedAt)
            ));

            return true;

        } catch (\Throwable $e) {
            $this->logError('Fehler im StatistikCacheTask: ' . $e->getMessage());
            return false;
        }
    }

    // -----------------------------------------------------------------------
    // Alle aktiven niqbids aus fe_groups laden
    // -----------------------------------------------------------------------
    private function getAllNiqbids(): array
    {
        $qb = $this->getConnectionPool()->getQueryBuilderForTable(self::TABLE_FE_GROUPS);
        $qb->getRestrictions()->removeAll();

        $rows = $qb
            ->select('niqbid')
            ->from(self::TABLE_FE_GROUPS)
            ->where(
                $qb->expr()->neq('niqbid', $qb->createNamedParameter('')),
                $qb->expr()->eq('deleted', $qb->createNamedParameter(0, Connection::PARAM_INT))
            )
            ->groupBy('niqbid')
            ->executeQuery()
            ->fetchAllAssociative();

        return array_column($rows, 'niqbid');
    }

    // -----------------------------------------------------------------------
    // Alle Metriken für eine Beratungsstelle berechnen
    //
    // -----------------------------------------------------------------------
    private function computeMetrics(string $niqbid, int $jahr): array
    {        
        $emptystatusarray = array(1 => 0,2 => 0,3 => 0,4 => 0,5 => 0,6 => 0,7 => 0,8 => 0,9 => 0,10 => 0,11 => 0, 12 => 0);

        $angemeldeteTN = $emptystatusarray;
        $ergarrayangemeldete = $this->teilnehmerRepository->countTNby($niqbid, '%', 1, $jahr, '%');
        foreach($ergarrayangemeldete as $erg) $angemeldeteTN[$erg['monat']] = $erg['anzahl'];
        ksort($angemeldeteTN);

        return [
            'angemeldeteTN'           => $angemeldeteTN,
        ];
    }

    // -----------------------------------------------------------------------
    // Alle vorhandenen Cache-Einträge dieser niqbid löschen und neu schreiben
    // -----------------------------------------------------------------------
    private function replaceMetrics(
        string $niqbid,
        int    $year,
        array  $metrics,
        int    $generatedAt
    ): void {
        $connection = $this->getConnectionPool()->getConnectionForTable(self::TABLE_CACHE);

        // Alle bisherigen Einträge dieser Stelle löschen (kein Verlauf gewünscht)
        $connection->delete(
            self::TABLE_CACHE,
            ['niqbid' => $niqbid]
        );

        // Neue Einträge schreiben
        foreach ($metrics as $metric => $data) {
            $cacheKey = sprintf('%s_%d', $niqbid, $year);

            $connection->insert(self::TABLE_CACHE, [
                'pid'          => $this->storagePid,
                'cache_key'    => $cacheKey,
                'niqbid'       => $niqbid,
                'bezugsjahr'   => $year,
                'metric'       => $metric,
                'wert_json'    => json_encode($data),
                'generated_at' => $generatedAt,
            ]);
        }
    }

    // -----------------------------------------------------------------------
    // Hilfsmethoden
    // -----------------------------------------------------------------------

    /** QueryBuilder für die Teilnehmer-Tabelle ohne TYPO3-Restrictions */
    private function getTnQueryBuilder()
    {
        $qb = $this->getConnectionPool()->getQueryBuilderForTable(self::TABLE_TN);
        $qb->getRestrictions()->removeAll();
        return $qb;
    }

    private function getConnectionPool(): ConnectionPool
    {
        return GeneralUtility::makeInstance(ConnectionPool::class);
    }

    private function logInfo(string $message): void
    {
        $this->logger?->info($message);
    }

    private function logError(string $message): void
    {
        $this->logger?->error($message);
    }

    // -----------------------------------------------------------------------
    // Beschreibung für das TYPO3-Scheduler-Backend
    // -----------------------------------------------------------------------
    public function getAdditionalInformation(): string
    {
        return sprintf(
            'Berechnet Statistik-Cache für alle Beratungsstellen. Storage-PID: %d',
            $this->storagePid
        );
    }
}