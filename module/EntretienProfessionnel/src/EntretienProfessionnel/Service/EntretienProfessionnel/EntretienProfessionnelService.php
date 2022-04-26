<?php

namespace EntretienProfessionnel\Service\EntretienProfessionnel;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAffectation;
use Application\Entity\Db\Complement;

use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use UnicaenAutoform\Service\Formulaire\FormulaireInstanceServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\Delegue;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Service\Delegue\DelegueServiceAwareTrait;
use Exception;
use Ramsey\Uuid\Uuid;
use Structure\Entity\Db\Structure;
use Structure\Entity\Db\StructureResponsable;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenEtat\Entity\Db\Etat;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenValidation\Entity\Db\ValidationInstance;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;
use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class EntretienProfessionnelService {
    use AgentServiceAwareTrait;
    use EntityManagerAwareTrait;
    use ConfigurationServiceAwareTrait;
    use DelegueServiceAwareTrait;
    use FormulaireInstanceServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;
    use ValidationTypeServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function create(EntretienProfessionnel $entretien) : EntretienProfessionnel
    {
        try {
            $this->getEntityManager()->persist($entretien);
            $this->getEntityManager()->flush($entretien);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        $this->generateToken($entretien);
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function update(EntretienProfessionnel $entretien) : EntretienProfessionnel
    {
        try {
            $this->getEntityManager()->flush($entretien);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function historise(EntretienProfessionnel $entretien) : EntretienProfessionnel
    {
        try {
            $entretien->historiser();
            $this->getEntityManager()->flush($entretien);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function restore(EntretienProfessionnel $entretien) : EntretienProfessionnel
    {
        try {
            $entretien->dehistoriser();
            $this->getEntityManager()->flush($entretien);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function delete(EntretienProfessionnel $entretien) : EntretienProfessionnel
    {
        try {
            $this->getEntityManager()->remove($entretien);
            $this->getEntityManager()->flush($entretien);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $entretien;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->addSelect('agent')->join('entretien.agent', 'agent')
            ->addSelect('affectation')->join('agent.affectations', 'affectation')
            ->addSelect('astructure')->join('affectation.structure', 'astructure')

            ->addSelect('responsable')->join('entretien.responsable', 'responsable')
            ->addSelect('campagne')->join('entretien.campagne', 'campagne')
            ->addSelect('validation')->leftjoin('entretien.validations','validation')
        ;

        $qb = EntretienProfessionnel::decorateWithEtat($qb, 'entretien');
        $qb = AgentAffectation::decorateWithActif($qb, 'affectation');
        return $qb;
    }

    /**
     * @param Agent|null $agent
     * @param Agent|null $responsable
     * @param Structure|null $structure
     * @param Campagne|null $campagne
     * @param Etat|null $etat
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensProfessionnels(?Agent $agent = null, ?Agent $responsable = null, ?Structure $structure = null, ?Campagne  $campagne = null, ?Etat $etat = null) : array
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
            $qb = $qb->andWhere('entretien.etat = :etat')
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
    public function findAgentByTerm(string $texte) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("LOWER(CONCAT(agent.prenom, ' ', agent.nomUsuel)) like :search OR LOWER(CONCAT(agent.nomUsuel, ' ', agent.prenom)) like :search")
            ->setParameter('search', '%'.strtolower($texte).'%');
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
    public function findResponsableByTerm(string $texte) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("LOWER(CONCAT(responsable.prenom, ' ', responsable.nomUsuel)) like :search OR LOWER(CONCAT(responsable.nomUsuel, ' ', responsable.prenom)) like :search")
            ->setParameter('search', '%'.strtolower($texte).'%');
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
    public function findStructureByTerm(string $texte) : array
    {
        $qb = $this->createQueryBuilder()
            ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
            ->andWhere('LOWER(structure.libelleLong) like :search OR LOWER(structure.libelleCourt) like :search')
            ->setParameter('search', '%'.strtolower($texte).'%')
            ->andWhere('structure.fermeture IS NULL')
            ->andWhere('affectation.dateDebut <= entretien.dateEntretien')
            ->andWhere('affectation.dateFin IS NULL OR affectation.dateFin >= entretien.dateEntretien')
        ;
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
    public function getEntretienProfessionnel(?int $id) : ?EntretienProfessionnel
    {
        $qb = $this->createQueryBuilder()
            ->addSelect('formulaireInstance')->join('entretien.formulaireInstance', 'formulaireInstance')
            ->addSelect('reponse')->leftJoin('formulaireInstance.reponses', 'reponse')
            ->addSelect('formulaire')->leftJoin('formulaireInstance.formulaire', 'formulaire')
            ->addSelect('categorie')->leftJoin('formulaire.categories', 'categorie')
            ->addSelect('champ')->leftJoin('categorie.champs', 'champ')
            ->andWhere('entretien.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EntretienProfessionnel partagent le même identifiant [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return EntretienProfessionnel
     */
    public function getRequestedEntretienProfessionnel(AbstractActionController $controller, string $paramName = 'entretien-professionnel') : ?EntretienProfessionnel
    {
        $id = $controller->params()->fromRoute($paramName);
        $entretien = $this->getEntretienProfessionnel($id);
        return $entretien;
    }

    /**
     * @param Agent $agent
     * @return array
     */
    public function getEntretiensProfessionnelsByAgent(Agent $agent)  : array
    {
        $qb = $this->getEntityManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->leftJoin('entretien.responsable', 'responsable')->addSelect('responsable')
            ->andWhere('entretien.agent = :agent')
            ->setParameter('agent', $agent);

        $qb = EntretienProfessionnel::decorateWithEtat($qb, 'entretien');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Agent $delegue
     * @return array
     */
    public function getEntretiensProfessionnelsByDelegue(Agent $delegue)  : array
    {
        $qb = $this->getEntityManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->leftJoin('entretien.responsable', 'responsable')->addSelect('responsable')
            ->andWhere('entretien.responsable = :delegue')
            ->setParameter('delegue', $delegue);

        $qb = EntretienProfessionnel::decorateWithEtat($qb, 'entretien');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string token
     * @return EntretienProfessionnel|null
     */
    public function getEntretienProfessionnelByToken(string $token) : ?EntretienProfessionnel
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('entretien.token = :token')
            ->setParameter('token', $token)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EntretienProfessionnel partagent le même token [".$token."]", $e);
        }
        return $result;
    }

    /**
     * @param EntretienProfessionnel $entretien
     * @return EntretienProfessionnel|null
     */
    public function getPreviousEntretienProfessionnel(EntretienProfessionnel $entretien) : ?EntretienProfessionnel
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

        if ($result === null) return null;
        return $result[0];
    }

    /**
     * @param Agent $agent
     * @param Campagne $campagne
     * @return EntretienProfessionnel|null
     */
    public function getEntretienProfessionnelByAgentAndCampagne(Agent $agent, Campagne $campagne) : ?EntretienProfessionnel
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('entretien.agent = :agent')
            ->setParameter('agent', $agent)
            ->andWhere('entretien.campagne = :campagne')
            ->setParameter('campagne', $campagne)
            ->andWhere('entretien.histoDestruction IS NULL')
            ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs entretiens professionnels ont été trouvés pour la campagne [".$campagne->getId()."|".$campagne->getAnnee()."] et l'agent [".$agent->getId()."|".$agent->getDenomination()."]",0,$e);
        }
        return $result;
    }

    /** FONCTIONS UTILITAIRES *****************************************************************************************/

    /**
     * @param EntretienProfessionnel $entretien
     * @return EntretienProfessionnel
     */
    public function generateToken(EntretienProfessionnel $entretien) : EntretienProfessionnel
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

    /**
     * @param EntretienProfessionnel $entretien
     * @return EntretienProfessionnel
     */
    public function recopiePrecedent(EntretienProfessionnel $entretien) : EntretienProfessionnel
    {
        $previous = $this->getPreviousEntretienProfessionnel($entretien);
        if ($previous) {
            $recopies = $this->getConfigurationService()->getConfigurationsEntretienProfessionnel();
            foreach ($recopies as $recopie) {
                $splits = explode(";", $recopie->getValeur());
                $this->getFormulaireInstanceService()->recopie($previous->getFormulaireInstance(), $entretien->getFormulaireInstance(), $splits[0], $splits[1]);
            }
        }
        return $entretien;
    }

    /**
     * @return array
     */
    public function getDocumentsUtiles() : array
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
     * @param Structure $structure
     * @param string $term
     * @return Agent[]
     */
    public function findResponsablePourEntretien(Structure $structure, string $term) : array
    {
        $result_resp = array_map(
            function (StructureResponsable $a) { return $a->getAgent(); },
            $structure->getResponsables()
        );
        $result_resp = array_filter($result_resp, function (Agent $a) use ($term) { return str_contains(strtolower($a->getDenomination()),strtolower($term)); });

        if ($structure->getParent() AND $structure->getParent() !== $structure) {
            $parent = $this->findResponsablePourEntretien($structure->getParent(), $term);
            $result_resp = array_merge($result_resp, $parent);
        }
        return $result_resp;
    }

    public function findDeleguePourEntretien(?Structure $structure, Campagne $campagne, $term) : array
    {
        $result_dele = array_map(
            function (Delegue $a) { return $a->getAgent(); },
            $this->getDelegueService()->getDeleguesByStructureAndCampagne($structure, $campagne)
        );
        $result_dele = array_filter($result_dele,function (Agent $a) use ($term) { return str_contains(strtolower($a->getDenomination()),strtolower($term)); });

        if ($structure->getParent() AND $structure->getParent() !== $structure) {
            $parent = $this->findDeleguePourEntretien($structure->getParent(), $campagne, $term);
            $result_dele = array_merge($result_dele, $parent);
        }
        return $result_dele;
    }

    /**
     * @param Agent $agent
     * @param string $term
     * @return Agent[]|null[]
     */
    public function findSuperieurPourEntretien(Agent $agent, string $term) : array
    {
        $superieurs = [];

        /** @var Complement[] $complements */
        $complements = $agent->getComplementsByType(Complement::COMPLEMENT_TYPE_RESPONSABLE);
        foreach ($complements as $complement) {
            $superieur = $this->getAgentService()->getAgent($complement->getComplementId());
            if ($superieur !== null) {
                $superieurs[$superieur->getId()] = $superieur;
            }
        }


        $result = array_filter($superieurs,function (Agent $a) use ($term) { return str_contains(strtolower($a->getDenomination()),strtolower($term)); });
        return $result;
    }

    /**
     * @param EntretienProfessionnel $entretien
     * @return EntretienProfessionnel
     */
    public function initialiser(EntretienProfessionnel $entretien) : EntretienProfessionnel
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
     * @param string $type
     * @param EntretienProfessionnel|null $entretien
     * @param string|null $value
     * @return ValidationInstance
     */
    public function addValidation(string $type, ?EntretienProfessionnel $entretien, ?string $value = null) : ValidationInstance
    {
        $vtype = $this->getValidationTypeService()->getValidationTypeByCode($type);
        $validation = new ValidationInstance();
        $validation->setEntity($entretien);
        $validation->setType($vtype);
        $validation->setValeur($value);
        $this->getValidationInstanceService()->create($validation);
        $entretien->addValidation($validation);
        $this->update($entretien);
        return $validation;
    }
}
