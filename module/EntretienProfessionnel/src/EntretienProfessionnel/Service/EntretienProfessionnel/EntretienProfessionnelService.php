<?php

namespace EntretienProfessionnel\Service\EntretienProfessionnel;

use Application\Entity\Db\Agent;
use Agent\Entity\Db\AgentAffectation;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use Exception;
use Laminas\Mvc\Controller\AbstractActionController;
use Ramsey\Uuid\Uuid;
use Structure\Entity\Db\Structure;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenAutoform\Service\Formulaire\FormulaireInstanceServiceAwareTrait;
use UnicaenEtat\Entity\Db\EtatType;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;
use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;

class EntretienProfessionnelService
{
    use AgentServiceAwareTrait;
    use ProvidesObjectManager;
    use ConfigurationServiceAwareTrait;
    use FormulaireInstanceServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use StructureServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;
    use ValidationTypeServiceAwareTrait;

    public array $config = [];

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(EntretienProfessionnel $entretien): EntretienProfessionnel
    {
        $this->getObjectManager()->persist($entretien);
        $this->getObjectManager()->flush($entretien);
        $this->generateToken($entretien);
        return $entretien;
    }

    public function update(EntretienProfessionnel $entretien): EntretienProfessionnel
    {
        $this->getObjectManager()->flush($entretien);
        return $entretien;
    }

    public function historise(EntretienProfessionnel $entretien): EntretienProfessionnel
    {
        $entretien->historiser();
        $this->getObjectManager()->flush($entretien);
        return $entretien;
    }

    public function restore(EntretienProfessionnel $entretien): EntretienProfessionnel
    {
        $entretien->dehistoriser();
        $this->getObjectManager()->flush($entretien);
        return $entretien;
    }

    public function delete(EntretienProfessionnel $entretien): EntretienProfessionnel
    {
        $this->getObjectManager()->remove($entretien);
        $this->getObjectManager()->flush($entretien);
        return $entretien;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(bool $withAffectation = true): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->addSelect('agent')->leftjoin('entretien.agent', 'agent')
            ->addSelect('fichesa')->leftjoin('agent.fiches', 'fichesa')
            ->addSelect('affectation')->leftjoin('agent.affectations', 'affectation')
            ->addSelect('astructure')->leftjoin('affectation.structure', 'astructure')
            ->addSelect('responsable')->leftjoin('entretien.responsable', 'responsable')
            ->addSelect('fichesr')->leftjoin('responsable.fiches', 'fichesr')
            ->addSelect('campagne')->leftjoin('entretien.campagne', 'campagne')
            ->addSelect('validation')->leftjoin('entretien.validations', 'validation')
            ->addSelect('observation')->leftjoin('entretien.observations', 'observation')
            ->addSelect('ovalidation')->leftjoin('observation.validations', 'ovalidation')
            ->addSelect('vtype')->leftjoin('validation.type', 'vtype');

        //cette jointure fait exploser le temps de récupération ...
//        $qb  = $qb->leftJoin('agent.superieurs', 'agentsuperieur')->addSelect('agentsuperieur');
//        $qb  = $qb->leftJoin('agent.autorites', 'agentautorite')->addSelect('agentautorite');


        $qb = EntretienProfessionnel::decorateWithEtats($qb, 'entretien'); //todo remettre
        if ($withAffectation) $qb = AgentAffectation::decorateWithActif($qb, 'affectation');


        return $qb;
    }

