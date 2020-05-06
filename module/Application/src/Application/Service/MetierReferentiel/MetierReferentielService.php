<?php

namespace Application\Service\MetierReferentiel;

use Application\Entity\Db\MetierReferentiel;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class MetierReferentielService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param MetierReferentiel $referentiel
     * @return MetierReferentiel
     */
    public function create(MetierReferentiel $referentiel)
    {
        $this->createFromTrait($referentiel);
        return $referentiel;
    }

    /**
     * @param MetierReferentiel $referentiel
     * @return MetierReferentiel
     */
    public function update(MetierReferentiel $referentiel)
    {
        $this->updateFromTrait($referentiel);
        return $referentiel;
    }

    /**
     * @param MetierReferentiel $referentiel
     * @return MetierReferentiel
     */
    public function historise(MetierReferentiel $referentiel)
    {
        $this->historiserFromTrait($referentiel);
        return $referentiel;
    }

    /**
     * @param MetierReferentiel $referentiel
     * @return MetierReferentiel
     */
    public function restore(MetierReferentiel $referentiel)
    {
        $this->restoreFromTrait($referentiel);
        return $referentiel;
    }

    /**
     * @param MetierReferentiel $referentiel
     * @return MetierReferentiel
     */
    public function delete(MetierReferentiel $referentiel)
    {
        $this->deleteFromTrait($referentiel);
        return $referentiel;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(MetierReferentiel::class)->createQueryBuilder('referentiel')
            ;

        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return MetierReferentiel[]
     */
    public function getMetiersReferentiels($champ = 'libelleCourt', $ordre = 'ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('referentiel.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getMetiersReferentielsAsOptions()
    {
        $referentiels = $this->getMetiersReferentiels();
        $array = [];

        foreach ($referentiels as $referentiel) {
            $array[$referentiel->getId()] = $referentiel->getLibelleCourt() . " - " . $referentiel->getLibelleLong();
        }

        return $array;
    }

    /**
     * @param integer $id
     * @return MetierReferentiel
     */
    public function getMetierReferentiel($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('referentiel.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs MetierReferentiel partagent le même id [".$id."]",0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return MetierReferentiel
     */
    public function getRequestedMetierReferentiel($controller, $param = "referentiel")
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getMetierReferentiel($id);

        return $result;
    }

}