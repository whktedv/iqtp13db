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
     * Findet alle Gruppenberatungen
     *
     * @param $orderby
     * @param $order
     * @param $niqbid
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAllOrder4List($orderby, $order, $niqbid)
    {
        $query = $this->createQuery();
        
        $query->matching(
               $query->like('niqbid', $niqbid)
        );
        
        if($order == 'DESC') $order = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING;
        else $order = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING;
        
        $query->setOrderings([ $orderby => $order ]);
        
        $query = $query->execute();
        return $query;
    }  
    
    /**
     * Findet alle Gruppenberatungen mit verfügbaren Plätzen
     *
     * @param $niqbid
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAvailable($niqbid)
    {
        $query = $this->createQuery();
        
        return $query->matching(
                $query->like('niqbid', $niqbid)
        )->execute();
    }

    /**
     * Findet Gruppenberatungen nach Datum
     *
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param $niqbid
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByDateRange(\DateTime $startDate, \DateTime $endDate, $niqbid)
    {
        $query = $this->createQuery();
        
        return $query->matching(
            $query->logicalAnd(
                $query->greaterThanOrEqual('datum', $startDate),
                $query->lessThanOrEqual('datum', $endDate),
                $query->like('niqbid', $niqbid)
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
     * @param $niqbid
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findUpcoming($niqbid)
    {
        $query = $this->createQuery();
        
        return $query->matching(
            $query->logicalAnd(
                $query->greaterThan('datum', new \DateTime()),
                $query->like('niqbid', $niqbid)
            )
        )->setOrderings(['datum' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING])
        ->execute();
    }
}