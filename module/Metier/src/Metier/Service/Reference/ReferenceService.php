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

    public function update(Reference $referentiel) : Reference
    {
        try {
            $this->getEntityManager()->flush($referentiel);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $referentiel;
    }

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

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Reference::class)->createQueryBuilder('reference')
            ->addSelect('metier')->join('reference.metier', 'metier')
            ->addSelect('categorie')->leftJoin('metier.categorie', 'categorie')
            ->addSelect('referentiel')->join('reference.referentiel', 'referentiel');

        return $qb;
    }

    /** @return Reference[] */
    public function getReferences(string $champ = 'code', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('reference.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getReference(?int $id) : ?Reference
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

    public function getRequestedReference(AbstractActionController $controller, string $param = "reference") : ?Reference
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getReference($id);

        return $result;
    }

}