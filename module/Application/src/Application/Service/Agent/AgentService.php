<?php

namespace Application\Service\Agent;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentMissionSpecifique;
use Application\Entity\Db\Structure;
use Application\Service\GestionEntiteHistorisationTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Formation\Entity\Db\FormationElement;
use UnicaenApp\Exception\RuntimeException;
use UnicaenUtilisateur\Entity\Db\User;
use Zend\Mvc\Controller\AbstractActionController;

class AgentService {
    use GestionEntiteHistorisationTrait;
    use StructureServiceAwareTrait;

    /** AGENT *********************************************************************************************************/

    /**
     * @param Agent $agent
     * @return Agent
     */
    public function update(Agent $agent)
    {
        try {
            $this->getEntityManager()->flush($agent);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème a été recontré lors de la mise à jour de l'agent", $e);
        }
        return $agent;
    }

    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            //quotite de l'agent
            ->addSelect('quotite')->leftJoin('agent.quotites', 'quotite')
            //status de l'agent
            ->addSelect('statut')->leftJoin('agent.statuts', 'statut')
            ->addSelect('statut_structure')->leftJoin('statut.structure', 'statut_structure')
            //grade de l'agent
            ->addSelect('grade')->leftJoin('agent.grades', 'grade')
            ->addSelect('grade_structure')->leftJoin('grade.structure', 'grade_structure')
            ->addSelect('grade_grade')->leftJoin('grade.grade', 'grade_grade')
            ->addSelect('grade_corps')->leftJoin('grade.corps', 'grade_corps')
            ->addSelect('grade_correspondance')->leftJoin('grade.bap', 'grade_correspondance')

            //applications liées à l'agent
            ->addSelect('agentapplication')->leftJoin('agent.applications', 'agentapplication')
            ->addSelect('application')->leftJoin('agentapplication.application', 'application')
            ->addSelect('application_niveau')->leftJoin('agentapplication.niveau', 'application_niveau')
            ->addSelect('application_groupe')->leftJoin('application.groupe', 'application_groupe')
            ->addSelect('fapplication')->leftJoin('agentapplication.validation', 'fapplication')
            //competences liées à l'agent
            ->addSelect('agentcompetence')->leftJoin('agent.competences', 'agentcompetence')
            ->addSelect('competence')->leftJoin('agentcompetence.competence', 'competence')
            ->addSelect('competence_niveau')->leftJoin('agentcompetence.niveau', 'competence_niveau')
            ->addSelect('competence_theme')->leftJoin('competence.theme', 'competence_theme')
            ->addSelect('competence_type')->leftJoin('competence.type', 'competence_type')
            ->addSelect('fcompetence')->leftJoin('agentcompetence.validation', 'fcompetence')
            //formations liées à l'agent
            ->addSelect('agentformation')->leftJoin('agent.formations', 'agentformation')
            ->addSelect('formation')->leftJoin('agentformation.formation', 'formation')
            ->addSelect('formation_theme')->leftJoin('formation.theme', 'formation_theme')
            ->addSelect('fvalidation')->leftJoin('agentformation.validation', 'fvalidation')
            //missions spécifiques
//            ->addSelect('missionSpecifique')->leftJoin('agent.missionsSpecifiques', 'missionSpecifique')
//            ->addSelect('structureM')->leftJoin('missionSpecifique.structure', 'structureM')
//            ->addSelect('mission')->leftJoin('missionSpecifique.mission', 'mission')
//            ->addSelect('mission_theme')->leftJoin('mission.theme', 'mission_theme')
//            ->addSelect('mission_type')->leftJoin('mission.type', 'mission_type')

//            ->addSelect('fichePoste')->leftJoin('agent.fiches','fichePoste')
//            ->addSelect('fpPoste')->leftJoin('fichePoste.poste', 'fpPoste')
//            ->addSelect('structure')->leftJoin('fichePoste.structure', 'structure')
//


            ->addSelect('entretien')->leftJoin('agent.entretiens', 'entretien')
            ->addSelect('entretienValidationAgent')->leftJoin('entretien.validationAgent', 'entretienValidationAgent')
//            ->addSelect('evaModificateur')->leftJoin('entretienValidationAgent.histoModificateur', 'evaModificateur')
            ->addSelect('entretienValidationResponsable')->leftJoin('entretien.validationResponsable', 'entretienValidationResponsable')
//            ->addSelect('evrModificateur')->leftJoin('entretienValidationResponsable.histoModificateur', 'evrModificateur')

            ->addSelect('fichier')->leftJoin('agent.fichiers', 'fichier')



