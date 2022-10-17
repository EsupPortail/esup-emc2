<?php

namespace Metier\Service\Reference;

use Doctrine\ORM\ORMException;
use Metier\Entity\Db\Reference;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class ReferenceService
{
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Reference $referentiel
     * @return Reference
     */
    public function create(Reference $referentiel) : Reference
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
     * @param Reference $referentiel
     * @return Reference
     */
    public function update(Reference $referentiel) : Reference
    {
        try {
            $this->getEntityManager()->flush($referentiel);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $referentiel;
    }

    /**
     * @param Reference $referentiel
     * @return Reference
     */
    public function historise(Reference $referentiel) : Reference
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
     * @param Reference $referentiel
     * @return Reference
     */
    public function restore(Reference $referentiel) : Reference
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
     * @param Reference $referentiel
     * @return Reference
     */
    public function delete(Reference $referentiel) : Reference
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
        $qb = $this->getEntityManager()->getRepository(Reference::class)->createQueryBuilder('reference')
            ->addSelect('metier')->join('reference.metier', 'metier')
            ->addSelect('categorie')->leftJoin('metier.categorie', 'categorie')
            ->addSelect('referentiel')->join('reference.referentiel', 'referentiel');

        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Reference[]
     */
    public function getReferences(string $champ = 'code', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('reference.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getReferencesAsOptions() : array
    {
        $references = $this->getReferences();
        $array = [];

        foreach ($references as $reference) {
            $array[$reference->getId()] = $reference->getReferentiel()->getLibelleCourt() . " - " . $reference->getCode();
        }

        return $array;
    }

    /**
     * @param integer $id
     * @return Reference
     */
    public function getReference(int $id) : Reference
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('reference.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Reference partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Reference
     */
    public function getRequestedReference(AbstractActionController $controller, string $param = "reference") : Reference
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getReference($id);

        return $result;
    }

}