    /** @return EntretienProfessionnel[] */
    public function getEntretiensProfessionnels(?Agent $agent = null, ?Agent $responsable = null, ?Structure $structure = null, ?Campagne $campagne = null, ?EtatType $etat = null): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('campagne.annee, agent.nomUsuel, agent.prenom');
        if ($agent !== null) {
            $qb = $qb->andWhere('entretien.agent = :agent')
                ->setParameter('agent', $agent);
        }
        if ($responsable !== null) {
            $qb = $qb->andWhere('entretien.responsable = :responsable')
                ->setParameter('responsable', $responsable);
        }
        if ($campagne !== null) {
            $qb = $qb->andWhere('entretien.campagne = :campagne')
                ->setParameter('campagne', $campagne);
        }
        if ($etat !== null) {
            $qb = $qb->andWhere('etat.type = :etat')
                ->setParameter('etat', $etat);
        }
        if ($structure !== null) {
            $qb = $qb
                ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
                ->andWhere('affectation.structure = :structure')
                ->setParameter('structure', $structure)
                ->andWhere('structure.fermeture IS NULL')
                ->andWhere('affectation.dateDebut <= entretien.dateEntretien')
                ->andWhere('affectation.dateFin IS NULL OR affectation.dateFin >= entretien.dateEntretien');
        }
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $texte
     * @return Agent[]
     */
    public function findAgentByTerm(string $texte): array
    {
        $qb = $this->createQueryBuilder(false)
            ->andWhere("LOWER(CONCAT(agent.prenom, ' ', agent.nomUsuel)) like :search OR LOWER(CONCAT(agent.nomUsuel, ' ', agent.prenom)) like :search")
            ->setParameter('search', '%' . strtolower($texte) . '%');
        $result = $qb->getQuery()->getResult();

        $agents = [];
        /** @var EntretienProfessionnel $item */
        foreach ($result as $item) {
            $agent = $item->getAgent();
            $agents[$agent->getId()] = $agent;
        }
        return $agents;
    }

    /**
     * @param string $texte
     * @return Agent[]
     */
    public function findResponsableByTerm(string $texte): array
    {
        $qb = $this->createQueryBuilder(false)
            ->andWhere("LOWER(CONCAT(responsable.prenom, ' ', responsable.nomUsuel)) like :search OR LOWER(CONCAT(responsable.nomUsuel, ' ', responsable.prenom)) like :search")
            ->setParameter('search', '%' . strtolower($texte) . '%');
        $result = $qb->getQuery()->getResult();

        $responsables = [];
        /** @var EntretienProfessionnel $item */
        foreach ($result as $item) {
            $responsable = $item->getResponsable();
            $responsables[$responsable->getId()] = $responsable;
        }
        return $responsables;
    }

    /**
     * @param string $texte
     * @return Structure[]
     */
    public function findStructureByTerm(string $texte): array
    {
        $qb = $this->createQueryBuilder(false)
            ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
            ->andWhere('LOWER(structure.libelleLong) like :search OR LOWER(structure.libelleCourt) like :search')
            ->setParameter('search', '%' . strtolower($texte) . '%')
            ->andWhere('structure.fermeture IS NULL')
            ->andWhere('affectation.dateDebut <= entretien.dateEntretien')
            ->andWhere('affectation.dateFin IS NULL OR affectation.dateFin >= entretien.dateEntretien');
        $result = $qb->getQuery()->getResult();

        $structures = [];
        /** @var EntretienProfessionnel $item */
        foreach ($result as $item) {
            $affections = $item->getAgent()->getAffectations($item->getDateEntretien());
            foreach ($affections as $affectation) {
                $structures[$affectation->getStructure()->getId()] = $affectation->getStructure();
            }
        }
        return $structures;
    }

