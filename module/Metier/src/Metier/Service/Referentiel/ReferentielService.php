<?php

namespace Metier\Service\Referentiel;

use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Metier\Entity\Db\Referentiel;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class ReferentielService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Referentiel $referentiel
     * @return Referentiel
     */
    public function create(Referentiel $referentiel) : Referentiel
    {
        $this->createFromTrait($referentiel);
        return $referentiel;
    }

    /**
     * @param Referentiel $referentiel
     * @return Referentiel
     */
    public function update(Referentiel $referentiel) : Referentiel
    {
        $this->updateFromTrait($referentiel);
        return $referentiel;
    }

    /**
     * @param Referentiel $referentiel
     * @return Referentiel
     */
    public function historise(Referentiel $referentiel) : Referentiel
    {
        $this->historiserFromTrait($referentiel);
        return $referentiel;
    }

    /**
     * @param Referentiel $referentiel
     * @return Referentiel
     */
    public function restore(Referentiel $referentiel) : Referentiel
    {
        $this->restoreFromTrait($referentiel);
        return $referentiel;
    }

    /**
     * @param Referentiel $referentiel
     * @return Referentiel
     */
    public function delete(Referentiel $referentiel) : Referentiel
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
        $qb = $this->getEntityManager()->getRepository(Referentiel::class)->createQueryBuilder('referentiel')
            ;

        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Referentiel[]
     */
    public function getReferentiels($champ = 'libelleCourt', $ordre = 'ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('referentiel.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getReferentielsAsOptions()
    {
        $referentiels = $this->getReferentiels();
        $array = [];

        foreach ($referentiels as $referentiel) {
            $array[$referentiel->getId()] = $referentiel->getLibelleCourt() . " - " . $referentiel->getLibelleLong();
        }

        return $array;
    }

    /**
     * @param int|null $id
     * @return Referentiel
     */
    public function getReferentiel(?int $id)
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
     * @return Referentiel
     */
    public function getRequestedReferentiel(AbstractActionController $controller, $param = "referentiel")
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getReferentiel($id);

        return $result;
    }

}