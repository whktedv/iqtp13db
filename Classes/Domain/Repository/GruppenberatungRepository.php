<?php

declare(strict_types=1);

namespace Ud\Iqtp13db\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use Ud\Iqtp13db\Domain\Model\Gruppenberatung;
use Ud\Iqtp13db\Domain\Model\Teilnehmer;

/**
 * GruppenberatungRepository
 */
class GruppenberatungRepository extends Repository
{
    /**
     * Findet alle Gruppenberatungen mit verfügbaren Plätzen
     *
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAvailable()
    {
        $query = $this->createQuery();
        
        return $query->matching(
            $query->logicalOr(
                $query->equals('maxTeilnehmer', 0), // Unbegrenzte Teilnehmerzahl
                $query->lessThan('teilnehmer', $query->statement('max_teilnehmer'))
            )
        )->execute();
    }

    /**
     * Findet Gruppenberatungen nach Datum
     *
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByDateRange(\DateTime $startDate, \DateTime $endDate)
    {
        $query = $this->createQuery();
        
        return $query->matching(
            $query->logicalAnd(
                $query->greaterThanOrEqual('datum', $startDate),
                $query->lessThanOrEqual('datum', $endDate)
            )
        )->setOrderings(['datum' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING])
        ->execute();
    }

    /**
     * Findet alle Gruppenberatungen eines bestimmten Teilnehmers
     *
     * @param Teilnehmer $teilnehmer
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByTeilnehmer(Teilnehmer $teilnehmer)
    {
        $query = $this->createQuery();
        
        return $query->matching(
            $query->contains('teilnehmer', $teilnehmer)
        )->execute();
    }

    /**
     * Findet kommende Gruppenberatungen
     *
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findUpcoming()
    {
        $query = $this->createQuery();
        
        return $query->matching(
            $query->greaterThan('datum', new \DateTime())
        )->setOrderings(['datum' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING])
        ->execute();
    }
}