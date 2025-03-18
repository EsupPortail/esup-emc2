<?php

namespace Metier\Service\Reference;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Metier\Entity\Db\Reference;
use Metier\Entity\Db\Referentiel;
use UnicaenApp\Exception\RuntimeException;

class ReferenceService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(Reference $referentiel): Reference
    {
        $this->getObjectManager()->persist($referentiel);
        $this->getObjectManager()->flush($referentiel);
        return $referentiel;
    }

    public function update(Reference $referentiel): Reference
    {
        $this->getObjectManager()->flush($referentiel);
        return $referentiel;
    }

    public function historise(Reference $referentiel): Reference
    {
        $referentiel->historiser();
        $this->getObjectManager()->flush($referentiel);
        return $referentiel;
    }

    public function restore(Reference $referentiel): Reference
    {
        $referentiel->dehistoriser();
        $this->getObjectManager()->flush($referentiel);
        return $referentiel;
    }

    public function delete(Reference $referentiel): Reference
    {
        $this->getObjectManager()->remove($referentiel);
        $this->getObjectManager()->flush($referentiel);
        return $referentiel;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Reference::class)->createQueryBuilder('reference')
            ->addSelect('metier')->join('reference.metier', 'metier')
            ->addSelect('categorie')->leftJoin('metier.categorie', 'categorie')
            ->addSelect('referentiel')->join('reference.referentiel', 'referentiel');

        return $qb;
    }

    /** @return Reference[] */
    public function getReferences(string $champ = 'code', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('reference.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }


    /** @return Reference[] */
    public function getReferencesByReferentiel(?Referentiel $referentiel, string $champ = 'code', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('reference.referentiel = :referentiel')->setParameter('referentiel', $referentiel)
            ->orderBy('reference.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }


    public function getReference(?int $id): ?Reference
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

    public function getRequestedReference(AbstractActionController $controller, string $param = "reference"): ?Reference
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getReference($id);

        return $result;
    }

}