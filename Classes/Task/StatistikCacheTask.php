<?php
declare(strict_types=1);

namespace Ud\Iqtp13db\Task;

use \Datetime;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Task\AbstractTask;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogManager;

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

use Ud\Iqtp13db\Domain\Repository\TeilnehmerRepository;
use Ud\Iqtp13db\Domain\Repository\FolgekontaktRepository;

/**
 * Scheduler-Task: Statistik-Cache täglich neu berechnen
 *
 */
class StatistikCacheTask extends AbstractTask
{
    private ?TeilnehmerRepository $teilnehmerRepository = null;
    private ?FolgekontaktRepository $folgekontaktRepository = null;
    private ?Logger $mylogger = null;

    // -----------------------------------------------------------------------
    // Tabellennamen
    // -----------------------------------------------------------------------
    private const TABLE_CACHE        = 'tx_iqtp13db_domain_model_statistik_cache';
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
            $arrayniqbids = $this->getAllNiqbids();
       
            if (empty($arrayniqbids)) {
                $this->getLogger()->error('Keine Beratungsstellen (niqbid) gefunden. Task beendet.');
                return true;
            }

            $connection = $this->getConnectionPool()->getConnectionForTable(self::TABLE_CACHE);
            $generatedAt = time();
             
            foreach ($arrayniqbids as $niqbid) {
                // Alle bisherigen Einträge dieser Stelle löschen (kein Verlauf gewünscht)
                $connection->delete(
                    self::TABLE_CACHE,
                    ['niqbid' => $niqbid['niqbid']]
                );

                //$bundesland = intval($niqbid['niqbid']) < 999 ? $niqbid['bundesland'] : '%';
                $bundesland = $niqbid['bundesland'];

                $metrics = $this->computeYearlyMetrics($niqbid['niqbid'], $bundesland, 0);
                $this->replaceMetrics($niqbid['niqbid'], $bundesland, 0, $metrics, $generatedAt); // 0 = letzte 12 Monate
                $metrics = $this->computeYearlyMetrics($niqbid['niqbid'], $bundesland, 99);
                $this->replaceMetrics($niqbid['niqbid'], $bundesland, 99, $metrics, $generatedAt); // 99 = alle seit 2023
                for($jahr=2023;$jahr<=date('Y');$jahr++){                    
                    $metrics = $this->computeYearlyMetrics($niqbid['niqbid'], $bundesland, $jahr);
                    $this->replaceMetrics($niqbid['niqbid'], $bundesland, $jahr, $metrics, $generatedAt);
                }  
                
                $currmetrics = $this->computeCurrentMetrics($niqbid['niqbid'], $bundesland);
                $this->replaceMetrics($niqbid['niqbid'], $bundesland, 999, $currmetrics, $generatedAt);
            }

            $this->getLogger()->info(sprintf(
                'Statistik-Cache erfolgreich aktualisiert. %d Beratungsstellen, Zeitstempel %s.',
                count($arrayniqbids),
                date('Y-m-d H:i:s', $generatedAt)
            ));

