<?php

namespace EntretienProfessionnel\Service\EntretienProfessionnel;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Structure;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use Application\Service\GestionEntiteHistorisationTrait;
use Autoform\Service\Formulaire\FormulaireInstanceServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use Exception;
use Ramsey\Uuid\Uuid;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Entity\Db\Etat;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use Zend\Mvc\Controller\AbstractActionController;

class EntretienProfessionnelService {
    use GestionEntiteHistorisationTrait;
    use ConfigurationServiceAwareTrait;
    use FormulaireInstanceServiceAwareTrait;
    use ParametreServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function create(EntretienProfessionnel $entretien) : EntretienProfessionnel
    {
        $this->createFromTrait($entretien);
        $this->generateToken($entretien);
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function update(EntretienProfessionnel $entretien) : EntretienProfessionnel
    {
        $this->updateFromTrait($entretien);
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function historise(EntretienProfessionnel $entretien) : EntretienProfessionnel
    {
        $this->historiserFromTrait($entretien);
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function restore(EntretienProfessionnel $entretien) : EntretienProfessionnel
    {
        $this->restoreFromTrait($entretien);
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function delete(EntretienProfessionnel $entretien) : EntretienProfessionnel
    {
        $this->deleteFromTrait($entretien);
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
            ->addSelect('responsable')->join('entretien.responsable', 'responsable')
            ->addSelect('campagne')->join('entretien.campagne', 'campagne')
            ->addSelect('formulaireInstance')->join('entretien.formulaireInstance', 'formulaireInstance')
            ->addSelect('reponse')->leftJoin('formulaireInstance.reponses', 'reponse')
            ->addSelect('formulaire')->join('formulaireInstance.formulaire', 'formulaire')
            ->addSelect('categorie')->join('formulaire.categories', 'categorie')
            ->addSelect('champ')->join('categorie.champs', 'champ')

            ->addSelect('etat')->leftjoin('entretien.etat', 'etat')
            ->addSelect('etattype')->leftjoin('etat.type', 'etattype')

            ->addSelect('validationResponsable')->leftjoin('entretien.validationResponsable','validationResponsable')
            ->addSelect('validationAgent')->leftjoin('entretien.validationAgent','validationAgent')
            ->addSelect('validationDRH')->leftjoin('entretien.validationDRH','validationDRH')
        ;
        return $qb;
    }

    /**
     * @param Agent|null $agent
     * @param User|null $responsable
     * @param Structure|null $structure
     * @param Campagne|null $campagne
     * @param Etat|null $etat
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensProfessionnels(?Agent $agent = null, ?User $responsable = null, ?Structure $structure = null, ?Campagne  $campagne = null, ?Etat $etat = null) : array
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
            $qb = $qb->addSelect('affectation')->leftJoin('agent.affectations', 'affectation')
                ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
                ->andWhere('affectation.structure = :structure')
                ->setParameter('structure', $structure)
                ->andWhere('structure.histo IS NULL')
                ->andWhere('structure.fermeture IS NULL')
                ->andWhere('affectation.dateDebut <= entretien.dateEntretien')
                ->andWhere('affectation.dateFin IS NULL OR affectation.dateFin >= entretien.dateEntretien');
        }
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $texte
     * @return User[]
     */
    public function findResponsableByTerm(string $texte)
    {
            $qb = $this->createQueryBuilder()
                ->andWhere("LOWER(responsable.displayName) LIKE :critere")
                ->setParameter("critere", '%'.strtolower($texte).'%');
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
     * @return Agent[]
     */
    public function findAgentByTerm(string $texte)
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
     * @return Structure[]
     */
    public function findStructureByTerm(string $texte)
    {
        $qb = $this->createQueryBuilder()
            ->addSelect('affectation')->leftJoin('agent.affectations', 'affectation')
            ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
            ->andWhere('LOWER(structure.libelleLong) like :search OR LOWER(structure.libelleCourt) like :search')
            ->setParameter('search', '%'.strtolower($texte).'%')
            ->andWhere('structure.histo IS NULL')
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
     * @param integer $id
     * @return EntretienProfessionnel|null
     */
    public function getEntretienProfessionnel(int $id) : ?EntretienProfessionnel
    {
        $qb = $this->createQueryBuilder()
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
    public function getRequestedEntretienProfessionnel(AbstractActionController $controller, $paramName = 'entretien-professionnel') : ?EntretienProfessionnel
    {
        $id = $controller->params()->fromRoute($paramName);
        $entretien = $this->getEntretienProfessionnel($id);
        return $entretien;
    }

    /**
     * @param Agent $agent
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensProfessionnelsParAgent(Agent $agent) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('entretien.agent = :agent')
            ->setParameter('agent', $agent)
            ->orderBy('campagne.annee', 'ASC')
        ;

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
        $liste = ['GUIDE_EPRO', 'OUTILS_EPRO', 'GRILLE_EPRO', 'COMPETENCE_EPRO', 'DEFINITIONS_EPRO'];
        $documents = [];
        foreach ($liste as $item) {
            if ($this->getParametreService()->getParametreByCode('ENTRETIEN_PROFESSIONNEL', $item)
                and $this->getParametreService()->getParametreByCode('ENTRETIEN_PROFESSIONNEL', $item)->getValeur() !== null)
                $documents[] = $this->getParametreService()->getParametreByCode('ENTRETIEN_PROFESSIONNEL', $item)->getValeur();
        }

        return $documents;
    }
}
