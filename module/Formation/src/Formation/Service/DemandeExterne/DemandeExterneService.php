<?php

namespace Formation\Service\DemandeExterne;

use Application\Entity\Db\Agent;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\DemandeExterne;
use Formation\Provider\Validation\DemandeExterneValidations;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenValidation\Entity\Db\ValidationInstance;
use UnicaenValidation\Entity\Db\ValidationType;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;
use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;

class DemandeExterneService {
    use EntityManagerAwareTrait;
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

}