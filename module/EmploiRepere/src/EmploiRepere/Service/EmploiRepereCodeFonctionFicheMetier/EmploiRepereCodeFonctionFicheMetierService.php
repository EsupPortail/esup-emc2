<?php

namespace EmploiRepere\Service\EmploiRepereCodeFonctionFicheMetier;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use EmploiRepere\Entity\Db\EmploiRepereCodeFonctionFicheMetier;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class EmploiRepereCodeFonctionFicheMetierService {
    use ProvidesObjectManager;

    /** GESTION DES ENTITY ***************************************************************/

    public function create(EmploiRepereCodeFonctionFicheMetier $emploiRepereCodeFonctionFicheMetier): void
    {
        $this->getObjectManager()->persist($emploiRepereCodeFonctionFicheMetier);
        $this->getObjectManager()->flush($emploiRepereCodeFonctionFicheMetier);
    }

    public function update(EmploiRepereCodeFonctionFicheMetier $emploiRepereCodeFonctionFicheMetier): void
    {
        $this->getObjectManager()->flush($emploiRepereCodeFonctionFicheMetier);
    }

    public function delete(EmploiRepereCodeFonctionFicheMetier $emploiRepereCodeFonctionFicheMetier): void
    {
        $this->getObjectManager()->remove($emploiRepereCodeFonctionFicheMetier);
        $this->getObjectManager()->flush($emploiRepereCodeFonctionFicheMetier);
    }

    /** QUERRYING *************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->getObjectManager()->getRepository(EmploiRepereCodeFonctionFicheMetier::class)->createQueryBuilder('ercffm')
            ->leftJoin('ercffm.emploiRepere', 'emploiRepere')->addSelect('emploiRepere')
            ->leftJoin('ercffm.codeFonction', 'codeFonction')->addSelect('codeFonction')
            ->leftJoin('ercffm.ficheMetier', 'ficheMetier')->addSelect('ficheMetier')
        ;
        return $queryBuilder;
    }

    public function getEmploiRepereCodeFonctionFicheMetier(?int $id): ?EmploiRepereCodeFonctionFicheMetier
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('ercffm.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".EmploiRepereCodeFonctionFicheMetier::class."] partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getRequestedEmploiRepereCodeFonctionFicheMetier(AbstractActionController $controller, string $param = "emploi-repere-code-fonction-fiche-metier"): ?EmploiRepereCodeFonctionFicheMetier
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getEmploiRepereCodeFonctionFicheMetier($id);
    }

    /** FACADE ****************************************************************************/


}
