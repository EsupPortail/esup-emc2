<?php

namespace Application\Service\Agent;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Complement;
use DateTime;
use Doctrine\DBAL\Driver\Exception as DRV_Exception;
use Doctrine\DBAL\Exception as DBA_Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\FormationElement;
use Structure\Entity\Db\Structure;
use Structure\Entity\Db\StructureGestionnaire;
use Structure\Entity\Db\StructureResponsable;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use Zend\Mvc\Controller\AbstractActionController;

class AgentService {
    use EntityManagerAwareTrait;
    use StructureServiceAwareTrait;

    /** AGENT *********************************************************************************************************/

    /**
     * @param Agent $agent
     * @return Agent
     */
    public function update(Agent $agent) : Agent
    {
        try {
            $this->getEntityManager()->flush($agent);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème a été recontré lors de la mise à jour de l'agent", $e);
        }
        return $agent;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function getAgentsPourIndex() : array
    {
        $sql = <<<EOS
select
    a.c_individu AS ID,
    a.prenom AS PRENOM,
    a.nom_usage AS NOM_USAGE,
    uuu.username AS UTILISATEUR,
    current_date
from agent a
left join unicaen_utilisateur_user uuu on a.utilisateur_id = uuu.id
left join agent_carriere_affectation aa on aa.agent_id = a.c_individu
where aa.t_principale = 'O'
and aa.date_debut <= current_date AND (aa.date_fin IS NULL OR aa.date_fin >= current_date)
group by a.c_individu, a.prenom, a.c_individu, a.nom_usage, uuu.username
order by a.nom_usage, a.prenom
EOS;

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
        return $tmp;
    }

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            //affectations
            ->addSelect('affectation')->leftJoin('agent.affectations', 'affectation')
            ->addSelect('affectation_structure')->leftJoin('affectation.structure', 'affectation_structure')
            //quotite de l'agent
            ->addSelect('quotite')->leftJoin('agent.quotites', 'quotite')

            ->addSelect('utilisateur')->leftJoin('agent.utilisateur', 'utilisateur')
            ->andWhere('agent.deleted_on IS NULL')
        ;
        return $qb;
    }

    /**
     * @param array $temoins
     * @param string|null $order
     * @return Agent[]
     */
    public function getAgents(array $temoins = [], ?string $order = null) : array
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->andWhere('agent.deleted_on IS NULL')
            ->addSelect('affectation')->join('agent.affectations', 'affectation')
            ->addSelect('utilisateur')->leftjoin('agent.utilisateur', 'utilisateur')
//            ->addSelect('statut')->leftJoin('agent.statuts', 'statut')
            ->andWhere('affectation.dateDebut <= :NOW')
            ->andWhere('affectation.dateFin >= :NOW OR affectation.dateFin IS NULL')
            ->setParameter('NOW', new DateTime())
        ;

//        $tmp = ['statut IS NULL'];
//        foreach ($temoins as $temoin => $value) {
//            if ($value) $tmp[] = 'statut.'. $temoin .' = :TRUE';
//        }
//        if (!empty($tmp)) {
//            $qb = $qb->andWhere(implode(" OR ",$tmp))
//                ->setParameter('TRUE', 'O');
//        }

        if ($order !== null) {
            $qb = $qb->orderBy('agent.' . $order);
        } else {
            $qb = $qb->orderBy('agent.nomUsuel, agent.prenom');
        }

        $result =  $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string|null $term
     * @param Structure[]|null $structures
     * @return Agent[]
     */
    public function getAgentsByTerm(?string $term, ?array $structures = null) : array
    {
        $date = new DateTime();
        $qb = $this->createQueryBuilder()
            ->andWhere("LOWER(CONCAT(agent.prenom, ' ', agent.nomUsuel)) like :search OR LOWER(CONCAT(agent.nomUsuel, ' ', agent.prenom)) like :search")
            ->addSelect('structure')->join('affectation.structure', 'structure')
            ->andWhere('affectation.dateDebut <= :date OR affectation.dateDebut IS NULL')
            ->andWhere('affectation.dateFin >= :date OR affectation.dateFin IS NULL')
            ->setParameter('date', $date)
            ->setParameter('search', '%'.strtolower($term).'%')
        ;

        if ($structures !== null) {
            $qb = $qb
                ->andWhere('structure IN (:structures)')
                ->setParameter('structures', $structures)
            ;
        }

        $result =  $qb->getQuery()->getResult();
        return $result;
    }


