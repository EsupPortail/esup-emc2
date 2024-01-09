<?php

namespace Application\Service\Agent;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAffectation;
use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentStatut;
use Application\Entity\Db\AgentSuperieur;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use Application\Provider\Parametre\GlobalParametres;
use Application\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use DateTime;
use Doctrine\DBAL\Driver\Exception as DRV_Exception;
use Doctrine\DBAL\Exception as DBA_Exception;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Fichier\Entity\Db\Fichier;
use Formation\Entity\Db\FormationElement;
use Laminas\Mvc\Controller\AbstractActionController;
use Structure\Entity\Db\Structure;
use Structure\Entity\Db\StructureAgentForce;
use Structure\Entity\Db\StructureGestionnaire;
use Structure\Entity\Db\StructureResponsable;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenParametre\Entity\Db\Parametre;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class AgentService {
    use EntityManagerAwareTrait;
    use AgentAffectationServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    use ParametreServiceAwareTrait;

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
        try {
            $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
                //affectations
                ->addSelect('affectation')->leftJoin('agent.affectations', 'affectation')
                ->addSelect('affectation_structure')->leftJoin('affectation.structure', 'affectation_structure')
                //quotite de l'agent
                ->addSelect('quotite')->leftJoin('agent.quotites', 'quotite')
                ->addSelect('utilisateur')->leftJoin('agent.utilisateur', 'utilisateur')
                ->andWhere('agent.deleted_on IS NULL')
                ->andWhere('affectation.deleted_on IS NULL');
        } catch (NotSupported $e) {
            throw new RuntimeException("Un problème est survenu lors de la création du QueryBuilder de [".Agent::class."]",0,$e);
        }
        return $qb;
    }

    public static function decorateWithTerm(QueryBuilder $qb, string $term, ?string $entityName = 'agent') : QueryBuilder
    {
        $qb = $qb->andWhere("LOWER(CONCAT(". $entityName. ".nomUsuel, ' ', ".$entityName.".prenom)) like :search OR LOWER(CONCAT(". $entityName. ".prenom, ' ', ".$entityName.".nomUsuel)) like :search")
            ->setParameter('search', '%'.strtolower($term).'%')
        ;
        return $qb;
    }

    public static function decorateWithStructure(QueryBuilder $qb, array $structures, ?string $entityName = 'affectation') : QueryBuilder
    {
        $qb = $qb->andWhere($entityName. ".structure in (:structures)")
            ->setParameter('structures', $structures)
        ;
        return $qb;
    }

    /**
     * @return Agent[]
     */
    public function getAgents() : array
    {
        try {
            $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
                ->addSelect('utilisateur')->leftjoin('agent.utilisateur', 'utilisateur')
                ->addSelect('statut')->leftjoin('agent.statuts', 'statut')
                //            ->addSelect('affectation')->leftjoin('agent.affectations', 'affectation')
                ->andWhere('agent.deleted_on IS NULL')
                ->orderBy('agent.nomUsuel, agent.prenom');
        } catch (NotSupported $e) {
            throw new RuntimeException("Un problème est survenu lors de la création du QueryBuilder de [".Agent::class."]",0,$e);
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
            ->addSelect('structure')->join('affectation.structure', 'structure')
            ->andWhere('affectation.dateDebut <= :date OR affectation.dateDebut IS NULL')
            ->andWhere('affectation.dateFin >= :date OR affectation.dateFin IS NULL')
            ->setParameter('date', $date)
        ;

        if ($term !== null) {
            $qb = AgentService::decorateWithTerm($qb, $term);
        }

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
            throw new RuntimeException("Plusieurs agents partagent le même identifiant [".$id."]",0,$e);
        }
        return $result;
    }

    /** @return Agent[] */
    public function getAgentsLargeByTerm(?string $term) : array
    {
        $params = ["term" => $term];
        $sql = <<<EOS
select a.c_individu as id
from agent a
where unaccent(LOWER(CONCAT(a.prenom, ' ', a.nom_usage))) like unaccent('%' || :term || '%') OR unaccent(LOWER(CONCAT(a.nom_usage, ' ', a.prenom))) like unaccent('%' || :term || '%')
EOS;

        // where LOWER(CONCAT(agent.prenom, ' ', agent.nomUsuel)) like :search OR LOWER(CONCAT(agent.nomUsuel, ' ', agent.prenom)) like UNACCENT(:search)
        try {
            $res = $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
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
            $agent = $this->getAgent("".$id['id'], true);
            if ($agent !== null) $agents[] = $agent;
        }
        return $agents;


//        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
//            ->andWhere("LOWER(CONCAT(agent.prenom, ' ', agent.nomUsuel)) like :search OR LOWER(CONCAT(agent.nomUsuel, ' ', agent.prenom)) like :search")
//            ->setParameter('search', '%'.strtolower($term).'%')
//        ;
//
//        $result =  $qb->getQuery()->getResult();
//        return $result;
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

        //en utilisant l'id
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->andWhere('agent.utilisateur = :user')
            ->setParameter('user', $user)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Agent liés au même User [Id:".$user->getId()." Username:".$user->getUsername()."]", $e);
        }
        if ($result !== null) return $result;

        //en utilisant l'username si echec
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->andWhere('agent.login = :username')
            ->andWhere('agent.deleted_on IS NULL')
            ->setParameter('username', $user->getUsername())
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Agent liés au même Username [".$user->getUsername()."]", $e);
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
            ->andWhere('affectation.deleted_on IS NULL')
            //STATUS
            ->addSelect('statut')->leftjoin('agent.statuts', 'statut')
