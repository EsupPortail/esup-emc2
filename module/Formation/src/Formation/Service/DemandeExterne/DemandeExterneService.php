<?php

namespace Formation\Service\DemandeExterne;

use Application\Entity\Db\Agent;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\DemandeExterne;
use Formation\Provider\Validation\DemandeExterneValidations;
use Laminas\Mvc\Controller\AbstractActionController;
use Structure\Service\Structure\StructureServiceAwareTrait;
use Structure\Entity\Db\Structure;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenValidation\Entity\Db\ValidationInstance;
use UnicaenValidation\Entity\Db\ValidationType;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;
use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;

class DemandeExterneService {
    use EntityManagerAwareTrait;
    use StructureServiceAwareTrait;
    use ValidationTypeServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;

    /** GESTION ENTITE ************************************************************************************************/

    public function create(DemandeExterne $demande) : DemandeExterne
    {
        try {
            $this->getEntityManager()->persist($demande);
            $this->getEntityManager()->flush($demande);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue en BD.", 0, $e);
        }
        return $demande;
    }

    public function update(DemandeExterne $demande) : DemandeExterne
    {
        try {
            $this->getEntityManager()->flush($demande);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue en BD.", 0, $e);
        }
        return $demande;
    }

    public function historise(DemandeExterne $demande) : DemandeExterne
    {
        try {
            $demande->historiser();
            $this->getEntityManager()->flush($demande);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue en BD.", 0, $e);
        }
        return $demande;
    }

    public function restore(DemandeExterne $demande) : DemandeExterne
    {
        try {
            $demande->dehistoriser();
            $this->getEntityManager()->flush($demande);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue en BD.", 0, $e);
        }
        return $demande;
    }

    public function delete(DemandeExterne $demande) : DemandeExterne
    {
        try {
            $this->getEntityManager()->remove($demande);
            $this->getEntityManager()->flush($demande);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue en BD.", 0, $e);
        }
        return $demande;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(DemandeExterne::class)->createQueryBuilder('demande')
            ->join('demande.agent', 'agent')->addSelect('agent');
        return $qb;
    }

    public function getDemandeExterne(?int $id) : ?DemandeExterne
    {
        $qb = $this->createQueryBuilder()
         ->andWhere('demande.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs DemandeExterne partagent le même id [".$id."]", 0, $e);
        }
        return $result;
    }

