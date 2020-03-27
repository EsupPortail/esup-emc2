<?php

namespace Autoform\Service\Validation;

use Autoform\Entity\Db\FormulaireInstance;
use Autoform\Entity\Db\Validation;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ValidationService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param AbstractActionController $controller
     * @param string $label
     * @return Validation
     */
    public function getRequestedValidation($controller, $label)
    {
        $id = $controller->params()->fromRoute($label);
        $validation = $this->getValidation($id);
        return $validation;
    }
    /**
     * @return Validation[]
     */
    public function getValidations()
    {
        $qb = $this->getEntityManager()->getRepository(Validation::class)->createQueryBuilder('validation');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Validation
     */
    public function getValidation($id)
    {
        $qb = $this->getEntityManager()->getRepository(Validation::class)->createQueryBuilder('validation')
            ->andWhere('validation.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Validation partagent le même identifiant [".$id."].", $e);
        }
        return $result;
    }

    /**
     * @param Validation $validation
     * @return Validation
     */
    public function create($validation)
    {
        /** @var User $user */
        $user = $this->getUserService()->getConnectedUser();
        /** @var DateTime $date */
        try {
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }
        $validation->setHistoCreateur($user);
        $validation->setHistoCreation($date);
        $validation->setHistoModificateur($user);
        $validation->setHistoModification($date);

        try {
            $this->getEntityManager()->persist($validation);
            $this->getEntityManager()->flush($validation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la création d'un Validation.", $e);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la création d'un Validation.", $e);
        }
        return $validation;
    }

    /**
     * @param Validation $validation
     * @return Validation
     */
    public function update($validation)
    {
        /** @var User $user */
        $user = $this->getUserService()->getConnectedUser();
        /** @var DateTime $date */
        try {
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }
        $validation->setHistoModificateur($user);
        $validation->setHistoModification($date);

        try {
            $this->getEntityManager()->flush($validation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la mise à jour d'un Validation.", $e);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la mise à jour d'un Validation.", $e);
        }
        return $validation;
    }

    /**
     * @param Validation $validation
     * @return Validation
     */
    public function historise($validation)
    {
        /** @var User $user */
        $user = $this->getUserService()->getConnectedUser();
        /** @var DateTime $date */
        try {
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }
        $validation->setHistoDestructeur($user);
        $validation->setHistoDestruction($date);

        try {
            $this->getEntityManager()->flush($validation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de l'historisation d'un Validation.", $e);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de l'historisation d'un Validation.", $e);
        }
        return $validation;
    }

    /**
     * @param Validation $validation
     * @return Validation
     */
    public function restaure($validation)
    {
        $validation->setHistoDestructeur(null);
        $validation->setHistoDestruction(null);

        try {
            $this->getEntityManager()->flush($validation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la restauration d'un Validation.", $e);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la restauration d'un Validation.", $e);
        }
        return $validation;
    }

    /**
     * @param Validation $validation
     * @return Validation
     */
    public function delete($validation)
    {

        try {
            $this->getEntityManager()->flush();
            $this->getEntityManager()->remove($validation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la suppression d'un Validation.", $e);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la suppression d'un Validation.", $e);
        }
        return $validation;
    }

    /**
     * @param string $type
     * @param FormulaireInstance $instance
     * @return Validation
     */
    public function getValidationByTypeAndInstance($type, $instance)
    {
        $qb = $this->getEntityManager()->getRepository(Validation::class)->createQueryBuilder('validation')
            ->andWhere('validation.instance = :instance')
            ->andWhere('validation.type = :type')
            ->andWhere('validation.histoDestruction IS NULL')
            ->setParameter('instance', $instance)
            ->setParameter('type', $type)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs validations partagent la même instance et le même type [".$type."/".$instance->getId()."].", 0, $e);
        }
        return $result;
    }

}