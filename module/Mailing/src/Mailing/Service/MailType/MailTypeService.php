<?php

namespace Mailing\Service\MailType;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Mailing\Model\Db\MailType;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class MailTypeService {
    use DateTimeAwareTrait;
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** Gestion des entités *******************************************************************************************/

    /**
     * @param MailType $entity
     * @return MailType
     */
    public function create(MailType $entity)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $entity->setHistoCreateur($user);
        $entity->setHistoCreation($date);
        $entity->setHistoModificateur($user);
        $entity->setHistoModification($date);

        try {
            $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush($entity);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.", 0, $e);
        }

        return $entity;
    }

    /**
     * @param MailType $entity
     * @return MailType
     */
    public function update(MailType $entity)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $entity->setHistoModificateur($user);
        $entity->setHistoModification($date);

        try {
            $this->getEntityManager()->flush($entity);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.", 0, $e);
        }

        return $entity;
    }

    /**
     * @param MailType $entity
     * @return MailType
     */
    public function historise(MailType $entity)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $entity->setHistoDestructeur($user);
        $entity->setHistoDestruction($date);

        try {
            $this->getEntityManager()->flush($entity);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.", 0, $e);
        }

        return $entity;
    }

    /**
     * @param MailType $entity
     * @return MailType
     */
    public function restore(MailType $entity)
    {
        $entity->setHistoDestructeur(null);
        $entity->setHistoDestruction(null);

        try {
            $this->getEntityManager()->flush($entity);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.", 0, $e);
        }

        return $entity;
    }

    /**
     * @param MailType $entity
     * @return MailType
     */
    public function delete(MailType $entity)
    {
        try {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush($entity);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.", 0, $e);
        }

        return $entity;
    }

    /** Requetages ****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(MailType::class)->createQueryBuilder('type');
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return MailType[]
     */
    public function getMailsTypes($champ = 'code', $ordre = 'ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('type.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param $id
     * @return MailType
     */
    public function getMailType($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('type.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs MailType partagent le même id [".$id."].", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return MailType
     */
    public function getRequestedMailType($controller, $param = 'mail-type')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getMailType($id);
        return $result;
    }

    /**
     * @param $code
     * @return MailType
     */
    public function getMailTypeByCode($code)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('type.code = :code')
            ->setParameter('code', $code);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs MailType partagent le même code [".$code."].", 0, $e);
        }
        return $result;
    }
}