    /**
     * @param int|null $id
     * @return EntretienProfessionnel|null
     */
    public function getEntretienProfessionnel(?int $id): ?EntretienProfessionnel
    {
        $qb = $this->createQueryBuilder(false)
//            ->addSelect('crep')->leftJoin('entretien.formulaireInstance', 'crep')
//            ->addSelect('crep_reponse')->leftJoin('crep.reponses', 'crep_reponse')
//            ->addSelect('crep_champ')->leftJoin('crep_reponse.champ', 'crep_champ')
//            ->addSelect('crep_categorie')->leftJoin('crep_champ.categorie', 'crep_categorie')
//            ->addSelect('cref')->leftJoin('entretien.formationInstance', 'cref')
//            ->addSelect('cref_reponse')->leftJoin('cref.reponses', 'cref_reponse')
//            ->addSelect('cref_champ')->leftJoin('cref_reponse.champ', 'cref_champ')
//            ->addSelect('cref_categorie')->leftJoin('cref_champ.categorie', 'cref_categorie')
            ->andWhere('entretien.id = :id')->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EntretienProfessionnel partagent le même identifiant [" . $id . "]", $e);
        }
        return $result;
    }

    public function getRequestedEntretienProfessionnel(AbstractActionController $controller, string $paramName = 'entretien-professionnel'): ?EntretienProfessionnel
    {
        $id = $controller->params()->fromRoute($paramName);
        $entretien = $this->getEntretienProfessionnel($id);
        return $entretien;
    }

    /** @return EntretienProfessionnel[] */
    public function getEntretiensProfessionnelsByAgent(Agent $agent, bool $histo = false, bool $withAffectation = false): array
    {
        $qb = $this->createQueryBuilder($withAffectation)
            ->andWhere('entretien.agent = :agent')->setParameter('agent', $agent);
        if ($histo === false) $qb = $qb->andWhere('entretien.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getEntretienProfessionnelByToken(string $token): ?EntretienProfessionnel
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('entretien.token = :token')
            ->setParameter('token', $token);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EntretienProfessionnel partagent le même token [" . $token . "]", $e);
        }
        return $result;
    }

    /**
     * @param EntretienProfessionnel $entretien
     * @return EntretienProfessionnel|null
     */
    public function getPreviousEntretienProfessionnel(EntretienProfessionnel $entretien): ?EntretienProfessionnel
    {
        $agent = $entretien->getAgent();
        $date = $entretien->getDateEntretien();

        $qb = $this->createQueryBuilder()
            ->andWhere('entretien.agent = :agent')
            ->andWhere('entretien.dateEntretien < :date')
            ->setParameter('agent', $agent)
            ->setParameter('date', $date)
            ->orderBy('entretien.dateEntretien', 'DESC');
        $result = $qb->getQuery()->getResult();

        if (empty($result)) return null;
        return $result[0];
    }

    /**
     * @param Agent $agent
     * @param Campagne $campagne
     * @return EntretienProfessionnel|null
     */
    public function getEntretienProfessionnelByAgentAndCampagne(Agent $agent, Campagne $campagne): ?EntretienProfessionnel
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('entretien.agent = :agent')
            ->setParameter('agent', $agent)
            ->andWhere('entretien.campagne = :campagne')
            ->setParameter('campagne', $campagne)
            ->andWhere('entretien.histoDestruction IS NULL');

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs entretiens professionnels ont été trouvés pour la campagne [" . $campagne->getId() . "|" . $campagne->getAnnee() . "] et l'agent [" . $agent->getId() . "|" . $agent->getDenomination() . "]", 0, $e);
        }
        return $result;
    }

    /** @return EntretienProfessionnel[] */
    public function getEntretiensProfessionnelsByResponsableAndCampagne(Agent $responsable, ?Campagne $campagne = null, bool $histo = false, bool $withAffectation = true): array
    {
        $qb = $this->createQueryBuilder($withAffectation)
            ->andWhere('entretien.responsable = :responsable')->setParameter('responsable', $responsable);
        if (!$histo) $qb = $qb->andWhere('entretien.histoDestruction IS NULL');
        if ($campagne != null) $qb = $qb->andWhere('entretien.campagne = :campagne')->setParameter('campagne', $campagne);

        return $qb->getQuery()->getResult();
    }

    /** FONCTIONS UTILITAIRES *****************************************************************************************/

    /**
     * @param EntretienProfessionnel $entretien
     * @return EntretienProfessionnel
     */
    public function generateToken(EntretienProfessionnel $entretien): EntretienProfessionnel
    {
        try {
            $token = Uuid::uuid4();
        } catch (Exception $e) {
            throw new RuntimeException("Erreur rencontrée lors de la génération du UUID.", null, $e);
        }
        $entretien->setToken($token);
        $entretien->setAcceptation(null);
        $this->update($entretien);
        return $entretien;
    }

    public function recopiePrecedent(EntretienProfessionnel $entretien): EntretienProfessionnel
    {
        $previous = $this->getPreviousEntretienProfessionnel($entretien);
        if ($previous) {
            $recopies = $this->getConfigurationService()->getConfigurationsEntretienProfessionnel();
            foreach ($recopies as $recopie) {
                [$form, $ids] = explode('|', $recopie->getValeur());
                [$from, $to] = explode(';', $ids);
                $previousFormulaire = null; $currentFormulaire = null;
                if ($form === 'CREP') {
                    $previousFormulaire = $previous->getFormulaireInstance();
                    $currentFormulaire = $entretien->getFormulaireInstance();
                }
                if ($form === 'CREF') {
                    $previousFormulaire = $previous->getFormationInstance();
                    $currentFormulaire = $entretien->getFormationInstance();
                }
                if ($previousFormulaire !== null && $currentFormulaire !== null) {
                    $this->getFormulaireInstanceService()->recopie($previousFormulaire, $currentFormulaire, $from, $to);
                }
            }
        }
        return $entretien;
    }

    /**
     * @return array
     */
    public function getDocumentsUtiles(): array
    {
        $liste = ['INTRANET_DOCUMENT'];
        $documents = [];
        foreach ($liste as $item) {
            if ($this->getParametreService()->getParametreByCode('ENTRETIEN_PROFESSIONNEL', $item)
                and $this->getParametreService()->getParametreByCode('ENTRETIEN_PROFESSIONNEL', $item)->getValeur() !== null) {
                $value = $this->getParametreService()->getParametreByCode('ENTRETIEN_PROFESSIONNEL', $item)->getValeur();
                $documents[] = $value;
            }
        }
        return $documents;
    }

    /**
     * @param EntretienProfessionnel $entretien
     * @return EntretienProfessionnel
     */
    public function initialiser(EntretienProfessionnel $entretien): EntretienProfessionnel
    {
        $entretien_instance = $this->getFormulaireInstanceService()->createInstance('CREP');
        $formation_instance = $this->getFormulaireInstanceService()->createInstance('CREF');
        $entretien->setFormulaireInstance($entretien_instance);
        $entretien->setFormationInstance($formation_instance);
        $this->create($entretien);
        $this->recopiePrecedent($entretien);
        return $entretien;
    }

    /**
     * @param array $params
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensProfessionnelsWithFiltre(array $params): array
    {
        $qb = $this->getObjectManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->join('entretien.agent', 'agent')->addSelect('agent')
            ->join('entretien.responsable', 'responsable')->addSelect('responsable')
            ->join('entretien.campagne', 'campagne')->addSelect('campagne')
            ->join('entretien.etats', 'etat')->addSelect('etat')
            ->join('etat.type', 'etype')->addSelect('etype');

        if ($params['campagne'] !== null and $params['campagne'] !== "") {
            $qb = $qb->andWhere('campagne.id = :campagne')->setParameter('campagne', $params['campagne']);
        }
        if (isset($params['etat']) and $params['etat'] !== "") {
            $qb = $qb->andWhere('etype.id = :etat')->setParameter('etat', $params['etat']);
        }
        // NOTE Changement provoqué par la gestion des structures "mères"
//        if (isset($params['structure-filtre']) and $params['structure-filtre']['id'] !== "") {
//            $qb = $qb
//                ->leftJoin('agent.affectations', 'affectation')->addSelect('affectation')
//                ->leftJoin('affectation.structure', 'structure')->addSelect('structure')
//                ->andWhere('structure.id = :structure')->setParameter('structure', $params['structure-filtre']['id'])
//                ->andWhere('affectation.dateDebut IS NULL OR affectation.dateDebut <= entretien.dateEntretien')
//                ->andWhere('affectation.dateFin IS NULL OR affectation.dateFin >= entretien.dateEntretien')
//                ->andWhere('affectation.id IS NOT NULL');
//        }


        if (isset($params['agent-filtre']) and $params['agent-filtre']['id'] !== "") {
            $qb = $qb->andWhere('agent.id = :id')->setParameter('id', $params['agent-filtre']['id']);
        }
        if (isset($params['responsable-filtre']) and $params['responsable-filtre']['id'] !== "") {
            $qb = $qb->andWhere('responsable.id = :id')->setParameter('id', $params['responsable-filtre']['id']);
        }

        $result = $qb->getQuery()->getResult();

        if (isset($params['structure-filtre']) and $params['structure-filtre']['id'] !== "") {
            $structure = $this->getStructureService()->getStructure($params['structure-filtre']['id']);
            // NOTE Changement provoqué par la gestion des structures "mères"
            $entretiens = [];
            /** @var EntretienProfessionnel $item */
            foreach ($result as $item) {
                $agent = $item->getAgent();
                $affectations = $agent->getAffectations($item->getDateEntretien());
                //todo filtrer les affectations ?
                foreach ($affectations as $affectation) {
                    if ($affectation->getStructure()->isCompatible($structure)) {
                        $entretiens[] = $item;
                        break;
                    }
                }
            }
            $result = $entretiens;
        }

        return $result;
    }

    /** @return EntretienProfessionnel[] @desc [agentId => entretien] */
    public function getEntretienProfessionnelByCampagneAndAgents(?Campagne $campagne, array $agents, bool $histo = false, bool $withAffectation = true): array
    {
        if ($campagne === null) return [];

        $qb = $this->createQueryBuilder($withAffectation)
            ->andWhere('entretien.campagne = :campagne')->setParameter('campagne', $campagne)
            ->andWhere('entretien.agent in (:agents)')->setParameter('agents', $agents);
        //gestion de l'affectation en date de la campagne (pourquoi ?)
//        $qb = $qb
//            ->andWhere('affectation.dateDebut IS NULL OR affectation.dateDebut <= :dateDebut')->setParameter('dateDebut', $campagne->getDateDebut())
//            ->andWhere('affectation.dateFin IS NULL OR affectation.dateFin >= :dateFin')->setParameter('dateFin', $campagne->getDateFin())
//        ;
        if ($histo === false) $qb = $qb->andWhere('entretien.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();

        $dictionnaire = [];
        foreach ($result as $entretien) {
            $dictionnaire[$entretien->getAgent()->getId()] = $entretien;
        }
        return $dictionnaire;
    }


    /** @return  EntretienProfessionnel[] */
    public function getEntretiensProfessionnelsByCampagne(Campagne $campagne, bool $sortByEtat = false): array
    {
        $qb = $this->getObjectManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->addSelect('agent')->leftjoin('entretien.agent', 'agent')
            ->addSelect('responsable')->leftjoin('entretien.responsable', 'responsable')
            ->addSelect('campagne')->leftjoin('entretien.campagne', 'campagne')
            ->andWhere('entretien.campagne = :campagne')->setParameter('campagne', $campagne)
            ->andWhere('entretien.histoDestruction IS NULL')
        ;
        $qb = EntretienProfessionnel::decorateWithEtats($qb, 'entretien');


        /** @var EntretienProfessionnel[] $entretiens */
        $entretiens = $qb->getQuery()->getResult();
        if (!$sortByEtat) return $entretiens;
        return $this->sortByEtat($entretiens);
    }

    /**
     * @var EntretienProfessionnel[] $entretiens
     * @return EntretienProfessionnel[]
     */
    public function sortByEtat(array $entretiens): array
    {
        $dictionnaire = [];
        foreach ($entretiens as $entretien) {
            $etat = $entretien->getEtatActif()->getType()->getCode();
            $dictionnaire[$etat][] = $entretien;
        }
        return $dictionnaire;
    }
}
