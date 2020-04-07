<?php

namespace Application\Service\Poste;

use Application\Entity\Db\Poste;
use Application\Entity\Db\Structure;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class PosteService {
    use EntityManagerAwareTrait;

    /**
     *
     * @param string $champ
     * @param string $ordre
     * @return QueryBuilder
     */
    public function createQueryBuilder($champ = 'id', $ordre = 'ASC') {
        $qb = $this->getEntityManager()->getRepository(Poste::class)->createQueryBuilder('poste')
            ->addSelect('structure')->join('poste.structure', 'structure')
            ->addSelect('correspondance')->join('poste.correspondance', 'correspondance')
            ->addSelect('responsable')->join('poste.rattachementHierarchique', 'responsable')
            ->addSelect('domaine')->join('poste.domaine', 'domaine')
            ->addSelect('fiche')->leftJoin('poste.fichePoste','fiche')
            ->orderBy('poste.' . $champ, $ordre)
        ;

        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Poste[]
     */
    public function getPostes($champ = 'id', $ordre = 'ASC') {
        $qb = $this->createQueryBuilder($champ = 'id', $ordre = 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Poste[]
     */
    public function getPostesLibres($champ = 'id', $ordre = 'ASC') {
        $qb = $this->createQueryBuilder()
            ->andWhere('fiche IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }


    /**
     * @param integer $id
     * @return Poste
     */
    public function getPoste($id) {
        $qb = $this->createQueryBuilder()
            ->andWhere('poste.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs postes partagent le même identifiant [".$id."].",$e);
        }
        return $result;
    }

    /**
     * @param Poste $poste
     * @return Poste
     */
    public function create($poste) {
        $this->getEntityManager()->persist($poste);
        try {
            $this->getEntityManager()->flush($poste);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Problème lors de la création en base du Poste.",$e);
        }
        return $poste;
    }

    /**
     * @param Poste $poste
     * @return Poste
     */
    public function update($poste) {
        try {
            $this->getEntityManager()->flush($poste);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Problème lors de la mise à jour en base du Poste.",$e);
        }
        return $poste;
    }

    public function delete($poste) {
        $this->getEntityManager()->remove($poste);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Problème lors de la suppression en base du Poste.",$e);
        }
    }

    /**
     * @param Structure $structure
     * @param bool $sousstructure
     * @param bool $libre
     * @return  Poste[]
     */
    public function getPostesByStructure($structure = null, $sousstructure = false, $libre = false)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder();
        if ($libre === true) {
            $qb = $qb->andWhere('fiche IS NULL');
        }

        if ($structure !== null AND $sousstructure === false) {
            $qb = $qb->andWhere('poste.structure = :structure')
                ->setParameter('structure', $structure);
        }
        if ($structure !== null AND $sousstructure === true) {
            $structures = $this->getStructureService()->getStructuresFilles($structure);
            $structures[] = $structure;

            $qb = $qb->andWhere('grade.structure IN (:structures)')
                ->setParameter('structures', $structures);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure[] $structures
     * @param bool $libre
     * @return  Poste[]
     */
    public function getPostesByStructures($structures = [],  $libre = false)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder()
                ->andWhere('poste.structure IN (:structures)')
                ->setParameter('structures', $structures);

        if ($libre === true) $qb = $qb->andWhere('fiche IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

}