    public function getRequestedDemandeExterne(AbstractActionController $controller, string $param = 'demande-externe') : ?DemandeExterne
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getDemandeExterne($id);
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return DemandeExterne[]
     */
    public function getDemandesExternes(string $champ = 'histoCreation', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('demande.'.$champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param array $params ['agent' Agent, 'organisme' string, 'etat' Etat, 'histo' ??? ]
     * @param string $champ
     * @param string $ordre
     * @return array
     */
    public function getDemandesExternesWithFiltre(array $params, string $champ = 'histoCreation', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()->orderBy('demande.'.$champ, $ordre);

        if (isset($params['agent'])) $qb = $qb->andWhere('demande.agent = :agent')->setParameter('agent', $params['agent']);
        if (isset($params['organisme']) AND trim($params['organisme'] !== '')) $qb = $qb->andWhere('demande.organisme = :organisme')->setParameter('organisme', $params['organisme']);
        if (isset($params['etat'])) $qb = $qb->andWhere('demande.etat = :etat')->setParameter('etat', $params['etat']);
        if (isset($params['historise'])) {
            if ($params['historise'] === '1') $qb = $qb->andWhere('demande.histoDestruction IS NOT NULL');
            if ($params['historise'] === '0') $qb = $qb->andWhere('demande.histoDestruction IS NULL');
        }


        $result = $qb->getQuery()->getResult();
        return $result;

    }
    /**
     * @param Agent $agent
     * @param string $champ
     * @param string $ordre
     * @return DemandeExterne[]
     */
    public function getDemandesExternesByAgent(Agent $agent, string $champ = 'histoCreation', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('demande.agent = :agent')->setParameter('agent', $agent)
            ->orderBy('demande.'.$champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function addValidation(ValidationType $type, ?DemandeExterne $demande, ?string $justification = null) : ValidationInstance
    {
        $validation = new ValidationInstance();
        $validation->setEntity($demande);
        $validation->setType($type);
        $validation->setValeur($justification);
        $this->getValidationInstanceService()->create($validation);
        $demande->addValidation($validation);
        $this->update($demande);
        return $validation;
    }

    public function addValidationAgent(?DemandeExterne $demande, ?string $justification = null) : ValidationInstance
    {
        $vtype = $this->getValidationTypeService()->getValidationTypeByCode(DemandeExterneValidations::FORMATION_DEMANDE_AGENT);
        return $this->addValidation($vtype, $demande, $justification);
    }

    public function addValidationResponsable(?DemandeExterne $demande, ?string $justification = null) : ValidationInstance
    {
        $vtype = $this->getValidationTypeService()->getValidationTypeByCode(DemandeExterneValidations::FORMATION_DEMANDE_RESPONSABLE);
        return $this->addValidation($vtype, $demande, $justification);
    }

    public function addValidationDrh(?DemandeExterne $demande, ?string $justification = null) : ValidationInstance
    {
        $vtype = $this->getValidationTypeService()->getValidationTypeByCode(DemandeExterneValidations::FORMATION_DEMANDE_DRH);
        return $this->addValidation($vtype, $demande, $justification);
    }

    public function addValidationRefus(?DemandeExterne $demande, ?string $justification = null) : ValidationInstance
    {
        $vtype = $this->getValidationTypeService()->getValidationTypeByCode(DemandeExterneValidations::FORMATION_DEMANDE_REFUS);
        return $this->addValidation($vtype, $demande, $justification);
    }

    /**
     * @param Structure $structure
     * @param bool $avecStructuresFilles
     * @param bool $anneeCourrante
     * @return DemandeExterne[]
     */
    public function getDemandeByStructure(Structure $structure, bool $avecStructuresFilles = false, bool $anneeCourrante = false) : array
    {
        $structures = [];
        $structures[] = $structure;

        if ($avecStructuresFilles === true) {
            $structures = $this->getStructureService()->getStructuresFilles($structure);
            $structures[] = $structure;
        }

        $qb = $this->createQueryBuilder()
            ->addSelect('affectation')->join('agent.affectations','affectation')
            ->andWhere('affectation.structure in (:structures)')
            ->setParameter('structures', $structures)
            ->andWhere('demande.histoDestruction IS NULL')
//            ->andWhere('inscritetat.code = :demandevalidation')
//            ->setParameter('demandevalidation', FormationInstanceInscrit::ETAT_DEMANDE_INSCRIPTION)
        ;

        if ($anneeCourrante) {
            $today = new DateTime();
            $month = ((int) $today->format('m'));
            $year  = ((int) $today->format('Y'));
            $annee = ($month > 8 ) ? $year : ($year-1) ;

            $mini = DateTime::createFromFormat('d/m/Y', '01/09/' . $annee);
            $maxi = DateTime::createFromFormat('d/m/Y', '31/08/' . ($annee+1));

            $qb = $qb->andWhere('demande.histoCreation >= :mini AND demande.histoCreation <= :maxi')
                ->setParameter('mini', $mini)
                ->setParameter('maxi', $maxi)
            ;
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FONCTION POUR LA RECHERCHE ************************************************************************************/

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
        /** @var DemandeExterne $item */
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
    public function findOrganismeByTerm(string $texte) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("LOWER(demande.organisme) like :search")
            ->setParameter('search', '%'.strtolower($texte).'%');
        $result = $qb->getQuery()->getResult();

        $organismes = [];
        /** @var DemandeExterne $item */
        foreach ($result as $item) {
            $organismes[$item->getOrganisme()] = $item->getOrganisme();
        }
        return $organismes;
    }
}