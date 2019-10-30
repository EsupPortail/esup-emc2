<?php

namespace Application\Service\Validation;

use Application\Entity\Db\Validation;
use Application\Entity\Db\ValidationType;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ValidationTypeService {
    use EntityManagerAwareTrait;

    /**
     * @param string $champ
     * @param string $order
     * @return ValidationType[]
     */
    public function getValidationsTypes($champ = 'histoModification', $order = 'DESC')
    {
        $qb = $this->getEntityManager()->getRepository(ValidationType::class)->createQueryBuilder('type')
            ->orderBy('type.' . $champ, $order)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param $id
     * @return ValidationType
     */
    public function getValidationType($id)
    {
        $qb = $this->getEntityManager()->getRepository(Validation::class)->createQueryBuilder('type')
            ->andWhere('type.id = :id')
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
     * @param string $code
     * @return ValidationType
     */
    public function getValidationTypebyCode($code)
    {
        $qb = $this->getEntityManager()->getRepository(Validation::class)->createQueryBuilder('type')
            ->andWhere('type.code = :code')
            ->setParameter('code', $code)
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
     * @return ValidationType
     */
    public function getRequestedValidationType($controller, $paramName = 'type')
    {
        $id = $controller->params()->fromRoute($paramName);
        $validation = $this->getValidationType($id);
        return $validation;
    }

    /**
     * @param ValidationType $type
     * @return ValidationType
     */
    public function create($type)
    {
        try {
            $this->getEntityManager()->persist($type);
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param ValidationType $type
     * @return ValidationType
     */
    public function update($type)
    {
        try {
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param ValidationType $type
     * @return ValidationType
     */
    public function historise($type)
    {
        try {
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param ValidationType $type
     * @return ValidationType
     */
    public function restore($type)
    {
        try {
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param ValidationType $type
     * @return ValidationType
     */
    public function delete($type)
    {
        try {
            $this->getEntityManager()->remove($type);
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }
}