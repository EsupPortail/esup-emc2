<?php

namespace Application\Service\Structure;

use Application\Entity\Db\Structure;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class StructureService
{
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param bool $ouverte
     * @return Structure[]
     */
    public function getStructures($ouverte = true)
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->addSelect('gestionnaire')->leftJoin('structure.gestionnaires', 'gestionnaire')
            ->addSelect('poste')->leftJoin('structure.postes', 'poste')
            ->addSelect('mission')->leftJoin('structure.missions', 'mission')
            ->orderBy('structure.code');
        if ($ouverte) $qb = $qb->andWhere("structure.histo = 'O'");
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @param integer $id
     * @return Structure
     */
    public function getStructure($id)
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->addSelect('gestionnaire')->leftJoin('structure.gestionnaires', 'gestionnaire')
            ->andWhere('structure.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Structure partagent le même identifiant [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Structure
     */
    public function getRequestedStructure($controller, $paramName = 'structure')
    {
        $id = $controller->params()->fromRoute($paramName);
        $structure = $this->getStructure($id);
        return $structure;
    }

    /**
     * @param string $term
     * @param Structure[] $structures
     * @return Structure[]
     */
    public function getStructuresByTerm($term, $structures = null)
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->andWhere('LOWER(structure.libelleLong) like :search OR LOWER(structure.libelleCourt) like :search')
            ->setParameter('search', '%'.strtolower($term).'%')
            ->andWhere('structure.histo = :nope')
            ->setParameter('nope', 'O')
        ;

        if ($structures !== null) {
            $qb = $qb->andWhere('structure IN (:structures)')
                ->setParameter('structures', $structures)
            ;
        }
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param bool $ouverte
     * @return array
     */
    public function getStructuresAsOptions($ouverte = true)
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->orderBy('structure.libelleLong')
        ;
        if ($ouverte) $qb = $qb->andWhere("structure.histo = 'O'");

        $result = $qb->getQuery()->getResult();

        $options = [];
        /** @var Structure $item */
        foreach ($result as $item) {
            if ($item->getId() !== null) $options[$item->getId()] = $item->getLibelleLong();
        }
        return $options;
    }

    /**
     * @param bool $ouverte
     * @return array
     */
    public function getStructuresAsGroupOptions($ouverte = true)
    {
        $structures = $this->getStructures($ouverte);

        $dictionnary = [];
        foreach ($structures as $structure) {
            $dictionnary[$structure->getType()][] = $structure;
        }

        $options = [];
        foreach ($dictionnary as $type => $structuresStored) {
            $optionsoptions = [];
            foreach ($structuresStored as $structure) {
                $optionsoptions[$structure->getId()] = $structure->getLibelleCourt();
            }
            asort($optionsoptions);
            $array = [
                'label' => $type,
                'options' => $optionsoptions,
            ];
            $options[] = $array;
        }
        return $options;
    }

    /**
     * @param Structure $structure
     * @param User $gestionnaire
     * @return Structure
     */
    public function addGestionnaire($structure, $gestionnaire)
    {
        $structure->addGestionnaire($gestionnaire);
        try {
            $this->getEntityManager()->flush($structure);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'inscription en base.", $e);
        }
        return $structure;
    }

    /**
     * @param Structure $structure
     * @param User $gestionnaire
     * @return Structure
     */
    public function removeGestionnaire($structure, $gestionnaire)
    {
        $structure->removeGestionnaire($gestionnaire);
        try {
            $this->getEntityManager()->flush($structure);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'inscription en base.", $e);
        }
        return $structure;
    }

    /**
     * @param Structure
     * @return Structure
     */
    public function update($structure)
    {
        try {
            $this->getEntityManager()->flush($structure);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'inscription en base.", $e);
        }
        return $structure;
    }

    /**
     * @param User $user
     * @return Structure[]
     */
    public function getStructuresByGestionnaire($user)
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->join('structure.gestionnaires', 'gestionnaireSelection')
            ->addSelect('gestionnaire')->join('structure.gestionnaires', 'gestionnaire')
            ->andWhere('gestionnaireSelection.id = :userId')
            ->setParameter('userId', $user->getId())
            ->orderBy('structure.libelleCourt')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure $structure
     * @param boolean $ouverte
     * @return Structure[]
     */
    public function getSousStructures($structure, $ouverte = true)
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
//            ->addSelect('gestionnaire')->leftJoin('structure.gestionnaires', 'gestionnaire')
//            ->addSelect('poste')->leftJoin('structure.postes', 'poste')
//            ->addSelect('mission')->leftJoin('structure.missions', 'mission')
            ->andWhere('structure.parent = :structure')
            ->setParameter('structure', $structure)
            ->orderBy('structure.code');
        if ($ouverte) $qb = $qb->andWhere("structure.histo = 'O'");
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @param Structure $structure
     * @return Structure[]
     */
    public function getStructuresFilles(Structure $structure) {
        $filles = [];
        $dejaTraitees = [];

        $aTraitees = [];
        $aTraitees[] = $structure;

        while(! empty($aTraitees)) {
            $current = array_shift($aTraitees);
            $result = $this->getSousStructures($current);
            foreach ($result as $item) {
                if (! isset($dejaTraitees[$item->getId()])) {
                    $filles[] = $item;
                    $dejaTraitees[$item->getId()] = true;
                    $aTraitees[] = $item;
                }
            }
        }

        return $filles;
    }

    /**
     * @param Structure $structure
     * @param User $user
     * @return boolean
     */
    public function isGestionnaire(Structure $structure, User $user)
    {
        if (array_search($user, $structure->getGestionnaires()) !== false) return true;
        if ($structure->getParent()) return $this->isGestionnaire($structure->getParent(), $user);
        return false;
    }
}
