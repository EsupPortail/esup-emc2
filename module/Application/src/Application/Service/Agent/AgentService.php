<?php

namespace Application\Service\Agent;

use Agent\Entity\Db\AgentAffectation;
use Agent\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Application\Entity\Db\Agent;
use DateTime;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Driver\Exception as DRV_Exception;
use Doctrine\DBAL\Exception as DBA_Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Fichier\Entity\Db\Fichier;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use Structure\Entity\Db\Structure;
use Structure\Entity\Db\StructureAgentForce;
use Structure\Entity\Db\StructureGestionnaire;
use Structure\Entity\Db\StructureResponsable;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class AgentService
{
    use ProvidesObjectManager;
    use AgentAffectationServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    use ParametreServiceAwareTrait;

    /** AGENT *********************************************************************************************************/

    /**
     * @param Agent $agent
     * @return Agent
     */
    public function update(Agent $agent): Agent
    {
        $this->getObjectManager()->flush($agent);
        return $agent;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(bool $enAffectation = true): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            //affectations
            ->addSelect('affectation')->leftJoin('agent.affectations', 'affectation')
            ->addSelect('affectation_structure')->leftJoin('affectation.structure', 'affectation_structure')
            //quotite de l'agent
            ->addSelect('quotite')->leftJoin('agent.quotites', 'quotite')
            ->addSelect('utilisateur')->leftJoin('agent.utilisateur', 'utilisateur')
            ->andWhere('agent.deletedOn IS NULL');

        if (!$enAffectation) $qb = $qb->andWhere('affectation.deletedOn IS NULL');

        return $qb;
    }

    public static function decorateWithTerm(QueryBuilder $qb, string $term, ?string $entityName = 'agent'): QueryBuilder
    {
        $qb = $qb->andWhere("LOWER(CONCAT(" . $entityName . ".nomUsuel, ' ', " . $entityName . ".prenom)) like :search OR LOWER(CONCAT(" . $entityName . ".prenom, ' ', " . $entityName . ".nomUsuel)) like :search")
            ->setParameter('search', '%' . strtolower($term) . '%');
        return $qb;
    }

    public static function decorateWithStructure(QueryBuilder $qb, array $structures, ?string $entityName = 'affectation'): QueryBuilder
    {
        $qb = $qb->andWhere($entityName . ".structure in (:structures)")
            ->setParameter('structures', $structures);
        return $qb;
    }

    /**
     * @return Agent[]
     */
    public function getAgents(): array
    {
        $qb = $this->getObjectManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->addSelect('utilisateur')->leftjoin('agent.utilisateur', 'utilisateur')
            ->addSelect('statut')->leftjoin('agent.statuts', 'statut')
//            ->addSelect('affectation')->leftjoin('agent.affectations', 'affectation')
            ->andWhere('agent.deletedOn IS NULL')
            ->orderBy('agent.nomUsuel, agent.prenom');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param DateTime|null $debut
     * @param DateTime|null $fin
     * @param Structure[]|null $structures
     * @return Agent[]
     */
    public function getAgentsWithDates(?DateTime $debut = null, ?DateTime $fin = null, ?array $structures = null): array
    {
        $qb = $this->getObjectManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->addSelect('affectation')->join('agent.affectations', 'affectation')
            ->addSelect('statut')->join('agent.statuts', 'statut')
            ->addSelect('grade')->join('agent.grades', 'grade')
            ->addSelect('emploitype')->leftjoin('grade.emploiType', 'emploitype')
            ->andWhere('agent.deletedOn IS NULL')
            // Devrait être un filtrage ... et pas une requete même si cela accélère les choses
            ->andWhere('statut.titulaire = :true OR (statut.cdd = :true AND agent.tContratLong =:true) OR statut.cdi = :true')
            ->andWhere('statut.enseignant = :false')
//            ->andWhere('emploitype.code <> :UCNRECH')
            ->setParameter('true', 'O')
            ->setParameter('false', 'N')
//            ->setParameter('UCNRECH', 'UCNRECH')
            ->orderBy('agent.nomUsuel, agent.prenom');

        if ($debut) $qb = $qb
            ->andWhere('affectation.dateFin IS NULL OR affectation.dateFin > :debut')
            ->andWhere('statut.dateFin IS NULL OR statut.dateFin > :debut')
            ->andWhere('grade.dateFin IS NULL OR grade.dateFin > :debut')
            ->andWhere('emploitype.dateFin IS NULL OR emploitype.dateFin > :debut')
            ->setParameter('debut', $debut);
        if ($fin) $qb = $qb
            ->andWhere('affectation.dateDebut IS NULL OR affectation.dateDebut < :fin')
            ->andWhere('statut.dateDebut IS NULL OR statut.dateDebut  < :fin')
            ->andWhere('grade.dateDebut IS NULL OR grade.dateDebut  < :fin')
            ->andWhere('emploitype.dateDebut IS NULL OR emploitype.dateDebut  < :fin')
            ->setParameter('fin', $fin);
        if ($structures) $qb = $qb
            ->andWhere('affectation.structure in (:structures)')
            ->setParameter('structures', $structures);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string|null $id
     * @param bool $enlarge (si mis à TRUE alors pas d'obligation de donnée minimum)
     * @param bool $enAffectation
     * @return Agent|null
     */
    public function getAgent(?string $id, bool $enlarge = false, bool $enAffectation = true): ?Agent
    {
        if ($id === null) return null;

        $qb = $this->createQueryBuilder();
        if ($enlarge === true) {
            $qb = $this->getObjectManager()->getRepository(Agent::class)->createQueryBuilder('agent');
        }
        $qb = $qb->andWhere('agent.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs agents partagent le même identifiant [" . $id . "]", 0, $e);
        }
        return $result;
    }

    /**
     * @param string|null $term
     * @param Structure[]|null $structures
     * @return Agent[]
     */
    public function getAgentsByTerm(?string $term, ?array $structures = null): array
    {
        $date = new DateTime();
        $qb = $this->createQueryBuilder()
            ->addSelect('structure')->join('affectation.structure', 'structure')
            ->andWhere('affectation.dateDebut <= :date OR affectation.dateDebut IS NULL')
            ->andWhere('affectation.dateFin >= :date OR affectation.dateFin IS NULL')
            ->setParameter('date', $date);

        if ($term !== null) {
            $qb = AgentService::decorateWithTerm($qb, strtolower($term));
        }

        if ($structures !== null) {
            $qb = $qb
                ->andWhere('structure IN (:structures)')
                ->setParameter('structures', $structures);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return Agent[] */
    public function getAgentsLargeByTerm(?string $term): array
    {
        $params = ["term" => strtolower($term)];
        $sql = <<<EOS
select a.c_individu as id
from agent a
where unaccent(LOWER(CONCAT(a.prenom, ' ', a.nom_usage))) like unaccent('%' || :term || '%') OR unaccent(LOWER(CONCAT(a.nom_usage, ' ', a.prenom))) like unaccent('%' || :term || '%')
EOS;

        // where LOWER(CONCAT(agent.prenom, ' ', agent.nomUsuel)) like :search OR LOWER(CONCAT(agent.nomUsuel, ' ', agent.prenom)) like UNACCENT(:search)
        try {
            $res = $this->getObjectManager()->getConnection()->executeQuery($sql, $params);
            try {
                $ids = $res->fetchAllAssociative();
            } catch (DRV_Exception $e) {
                throw new RuntimeException("Un problème est survenue lors de la récupération des fonctions d'un groupe d'individus", 0, $e);
            }
        } catch (DBA_Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des fonctions d'un groupe d'individus", 0, $e);
        }


        $agents = [];
        foreach ($ids as $id) {
            $agent = $this->getAgent("" . $id['id'], true);
            if ($agent !== null) $agents[] = $agent;
        }
        return $agents;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Agent|null
     */
    public function getRequestedAgent(AbstractActionController $controller, string $paramName = 'agent'): ?Agent
    {
        $id = $controller->params()->fromRoute($paramName);
        $agent = $this->getAgent($id, true);
        return $agent;
    }

    /**
     * @return Agent|null
     */
    public function getAgentByConnectedUser(): ?Agent
    {
        $utilisateur = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentByUser($utilisateur);
        return $agent;
    }

    /**
     * @param User|null $user
     * @return Agent|null
     */
    public function getAgentByUser(?User $user): ?Agent
    {
        if ($user === null) return null;

        //en utilisant l'id
        $qb = $this->getObjectManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->andWhere('agent.utilisateur = :user')
            ->setParameter('user', $user);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Agent liés au même User [Id:" . $user->getId() . " Username:" . $user->getUsername() . "]", 0, $e);
        }
        if ($result !== null) return $result;

        //en utilisant l'username si echec
        $qb = $this->getObjectManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->andWhere('agent.login = :username')
            ->andWhere('agent.deletedOn IS NULL')
            ->setParameter('username', $user->getUsername());
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Agent liés au même Username [" . $user->getUsername() . "]", 0, $e);
        }
        return $result;
    }

    /**
     * @param Structure[] $structures
     * @return Agent[]
     */
    public function getAgentsByStructures(array $structures, ?DateTime $dateDebut = null, ?DateTime $dateFin = null, bool $withJoin = true): array
    {
        if ($dateDebut === null) $dateDebut = new DateTime();
        $structuresId = [];
        foreach ($structures as $structure) {
            if (is_array($structure)) {
                $structure = $structure[0];
            }
            $structuresId[] = $structure?->getId();
        }
        $params = ['dateDebut' => $dateDebut?->format('Y-m-d'), 'dateFin' => $dateFin?->format('Y-m-d'), 'structures' => $structuresId];

        $sql = <<<EOS
select DISTINCT a.c_individu as c_individu
from agent a
join agent_carriere_affectation aca on a.c_individu = aca.agent_id
where
    aca.deleted_on IS NULL
    and aca.structure_id in (:structures)
EOS;
        if ($dateDebut and $dateFin) {
            $sql .= <<<EOS
and tsrange(aca.date_debut, aca.date_fin) && tsrange(:dateDebut, :dateFin)
EOS;
        }
        if ($dateDebut and !$dateFin) {
            $sql .= <<<EOS
and aca.date_debut <= :dateDebut and (aca.date_fin IS NULL OR aca.date_fin >= :dateDebut)
EOS;
        }

        try {
            $res = $this->getObjectManager()->getConnection()->executeQuery($sql, $params, ['structures' => ArrayParameterType::INTEGER]);
            try {
                $tmp = $res->fetchAllAssociative();
            } catch (DRV_Exception $e) {
                throw new RuntimeException("Un problème est survenue lors de la récupération des fonctions d'un groupe d'individus", 0, $e);
            }
        } catch (DBA_Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des fonctions d'un groupe d'individus", 0, $e);
        }
        $ids = [];
        foreach ($tmp as $row) {
            $ids[] = $row['c_individu'];
        }

        $agents = $this->getAgentsByIds($ids);
        return $agents;
    }

    /**
     * @param Structure[] $structures
     * @return Agent[]
     */
    public function getAgentsForcesByStructures(array $structures): array
    {
        $qb = $this->getObjectManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->addSelect('forcage')->join('agent.structuresForcees', 'forcage')
            ->andWhere('forcage.histoDestruction IS NULL');

        if (!empty(null)) {
            $qb = $qb->andWhere('forcage.structure IN (:structures)')
                ->setParameter('structures', $structures);
        }
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @param $st_prenom
     * @param $st_nom
     * @return Agent|null
     */
    public function getAgentByIdentification($st_prenom, $st_nom): ?Agent
    {
        $qb = $this->getObjectManager()->getRepository(Agent::class)->createQueryBuilder('agent');

        if ($st_prenom !== null) {
            $qb = $qb->andWhere('LOWER(agent.prenom) = LOWER(:prenom)')
                ->setParameter("prenom", $st_prenom);
        }
        if ($st_nom !== null) {
            $qb = $qb->andWhere('LOWER(agent.nomUsuel) = LOWER(:nom)')
                ->setParameter("nom", $st_nom);
        }
        $result = $qb->getQuery()->getResult();
        if (count($result) === 1) return $result[0];
        return null;
    }

    /** Recuperation des supérieures et autorités *********************************************************************/

    /**
     * @return StructureResponsable[]
     */
    public function computeSuperieures(Agent $agent, ?DateTime $date = null): array
    {
        if ($date === null) $date = new DateTime();

        //checking structure
        $affectationsPrincipales = $this->getAgentAffectationService()->getAgentAffectationHierarchiquePrincipaleByAgent($agent);
        if ($affectationsPrincipales === null or count($affectationsPrincipales) !== 1) return []; //throw new LogicException("Plusieurs affectations principales pour l'agent ".$agent->getId() . ":".$agent->getDenomination());

        if (reset($affectationsPrincipales)) {
            $affectationPrincipale = reset($affectationsPrincipales);
            $structure = $affectationPrincipale->getStructure();
            do {
                $responsablesAll = $this->getStructureService()->getResponsables($structure, $date);
                if (!in_array($agent, $responsablesAll)) {
                    $responsables = [];
                    foreach ($responsablesAll as $responsable) {
                        $responsables["structure_" . $responsable->getAgent()->getId()] = $responsable;
                    }
                    if (!empty($responsables)) return $responsables;
                }
                $structure = ($structure and $structure->getParent() !== $structure) ? $structure->getParent() : null;
            } while ($structure !== null);
        }

        return [];
    }

    /**
     * TODO :: a conserver pour init des autorites depuis les structures
     * Agent[]
     */
    public function computeAutorites(Agent $agent, array $superieurs = [], ?DateTime $date = null): array
    {
        if ($date === null) $date = new DateTime();

        //checking structure
        $affectationsPrincipales = $this->getAgentAffectationService()->getAgentAffectationHierarchiquePrincipaleByAgent($agent);
        if ($affectationsPrincipales === null) return [];
        $structure = null;
        if (count($affectationsPrincipales) === 1) {
            $structure = current($affectationsPrincipales)->getStructure()->getNiv2();
        } else {
            foreach ($affectationsPrincipales as $affectation) {
                $niveau2 = $affectation->getStructure()->getNiv2();
                if ($structure === null or $niveau2 === $structure) $structure = $niveau2;
                else return []; //throw new LogicException("Différentes structures de niveau2 affectations principales pour l'agent");
            }
        }

        $banAgent = array_map(function (StructureResponsable $sr) {
            return $sr->getAgent();
        }, $superieurs);
        do {
            $responsablesAll = $this->getStructureService()->getResponsables($structure, $date);
            if (!in_array($agent, $responsablesAll)) {
                $responsables = [];
                foreach ($responsablesAll as $responsable) {
                    //retirer les niv2 deja dans supereieurs
                    if (!in_array($responsable->getAgent(), $banAgent)) {
                        $responsables["structure_" . $responsable->getAgent()->getId()] = $responsable;
                    }
                }
                if (!empty($responsables)) return $responsables;
            }
            $structure = ($structure and $structure->getParent() !== $structure) ? $structure->getParent() : null;
        } while ($structure !== null);

        return [];
    }

    /** AgentFormation ************************************************************************************************/

    /**
     * @param Agent[] $agents
     * @return array
     */
    public function formatAgentJSON(array $agents): array
    {
        $result = [];
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
     * todo plutôt dans structure
     * @param Agent|null $agent
     * @return StructureResponsable[]|null
     */
    public function getResposabiliteStructure(?Agent $agent): ?array
    {
        if ($agent === null) return null;

        $qb = $this->getObjectManager()->getRepository(StructureResponsable::class)->createQueryBuilder('sr')
            ->join('sr.agent', 'agent')
            ->join('sr.structure', 'structure')
            ->andWhere('sr.agent = :agent')->setParameter('agent', $agent)
            ->andWhere('sr.deletedOn IS NULL')->andWhere('agent.deletedOn IS NULL')->andWhere('structure.deletedOn IS NULL')
            ->andWhere('sr.dateDebut IS NULL OR sr.dateDebut <= :now')
            ->andWhere('sr.dateFin IS NULL OR sr.dateFin >= :now')
            ->setParameter('now', new DateTime());
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * todo plutôt dans structure
     * @param Agent|null $agent
     * @return Structure[]|null
     */
    public function getGestionnaireStructure(?Agent $agent): ?array
    {
        if ($agent === null) return null;

        $qb = $this->getObjectManager()->getRepository(StructureGestionnaire::class)->createQueryBuilder('sg')
            ->join('sg.agent', 'agent')
            ->join('sg.structure', 'structure')
            ->andWhere('sg.agent = :agent')->setParameter('agent', $agent)
            ->andWhere('sg.deletedOn IS NULL')->andWhere('agent.deletedOn IS NULL')->andWhere('structure.deletedOn IS NULL')
            ->andWhere('sg.dateDebut IS NULL OR sg.dateDebut <= :now')
            ->andWhere('sg.dateFin IS NULL OR sg.dateFin >= :now')
            ->setParameter('now', new DateTime());
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return User[]
     */
    public function getUsersInAgent(): array
    {
        $qb = $this->getObjectManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->join('agent.utilisateur', 'utilisateur')
            ->orderBy('agent.nomUsuel, agent.prenom', 'ASC');
        $result = $qb->getQuery()->getResult();

        $users = [];
        /** @var Agent $item */
        foreach ($result as $item) {
            $users[] = $item->getUtilisateur();
        }
        return $users;
    }

    public function computesStructures(?Agent $agent, ?DateTime $date = null): array
    {
        if ($date === null) $date = new DateTime();

        $qb = $this->getObjectManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->addSelect('responsable')->leftJoin('structure.responsables', 'responsable')
            ->addSelect('gestionnaire')->leftJoin('structure.gestionnaires', 'gestionnaire')
            ->join('structure.affectations', 'affectation')
            ->andWhere('affectation.agent = :agent')->setParameter('agent', $agent);
        $qb = AgentAffectation::decorateWithActif($qb, 'affectation', $date);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return Agent[]
     */
    public function getAgentsWithFiltre($params): array
    {
        $term = (isset($params['denomination']) and trim($params['denomination']) !== '') ? trim($params['denomination']) : null;
        $encours = (isset($params['encours'])) ? $params['encours'] : null;
        $structure = (isset($params['structure-filtre']) and isset($params['structure-filtre']['id'])) ? $this->getStructureService()->getStructure($params['structure-filtre']['id']) : null;

        $qb = $this->createQueryBuilder();
        if ($term !== null) $qb = AgentService::decorateWithTerm($qb, $term);
        if ($structure !== null) {
            $structures = $this->getStructureService()->getStructuresFilles($structure);
            $structures[] = $structure;
            $qb = AgentService::decorateWithStructure($qb, $structures);
        }
        if ($encours === '1') {
            $qb = AgentAffectation::decorateWithActif($qb, 'affectation')
                ->andWhere('affectation.deletedOn IS NULL');
        }


        $result = $qb->getQuery()->getResult();
//        if ($encours === '1') {
//            $result = array_filter($result, function(Agent $a) { return !empty($a->getAffectationsActifs()); });
//        }
        return $result;
    }

    /** FICHE DE POSTE PDF ********************************************************************************************/

    /**
     * @param Agent[] $agents
     * @return Fichier[] :: [AgentId => Fichier]
     */
    public function getFichesPostesPdfByAgents(array $agents): array
    {

        $ids = array_map(function ($a) {
            if ($a instanceof StructureAgentForce) $a = $a->getAgent();
            return $a->getId();
        }, $agents);

        $qb = $this->getObjectManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->leftJoin('agent.fichiers', 'fichier')->addSelect('fichier')
            ->leftJoin('fichier.nature', 'nature')->addSelect('nature')
            ->andWhere('nature.code = :ficheposte')->setParameter('ficheposte', "FICHE_POSTE")
            ->andWhere('agent.id in (:ids)')->setParameter('ids', $ids);

        $result = $qb->getQuery()->getResult();

        $fiches = [];
        /** @var Agent $item */
        foreach ($result as $item) {
            $fiches[$item->getId()] = $item->getFichiersByCode("FICHE_POSTE");
        }
        return $fiches;
    }

    public function getAgentByLogin(string $login): ?Agent
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agent.login = :login')->setParameter('login', $login);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Agent partagent le même login [" . $login . "]", 0, $e);
        }
        return $result;
    }

    /**
     * @return Agent[]
     */
    public function getAgentsByIds(array $agentIds): array
    {
        $agents = [];
        foreach ($agentIds as $agentId) {
            $agent = $this->getAgent($agentId);
            if ($agent) $agents[$agentId] = $agent;
        }
        return $agents;
    }
}