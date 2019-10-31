<?php

namespace Application\Service\Validation;

use Application\Entity\Db\Validation;
use Application\Entity\Db\ValidationType;
use Application\Entity\Db\ValidationValeur;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ValidationValeurService {
    use EntityManagerAwareTrait;

    /**
     * @param string $champ
     * @param string $order
     * @return ValidationValeur[]
     */
    public function getValidationsValeurs($champ = 'id', $order = 'DESC')
    {
        $qb = $this->getEntityManager()->getRepository(ValidationValeur::class)->createQueryBuilder('valeur')
            ->orderBy('valeur.' . $champ, $order)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getValidationValeurAsOptions()
    {
        $valeurs = $this->getValidationsValeurs();
        $array = [];
        foreach ($valeurs as $valeur) {
            $array[$valeur->getId()] = $valeur->getLibelle();
        }
        return $array;
    }

    /**
     * @param integer $id
     * @return ValidationValeur
     */
    public function getValidationValeur($id)
    {
        $qb = $this->getEntityManager()->getRepository(ValidationValeur::class)->createQueryBuilder('valeur')
            ->andWhere('valeur.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ValidationValeur partagent le même id [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param string $code
     * @return ValidationValeur
     */
    public function getValidationValeurbyCode($code)
    {
        $qb = $this->getEntityManager()->getRepository(ValidationValeur::class)->createQueryBuilder('valeur')
            ->andWhere('valeur.code = :code')
            ->setParameter('code', $code)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Validation partagent le même id [".$code."]", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return ValidationValeur
     */
    public function getRequestedValidationValeur($controller, $paramName = 'valeur')
    {
        $id = $controller->params()->fromRoute($paramName);
        $validation = $this->getValidationValeur($id);
        return $validation;
    }

    /**
     * @param ValidationValeur $valeur
     * @return ValidationValeur
     */
    public function create($valeur)
    {
        try {
            $this->getEntityManager()->persist($valeur);
            $this->getEntityManager()->flush($valeur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $valeur;
    }

    /**
     * @param ValidationValeur $valeur
     * @return ValidationValeur
     */
    public function update($valeur)
    {
        try {
            $this->getEntityManager()->flush($valeur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $valeur;
    }

    /**
     * @param ValidationValeur $valeur
     * @return ValidationValeur
     */
    public function historise($valeur)
    {
        try {
            $this->getEntityManager()->flush($valeur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $valeur;
    }

    /**
     * @param ValidationValeur $valeur
     * @return ValidationValeur
     */
    public function restore($valeur)
    {
        try {
            $this->getEntityManager()->flush($valeur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $valeur;
    }

    /**
     * @param ValidationValeur $valeur
     * @return ValidationValeur
     */
    public function delete($valeur)
    {
        try {
            $this->getEntityManager()->remove($valeur);
            $this->getEntityManager()->flush($valeur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $valeur;
    }
}