    /**
     * @param string|null $id
     * @param bool $enlarge (si mis à TRUE alors pas d'obligation de donnée minimum)
     * @return Agent|null
     */
    public function getAgent(?string $id, bool $enlarge = false) : ?Agent
    {
        if ($id === null) return null;

        $qb = $this->createQueryBuilder();
        if ($enlarge === true) {
            $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent');
        }
        $qb = $qb->andWhere('agent.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs agents partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    public function getAgentsLargeByTerm(?string $term) : array
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->andWhere("LOWER(CONCAT(agent.prenom, ' ', agent.nomUsuel)) like :search OR LOWER(CONCAT(agent.nomUsuel, ' ', agent.prenom)) like :search")
            ->setParameter('search', '%'.strtolower($term).'%')
        ;

        $result =  $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Agent|null
     */
    public function getRequestedAgent(AbstractActionController $controller, string $paramName = 'agent') : ?Agent
    {
        $id = $controller->params()->fromRoute($paramName);
        $agent = $this->getAgent($id, true);
        return $agent;
    }

    /**
     * @param User|null $user
     * @return Agent|null
     */
    public function getAgentByUser(?User $user) : ?Agent
    {
        if ($user === null) return null;

        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->andWhere('agent.utilisateur = :user')
            ->setParameter('user', $user)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Agent liés au même User [".$user->getId()."]", $e);
        }
        return $result;
    }

    /**
     * @param string|null $unsername
     * @return Agent|null
     */
    public function getAgentByUsername(?string $username) : ?Agent
    {
        if ($username === null) return null;

        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->andWhere('agent.login = :username')
            ->setParameter('username', $username)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Agent liés au même Username [".$username."]", $e);
        }
        return $result;
    }

