<?php

namespace Application\Service\Validation;

use Application\Entity\Db\Domaine;
use Application\Entity\Db\FicheMetier;
use Application\Entity\Db\ValidationDemande;
use Application\Entity\Db\ValidationType;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Entity\Db\User;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ValidationDemandeService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use ValidationTypeServiceAwareTrait;

    /**
     * @param string $alias
     * @return QueryBuilder
     */
    public function createQueryBuilder($alias = 'demande') {
        $qb = $this->getEntityManager()->getRepository(ValidationDemande::class)->createQueryBuilder($alias)
            ->addSelect('type')->join('demande.type', 'type')
            ->addSelect('validateur')->join('demande.validateur', 'validateur')
            ->addSelect('validation')->leftJoin('demande.validation', 'validation')
            ->addSelect('createur')->join('demande.histoCreateur', 'createur')
            ->addSelect('modificateur')->join('demande.histoModificateur', 'modificateur')
            ->addSelect('destructeur')->leftJoin('demande.histoDestructeur', 'destructeur')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $order
     * @return ValidationDemande[]
     */
    public function getValidationsDemandes($champ = 'id', $order = 'ASC') {
        $qb = $this->createQueryBuilder('demande')
            ->orderBy('demande.' . $champ, $order)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return ValidationDemande
     */
    public function getValidationDemande($id) {
        $qb = $this->createQueryBuilder('demande')
            ->andWhere('demande.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ValidationDemande partagent le même id [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return ValidationDemande
     */
    public function getRequestedDemandeValidation($controller, $paramName = 'demande') {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getValidationDemande($id);
        return $result;
    }

    /**
     * @param User $validateur
     * @return ValidationDemande[]
     */
    public function getValidationsDemandesByValidateur($validateur) {
        $qb = $this->createQueryBuilder('demande')
            ->andWhere('validateur.id = :validateur')
            ->setParameter('validateur', $validateur->getId())
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param ValidationDemande $demande
     * @return ValidationDemande
     */
    public function create($demande)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des informations d'historisation.", $e);
        }

        $demande->setHistoCreation($date);
        $demande->setHistoCreateur($user);
        $demande->setHistoModification($date);
        $demande->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($demande);
            $this->getEntityManager()->flush($demande);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de lenregistrement en BD.", $e);
        }

        return $demande;

    }

    /**
     * @param ValidationDemande $demande
     * @return ValidationDemande
     */
    public function update($demande)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des informations d'historisation.", $e);
        }

        $demande->setHistoModification($date);
        $demande->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($demande);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de lenregistrement en BD.", $e);
        }

        return $demande;
    }

    /**
     * @param ValidationDemande $demande
     * @return ValidationDemande
     */
    public function delete($demande)
    {
        try {
            $this->getEntityManager()->remove($demande);
            $this->getEntityManager()->flush($demande);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de lenregistrement en BD.", $e);
        }

        return $demande;
    }

    /**
     * @param User $validateur
     * @param Domaine $domaine
     * @return ValidationDemande[]
     */
    public function creerDemandesFicheMetierDomaine($validateur, $domaine)
    {
        $demandes = [];

        $fichesMetiers = $this->getFicheMetierService()->getFicheByDomaine($domaine);
        $type = $this->getValidationTypeService()->getValidationTypebyCode(ValidationType::FICHE_METIER_RELECTURE);

        foreach ($fichesMetiers as $ficheMetier) {
            $demande = new ValidationDemande();
            $demande->setValidateur($validateur);
            $demande->setType($type);
            $demande->setEntity('Application\Entity\Db\FicheMetier');
            $demande->setObjectId($ficheMetier->getId());
            $this->create($demande);
            $demandes[] = $demande;
        }

        return $demandes;
    }

    /**
     * @param User $validateur
     * @param FicheMetier $ficheMetier
     * @return ValidationDemande[]
     */
    public function creerDemandeFicheMetier($validateur, $ficheMetier)
    {
        $demandes = [];

        $type = $this->getValidationTypeService()->getValidationTypebyCode(ValidationType::FICHE_METIER_RELECTURE);

        $demande = new ValidationDemande();
        $demande->setValidateur($validateur);
        $demande->setType($type);
        $demande->setEntity('Application\Entity\Db\FicheMetier');
        $demande->setObjectId($ficheMetier->getId());
        $this->create($demande);
        $demandes[] = $demande;

        return $demandes;
    }
}