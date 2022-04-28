<?php

namespace Structure\Service\Structure;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAffectation;
use Application\Entity\Db\FichePoste;
use DateTime;
use Doctrine\DBAL\Driver\Exception as DRV_Exception;
use Doctrine\DBAL\Exception as DBA_Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Structure\Entity\Db\Structure;
use Structure\Entity\Db\StructureGestionnaire;
use Structure\Entity\Db\StructureResponsable;
use Structure\Provider\RoleProvider;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class StructureService
{
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Structure
     * @return Structure
     */
    public function update($structure) : Structure
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
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->addSelect('gestionnaire')->leftJoin('structure.gestionnaires', 'gestionnaire')
            ->addSelect('responsable')->leftJoin('structure.responsables', 'responsable')
            ->addSelect('type')->join('structure.type', 'type')
            ->andWhere('structure.deleted_on IS NULL')
            ->orderBy('structure.code')
        ;
        return $qb;
    }

    /**
     * @param bool $ouverte
     * @return Structure[]
     */
    public function getStructures(bool $ouverte = true) : array
    {
        $qb = $this->createQueryBuilder();
        if ($ouverte) $qb = $qb->andWhere("structure.fermeture IS NULL");
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @param string|null $id
     * @return Structure|null
     */
    public function getStructure(?string $id) : ?Structure
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
     * @return Structure|null
     */
    public function getRequestedStructure(AbstractActionController $controller, string $paramName = 'structure') : ?Structure
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
    public function getStructuresByTerm(string $term, array $structures = null) : array
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->andWhere('LOWER(structure.libelleLong) like :search OR LOWER(structure.libelleCourt) like :search')
            ->setParameter('search', '%'.strtolower($term).'%')
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
     * @param Structure $structure
     * @param bool $ouverte
     * @return Structure[]
     */
    public function getSousStructures(Structure $structure, bool $ouverte = true) : array
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->andWhere('structure.parent = :structure')
            ->setParameter('structure', $structure)
            ->orderBy('structure.code')
            ->andWhere("structure.deleted_on IS NULL");
        if ($ouverte) $qb = $qb->andWhere("structure.fermeture IS NULL");
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @param Structure $structure
     * @return Structure[]
     */
    public function getStructuresFilles(Structure $structure) : array
    {
        $filles = [];
        $dejaTraitees = [];

        $aTraitees = [];
        $aTraitees[] = $structure;

        while(! empty($aTraitees)) {
            $current = array_shift($aTraitees);
            $result = $this->getSousStructures($current);
            foreach ($result as $item) {
                if (!$item->isDeleted() AND !isset($dejaTraitees[$item->getId()])) {
                    $filles[] = $item;
                    $dejaTraitees[$item->getId()] = true;
                    $aTraitees[] = $item;
                }
            }
        }

        return $filles;
    }


    /**
     * @param Structure[] $structures
     * @return array
     */
    public function formatStructureJSON(array $structures) : array
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

    /**
     * @param Structure[] $structures
     * @return FichePoste[]
     */
    public function getFichesPostesRecrutementsByStructures(array $structures) : array
    {
        $fiches = [];
        foreach ($structures as $structure) {
            $fps = $structure->getFichesPostesRecrutements();
            foreach ($fps as $fp) $fiches[$fp->getId()] = $fp;
        }

        $result = [];
        foreach ($fiches as $fiche) {
            $result[] = ['id' => $fiche->getId(),
                'agent_id' => ($fiche->getAgent())?$fiche->getAgent()->getId():null,
                'prenom' => ($fiche->getAgent())?$fiche->getAgent()->getPrenom():null,
                'nom_usage' => ($fiche->getAgent())?$fiche->getAgent()->getNomUsuel():null,
                'fiche_principale' => ($fiche->getFicheTypeExternePrincipale())?$fiche->getFicheTypeExternePrincipale()->getFicheType()->getMetier()->getLibelle():null,
            ];
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getStructuresAsOptions() : array
    {
        $sql = <<<EOS
select
    s.id as ID,
    s.libelle_court as LIBELLE_COURT,
    s.libelle_long as LIBELLE_LONG,
    st.libelle AS TYPE
from structure s
left join structure_type st on st.id = s.type_id
where s.fermeture IS NULL
EOS;

        $tmp = null;
        try {
            $res = $this->getEntityManager()->getConnection()->executeQuery($sql, []);
            try {
                $tmp = $res->fetchAllAssociative();
            } catch (DRV_Exception $e) {
                throw new RuntimeException("Un problème est survenue lors de la récupération des fonctions d'un groupe d'individus", 0, $e);
            }
        } catch (DBA_Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des fonctions d'un groupe d'individus", 0, $e);
        }

        $options = [];
        foreach ($tmp as $structure) {
            $options[$structure['id']] = "[".$structure['type']."] ".$structure['libelle_long'] . " (".$structure['libelle_court'].")";
        }
        return $options;
    }

    /**
     * @return User[]
     */
    public function getUsersInResponsables() : array
    {
        $qb = $this->getEntityManager()->getRepository(StructureResponsable::class)->createQueryBuilder("sr")
            ->join('sr.agent', 'agent')->addSelect('agent')
            ->orderBy('agent.nomUsuel, agent.prenom' , 'ASC')
        ;
        $result = $qb->getQuery()->getResult();

        /** @var StructureResponsable[] $result */
        $users = [];
        foreach ($result as $responsable) {
            $user = $responsable->getAgent()->getUtilisateur();
            if ($user !== null) $users[$user->getId()] = $user;
        }
        return $users;
    }

    /**
     * @return User[]
     */
    public function getUsersInGestionnaires() : array
    {
        $qb = $this->getEntityManager()->getRepository(StructureGestionnaire::class)->createQueryBuilder("sg")
            ->join('sg.agent', 'agent')->addSelect('agent')
            ->orderBy('agent.nomUsuel, agent.prenom' , 'ASC')
        ;
        $result = $qb->getQuery()->getResult();

        /** @var StructureGestionnaire[] $result */
        $users = [];
        foreach ($result as $responsable) {
            $user = $responsable->getAgent()->getUtilisateur();
            if ($user !== null) $users[$user->getId()] = $user;
        }
        return $users;
    }

    /**
     * @param User $user
     * @param bool $ouverte
     * @return Structure[]
     */
    public function getStructuresByGestionnaire(User $user,  bool $ouverte = true) : array
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->join('structure.gestionnaires', 'gestionnaireSelection')
            ->addSelect('gestionnaire')->join('structure.gestionnaires', 'gestionnaire')
            ->addSelect('agent')->join('gestionnaire.agent', 'agent')
            ->andWhere('agent.utilisateur = :user')
            ->setParameter('user', $user)
            ->orderBy('structure.libelleCourt')
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
    public function getStructuresByResponsable(User $user, bool $ouverte = true) : array
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->join('structure.responsables', 'responsableSelection')
            ->addSelect('responsable')->join('structure.responsables', 'responsable')
            ->addSelect('agent')->join('responsable.agent', 'agent')
            ->andWhere('agent.utilisateur = :user')
            ->setParameter('user', $user)
            ->orderBy('structure.libelleCourt')
        ;
        if ($ouverte) $qb = $qb->andWhere("structure.fermeture IS NULL");

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure $structure
     * @param Agent|null $agent
     * @return boolean
     */
    public function isGestionnaire(Structure $structure, ?Agent $agent) : bool
    {
        if ($agent === null) return false;
        $date = (new DateTime());

        $gestionnaires = $structure->getGestionnaires();
        foreach ($gestionnaires as $gestionnaire) {
            if (    ($gestionnaire->getAgent() === $agent)
                AND ($gestionnaire->getDateDebut() === NULL OR $gestionnaire->getDateDebut() <= $date)
                AND ($gestionnaire->getDateFin() === NULL OR $gestionnaire->getDateFin() >= $date)
                AND (!$gestionnaire->isImported() OR !$gestionnaire->isDeleted())
            ) return true;
        }
        if ($structure->getParent()) return $this->isGestionnaire($structure->getParent(), $agent);
        return false;
    }

    /**
     * @param Structure[] $structures
     * @param Agent|null $agent
     * @return boolean
     */
    public function isGestionnaireS(array $structures, ?Agent $agent)  : bool
    {
        foreach ($structures as $structure) {
            $result = $this->isGestionnaire($structure, $agent);
            if ($result) return true;
        }
        return false;
    }

    /**
     * @param Structure $structure
     * @param Agent|null $agent
     * @return boolean
     */
    public function isResponsable(Structure $structure, ?Agent $agent)  : bool
    {
        if ($agent === null) return false;
        $date = (new DateTime());

        $responsables = $structure->getResponsables();
        foreach ($responsables as $responsable) {
            if (    ($responsable->getAgent() === $agent)
                AND ($responsable->getDateDebut() === NULL OR $responsable->getDateDebut() <= $date)
                AND ($responsable->getDateFin() === NULL OR $responsable->getDateFin() >= $date)
                AND (!$responsable->isImported() OR !$responsable->isDeleted())
            ) return true;
        }
        if ($structure->getParent()) return $this->isResponsable($structure->getParent(), $agent);
        return false;
    }

    /**
     * @param Structure[] $structures
     * @param Agent|null $agent
     * @return boolean
     */
    public function isResponsableS(array $structures, ?Agent $agent)  : bool
    {
        foreach ($structures as $structure) {
            $result = $this->isResponsable($structure, $agent);
            if ($result) return true;
        }
        return false;
    }

    /**
     * @param Structure $structure
     * @param Agent|null $agent
     * @return bool
     */
    public function isAutorite(Structure $structure, ?Agent $agent) : bool
    {
        if ($agent === null) return false;
        $date = (new DateTime());
        $structure = $structure->getNiv2();

        if ($structure === null) return false;

        $responsables = $structure->getResponsables();
        foreach ($responsables as $responsable) {
            if (    ($responsable->getAgent() === $agent)
                AND ($responsable->getDateDebut() === NULL OR $responsable->getDateDebut() <= $date)
                AND ($responsable->getDateFin() === NULL OR $responsable->getDateFin() >= $date)
                AND (!$responsable->isImported() OR !$responsable->isDeleted())
            ) return true;
        }
        return false;
    }

    /**
     * @param Structure[] $structures
     * @param Agent|null $agent
     * @return boolean
     */
    public function isAutoriteS(array $structures, ?Agent $agent)  : bool
    {
        foreach ($structures as $structure) {
            $result = $this->isAutorite($structure, $agent);
            if ($result) return true;
        }
        return false;
    }

    /**
     * @param Structure $structure
     * @param bool $filles
     * @return User[]
     */
    public function getGestionnairesByStructure(Structure $structure, bool $filles = true) : array
    {
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
     * @return Structure[]
     */
    public function getStructuresByCurrentRole(User $user, Role $role) : array
    {
        $selecteur = [];
        if ($role->getRoleId() === RoleProvider::GESTIONNAIRE) {
            $structures = $this->getStructuresByGestionnaire($user);
            usort($structures, function(Structure $a, Structure $b) {return $a->getLibelleCourt() > $b->getLibelleCourt();});
            $selecteur = $structures;
        }
        if ($role->getRoleId() === RoleProvider::RESPONSABLE) {
            $structures = $this->getStructuresByResponsable($user);
            usort($structures, function(Structure $a, Structure $b) {return $a->getLibelleCourt() > $b->getLibelleCourt();});
            $selecteur = $structures;
        }

        return $selecteur;
    }

    /** Fonctions pour l'organigramme *********************************************************************************/

    public function getAgentsEnAffectationSecondaire(Structure $structure, ?DateTime $date = null) : array
    {
        if ($date === null) $date = new DateTime();
        $qb = $this->getEntityManager()->getRepository(AgentAffectation::class)->createQueryBuilder('affectation')
//            ->join('affectation.agent', 'agent')->addSelect('agent')
            // affecation en cours non principale dans la structure
            ->andWhere('affectation.structure = :structure')
            ->setParameter('structure', $structure)
            ->andWhere('affectation.principale IS NULL OR affectation.principale = :N')
            ->setParameter('N', 'N')
            ->andWhere('affectation.dateDebut <= :currentdate')
            ->andWhere('affectation.dateFin IS NULL OR affectation.dateFin >= :currentdate')
            ->andWhere('affectation.deleted_on IS NULL')
            ->setParameter('currentdate', $date)
//            ->orderBy('agent.nomUsuel, agent.prenom')
        ;

        /** @var AgentAffectation[] $result */
        $result = $qb->getQuery()->getResult();

        $listing = [];
        foreach ($result as $affectation) {
            $agent = $affectation->getAgent();
            $affectationPrincipale = $agent->getAffectationPrincipale($date);
            if ($affectationPrincipale) {
                $structurePrincipale = $affectationPrincipale->getStructure();
                $listing[$structurePrincipale->getLibelleLong()][] = $agent->getDenomination();
            } else {
                $listing["ZZZ"][] = $agent->getDenomination();
            }
        }
        return $listing;
    }
}
