<?php

namespace Application\Service\Validation;

use Application\Entity\Db\Validation;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ValidationService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param string $champ
     * @param string $order
     * @return Validation[]
     */
    public function getValidations($champ = 'histoModification', $order = 'DESC')
    {
        $qb = $this->getEntityManager()->getRepository(Validation::class)->createQueryBuilder('validation')
            ->orderBy('validation.' . $champ, $order)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param $id
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
            throw new RuntimeException("Plusieurs Validation partagent le même id [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Validation
     */
    public function getRequestedValidation($controller, $paramName = 'validation')
    {
        $id = $controller->params()->fromRoute($paramName);
        $validation = $this->getValidation($id);
        return $validation;
    }

    /**
     * @param Validation $validation
     * @return Validation
     */
    public function create($validation)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des données d'historisation.", $e);
        }

        $validation->setHistoCreateur($user);
        $validation->setHistoCreation($date);
        $validation->setHistoModificateur($user);
        $validation->setHistoModification($date);

        try {
            $this->getEntityManager()->persist($validation);
            $this->getEntityManager()->flush($validation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", 0, $e);
        }
        return $validation;
    }

    /**
     * @param Validation $validation
     * @return Validation
     */
    public function update($validation)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des données d'historisation.", $e);
        }

        $validation->setHistoModificateur($user);
        $validation->setHistoModification($date);

        try {
            $this->getEntityManager()->flush($validation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $validation;
    }

    /**
     * @param Validation $validation
     * @return Validation
     */
    public function historise($validation)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des données d'historisation.", $e);
        }

        $validation->setHistoDestructeur($user);
        $validation->setHistoDestruction($date);

        try {
            $this->getEntityManager()->flush($validation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $validation;
    }

    /**
     * @param Validation $validation
     * @return Validation
     */
    public function restore($validation)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des données d'historisation.", $e);
        }

        $validation->setHistoModificateur($user);
        $validation->setHistoModification($date);

        try {
            $this->getEntityManager()->flush($validation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
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
            $this->getEntityManager()->remove($validation);
            $this->getEntityManager()->flush($validation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $validation;
    }
}