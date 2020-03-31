<?php

namespace UnicaenValidation\Service\ValidationType;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use UnicaenValidation\Entity\Db\ValidationType;
use Zend\Mvc\Controller\AbstractActionController;

class ValidationTypeService {
    use DateTimeAwareTrait;
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param ValidationType $type
     * @return ValidationType
     */
    public function create(ValidationType $type)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $type->setHistoCreation($date);
        $type->setHistoCreateur($user);
        $type->setHistoModification($date);
        $type->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($type);
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.",0,$e);
        }

        return $type;
    }

    /**
     * @param ValidationType $type
     * @return ValidationType
     */
    public function update(ValidationType $type)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $type->setHistoModification($date);
        $type->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.",0,$e);
        }

        return $type;
    }

    /**
     * @param ValidationType $type
     * @return ValidationType
     */
    public function historise(ValidationType $type)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $type->setHistoDestruction($date);
        $type->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.",0,$e);
        }

        return $type;
    }

    /**
     * @param ValidationType $type
     * @return ValidationType
     */
    public function restore(ValidationType $type)
    {
        $type->setHistoDestruction(null);
        $type->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.",0,$e);
        }

        return $type;
    }

    /**
     * @param ValidationType $type
     * @return ValidationType
     */
    public function delete(ValidationType $type)
    {
        try {
            $this->getEntityManager()->remove($type);
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.",0,$e);
        }

        return $type;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(ValidationType::class)->createQueryBuilder('type')
            ->addSelect('modificateur')->join('type.histoModificateur', 'modificateur')
            ;

        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return ValidationType[]
     */
    public function getValidationsTypes($champ = "libelle", $ordre = "ASC")
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('type.' . $champ, $ordre)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return array
     */
    public function getValidationsTypesAsOptions($champ = "libelle", $ordre = "ASC")
    {
        $result = $this->getValidationsTypes($champ, $ordre);
        $array = [];
        foreach ($result as $item) $array[$item->getId()] = $item->getCode();
        return $array;
    }

    /**
     * @param integer $id
     * @return ValidationType
     */
    public function getValidationType($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('type.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ValidationType partagent le même id [".$id."]", 0, $e);
        }

        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return ValidationType
     */
    public function getRequestedValidationType($controller, $param='type')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getValidationType($id);
        return $result;
    }

    /**
     * @param string $code
     * @return ValidationType
     */
    public function getValidationTypeByCode($code)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('type.code = :code')
            ->setParameter('code', $code)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ValidationType partagent le même code [".$code."]", 0, $e);
        }
        return $result;
    }
}