<?php

namespace Structure\Service\Structure;

use Application\Entity\Db\Agent;
use Agent\Entity\Db\AgentAffectation;
use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Application\Entity\Db\FichePoste;
use Application\Provider\Parametre\GlobalParametres;
use DateTime;
use Doctrine\DBAL\Driver\Exception as DRV_Exception;
use Doctrine\DBAL\Exception as DBA_Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Permissions\Acl\Role\RoleInterface;
use Structure\Entity\Db\Structure;
use Structure\Entity\Db\StructureAgentForce;
use Structure\Entity\Db\StructureGestionnaire;
use Structure\Entity\Db\StructureResponsable;
use Structure\Provider\Parametre\StructureParametres;
use Structure\Provider\Role\RoleProvider;
use UnicaenApp\Exception\RuntimeException;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class StructureService
{
    use ProvidesObjectManager;
    use UserServiceAwareTrait;
    use ParametreServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function update(Structure $structure): Structure
    {
        $this->getObjectManager()->flush($structure);
        return $structure;
    }

    /** REQUETAGES ****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->addSelect('gestionnaire')->leftJoin('structure.gestionnaires', 'gestionnaire')
            ->leftJoin('gestionnaire.agent', 'gAgent')->addSelect('gAgent')
            ->addSelect('responsable')->leftJoin('structure.responsables', 'responsable')
            ->leftJoin('responsable.agent', 'rAgent')->addSelect('rAgent')
            ->addSelect('type')->leftjoin('structure.type', 'type')
            ->andWhere('structure.deletedOn IS NULL')
            ->orderBy('structure.code');
        return $qb;
    }

    /**
     * @param bool $ouverte
     * @return Structure[]
     */
    public function getStructures(bool $ouverte = true): array
    {
        $qb = $this->createQueryBuilder();
        if ($ouverte)
            $qb = $qb
                ->andWhere("coalesce(structure.fermetureOW,structure.fermeture) IS NULL OR coalesce(structure.fermetureOW,structure.fermeture) >= :now")
                ->setParameter('now', new DateTime());
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @param string|null $id
     * @return Structure|null
     */
    public function getStructure(?string $id): ?Structure
    {
        if ($id === "" or $id === null) return null;
        $qb = $this->createQueryBuilder()
            ->andWhere('structure.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Structure partagent le même identifiant [" . $id . "]", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Structure|null
     */
    public function getRequestedStructure(AbstractActionController $controller, string $paramName = 'structure'): ?Structure
    {
        $id = $controller->params()->fromRoute($paramName);
        $structure = $this->getStructure($id);
        return $structure;
    }

    public function getStructureByCode(?string $code): ?Structure
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('structure.code = :code')->setParameter('code', $code)
            ->andWhere('structure.deletedOn IS NULL');
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . Structure::class . "] actives partagent le même code [" . $code . "]", 0, $e);
        }
        return $result;
    }

    public function getStructureMere(): ?Structure
    {
        $code = $this->getParametreService()->getValeurForParametre(GlobalParametres::TYPE, GlobalParametres::CODE_UNIV);
        return $this->getStructureByCode($code);
    }

    /**
     * @param Structure[] $structures
     * @return Structure[]
     */
    public function getStructuresByTerm(string $term, array $structures = null, bool $histo = false): array
    {
        $qb = $this->getObjectManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->andWhere('LOWER(structure.libelleLong) like :search OR LOWER(structure.libelleCourt) like :search')
            ->setParameter('search', '%' . strtolower($term) . '%')
            ->andWhere('structure.fermeture IS NULL');
        if (!$histo) $qb = $qb->andWhere('structure.deletedOn IS NULL');

        if ($structures !== null) {
            $qb = $qb->andWhere('structure IN (:structures)')
                ->setParameter('structures', $structures);
        }
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure $structure
     * @param bool $ouverte
     * @return Structure[]
     */
    public function getSousStructures(Structure $structure, bool $ouverte = true): array
    {
        $qb = $this->getObjectManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->andWhere('structure.parent = :structure')
            ->setParameter('structure', $structure)
            ->orderBy('structure.code')
            ->andWhere("structure.deletedOn IS NULL");
        if ($ouverte) $qb = $qb->andWhere("structure.fermeture IS NULL");
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @param Structure $structure
     * @param bool $withRoot (ajoute $structrure au résultat)
     * @return Structure[]
     */
    public function getStructuresFilles(Structure $structure, bool $withRoot = false): array
    {
        $filles = [];
        $dejaTraitees = [];

        $aTraitees = [];
        $aTraitees[] = $structure;

        while (!empty($aTraitees)) {
            $current = array_shift($aTraitees);
            $result = $this->getSousStructures($current);
            foreach ($result as $item) {
                if (!$item->isDeleted() and !isset($dejaTraitees[$item->getId()])) {
                    $filles[] = $item;
                    $dejaTraitees[$item->getId()] = true;
                    $aTraitees[] = $item;
                }
            }
        }

        if ($withRoot) $filles[] = $structure;
        return $filles;
    }


    /**
     * @param Structure[] $structures
     * @return array
     */
    public function formatStructureJSON(array $structures): array
    {
        $result = [];
        foreach ($structures as $structure) {
            $result[] = array(
                'id' => $structure->getId(),
                'label' => $structure->getLibelleLong(),
                'extra' => "<span class='badge' style='background-color: slategray;'>" . $structure->getLibelleCourt() . "</span>",
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }

    /**
     * @param Structure[] $structures
     * @return FichePoste[]
     */
    public function getFichesPostesRecrutementsByStructures(array $structures): array
    {
        $fiches = [];
        foreach ($structures as $structure) {
            $fps = $structure->getFichesPostesRecrutements();
            foreach ($fps as $fp) $fiches[$fp->getId()] = $fp;
        }

        $result = [];
        foreach ($fiches as $fiche) {
            $result[] = ['id' => $fiche->getId(),
                'agent_id' => ($fiche->getAgent()) ? $fiche->getAgent()->getId() : null,
                'prenom' => ($fiche->getAgent()) ? $fiche->getAgent()->getPrenom() : null,
                'nom_usage' => ($fiche->getAgent()) ? $fiche->getAgent()->getNomUsuel() : null,
                'fiche_principale' => ($fiche->getFicheTypeExternePrincipale()) ? $fiche->getFicheTypeExternePrincipale()->getFicheType()->getMetier()->getLibelle() : null,
            ];
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getStructuresAsOptions(): array
    {
        $sql = <<<EOS
select
    s.id as ID,
    s.libelle_court as LIBELLE_COURT,
    s.libelle_long as LIBELLE_LONG,
    st.libelle AS TYPE
from structure s
left join structure_type st on st.id = s.type_id
where s.d_fermeture IS NULL
order by st.libelle, s.libelle_long
EOS;

        try {
            $res = $this->getObjectManager()->getConnection()->executeQuery($sql, []);
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
            $options[$structure['id']] = "[" . $structure['type'] . "] " . $structure['libelle_long'] . " (" . $structure['libelle_court'] . ")";
        }
        return $options;
    }

    /**
     * @return User[]
     */
    public function getUsersInResponsables(): array
    {
        $qb = $this->getObjectManager()->getRepository(StructureResponsable::class)->createQueryBuilder("sr")
            ->join('sr.agent', 'agent')->addSelect('agent')
            ->orderBy('agent.nomUsuel, agent.prenom', 'ASC');
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
    public function getUsersInGestionnaires(): array
    {
        $qb = $this->getObjectManager()->getRepository(StructureGestionnaire::class)->createQueryBuilder("sg")
            ->join('sg.agent', 'agent')->addSelect('agent')
            ->orderBy('agent.nomUsuel, agent.prenom', 'ASC');
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
    public function getStructuresByGestionnaire(User $user, bool $ouverte = true): array
    {
        $qb = $this->getObjectManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->join('structure.gestionnaires', 'gestionnaireSelection')
            ->addSelect('gestionnaire')->join('structure.gestionnaires', 'gestionnaire')
            ->addSelect('agent')->join('gestionnaire.agent', 'agent')
            ->andWhere('agent.utilisateur = :user')
            ->setParameter('user', $user)
            ->orderBy('structure.libelleCourt');
        if ($ouverte) $qb = $qb->andWhere("structure.fermeture IS NULL");

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param User $user
     * @param bool $ouverte
     * @return Structure[]
     */
    public function getStructuresByResponsable(User $user, bool $ouverte = true): array
    {
        $qb = $this->getObjectManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->join('structure.responsables', 'responsableSelection')
            ->addSelect('responsable')->join('structure.responsables', 'responsable')
            ->addSelect('agent')->join('responsable.agent', 'agent')
            ->andWhere('agent.utilisateur = :user')
            ->setParameter('user', $user)
            ->orderBy('structure.libelleCourt');
        if ($ouverte) $qb = $qb->andWhere("structure.fermeture IS NULL");

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure $structure
     * @param Agent|null $agent
     * @return boolean
     */
    public function isGestionnaire(Structure $structure, ?Agent $agent): bool
    {
        if ($agent === null) return false;
        $date = (new DateTime());

        $gestionnaires = $structure->getGestionnaires();
        foreach ($gestionnaires as $gestionnaire) {
            if (($gestionnaire->getAgent() === $agent)
                and ($gestionnaire->getDateDebut() === NULL or $gestionnaire->getDateDebut() <= $date)
                and ($gestionnaire->getDateFin() === NULL or $gestionnaire->getDateFin() >= $date)
                and (!$gestionnaire->isDeleted())
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
    public function isGestionnaireS(array $structures, ?Agent $agent): bool
    {
        foreach ($structures as $structure) {
            $result = $this->isGestionnaire($structure, $agent);
            if ($result) return true;
        }
        return false;
    }

    public function isResponsable(?Structure $structure, ?Agent $agent): bool
    {
        if ($structure === null) return false;
        if ($agent === null) return false;
        $date = (new DateTime());

        $responsables = $structure->getResponsables();
        foreach ($responsables as $responsable) {
            if (($responsable->getAgent() === $agent)
                and ($responsable->getDateDebut() === NULL or $responsable->getDateDebut() <= $date)
                and ($responsable->getDateFin() === NULL or $responsable->getDateFin() >= $date)
                and (!$responsable->isDeleted())
            ) return true;
        }
        if ($structure->getParent() && $structure !== $structure->getParent()) return $this->isResponsable($structure->getParent(), $agent);
        return false;
    }

    /**
     * @param Structure[] $structures
     * @param Agent|null $agent
     * @return boolean
     */
    public function isResponsableS(array $structures, ?Agent $agent): bool
    {
        foreach ($structures as $structure) {
            $result = $this->isResponsable($structure, $agent);
            if ($result) return true;
        }
        return false;
    }

    public function isAutorite(?Structure $structure, ?Agent $agent): bool
    {
        if ($structure === null) return false;
        if ($agent === null) return false;
        $date = (new DateTime());
        $structure = $structure->getNiv2();

        if ($structure === null) return false;

        $responsables = $structure->getResponsables();
        foreach ($responsables as $responsable) {
            if (($responsable->getAgent() === $agent)
                and ($responsable->getDateDebut() === NULL or $responsable->getDateDebut() <= $date)
                and ($responsable->getDateFin() === NULL or $responsable->getDateFin() >= $date)
                and (!$responsable->isDeleted())
            ) return true;
        }
        return false;
    }

    /**
     * @param Structure[] $structures
     * @param Agent|null $agent
     * @return boolean
     */
    public function isAutoriteS(array $structures, ?Agent $agent): bool
    {
        foreach ($structures as $structure) {
            $result = $this->isAutorite($structure, $agent);
            if ($result) return true;
        }
        return false;
    }

    /**
     * @return Structure[]
     */
    public function getStructuresByCurrentRole(?User $user = null, ?RoleInterface $role = null): array
    {
        if ($user === null) $user = $this->getUserService()->getConnectedUser();
        if ($role === null) $role = $this->getUserService()->getConnectedRole();

        $selecteur = [];
        if ($role->getRoleId() === RoleProvider::RESPONSABLE) {
            $structures = $this->getStructuresByResponsable($user);
            usort($structures, function (Structure $a, Structure $b) {
                return strcmp($a->getLibelleCourt(), $b->getLibelleCourt());
            });
            $selecteur = $structures;
        }

        return $selecteur;
    }

    /** Fonctions pour l'organigramme *********************************************************************************/

    public function getAgentsEnAffectationSecondaire(Structure $structure, ?DateTime $date = null): array
    {
        if ($date === null) $date = new DateTime();
        $qb = $this->getObjectManager()->getRepository(AgentAffectation::class)->createQueryBuilder('affectation')
//            ->join('affectation.agent', 'agent')->addSelect('agent')
            // affectation en cours non principale dans la structure
            ->andWhere('affectation.structure = :structure')
            ->setParameter('structure', $structure)
            ->andWhere('affectation.principale IS NULL OR affectation.principale = :N')
            ->setParameter('N', 'N')
            ->andWhere('affectation.dateDebut <= :currentdate')
            ->andWhere('affectation.dateFin IS NULL OR affectation.dateFin >= :currentdate')
            ->andWhere('affectation.deletedOn IS NULL')
            ->setParameter('currentdate', $date)//            ->orderBy('agent.nomUsuel, agent.prenom')
        ;

        /** @var AgentAffectation[] $result */
        $result = $qb->getQuery()->getResult();

        $listing = [];
        foreach ($result as $affectation) {
            $agent = $affectation->getAgent();
            $affectationPrincipale = $agent->getAffectationPrincipale();
            if ($affectationPrincipale) {
                $structurePrincipale = $affectationPrincipale->getStructure();
                $listing[$structurePrincipale->getLibelleLong()][] = $agent->getDenomination();
            } else {
                $listing["ZZZ"][] = $agent->getDenomination();
            }
        }
        return $listing;
    }

    /**
     * @param Structure|null $structure
     * @param DateTime|null $date
     * @return StructureResponsable[]
     */
    public function getResponsables(?Structure $structure, ?DateTime $date = null): array
    {
        $qb = $this->getObjectManager()->getRepository(StructureResponsable::class)->createQueryBuilder('responsable')
//            ->join('responsable.source', 'source')->addSelect('source')
            ->join('responsable.agent', 'agent')->addSelect('agent')
            ->andWhere('responsable.structure = :structure')->setParameter('structure', $structure)
            ->andWhere('responsable.deletedOn IS NULL')
            ->orderBy('agent.nomUsuel, agent.prenom');
        if ($date !== null) $qb = StructureResponsable::decorateWithActif($qb, 'responsable', $date);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure|null $structure
     * @param DateTime|null $date
     * @return StructureGestionnaire[]
     */
    public function getGestionnaires(?Structure $structure, ?DateTime $date = null): array
    {
        $qb = $this->getObjectManager()->getRepository(StructureGestionnaire::class)->createQueryBuilder('gestionnaire')
            ->join('gestionnaire.agent', 'agent')->addSelect('agent')
            ->andWhere('gestionnaire.structure = :structure')
            ->setParameter('structure', $structure)
            ->orderBy('agent.nomUsuel, agent.prenom');
        if ($date !== null) $qb = StructureGestionnaire::decorateWithActif($qb, 'gestionnaire', $date);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** GESTION DES AGENTS FORCES *************************************************************************************/

    /**
     * @param Structure $structure
     * @param DateTime|null $date
     * @return StructureAgentForce[]
     */
    public function getAgentsForces(Structure $structure, ?DateTime $date = null): array
    {
        if ($date === null) $date = new DateTime();

        $qb = $this->getObjectManager()->getRepository(StructureAgentForce::class)->createQueryBuilder('af')
            ->join('af.agent', 'af_agent')->addSelect('af_agent')
            ->join('af.structure', 'af_structure')->addSelect('af_structure')
            ->leftjoin('af_agent.affectations', 'af_affectation')->addSelect('af_affectation')
            ->leftjoin('af_affectation.structure', 'affectation_structure')->addSelect('affectation_structure')
//            ->leftJoin('affectation_structure.responsables', 'structure_responsable')->addSelect('structure_responsable')
//            ->leftJoin('structure_responsable.agent', 'structure_responsable_agent')->addSelect('structure_responsable_agent')
            ->leftjoin('affectation_structure.niv2', 'affectation_niv2')->addSelect('affectation_niv2')
//            ->leftJoin('affectation_niv2.responsables', 'niv2_responsable')->addSelect('niv2_responsable')
//            ->leftJoin('niv2_responsable.agent', 'niv2_responsable_agent')->addSelect('niv2_responsable_agent')

            ->andWhere('af.structure = :structure')
            ->setParameter('structure', $structure)
            ->andWhere('af_affectation.dateDebut IS NULL or af_affectation.dateDebut <= :date')
            ->andWhere('af_affectation.dateFin IS NULL or af_affectation.dateFin >= :date')
//            ->andWhere('structure_responsable.dateDebut IS NULL or structure_responsable.dateDebut <= :date')
//            ->andWhere('structure_responsable.dateFin IS NULL or structure_responsable.dateFin >= :date')
//            ->andWhere('niv2_responsable.dateDebut IS NULL or niv2_responsable.dateDebut <= :date')
//            ->andWhere('niv2_responsable.dateFin IS NULL or niv2_responsable.dateFin >= :date')
            ->setParameter('date', $date);

        $qb = AgentSuperieur::decorateWithAgentSuperieur($qb, 'af_agent');
        $qb = AgentAutorite::decorateWithAgentAutorite($qb, 'af_agent');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function trierAgents(array $agents): array
    {
        $conserver = [];
        $retirer = [];
        $raison = [];

        $parametres = $this->getParametreService()->getParametresByCategorieCode(StructureParametres::TYPE);

        $now = new DateTime();
        $parametres = $this->getParametreService()->getParametresByCategorieCode(StructureParametres::TYPE);

        /** @var Agent $agent */
        foreach ($agents as $agent) {
            $raison[$agent->getId()] = "<ul>";

            $kept = true;

            if (!$agent->isValideEmploiType(
                $parametres[StructureParametres::AGENT_TEMOIN_EMPLOITYPE],
                $now))
            {
                $kept = false;
                $raison[$agent->getId()] .= "<li>Emploi-type invalide</li>";
            }
            if (!$agent->isValideStatut(
                $parametres[StructureParametres::AGENT_TEMOIN_STATUT],
                $now))
            {
                $kept = false;
                $raison[$agent->getId()] .= "<li>Statut invalide</li>";

            }
            if (!$agent->isValideAffectation(
                $parametres[StructureParametres::AGENT_TEMOIN_AFFECTATION],
                $now))
            {
                $kept = false;
                $raison[$agent->getId()] .= "<li>Affectation invalide</li>";
            }
            if (!$agent->isValideGrade(
                $parametres[StructureParametres::AGENT_TEMOIN_GRADE],
                $now))
            {
                $kept = false;
                $raison[$agent->getId()] .= "<li>Grade invalide</li>";
            }
            if (!$agent->isValideCorps(
                $parametres[StructureParametres::AGENT_TEMOIN_CORPS],
                $now))
            {
                $kept = false;
                $raison[$agent->getId()] .= "<li>Corps invalide</li>";
            }

            if ($kept) $conserver[$agent->getId()] = $agent; else $retirer[$agent->getId()] = $agent;
            $raison[$agent->getId()] .= "</ul>";
        }
        return [$conserver, $retirer, $raison];
    }

    public function isObservateurS(array $getStructures, Agent $inscrit)
    {
    }
}