    /**
     * @param int $supannId
     * @return Agent|null
     */
    public function getAgentBySupannId(int $supannId) : ?Agent
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->andWhere('agent.harpId = :supannId')
            ->setParameter('supannId', $supannId)
            ->andWhere('agent.deleted_on IS NULL');

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs agents partagent le même identifiant [".$supannId."]");
        }
        return $result;
    }

    /**
     * @param Structure|null $structure
     * @param boolean $sousstructure
     * @return Agent[]
     */
    public function getAgentsSansFichePosteByStructure(?Structure $structure = null, bool $sousstructure = false) : array
    {
        $today = new DateTime();

        /** !!TODO!! faire le lien entre agent et fiche de poste */
        $qb1 = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->addSelect('statut')->join('agent.statuts', 'statut')
            ->addSelect('grade')->join('agent.grades', 'grade')
            ->addSelect('structure')->join('grade.structure', 'structure')
            ->addSelect('fiche')->leftJoin('agent.fiches', 'fiche')
            ->addSelect('affectation')->join('agent.affectations', 'affectation')
            ->andWhere('statut.dateFin >= :today OR statut.dateFin IS NULL')
            ->andWhere('grade.dateFin >= :today OR grade.dateFin IS NULL')
//            ->andWhere('statut.administratif = :true')
            ->andWhere('statut.enseignant = :false AND statut.chercheur = :false AND statut.etudiant = :false AND statut.retraite = :false AND statut.heberge = :false AND statut.auditeurLibre = :false')
            ->andWhere('affectation.dateFin >= :today OR affectation.dateFin IS NULL')
            ->andWhere('affectation.principale = :true')
            //->andWhere('fiche.id IS NULL')
            ->setParameter('today', $today)
            ->setParameter('true', 'O')
            ->setParameter('false', 'N')
            ->orderBy('agent.nomUsuel, agent.prenom')
            ->andWhere('agent.deleted_on IS NULL');

        if ($structure !== null && $sousstructure === true) {
            $qb1 = $qb1->andWhere('grade.structure = :structure OR structure.parent = :structure')
                     ->setParameter('structure', $structure);
        }
        if ($structure !== null && $sousstructure === false) {
            $qb1 = $qb1->andWhere('statut.structure = :structure' )
                ->setParameter('structure', $structure);
        }
        $result1 = $qb1->getQuery()->getResult();

        //TODO ! faire la jointure ...
        $result = [];
        /** @var Agent $agent */
        foreach ($result1 as $agent) {
            if (empty($agent->getFiches())) $result[] = $agent;
        }

        return $result;
    }

    /**
     * @param Structure[] $structures
     * @return Agent[]
     */
    public function getAgentsByStructures(array $structures, ?DateTime $date = null) : array
    {
        if ($date === null) $date = new DateTime();

        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            //AFFECTATION
            ->addSelect('affectation')->join('agent.affectations', 'affectation')
            ->addSelect('astructure')->join('affectation.structure', 'astructure')
            ->andWhere('affectation.dateFin >= :today OR affectation.dateFin IS NULL')
            ->andWhere('affectation.dateDebut <= :today')
            ->andWhere('affectation.principale = :true')
            ->andWhere('affectation.deleted_on IS NULL')
            //STATUS
            ->addSelect('statut')->leftjoin('agent.statuts', 'statut')
            ->andWhere('statut.dateFin >= :today OR statut.dateFin IS NULL')
            ->andWhere('statut.dateDebut <= :today')
            ->andWhere('statut.dispo = :false')
            ->andWhere('(statut.enseignant = :false AND statut.chercheur = :false AND statut.etudiant = :false AND statut.retraite = :false AND (statut.detacheOut = :false OR (statut.detacheOut = :true AND statut.detacheIn = :true)) AND statut.vacataire = :false)')
            ->andWhere('statut.deleted_on IS NULL')
            //GRADE
            ->addSelect('grade')->leftjoin('agent.grades', 'grade')
            ->addSelect('gstructure')->leftjoin('grade.structure', 'gstructure')
            ->addSelect('ggrade')->leftjoin('grade.grade', 'ggrade')
            ->addSelect('gcorrespondance')->leftjoin('grade.bap', 'gcorrespondance')
            ->addSelect('gcorps')->leftjoin('grade.corps', 'gcorps')
            ->andWhere('grade.dateFin >= :today OR grade.dateFin IS NULL')
            ->andWhere('grade.dateDebut <= :today OR grade.dateDebut IS NULL')
            ->andWhere('grade.deleted_on IS NULL')
            //FICHE DE POSTE
            ->addSelect('ficheposte')->leftJoin('agent.fiches', 'ficheposte')

            ->setParameter('today', $date)
            ->setParameter('true', 'O')
            ->setParameter('false', 'N')
            ->andWhere('agent.deleted_on IS NULL')

            ->orderBy('agent.nomUsuel, agent.prenom', 'ASC')
        ;

        if ($structures !== null) {
            $qb = $qb->andWhere('affectation.structure IN (:structures)')
                ->setParameter('structures', $structures);
        }

        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @param Structure[] $structures
     * @return Agent[]
     */
    public function getAgentsForcesByStructures(array $structures) : array
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->addSelect('forcage')->join('agent.structuresForcees', 'forcage')
            ->andWhere('forcage.histoDestruction IS NULL');

        if ($structures !== null) {
            $qb = $qb->andWhere('forcage.structure IN (:structures)')
                ->setParameter('structures', $structures);
        }

        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function getAgentByHarp(string $st_harp_id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agent.harpId = :harp_id')
            ->setParameter('harp_id', $st_harp_id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs agents partagent le même harp_id [".$st_harp_id."]");
        }
        return $result;
    }

    /**
     * @param $st_prenom
     * @param $st_nom
     * @param $st_annee
     * @return Agent|null
     */
    public function getAgentByIdentification($st_prenom, $st_nom, $st_annee) : ?Agent
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent');

        if ($st_prenom !== null) {
            $qb = $qb->andWhere('LOWER(agent.prenom) = LOWER(:prenom)')
                ->setParameter("prenom", $st_prenom);
        }
        if ($st_nom !== null) {
            $qb = $qb->andWhere('LOWER(agent.nomUsuel) = LOWER(:nom)')
                ->setParameter("nom", $st_nom);
        }
//        if ($st_annee !== null) {
//            $qb = $qb->andWhere('LOWER(agent.nom) = LOWER(:nom)')
//                ->setParameter("prenom", $st_nom);
//        }
        $result = $qb->getQuery()->getResult();
        if (count($result) === 1) return $result[0];
        return null;
    }

    /**
     * @param Agent $agent
     * @return Agent[]|null
     */
    public function getResponsablesHierarchiques(Agent $agent) : ?array
    {
        $affectationPrincipale = $agent->getAffectationPrincipale();
        if ($affectationPrincipale === null) return null;
        $structure = $affectationPrincipale->getStructure();
        if ($structure === null) return null;

        $structureResponsables = $structure->getResponsables();
        $responsables = [];
        foreach ($structureResponsables as $structureResponsable) {
            $responsables[] = $structureResponsable->getAgent();
        }

        if ($responsables !== []) return $responsables;
        return null;
    }

    /**
     * @param Agent $agent
     * @return Agent[]|null
     */
    public function getAutoritesHierarchiques(Agent $agent) : ?array
    {
        $affectationPrincipale = $agent->getAffectationPrincipale();
        if ($affectationPrincipale === null) return null;
        $structure = $affectationPrincipale->getStructure();
        if ($structure === null) return null;
        $niv2 = $structure->getNiv2();
        if ($niv2 === null) return null;

        $structureResponsables = $niv2->getResponsables();
        $responsables = [];
        foreach ($structureResponsables as $structureResponsable) {
            $responsables[] = $structureResponsable->getAgent();
        }

        if ($responsables !== []) return $responsables;
        return null;
    }

    /** AgentFormation ************************************************************************************************/

    /**
     * @param Agent $agent
     * @param string $annee
     * @return FormationElement[]
     */
    public function getFormationsSuiviesByAnnee(Agent $agent, string $annee) : array
    {
        $result = [];
        $formations = $agent->getFormationListe();
        foreach ($formations as $formation) {
            $anneeFormation = explode(' - ',$formation->getCommentaire())[0];
            if ($anneeFormation === $annee) $result[] = $formation;
        }

        return $result;
    }

    /**
     * @param Agent[] $agents
     * @return array
     */
    public function formatAgentJSON(array $agents) : array
    {
        $result = [];
        /** @var Agent[] $agents */
        foreach ($agents as $agent) {
            $structure = ($agent->getAffectationPrincipale()) ? ($agent->getAffectationPrincipale()->getStructure()) : null;
            $extra = ($structure) ? $structure->getLibelleCourt() : "Affectation inconnue";
            $result[] = array(
                'id' => $agent->getId(),
                'label' => $agent->getDenomination(),
                'extra' => "<span class='badge' style='background-color: slategray;'>" . $extra . "</span>",
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }

    /**
     * @param Agent|null $agent
     * @return Agent[]
     */
    function getResponsables(?Agent $agent) : ?array
    {
        if ($agent === null) return null;

        $qb = $this->getEntityManager()->getRepository(StructureResponsable::class)->createQueryBuilder('sr')
            ->andWhere('sr.agent = :agent')
            ->setParameter('agent', $agent)
            ->andWhere('sr.deleted_on IS NULL or sr.imported = :false')
            ->setParameter('false', false)
        ;
        $result = $qb->getQuery()->getResult();
        $result = array_map(function (StructureResponsable $a) { return $a->getAgent();}, $result);
        $result = array_filter($result, function (Agent $a) use ($agent) { return $a !== $agent;});
        if (!empty(($result))) return $result;

        $complements = $agent->getComplementsByType(Complement::COMPLEMENT_TYPE_RESPONSABLE);
        foreach ($complements as $complement) {
            $responsable = $this->getAgent($complement->getComplementId());
            if ($responsable) $result[] = $responsable;
        }
        return $result;

    }

    /**
     * @param Agent|null $agent
     * @return StructureResponsable[]
     */
    public function getResposabiliteStructure(?Agent $agent) : ?array
    {
        if ($agent === null) return null;

        $qb = $this->getEntityManager()->getRepository(StructureResponsable::class)->createQueryBuilder('sr')
            ->andWhere('sr.agent = :agent')
            ->setParameter('agent', $agent)
            ->andWhere('sr.deleted_on IS NULL or sr.imported = :false')
            ->setParameter('false', false)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Agent|null $agent
     * @return Structure[]
     */
    public function getGestionnaireStructure(?Agent $agent) : ?array
    {
        if ($agent === null) return null;

        $qb = $this->getEntityManager()->getRepository(StructureGestionnaire::class)->createQueryBuilder('sg')
            ->andWhere('sg.agent = :agent')
            ->setParameter('agent', $agent)
            ->andWhere('sg.deleted_on IS NULL or sg.imported = :false')
            ->setParameter('false', false)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return User[]
     */
    public function getUsersInAgent() : array
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->join('agent.utilisateur', 'utilisateur')
            ->orderBy('agent.nomUsuel, agent.prenom', 'ASC')
        ;
        $result = $qb->getQuery()->getResult();

        $users = [];
        /** @var Agent $item */
        foreach ($result as $item) {
            $users[] = $item->getUtilisateur();
        }
        return $users;
    }

    /** FONCTION POUR LES RÔLES AUTOMATIQUES **************************************************************************/

    /**
     * @return User[]
     */
    public function getUsersInSuperieurs() : array
    {
        $qb = $this->getEntityManager()->getRepository(Complement::class)->createQueryBuilder("complement")
            ->andWhere('complement.histoDestruction IS NULL')
            ->andWhere('complement.type = :SUPERIEUR')
            ->setParameter('SUPERIEUR', Complement::COMPLEMENT_TYPE_RESPONSABLE)
        ;
        $result = $qb->getQuery()->getResult();

        /** @var Complement[] $result */

        $ids = [];
        foreach ($result as $item) $ids[$item->getComplementId()] = $item->getComplementId();

        $users = [];
        foreach ($ids as $id) {
            if ($this->getAgent($id) !== null) { $users[] = $this->getAgent($id)->getUtilisateur(); }
            //else {var_dump("No agent with id = ".$id);}
        }

        return $users;
    }

    /**
     * @param User|null $user
     * @return Complement[]|null
     */
    public function getSuperieurByUser(?User $user) : ?array
    {
        if ($user === null) return null;

        $agent = $this->getAgentByUser($user);
        if ($agent === null) return null;

        $qb = $this->getEntityManager()->getRepository(Complement::class)->createQueryBuilder('complement')
            ->andWhere('complement.type = :SUPERIEUR')
            ->setParameter('SUPERIEUR', Complement::COMPLEMENT_TYPE_RESPONSABLE)
            ->andWhere('complement.complementId = :agentId')
            ->setParameter('agentId', $agent->getId())
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Agent|null $agent
     * @return Agent[]
     */
    public function getSuperieursByAgent(?Agent $agent) : array
    {
        if ($agent === null) return [];

        $qb = $this->getEntityManager()->getRepository(Complement::class)->createQueryBuilder('complement')
            ->andWhere('complement.type = :SUPERIEUR')
            ->setParameter('SUPERIEUR', Complement::COMPLEMENT_TYPE_RESPONSABLE)
            ->andWhere('complement.attachmentId = :agentId')
            ->setParameter('agentId', $agent->getId())
        ;
        $result = $qb->getQuery()->getResult();

        $superieurs = [];
        /** @var Complement $item */
        foreach ($result as $item) {
            $superieur = $this->getAgent($item->getComplementId());
            if ($superieur) $superieurs[] = $superieur;
        }
        return $superieurs;
    }

    /**
     * @return User[]
     */
    public function getUsersInAutorites() : array
    {
        $qb = $this->getEntityManager()->getRepository(Complement::class)->createQueryBuilder("complement")
            ->andWhere('complement.histoDestruction IS NULL')
            ->andWhere('complement.type = :AUTORITE')
            ->setParameter('AUTORITE', Complement::COMPLEMENT_TYPE_AUTORITE)
        ;
        $result = $qb->getQuery()->getResult();

        /** @var Complement[] $result */

        $ids = [];
        foreach ($result as $item) $ids[$item->getComplementId()] = $item->getComplementId();

        $users = [];
        foreach ($ids as $id) {
            if ($this->getAgent($id) !== null) { $users[] = $this->getAgent($id)->getUtilisateur(); }
            //else {var_dump("No agent with id = ".$id);}
        }

        return $users;
    }

    /**
     * @param User|null $user
     * @return Complement[]|null
     */
    public function getAutoriteByUser(?User $user) : ?array
    {
        if ($user === null) return null;

        $agent = $this->getAgentByUser($user);
        if ($agent === null) return null;

        $qb = $this->getEntityManager()->getRepository(Complement::class)->createQueryBuilder('complement')
            ->andWhere('complement.type = :AUTORITE')
            ->setParameter('AUTORITE', Complement::COMPLEMENT_TYPE_AUTORITE)
            ->andWhere('complement.complementId = :agentId')
            ->setParameter('agentId', $agent->getId())
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Agent|null $agent
     * @return Agent[]
     */
    public function getAutoritesByAgent(?Agent $agent) : array
    {
        if ($agent === null) return [];

        $qb = $this->getEntityManager()->getRepository(Complement::class)->createQueryBuilder('complement')
            ->andWhere('complement.type = :AUTORITE')
            ->setParameter('AUTORITE', Complement::COMPLEMENT_TYPE_AUTORITE)
            ->andWhere('complement.attachmentId = :agentId')
            ->setParameter('agentId', $agent->getId())
        ;
        $result = $qb->getQuery()->getResult();

        $superieurs = [];
        /** @var Complement $item */
        foreach ($result as $item) {
            $superieur = $this->getAgent($item->getComplementId());
            if ($superieur) $superieurs[] = $superieur;
        }
        return $superieurs;
    }
}