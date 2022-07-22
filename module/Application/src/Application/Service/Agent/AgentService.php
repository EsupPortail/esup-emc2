<?php

namespace Application\Service\Agent;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Complement;
use Application\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Application\Service\Complement\ComplementServiceAwareTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\FormationElement;
use Laminas\Mvc\Controller\AbstractActionController;
use Structure\Entity\Db\Structure;
use Structure\Entity\Db\StructureGestionnaire;
use Structure\Entity\Db\StructureResponsable;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Exception\LogicException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class AgentService {
    use EntityManagerAwareTrait;
    use AgentAffectationServiceAwareTrait;
    use ComplementServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

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
     * @return Agent[]
     */
    public function getAgents() : array
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->addSelect('utilisateur')->leftjoin('agent.utilisateur', 'utilisateur')
            ->orderBy('agent.nomUsuel, agent.prenom');
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
     * @return Agent|null
     */
    public function getAgentByConnectedUser() : ?Agent
    {
        $utilisateur = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentByUser($utilisateur);
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
     * @return Agent|null
     */
    public function getAgentByIdentification($st_prenom, $st_nom) : ?Agent
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
        $result = $qb->getQuery()->getResult();
        if (count($result) === 1) return $result[0];
        return null;
    }

    /** Recuperation des supérieures et autorités  *********************************************************************/

    /**
     *
     * @var Agent $agent
     * @return array
     */
    public function computeSuperieures(Agent $agent) : array
    {
       //checking complement
        $complements = $this->getComplementService()->getCompelementsByAttachement(Agent::class, $agent->getId(), Complement::COMPLEMENT_TYPE_RESPONSABLE);
        $liste = [];
        if (!empty($complements)) {
            foreach ($complements as $complement) {
                $superieure = $this->getAgent($complement->getComplementId());
                if ($superieure !== null) $liste["complement_" . $complement->getId()] = $superieure;
            }
        }
        if (!empty($liste)) return $liste;

       //checking structure
       $affectationsPrincipales = $this->getAgentAffectationService()->getAgentAffectationsByAgent($agent, true, true);
       if (count($affectationsPrincipales) !== 1) throw new LogicException("Plusieurs affectations principales pour l'agent");

       $structure = $affectationsPrincipales[0]->getStructure();
       do {
           $responsablesAll = array_map(function (StructureResponsable $a) {
               return $a->getAgent();
           }, $this->getStructureService()->getResponsables($structure));
           if (!in_array($agent, $responsablesAll)) {
               $responsables = [];
               foreach ($responsablesAll as $responsable) {
                   $responsables["structure_" . $responsable->getId()] = $responsable;
               }
               if (!empty($responsables)) return $responsables;
           }

           $structure = $structure->getParent();
       } while($structure !== null);

        return [];
    }

    /**
     * @param Agent $agent
     * @param array|null $superieurs
     * @return array
     */
    public function computeAutorites(Agent $agent, ?array $superieurs = null) : array
    {
        //checking complement
        $complements = $this->getComplementService()->getCompelementsByAttachement(Agent::class, $agent->getId(), Complement::COMPLEMENT_TYPE_AUTORITE);
        $liste = [];
        if (!empty($complements)) {
            foreach ($complements as $complement) {
                $superieure = $this->getAgent($complement->getComplementId());
                if ($superieure !== null) $liste["complement_" . $complement->getId()] = $superieure;
            }
        }
        if (!empty($liste)) return $liste;

        // fetching superieurs if not given
        if ($superieurs === null) $superieurs = $this->computeSuperieures($agent);

        //checking structure
        $affectationsPrincipales = $this->getAgentAffectationService()->getAgentAffectationsByAgent($agent, true, true);
        if (count($affectationsPrincipales) !== 1) throw new LogicException("Plusieurs affectations principales pour l'agent");

        $structure = $affectationsPrincipales[0]->getStructure()->getNiv2();
        do {
            $responsablesAll = array_map(function (StructureResponsable $a) {
                return $a->getAgent();
            }, $this->getStructureService()->getResponsables($structure));
            if (!in_array($agent, $responsablesAll)) {
                $responsables = [];
                foreach ($responsablesAll as $responsable) {
                    if (!in_array($responsable, $superieurs)) {
                        $responsables["structure_" . $responsable->getId()] = $responsable;
                    }
                }
                if (!empty($responsables)) return $responsables;
            }

            $structure = $structure->getParent();
        } while($structure !== null);

        return [];
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
     * todo plutôt dans structure
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
     * todo plutôt dans structure
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

}