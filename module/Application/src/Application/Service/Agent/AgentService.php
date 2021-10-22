<?php

namespace Application\Service\Agent;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAffectation;
use Application\Entity\Db\AgentGrade;
use Application\Entity\Db\AgentMissionSpecifique;
use Application\Entity\Db\AgentQuotite;
use Application\Entity\Db\AgentStatut;
use Application\Entity\Db\Structure;
use Application\Entity\Db\StructureResponsable;
use Application\Service\DecoratorTrait;
use Application\Service\GestionEntiteHistorisationTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\FormationElement;
use UnicaenApp\Exception\RuntimeException;
use UnicaenUtilisateur\Entity\Db\User;
use Zend\Mvc\Controller\AbstractActionController;

class AgentService {
    use GestionEntiteHistorisationTrait;
    use StructureServiceAwareTrait;
    use DecoratorTrait;

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
            ->setParameter('NOW', $this->getDateTime())
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
        $date = $this->getDateTime();
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
     * @return Agent|null
     */
    public function getAgent(?string $id) : ?Agent
    {
        if ($id === null) return null;
        $qb = $this->createQueryBuilder()
            ->andWhere('agent.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs agents partagent le même identifiant [".$id."]");
        }
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
        $agent = $this->getAgent($id);
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
     * @param int $supannId
     * @return Agent|null
     */
    public function getAgentBySupannId(int $supannId) : ?Agent
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->andWhere('agent.harpId = :supannId')
            ->setParameter('supannId', $supannId);

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
        $today = $this->getDateTime();

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
    public function getAgentsByStructures(array $structures) : array
    {
        $today = $this->getDateTime();

        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            //AFFECTATION
            ->addSelect('affectation')->join('agent.affectations', 'affectation')
            ->addSelect('astructure')->join('affectation.structure', 'astructure')
            ->andWhere('affectation.dateFin >= :today OR affectation.dateFin IS NULL')
            ->andWhere('affectation.dateDebut <= :today')
            ->andWhere('affectation.principale = :true')
            //STATUS
            ->addSelect('statut')->leftjoin('agent.statuts', 'statut')
            ->andWhere('statut.dateFin >= :today OR statut.dateFin IS NULL')
            ->andWhere('statut.dateDebut <= :today')
            ->andWhere('statut.dispo = :false')
            ->andWhere('(statut.enseignant = :false AND statut.chercheur = :false AND statut.etudiant = :false AND statut.retraite = :false)')
            //GRADE
            ->addSelect('grade')->leftjoin('agent.grades', 'grade')
            ->addSelect('gstructure')->leftjoin('grade.structure', 'gstructure')
            ->addSelect('ggrade')->leftjoin('grade.grade', 'ggrade')
            ->addSelect('gcorrespondance')->leftjoin('grade.bap', 'gcorrespondance')
            ->addSelect('gcorps')->leftjoin('grade.corps', 'gcorps')
            ->andWhere('grade.dateFin >= :today OR grade.dateFin IS NULL')
            ->andWhere('grade.dateDebut <= :today OR grade.dateDebut IS NULL')
            //FICHE DE POSTE
            ->addSelect('ficheposte')->leftJoin('agent.fiches', 'ficheposte')

            ->setParameter('today', $today)
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
     * @return User[]|null
     */
    public function getResponsablesHierarchiques(Agent $agent) : ?array
    {
        $affectationPrincipale = $agent->getAffectationPrincipale();
        if ($affectationPrincipale === null) return null;
        $structure = $affectationPrincipale->getStructure();
        if ($structure === null) return null;


        while($structure->getParent() AND $structure->getParent()->getParent()) {
            $structure = $structure->getParent();
        }

        $structureResponsables = $structure->getResponsables();
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

    /** MISSION SPECIFIQUE ********************************************************************************************/

    /**
     * @param integer $id
     * @return AgentMissionSpecifique|null
     */
    public function getAgentMissionSpecifique(int $id) : ?AgentMissionSpecifique
    {
        $qb = $this->getEntityManager()->getRepository(AgentMissionSpecifique::class)->createQueryBuilder('mission')
            ->andWhere('mission.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (ORMException $e) {
            throw new RuntimeException("Plusieurs AgentMissionSpecifique partagent le même identifiant [". $id ."].", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return AgentMissionSpecifique|null
     */
    public function getRequestedAgentMissionSpecifique(AbstractActionController $controller, string $paramName = 'agent-mission-specifique') : ?AgentMissionSpecifique
    {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getAgentMissionSpecifique($id);
        return $result;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function createAgentMissionSpecifique(AgentMissionSpecifique $agentMissionSpecifique) : AgentMissionSpecifique
    {
        $this->createFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function updateAgentMissionSpecifique(AgentMissionSpecifique $agentMissionSpecifique) : AgentMissionSpecifique
    {
        $this->updateFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function historiserAgentMissionSpecifique(AgentMissionSpecifique $agentMissionSpecifique) : AgentMissionSpecifique
    {
        $this->historiserFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function restoreAgentMissionSpecifique(AgentMissionSpecifique $agentMissionSpecifique) : AgentMissionSpecifique
    {
        $this->restoreFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function deleteAgentMissionSpecifique(AgentMissionSpecifique $agentMissionSpecifique) : AgentMissionSpecifique
    {
        $this->deleteFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
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

    /** ************************************************************************************************************ **/
    /** Affectations, Status, Grades **********************************************************************************/
    /** ************************************************************************************************************ **/

    /**
     * @param Agent $agent
     * @param bool $actif
     * @return AgentAffectation[]
     */
    public function getAgentAffectationsByAgent(Agent $agent, bool $actif = true) : array
    {
        $qb = $this->getEntityManager()->getRepository(AgentAffectation::class)->createQueryBuilder('agentaffectation')
            ->addSelect('agent')->join('agentaffectation.agent', 'agent')
            ->addSelect('structure')->join('agentaffectation.structure', 'structure')
            ->andWhere('agentaffectation.agent = :agent')
            ->setParameter('agent', $agent)
            ->andWhere('agentaffectation.deleted_on IS NULL')
            ->orderBy('agentaffectation.dateDebut')
        ;

        if ($actif === true) {
            $qb = $this->decorateWithActif($qb, 'agentaffectation');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure $structure
     * @param bool $actif
     * @return AgentAffectation[]
     */
    public function getAgentAffectationsByStructure(Structure $structure, bool $actif = true) : array
    {
        $qb = $this->getEntityManager()->getRepository(AgentAffectation::class)->createQueryBuilder('agentaffectation')
            ->addSelect('agent')->join('agentaffectation.agent', 'agent')
            ->addSelect('structure')->join('agentaffectation.structure', 'structure')
            ->andWhere('agentaffectation.structure = :structure')
            ->setParameter('structure', $structure)
            ->andWhere('agentaffectation.deleted_on IS NULL')
        ;

        if ($actif === true) {
            $qb = $this->decorateWithActif($qb, 'agentaffectation');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Agent $agent
     * @param bool $actif
     * @return AgentStatut[]
     */
    public function getAgentStatutsByAgent(Agent $agent, bool $actif = true) : array
    {
        $qb = $this->getEntityManager()->getRepository(AgentStatut::class)->createQueryBuilder('agentstatut')
            ->andWhere('agentstatut.deleted_on IS NULL')
            ->orderBy('agentstatut.dateDebut')
        ;
        $qb = $this->decorateWithAgent($qb, 'agentstatut', $agent);
        $qb = $this->decorateWithStructure($qb, 'agentstatut');

        if ($actif === true) {
            $qb = $this->decorateWithActif($qb, 'agentstatut');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure $structure
     * @param bool $actif
     * @return AgentStatut[]
     */
    public function getAgentStatutsByStructure(Structure $structure, bool $actif = true) : array
    {
        $qb = $this->getEntityManager()->getRepository(AgentStatut::class)->createQueryBuilder('agentstatut')
            ->andWhere('agentstatut.deleted_on IS NULL')
        ;
        $qb = $this->decorateWithAgent($qb, 'agentstatut');
        $qb = $this->decorateWithStructure($qb, 'agentstatut', $structure);

        if ($actif === true) {
            $qb = $this->decorateWithActif($qb, 'agentstatut');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Agent $agent
     * @param bool $actif
     * @return AgentGrade[]
     */
    public function getAgentGradesByAgent(Agent $agent, bool $actif = true) : array
    {
        $qb = $this->getEntityManager()->getRepository(AgentGrade::class)->createQueryBuilder('agentgrade')
            ->addSelect('agent')->join('agentgrade.agent', 'agent')
            ->addSelect('structure')->join('agentgrade.structure', 'structure')
            ->addSelect('corps')->join('agentgrade.corps', 'corps')
            ->addSelect('corps')->join('agentgrade.grade', 'grade')
            ->addSelect('bap')->join('agentgrade.bap', 'bap')
            ->andWhere('agentgrade.agent = :agent')
            ->setParameter('agent', $agent)
            ->andWhere('agentgrade.deleted_on IS NULL')
            ->orderBy('agentgrade.dateDebut')
        ;

        if ($actif === true) {
            $qb = $this->decorateWithActif($qb, 'agentgrade');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure $structure
     * @param bool $actif
     * @return AgentGrade[]
     */
    public function getAgentGradesByStructure(Structure $structure, bool $actif = true) : array
    {
        $qb = $this->getEntityManager()->getRepository(AgentGrade::class)->createQueryBuilder('agentgrade')
            ->addSelect('agent')->join('agentgrade.agent', 'agent')
            ->addSelect('structure')->join('agentgrade.structure', 'structure')
            ->addSelect('corps')->join('agentgrade.corps', 'corps')
            ->addSelect('corps')->join('agentgrade.grade', 'grade')
            ->addSelect('bap')->join('agentgrade.bap', 'bap')
            ->andWhere('agentgrade.structure = :structure')
            ->setParameter('structure', $structure)
            ->andWhere('agentgrade.deleted_on IS NULL')
        ;

        if ($actif === true) {
            $qb = $this->decorateWithActif($qb, 'agentgrade');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** QUOTITE *******************************************************************************************************/

    /**
     * @param Agent $agent
     * @param DateTime|null $date
     * @return AgentQuotite|null
     */
    public function getAgentQuotite(Agent $agent, ?DateTime $date = null) : ?AgentQuotite
    {
        if ($date === null) $date = new DateTime();

        $qb = $this->getEntityManager()->getRepository(AgentQuotite::class)->createQueryBuilder('quotite')
            ->andWhere('quotite.agent = :agent')
            ->setParameter('agent', $agent)
            ->andWhere('quotite.debut <= :date')
            ->andWhere('quotite.fin IS NULL OR quotite.fin >= :date')
            ->setParameter('date', $date)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs AgentQuotite de retournées pour l'agent [".$agent->getId()."] en date du [".$date->format('d/m/Y')."]",0,$e);
        }
        return $result;
    }

    /** STATUTS *******************************************************************************************************/

    /**
     * @param Agent $agent
     * @param DateTime|null $date
     * @return AgentStatut[]
     */
    public function getAgentStatuts(Agent $agent, ?DateTime $date = null) : array
    {
        if ($date === null) $date = new DateTime();

        $qb = $this->getEntityManager()->getRepository(AgentStatut::class)->createQueryBuilder('astatut')
            ->leftjoin('astatut.structure', 'structure')->addSelect('structure')
            ->andWhere('astatut.agent = :agent')
            ->setParameter('agent', $agent)
            ->andWhere('astatut.dateDebut <= :date')
            ->andWhere('astatut.dateFin IS NULL OR astatut.dateFin >= :date')
            ->setParameter('date', $date)
        ;
        $result = $qb->getQuery()->getResult();
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
            ->andWhere('sr.deleted_on IS NULL')
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

        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->join('structure.gestionnaires', 'gestionnaire')
            ->andWhere('gestionnaire.id = :agentId')
            ->setParameter('agentId', $agent->getId())
            //->andWhere('structure.deleted_on IS NULL')
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

}