            ->addSelect('utilisateur')->leftJoin('agent.utilisateur', 'utilisateur')
            ->andWhere('agent.delete IS NULL')
        ;
        return $qb;
    }

    /**
     * @param array $temoins
     * @param string|null $order
     * @return Agent[]
     */
    public function getAgents(array $temoins = [], ?string $order = null)
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->andWhere('agent.delete IS NULL')

            ->addSelect('statut')->leftJoin('agent.statuts', 'statut')
            ->andWhere('statut.debut <= :NOW')
            ->andWhere('statut.fin >= :NOW OR statut.fin IS NULL')
            ->setParameter('NOW', $this->getDateTime())
        ;

        $tmp = [];
        foreach ($temoins as $temoin => $value) {
            if ($value) $tmp[] = 'statut.'. $temoin .' = :TRUE';
        }
        if (!empty($tmp)) {
            $qb = $qb->andWhere(implode(" OR ",$tmp))
                ->setParameter('TRUE', 'O')
            ;
        }

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
    public function getAgentsByTerm(?string $term, ?array $structures = null)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("LOWER(CONCAT(agent.prenom, ' ', agent.nomUsuel)) like :search OR LOWER(CONCAT(agent.nomUsuel, ' ', agent.prenom)) like :search")
            ->setParameter('search', '%'.strtolower($term).'%')
        ;

        if ($structures !== null) {
            $date = $this->getDateTime();
            $qb = $qb
                ->andWhere('grade.dateDebut <= :date')
                ->andWhere('grade.dateFin IS NULL OR grade.dateFin >= :date')
                ->setParameter('date', $date)
                ->addSelect('structure')->join('grade.structure', 'structure')
                ->andWhere('structure IN (:structures)')
                ->setParameter('structures', $structures)
            ;
        }
        $result =  $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer|null $id
     * @return Agent
     */
    public function getAgent(?int $id)
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
     * @return Agent
     */
    public function getRequestedAgent(AbstractActionController $controller, $paramName = 'agent')
    {
        $id = $controller->params()->fromRoute($paramName);
        $agent = $this->getAgent($id);
        return $agent;
    }

    /**
     * @param User $user
     * @return Agent
     */
    public function getAgentByUser(User $user)
    {
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
     * @return Agent
     */
    public function getAgentBySupannId(int $supannId)
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
    public function getAgentsSansFichePosteByStructure(?Structure $structure = null, bool $sousstructure = false)
    {
        $today = $this->getDateTime();

        /** !!TODO!! faire le lien entre agent et fiche de poste */
        $qb1 = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->addSelect('statut')->join('agent.statuts', 'statut')
            ->addSelect('grade')->join('agent.grades', 'grade')
            ->addSelect('structure')->join('grade.structure', 'structure')
            ->addSelect('fiche')->leftJoin('agent.fiches', 'fiche')
            ->addSelect('affectation')->join('agent.affectations', 'affectation')
            ->andWhere('statut.fin >= :today OR statut.fin IS NULL')
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
            ->andWhere('agent.delete IS NULL');

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
    public function getAgentsByStructures(array $structures)
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
            ->addSelect('statut')->join('agent.statuts', 'statut')
            ->andWhere('statut.fin >= :today OR statut.fin IS NULL')
            ->andWhere('statut.debut <= :today')
            ->andWhere('statut.dispo = :false')
            ->andWhere('statut.enseignant = :false AND statut.chercheur = :false AND statut.etudiant = :false AND statut.retraite = :false')
            //GRADE
            ->addSelect('grade')->join('agent.grades', 'grade')
            ->addSelect('gstructure')->join('grade.structure', 'gstructure')
            ->addSelect('ggrade')->join('grade.grade', 'ggrade')
            ->addSelect('gcorrespondance')->leftjoin('grade.bap', 'gcorrespondance')
            ->addSelect('gcorps')->leftjoin('grade.corps', 'gcorps')
            ->andWhere('grade.dateFin >= :today OR grade.dateFin IS NULL')
            ->andWhere('grade.dateDebut <= :today')
            //FICHE DE POSTE
            ->addSelect('ficheposte')->leftJoin('agent.fiches', 'ficheposte')

            ->setParameter('today', $today)
            ->setParameter('true', 'O')
            ->setParameter('false', 'N')
            ->andWhere('agent.delete IS NULL')
        ;

        if ($structures !== null) {
            $qb = $qb->andWhere('affectation.structure IN (:structures)')
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
    public function getAgentByIdentification($st_prenom, $st_nom, $st_annee)
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
    public function getResponsablesHierarchiques(Agent $agent) {
        $affectationPrincipale = $agent->getAffectationPrincipale();
        if ($affectationPrincipale === null) return null;
        $structure = $affectationPrincipale->getStructure();
        if ($structure === null) return null;


        while($structure->getParent() AND $structure->getParent()->getParent()) {
            $structure = $structure->getParent();
        }

        $responsables = $structure->getResponsables();
        if ($responsables !== []) return $responsables;


        return null;
    }

    /** AgentFormation ************************************************************************************************/

    /**
     * @param Agent $agent
     * @param string $annee
     * @return FormationElement[]
     */
    public function getFormationsSuiviesByAnnee(Agent $agent, string $annee)
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
     * @return AgentMissionSpecifique
     */
    public function getAgentMissionSpecifique(int $id)
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
     * @return AgentMissionSpecifique
     */
    public function getRequestedAgentMissionSpecifique(AbstractActionController $controller, $paramName = 'agent-mission-specifique')
    {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getAgentMissionSpecifique($id);
        return $result;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function createAgentMissionSpecifique(AgentMissionSpecifique $agentMissionSpecifique)
    {
        $this->createFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function updateAgentMissionSpecifique(AgentMissionSpecifique $agentMissionSpecifique)
    {
        $this->updateFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function historiserAgentMissionSpecifique(AgentMissionSpecifique $agentMissionSpecifique)
    {
        $this->historiserFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function restoreAgentMissionSpecifique(AgentMissionSpecifique $agentMissionSpecifique)
    {
        $this->restoreFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function deleteAgentMissionSpecifique(AgentMissionSpecifique $agentMissionSpecifique)
    {
        $this->deleteFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    /**
     * @param Agent[] $agents
     * @return array
     */
    public function formatAgentJSON(array $agents)
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


}