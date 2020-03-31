<?php

namespace UnicaenValidation\Service\ValidationInstance;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use UnicaenValidation\Entity\Db\ValidationInstance;
use Zend\Mvc\Controller\AbstractActionController;

class ValidationInstanceService {
    use DateTimeAwareTrait;
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param ValidationInstance $instance
     * @return ValidationInstance
     */
    public function create(ValidationInstance $instance)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $instance->setHistoCreation($date);
        $instance->setHistoCreateur($user);
        $instance->setHistoModification($date);
        $instance->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($instance);
            $this->getEntityManager()->flush($instance);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.",0,$e);
        }

        return $instance;
    }

    /**
     * @param ValidationInstance $instance
     * @return ValidationInstance
     */
    public function update(ValidationInstance $instance)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $instance->setHistoModification($date);
        $instance->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($instance);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.",0,$e);
        }

        return $instance;
    }

    /**
     * @param ValidationInstance $instance
     * @return ValidationInstance
     */
    public function historise(ValidationInstance $instance)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $instance->setHistoDestruction($date);
        $instance->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($instance);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.",0,$e);
        }

        return $instance;
    }

    /**
     * @param ValidationInstance $instance
     * @return ValidationInstance
     */
    public function restore(ValidationInstance $instance)
    {
        $instance->setHistoDestruction(null);
        $instance->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($instance);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.",0,$e);
        }

        return $instance;
    }

    /**
     * @param ValidationInstance $instance
     * @return ValidationInstance
     */
    public function delete(ValidationInstance $instance)
    {
        try {
            $this->getEntityManager()->remove($instance);
            $this->getEntityManager()->flush($instance);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.",0,$e);
        }

        return $instance;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(ValidationInstance::class)->createQueryBuilder('vinstance')
            ->addSelect('createur')->join('vinstance.histoCreateur', 'createur')
            ->addSelect('modificateur')->join('vinstance.histoModificateur', 'modificateur')
            ->addSelect('vtype')->join('vinstance.type', 'vtype')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return ValidationInstance[]
     */
    public function getValidationsInstances($champ = 'histoModification', $ordre = 'DESC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('vinstance.' . $champ, $ordre)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param $id
     * @return ValidationInstance
     */
    public function getValidationInstance($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('vinstance.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ValidationInstance partagent le même id [".$id."]", 0, true);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return ValidationInstance
     */
    public function getRequestedValidationInstance($controller, $param = "validation")
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getValidationInstance($id);
        return $result;
    }

    /**
     * @param string $code
     * @param Object $entite
     * @return ValidationInstance
     */
    public function getValidationInstanceByCodeAndEntite($code, $entite)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('vinstance.histoDestruction IS NULL')
            ->andWhere('vtype.code = :code')
            ->setParameter('code', $code)
            ->andWhere('vinstance.entityClass = :eclass')
            ->andWhere('vinstance.entityId = :eid')
            ->setParameter('eclass', get_class($entite))
            ->setParameter('eid', $entite->getId())
            ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ValidationInstance partagent le même code et entity", 0, $e);
        }

        return $result;
    }

}