            return true;

        } catch (\Throwable $e) {
            $this->getLogger()->error('Fehler im StatistikCacheTask: ' . $e->getMessage());
            return false;
        }
    }



    // -----------------------------------------------------------------------------
    // Alle jährlichen Metriken für Statistik-Matrix einer Beratungsstelle berechnen
    // -----------------------------------------------------------------------------
    private function computeYearlyMetrics(string $niqbid, string $bundesland, int $jahr): array
    {        
        $emptystatusarray = array(1 => 0,2 => 0,3 => 0,4 => 0,5 => 0,6 => 0,7 => 0,8 => 0,9 => 0,10 => 0,11 => 0, 12 => 0);

        // Angemeldete TN
        $angemeldeteTN = $emptystatusarray;        
        $ergarrayangemeldete = $this->getTeilnehmerRepository()->countTNby($niqbid, $bundesland, 1, $jahr, '%');
        foreach($ergarrayangemeldete as $erg) $angemeldeteTN[$erg['monat']] = $erg['anzahl'];
        ksort($angemeldeteTN);

        // Erstberatungen
        $erstberatung = $emptystatusarray;
        $ergarrayerstberatung = $this->getTeilnehmerRepository()->countTNby($niqbid, $bundesland, 2, $jahr, '%');
        foreach($ergarrayerstberatung as $erg) $erstberatung[$erg['monat']] = $erg['anzahl'];
        ksort($erstberatung);

        // Fertige Beratungen
        $beratungfertig = $emptystatusarray;
        $ergarrayberatungfertig = $this->getTeilnehmerRepository()->countTNby($niqbid, $bundesland, 3, $jahr, '%');
        foreach($ergarrayberatungfertig as $erg) $beratungfertig[$erg['monat']] = $erg['anzahl'];
        ksort($beratungfertig);

        // Folgekontakte/-beratungen
        $qfolgekontakte =  $emptystatusarray;
        $ergarrayfolgekontakte = $this->getFolgekontaktRepository()->countFKby($niqbid, $bundesland, $jahr, '%');
        foreach($ergarrayfolgekontakte as $erg) $qfolgekontakte[$erg['monat']] = $erg['anzahl'];
        ksort($qfolgekontakte);

        // Tage Wartezeit
        $days4wartezeit = $emptystatusarray;
        $ergarraywartezeitberatung = $this->getTeilnehmerRepository()->calcwaitingdays($niqbid, $bundesland, 'beratung', $jahr, '%');
        foreach($ergarraywartezeitberatung as $erg) $days4beratung[$erg['monat']] = $erg['wert'];
        ksort($days4wartezeit);
        
        // Tage für Beratung Start bis Beratung abgeschlossen
        $days4beratung = $emptystatusarray;
        $ergarraywartezeitanmeldung = $this->getTeilnehmerRepository()->calcwaitingdays($niqbid, $bundesland, 'anmeldung', $jahr, '%');
        foreach($ergarraywartezeitanmeldung as $erg) $days4wartezeit[$erg['monat']] = $erg['wert'];
        ksort($days4beratung);
        
        // FK/Beratungen aus Förderphase 2019-2022 in 2023
        $beratungfk22 = $emptystatusarray;
        $tnberatungenfk22 = $this->getFolgekontaktRepository()->fk4StatusFK2022("01.01.2023", "31.12.2023", $niqbid);
        for($m = 1; $m < 13; $m++) $beratungfk22[$m] = 0;
        foreach($tnberatungenfk22 as $fk22) {
            $fkmonat = DateTime::createFromFormat('Y-m-d', $fk22->getDatum())->format('n');
            $beratungfk22[$fkmonat]++;
        }
        ksort($beratungfk22);
        
        $beratungfk25 = $emptystatusarray;
         // FK/Beratungen aus Förderphase 2023-2025 in 2026
        $tnberatungenfk25 = $this->getFolgekontaktRepository()->fk4StatusFK2025("01.01.2026", "31.12.2026", $niqbid);
        for($m = 1; $m < 13; $m++) $beratungfk25[$m] = 0;
        foreach($tnberatungenfk25 as $fk25) {
            $fkmonat = DateTime::createFromFormat('Y-m-d', $fk25->getDatum())->format('n');
            $beratungfk25[$fkmonat]++;
        }
        ksort($beratungfk25);
        
        return [
            'angemeldeteTNunbestaetigt' => $angemeldeteTNunbestaetigt ?? '',
            'angemeldeteTN' => $angemeldeteTN,
            'erstberatung' => $erstberatung,
            'beratungfertig' => $beratungfertig,
            'qfolgekontakte' => $qfolgekontakte,
            'days4wartezeit' => $days4wartezeit,
            'days4beratung' => $days4beratung,
            'beratungfk22' => $beratungfk22,
            'tnberatungenfk22' => $tnberatungenfk22,
            'beratungfk25' => $beratungfk25,
            'tnberatungenfk25' => $tnberatungenfk25
        ];
    }

    // -----------------------------------------------------------------------
    // Alle aktuellen Metriken für eine Beratungsstelle berechnen
    // -----------------------------------------------------------------------
    private function computeCurrentMetrics(string $niqbid, string $bundesland): array
    {        
        $aktuelleanmeldungenunbestaetigt = $this->teilnehmerRepository->countAllOrder4Status(0, $niqbid, $bundesland)[0]['anzahl'];        
        $aktuelleanmeldungenbestaetigt = $this->teilnehmerRepository->countAllOrder4Status(1, $niqbid, $bundesland)[0]['anzahl'];
        $aktuelleanmeldungen = $aktuelleanmeldungenunbestaetigt + $aktuelleanmeldungenbestaetigt;                                
        $aktuellerstberatungen = $this->getTeilnehmerRepository()->countAllOrder4Status(2, $niqbid, $bundesland)[0]['anzahl'];
        $aktuellberatungenfertig = $this->getTeilnehmerRepository()->countAllOrder4Status(3, $niqbid, $bundesland)[0]['anzahl'];
        $archivierttotal = $this->getTeilnehmerRepository()->countAllOrder4Status(4, $niqbid, $bundesland)[0]['anzahl'];

        $neuanmeldungen7tage = array();
        for($i = 7; $i >= 0; $i--) {
            $reftag = date("d.m.Y", strtotime( '-'.$i.' days' ));
            $neuanmeldungen7tage[$i]["tag"] = date("l, d.m.Y", strtotime( '-'.$i.' days' ));
            $neuanmeldungen7tage[$i]["wert"] = $this->getTeilnehmerRepository()->count4Status($reftag, $reftag, $niqbid, 1, $bundesland)[0]['anzahl'];
        }
        return [
            'aktuelleanmeldungenunbestaetigt' => $aktuelleanmeldungenunbestaetigt,
            'aktuelleanmeldungenbestaetigt' => $aktuelleanmeldungenbestaetigt,
            'aktuelleanmeldungen' => $aktuelleanmeldungen,
            'aktuellerstberatungen' => $aktuellerstberatungen,
            'aktuellberatungenfertig' => $aktuellberatungenfertig,
            'archivierttotal' => $archivierttotal,
            'neuanmeldungen7tage' => $neuanmeldungen7tage
        ];
    }

    // -----------------------------------------------------------------------
    // Alle vorhandenen Cache-Einträge dieser niqbid schreiben
    // -----------------------------------------------------------------------
    private function replaceMetrics(
        string $niqbid,
        string $bundesland,
        int    $year,
        array  $metrics,
        int    $generatedAt
    ): void {
        $connection = $this->getConnectionPool()->getConnectionForTable(self::TABLE_CACHE);

        // Neue Einträge schreiben
        foreach ($metrics as $metric => $data) {
            $cacheKey = sprintf('%s_%d_%s', $niqbid, $year, $metric);

            $connection->insert(self::TABLE_CACHE, [
                'pid'          => $this->storagePid,
                'cache_key'    => $cacheKey,
                'niqbid'       => $niqbid,
                'bundesland'   => $bundesland,
                'bezugsjahr'   => $year,
                'metric'       => $metric,
                'wert_json'    => $data,
                'generated_at' => $generatedAt,
            ]);
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
            ->select('niqbid', 'bundesland')
            ->from(self::TABLE_FE_GROUPS)
            ->where(
                $qb->expr()->neq('niqbid', $qb->createNamedParameter('')),
                $qb->expr()->eq('deleted', $qb->createNamedParameter(0, Connection::PARAM_INT)),
                $qb->expr()->neq('title', $qb->createNamedParameter('AA%'))
            )
            ->groupBy('niqbid')
            ->executeQuery()
            ->fetchAllAssociative();

        return $rows;
    }


    // -----------------------------------------------------------------------
    // Getter-Methoden für Repos
    // -----------------------------------------------------------------------
    private function getTeilnehmerRepository(): TeilnehmerRepository
    {
        if ($this->teilnehmerRepository === null) {
            $this->teilnehmerRepository = GeneralUtility::makeInstance(TeilnehmerRepository::class);

            // Query-Settings explizit konfigurieren
            $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);

            // Keinen bestimmten StoragePid erzwingen – alle Pids abfragen
            $querySettings->setRespectStoragePage(false);

            // deleted/hidden aus TCA werden weiterhin respektiert
            $querySettings->setIgnoreEnableFields(false);

            $this->teilnehmerRepository->setDefaultQuerySettings($querySettings);
        }

        return $this->teilnehmerRepository;
    }

    private function getFolgekontaktRepository(): FolgekontaktRepository
    {
        if ($this->folgekontaktRepository === null) {
            $this->folgekontaktRepository = GeneralUtility::makeInstance(FolgekontaktRepository::class);

            $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
            $querySettings->setRespectStoragePage(false);
            $querySettings->setIgnoreEnableFields(false);

            $this->folgekontaktRepository->setDefaultQuerySettings($querySettings);
        }

        return $this->folgekontaktRepository;
    }

    // -----------------------------------------------------------------------
    // Hilfsmethoden
    // -----------------------------------------------------------------------

    private function getLogger(): Logger
    {
        if ($this->mylogger === null) {
            // Klassenname als Channel – so findest du die Einträge
            // im Log gezielt unter Ud.Iqtp13db.Task.StatistikCacheTask
            $this->mylogger = GeneralUtility::makeInstance(LogManager::class)
                ->getLogger(__CLASS__);
        }

        return $this->mylogger;
    }

    private function getConnectionPool(): ConnectionPool
    {
        return GeneralUtility::makeInstance(ConnectionPool::class);
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