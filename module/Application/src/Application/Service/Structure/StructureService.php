<?php

namespace Application\Service\Structure;

use Application\Entity\Db\Structure;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class StructureService
{
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * Les structures sont importées de Octopus du coup les créations, historisations et suppressions sont gérées par
     * le module d'import. Seul la mise à jour est utile car des informations sont mises à jour de façon interne dans
     * l'application (p.e. les gestionnaires, les descriptions, ...)
     */

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

    /** REQUETAGES ****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->addSelect('gestionnaire')->leftJoin('structure.gestionnaires', 'gestionnaire')
            ->addSelect('responsable')->leftJoin('structure.responsables', 'responsable')
            ->addSelect('type')->join('structure.type', 'type')
            ->addSelect('poste')->leftJoin('structure.postes', 'poste')
            //->addSelect('ficheposte')->leftJoin('poste.fichePoste', 'ficheposte')
            ->addSelect('mission')->leftJoin('structure.missions', 'mission')
            ->orderBy('structure.code')
            ->andWhere("structure.histo IS NULL")
        ;
        return $qb;
    }

    /**
     * @param bool $ouverte
     * @return Structure[]
     */
    public function getStructures($ouverte = true)
    {
        $qb = $this->createQueryBuilder();
        if ($ouverte) $qb = $qb->andWhere("structure.fermeture IS NULL");
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @param integer $id
     * @return Structure
     */
    public function getStructure($id)
    {
        if ($id === "" OR $id === null) return null;
        $qb = $this->createQueryBuilder()
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
            ->andWhere('structure.histo IS NULL')
            ->andWhere('structure.fermeture IS NULL')
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
     * @param User $user
     * @param bool $ouverte
     * @return Structure[]
     */
    public function getStructuresByGestionnaire($user, $ouverte = true)
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->join('structure.gestionnaires', 'gestionnaireSelection')
            ->addSelect('gestionnaire')->join('structure.gestionnaires', 'gestionnaire')
            ->andWhere('gestionnaireSelection.id = :userId')
            ->setParameter('userId', $user->getId())
            ->orderBy('structure.libelleCourt')
            ->andWhere("structure.histo IS NULL")
        ;
        if ($ouverte) $qb = $qb->andWhere("structure.fermeture IS NULL");

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param User $user
     * @param bool $ouverte
     * @return Structure[]
     */
    public function getStructuresByResponsable($user, $ouverte = true)
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->join('structure.responsables', 'responsableSelection')
            ->addSelect('responsable')->join('structure.responsables', 'responsable')
            ->andWhere('responsableSelection.id = :userId')
            ->setParameter('userId', $user->getId())
            ->orderBy('structure.libelleCourt')
            ->andWhere("structure.histo IS NULL")
        ;
        if ($ouverte) $qb = $qb->andWhere("structure.fermeture IS NULL");

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
            ->orderBy('structure.code')
            ->andWhere("structure.histo IS NULL")
            ->andWhere("structure.importationHistorisation IS NULL");
        if ($ouverte) $qb = $qb->andWhere("structure.fermeture IS NULL");
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @param Structure $structure
     * @return Structure[]
     */
    public function getStructuresFilles($structure) {
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

    /**
     * @param Structure $structure
     * @param User $user
     * @return boolean
     */
    public function isResponsable(Structure $structure, User $user)
    {
        if (array_search($user, $structure->getResponsables()) !== false) return true;
        if ($structure->getParent()) return $this->isResponsable($structure->getParent(), $user);
        return false;
    }

    /**
     * @param Structure $structure
     * @param bool $filles
     * @return User[]
     */
    public function getGestionnairesByStructure(Structure $structure, $filles = true) {
        $gestionnaires = [];
        $vue = [];

        $structures = [];
        $structures[] = $structure;
        $vue[$structure->getId()] = true;

        while (! empty($structures)) {
            $current = array_shift($structures);
            foreach ($current->getGestionnaires() as $gestionnaire) {
                $gestionnaires[$gestionnaire->getId()] = $gestionnaire;
            }
            foreach ($current->getResponsables() as $responsable) {
                $gestionnaires[$responsable->getId()] = $responsable;
            }
            if ($filles) {
                foreach ($current->getEnfants() as $enfant) {
                    if ($vue[$enfant->getId()] !== true) {
                        $structures[] = $enfant;
                        $vue[$enfant->getId()] = true;
                    }
                }
            }
        }
        return $gestionnaires;
    }

    /**
     * @param Structure[] $structures
     * @return array
     */
    public function formatStructureJSON(array $structures)
    {
        $result = [];
        foreach ($structures as $structure) {
            $result[] = array(
                'id'    => $structure->getId(),
                'label' => $structure->getLibelleLong(),
                'extra' => "<span class='badge' style='background-color: slategray;'>".$structure->getLibelleCourt()."</span>",
            );
        }
        usort($result, function($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }
}
