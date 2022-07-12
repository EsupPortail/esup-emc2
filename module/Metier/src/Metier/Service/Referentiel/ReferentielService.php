<?php

namespace Metier\Service\Referentiel;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Metier\Entity\Db\Referentiel;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class ReferentielService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Referentiel $referentiel
     * @return Referentiel
     */
    public function create(Referentiel $referentiel) : Referentiel
    {
        try {
            $this->getEntityManager()->persist($referentiel);
            $this->getEntityManager()->flush($referentiel);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $referentiel;
    }

    /**
     * @param Referentiel $referentiel
     * @return Referentiel
     */
    public function update(Referentiel $referentiel) : Referentiel
    {
        try {
            $this->getEntityManager()->flush($referentiel);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $referentiel;
    }

    /**
     * @param Referentiel $referentiel
     * @return Referentiel
     */
    public function historise(Referentiel $referentiel) : Referentiel
    {
        try {
            $referentiel->historiser();
            $this->getEntityManager()->flush($referentiel);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $referentiel;
    }

    /**
     * @param Referentiel $referentiel
     * @return Referentiel
     */
    public function restore(Referentiel $referentiel) : Referentiel
    {
        try {
            $referentiel->dehistoriser();
            $this->getEntityManager()->flush($referentiel);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $referentiel;
    }

    /**
     * @param Referentiel $referentiel
     * @return Referentiel
     */
    public function delete(Referentiel $referentiel) : Referentiel
    {
        try {
            $this->getEntityManager()->remove($referentiel);
            $this->getEntityManager()->flush($referentiel);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $referentiel;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
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
    public function getReferentiels(string $champ = 'libelleCourt', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('referentiel.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getReferentielsAsOptions() : array
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
     * @return Referentiel|null
     */
    public function getReferentiel(?int $id) : ?Referentiel
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
     * @return Referentiel|null
     */
    public function getRequestedReferentiel(AbstractActionController $controller, string $param = "referentiel") : ?Referentiel
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getReferentiel($id);

        return $result;
    }

}