//            ->andWhere('(statut.enseignant = :false AND statut.chercheur = :false AND statut.retraite = :false AND (statut.detacheOut = :false OR (statut.detacheOut = :true AND statut.detacheIn = :true)) AND statut.vacataire = :false)')
            ->andWhere('statut.deleted_on IS NULL')
            //GRADE
            ->addSelect('grade')->leftjoin('agent.grades', 'grade')
            ->addSelect('gstructure')->leftjoin('grade.structure', 'gstructure')
            ->addSelect('ggrade')->leftjoin('grade.grade', 'ggrade')
            ->addSelect('gcorrespondance')->leftjoin('grade.correspondance', 'gcorrespondance')
            ->addSelect('gcorps')->leftjoin('grade.corps', 'gcorps')
//            ->andWhere('grade.dateFin >= :today OR grade.dateFin IS NULL')
//            ->andWhere('grade.dateDebut <= :today OR grade.dateDebut IS NULL')
            ->andWhere('grade.deleted_on IS NULL')
            //FICHE DE POSTE
            ->addSelect('ficheposte')->leftJoin('agent.fiches', 'ficheposte')

            ->setParameter('today', $date)
            ->andWhere('agent.deleted_on IS NULL')

            ->orderBy('agent.nomUsuel, agent.prenom', 'ASC')
        ;

        $qb = AgentSuperieur::decorateWithAgentSuperieur($qb);
        $qb = AgentAutorite::decorateWithAgentAutorite($qb);

        if (!empty($structures)) {
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
     * TODO :: a conserver pour init des superieurs depuis les structures
     * Agent[]
     */
    public function computeSuperieures(Agent $agent, ?DateTime $date = null) : array
    {
        if ($date === null) $date = new DateTime();

       //checking structure
       $affectationsPrincipales = $this->getAgentAffectationService()->getAgentAffectationHierarchiquePrincipaleByAgent($agent);
       if ($affectationsPrincipales === null OR count($affectationsPrincipales) !== 1) return []; //throw new LogicException("Plusieurs affectations principales pour l'agent ".$agent->getId() . ":".$agent->getDenomination());

       $affectationPrincipale = $affectationsPrincipales[0];

       $structure = $affectationPrincipale->getStructure();
       do {
           $responsablesAll = array_map(function (StructureResponsable $a) {
               return $a->getAgent();
           }, $this->getStructureService()->getResponsables($structure, $date));
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
     * TODO :: a conserver pour init des autorites depuis les structures
     * Agent[]
     */
    public function computeAutorites(Agent $agent, array $superieurs = [], ?DateTime $date = null) : array
    {
        if ($date === null) $date = new DateTime();

        //checking structure
        $affectationsPrincipales = $this->getAgentAffectationService()->getAgentAffectationHierarchiquePrincipaleByAgent($agent);
        if ($affectationsPrincipales === null) return [];
        $structure = null;
        if (count($affectationsPrincipales) === 1) {
            $structure = $affectationsPrincipales[0]->getStructure()->getNiv2();
        } else {
            foreach ($affectationsPrincipales as $affectation) {
                $niveau2 = $affectation->getStructure()->getNiv2();
                if ($structure === null OR $niveau2 === $structure) $structure = $niveau2;
                else return []; //throw new LogicException("Différentes structures de niveau2 affectations principales pour l'agent");
            }
        }
        do {
            $responsablesAll = array_map(function (StructureResponsable $a) {
                return $a->getAgent();
            }, $this->getStructureService()->getResponsables($structure, $date));
            if (!in_array($agent, $responsablesAll)) {
                $responsables = [];
                foreach ($responsablesAll as $responsable) {
                    if (!in_array($responsable, $superieurs)) {
                        $responsables["structure_" . $responsable->getId()] = $responsable;
                    }
                }
                if (!empty($responsables)) return $responsables;
            }

            $structure = ($structure)?$structure->getParent():null;
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
            ->andWhere('sr.deleted_on IS NULL')
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
            ->andWhere('sg.deleted_on IS NULL')
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

    public function computesStructures(?Agent $agent, ?DateTime $date = null) : array
    {
        if ($date === null) $date = new DateTime();

        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('structure')
            ->addSelect('responsable')->leftJoin('structure.responsables', 'responsable')
            ->addSelect('gestionnaire')->leftJoin('structure.gestionnaires', 'gestionnaire')
            ->join('structure.affectations', 'affectation')
            ->andWhere('affectation.agent = :agent')->setParameter('agent', $agent)
        ;
        $qb = HasPeriodeTrait::decorateWithActif($qb,'affectation', $date);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return Agent[]
     */
    public function getAgentsWithFiltre($params) : array
    {
        $term = (isset($params['denomination']) AND trim($params['denomination']) !== '')?trim($params['denomination']):null;
        $encours = (isset($params['encours']))?$params['encours']:null;
        $structure = (isset($params['structure-filtre']) AND isset($params['structure-filtre']['id']))?$this->getStructureService()->getStructure($params['structure-filtre']['id']):null;

        $qb = $this->createQueryBuilder();
        if ($term !== null) $qb = AgentService::decorateWithTerm($qb, $term);
        if ($structure !== null) {
            $structures = $this->getStructureService()->getStructuresFilles($structure);
            $structures[] = $structure;
            $qb = AgentService::decorateWithStructure($qb, $structures);
        }
        if ($encours === '1') {
            $qb = AgentAffectation::decorateWithActif($qb, 'affectation')
                ->andWhere('affectation.deleted_on IS NULL')
            ;
        }


        $result = $qb->getQuery()->getResult();
//        if ($encours === '1') {
//            $result = array_filter($result, function(Agent $a) { return !empty($a->getAffectationsActifs()); });
//        }
        return $result;
    }

    /** FICHE DE POSTE PDF ********************************************************************************************/

    /**
     * @param Agent[]$agents
     * @return Fichier[] :: [AgentId => Fichier]
     */
    public function getFichesPostesPdfByAgents(array $agents) : array
    {

        $ids = array_map(function ($a) {
            if ($a instanceof StructureAgentForce) $a = $a->getAgent();
            return $a->getId(); }, $agents);

        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
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

    public function getAgentByLogin(string $login) : ?Agent
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agent.login = :login')->setParameter('login', $login);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Agent partagent le même login [".$login."]",0, $e);
        }
        return $result;
    }


    /** ATTENTION PEUT-ÊTRE REDONDANT !!! */

    /**
     * @param Structure[] $structures
     * @param DateTime|null $date
     * @return array
     */
    public function getAgentsByStructuresAndDate(array $structures, ?DateTime $date = null, array $temoins = []) : array
    {
        if ($date === null) $date = new DateTime();

        //extractions de la liste des agents ...
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->join('agent.affectations', 'affectation')
            ->andWhere('affectation.dateFin IS NULL OR affectation.dateDebut <= :date')
            ->andWhere('affectation.dateFin IS NULL OR affectation.dateFin >= :date')->setParameter('date', $date)
            ->andWhere('affectation.structure in (:structures)')->setParameter('structures', $structures)
        ;
        if (in_array('hierarchique', $temoins)) $qb = $qb->andWhere("affectation.hierarchique = 'O'");
        if (in_array('fonctionnelle', $temoins)) $qb = $qb->andWhere("affectation.fonctionnel = 'O'");


        $liste = $qb->getQuery()->getResult();

        $ids = [];
        foreach ($liste as $item) {
            $ids[] = $item->getId();
        }

        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->join('agent.affectations', 'affectation')->addSelect('affectation')
            ->join('affectation.structure', 'affstructure')->addSelect('affstructure')
            ->join('agent.grades', 'grade')->addSelect('grade')
            ->join('agent.statuts', 'statut')->addSelect('statut')
            ->andWhere('agent.id in (:ids)')->setParameter('ids',$ids)
            ->orderBy('agent.nomUsuel, agent.prenom', 'ASC')

//            ->join('agent.autorites', 'autorite')->addSelect('autorite')
//            ->join('autorite.autorite', 'aautorite')->addSelect('aautorite')
//            ->join('agent.superieurs', 'superieur')->addSelect('superieur')
//            ->join('autorite.autorite', 'aautorite')->addSelect('aautorite')
        ;
        $agents = $qb->getQuery()->getResult();

        $agents = array_filter($agents, function (Agent $a) use ($date) { return !$this->filtrerByStatutInvalide($a, $date);});
        return $agents;
    }


    /** Filtrer les agent·es ayant seulement un statut d'Administratif/Vacataire ou de détaché_out */
    public function filtrerByStatutInvalide(Agent $agent, ?DateTime $date = null) : bool
    {
        try {
            $code = $this->getParametreService()->getValeurForParametre(GlobalParametres::TYPE, GlobalParametres::CODE_UNIV);
        } catch (Exception $e) {
            $code = null;
        }

        $statuts = $agent->getStatutsActifs($date);
        if ($code !== null) {
            $statuts = array_filter($statuts, function (AgentStatut $a) use ($code) { return $a->getStructure()->getCode() === $code;});
        }
        if (empty($statuts)) return false;
        foreach ($statuts as $statut) {
            if (!($statut->isAdministratif() AND $statut->isVacataire())
                AND !($statut->isDetacheOut())
                AND !($statut->isDispo())
                AND !($statut->isRetraite())
            ) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param Agent[] $agents
     * @param Parametre|null $parametre
     * @param DateTime|null $date
     * @param Structure|null $structure
     * @return Agent[]
     */
    public function filtrerWithStatutTemoin(array $agents, ?Parametre $parametre, ?DateTime $date = null, ?Structure $structure = null) : array
    {
        if ($parametre === null || $parametre->getValeur() === null || $parametre->getValeur() === '') return $agents;
        if ($date === null) $date = new DateTime();

        $listing = explode(";", $parametre->getValeur());
        $on = []; $off = [];
        foreach ($listing as $item) {
            if ($item[0] === '!') $off[] = substr($item,1); else $on[] = $item;
        }

        $result = [];
        foreach ($agents as $agent) {
            $count = [];
            $statuts = $agent->getStatutsActifs($date, $structure);
            foreach ($statuts as $statut) {
                foreach (AgentStatut::TEMOINS as $temoin) {
                    if ($statut->getTemoin($temoin)) {
                        $count[$temoin] = true;
                    }
                }
            }

            $keep = true;
            foreach($on as $temoin) {
                if (!isset($count[$temoin])) {
                    $keep = false ; break;
                }
            }
            foreach($off as $temoin) {
                if (isset($count[$temoin])) {
                    $keep = false ; break;
                }
            }
            if ($keep) $result[] = $agent;
        }

        return $result;
    }

    /**
     * @param Agent[] $agents
     * @param Parametre|null $parametre
     * @param DateTime|null $date
     * @param Structure|null $structure
     * @return Agent[]
     */
    public function filtrerWithAffectationTemoin(array $agents, ?Parametre $parametre, ?DateTime $date = null, ?array $structures = null) : array
    {
        if ($parametre === null || $parametre->getValeur() === null || $parametre->getValeur() === '') return $agents;
        if ($date === null) $date = new DateTime();

        $listing = explode(";", $parametre->getValeur());
        $on = []; $off = [];
        foreach ($listing as $item) {
            if ($item[0] === '!') $off[] = substr($item,1); else $on[] = $item;
        }

        $result = [];
        foreach ($agents as $agent) {
            $count = [];
            $statuts = $agent->getAffectationsActifs($date, $structures);
            foreach ($statuts as $statut) {
                foreach (AgentAffectation::TEMOINS as $temoin) {
                    if ($statut->getTemoin($temoin)) {
                        $count[$temoin] = true;
                    }
                }
            }

            $keep = true;
            foreach($on as $temoin) {
                if (!isset($count[$temoin])) {
                    $keep = false ; break;
                }
            }
            foreach($off as $temoin) {
                if (isset($count[$temoin])) {
                    $keep = false ; break;
                }
            }
            if ($keep) $result[] = $agent;
        }

        return $result;
    }

    public function isValideEmploiType(Agent $agent, ?Parametre $parametre, ?DateTime $date = null, ?Structure $structure = null) : bool
    {
        $listing = explode(";", $parametre->getValeur());
        $on = []; $off = [];
        foreach ($listing as $item) {
            if ($item[0] === '!') $off[] = substr($item,1); else $on[] = $item;
        }

        $emploitypes = $agent->getEmploiTypesActifs($date, $structure?[$structure]:null);
        $count = [];
        foreach ($emploitypes as $grade) {
            if($grade->getEmploiType()) $count[$grade->getEmploiType()->getCode()] = true;
        }

        $keep = true;
        foreach($on as $temoin) {
            if (!isset($count[$temoin])) {
                $keep = false ; break;
            }
        }
        foreach($off as $temoin) {
            if (isset($count[$temoin])) {
                $keep = false ; break;
            }
        }
        return $keep;
    }

    /**
     * @param Agent[] $agents
     * @param Parametre|null $parametre
     * @param DateTime|null $date
     * @param Structure|null $structure
     * @return Agent[]
     */
    public function filtrerWithEmploiTypeTemoin(array $agents, ?Parametre $parametre, ?DateTime $date = null, ?Structure $structure = null) : array
    {
        if ($parametre === null || $parametre->getValeur() === null || $parametre->getValeur() === '') return $agents;
        if ($date === null) $date = new DateTime();

        $listing = explode(";", $parametre->getValeur());
        $on = []; $off = [];
        foreach ($listing as $item) {
            if ($item[0] === '!') $off[] = substr($item,1); else $on[] = $item;
        }

        $result = [];
        foreach ($agents as $agent) {
            $count = [];
            $grades = $agent->getEmploiTypesActifs($date, $structure);
            foreach ($grades as $grade) {
                if($grade->getEmploiType()) $count[$grade->getEmploiType()->getCode()] = true;
            }

            $keep = true;
            foreach($on as $temoin) {
                if (!isset($count[$temoin])) {
                    $keep = false ; break;
                }
            }
            foreach($off as $temoin) {
                if (isset($count[$temoin])) {
                    $keep = false ; break;
                }
            }
            if ($keep) $result[] = $agent;
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