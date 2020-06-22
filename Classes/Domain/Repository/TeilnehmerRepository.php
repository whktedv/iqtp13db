<?php
namespace Ud\Iqtp13db\Domain\Repository;

/***
 *
 * This file is part of the "IQ TP13 Datenbank Anerkennungserstberatung NRW" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Uli Dohmen <edv@whkt.de>, WHKT
 *
 ***/

/**
 * The repository for Teilnehmers
 */
class TeilnehmerRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * Finds Teilnehmer by the specified name, ort and/or geburtsland
     *
     * @param string $name
     * @param string $ort
     * @param string $land
     * @return Tx_Extbase_Persistence_QueryResultInterface Teilnehmer
     */
    public function searchTeilnehmer($name, $ort, $land)
    {
        $name = $name == '' ? '%' : $name;
        $ort = $ort == '' ? '%' : $ort;
        $land = $land == '' ? '%' : $land;
        $query = $this->createQuery();
        $query->matching($query->logicalAnd($query->logicalOr($query->like('nachname', '%' . $name . '%'), $query->like('vorname', '%' . $name . '%')), $query->like('ort', $ort), $query->like('geburtsland', $land)));
        return $query->execute();
    }

    /**
     * @param $uid
     */
    public function findHiddenByUid($uid)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(TRUE);
        $query->getQuerySettings()->setEnableFieldsToBeIgnored(array('disabled', 'hidden'));
        return $query->matching($query->equals('uid', $uid))->execute()->getFirst();
    }

    public function findAllOrder4List()
    {
        $query = $this->createQuery();
        $query->matching($query->logicalAnd($query->logicalOr($query->greaterThan('verification_date', 0))));
        $query->setOrderings(array('anzBeratungen' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING, 'crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        $query = $query->execute();
        return $query;
    }
    
    public function count4status()
    {
        $query = $this->createQuery();
        $query->matching($query->logicalAnd($query->logicalOr($query->greaterThan('verification_date', 0))));
        $query = $query->execute();
        return count($query);
    }
            
    /**
     * Gets the Teilnehmer by verificationCode
     *
     * @param string $verificationCode
     * @return Tx_Extbase_Persistence_QueryResultInterface Teilnehmer
     */
    public function findByVerificationCode(String $verificationCode)
    {
        $query = $this->createQuery();
        
        $constraints = array(
            $query->equals('verification_code', $verificationCode),
            $query->equals('verification_date', 0)
        );
        
        $query->matching(
            $query->logicalAnd($constraints)
            );
        
        $result = $query->execute()->toArray();
        if(count($result) > 0) {
            return $result[0];
        } else {
            return NULL;
        }
    }
}
