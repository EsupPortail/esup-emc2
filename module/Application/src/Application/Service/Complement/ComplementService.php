<?php

namespace Application\Service\Complement;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Complement;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class ComplementService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(Complement $complement) : Complement
    {
        try {
            $this->getEntityManager()->persist($complement);
            $this->getEntityManager()->flush($complement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu au niveau de l'ORM", 0, $e);
        }
        return $complement;
    }

    public function update(Complement $complement) : Complement
    {
        try {
            $this->getEntityManager()->flush($complement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu au niveau de l'ORM", 0, $e);
        }
        return $complement;
    }

    public function historise(Complement $complement) : Complement
    {
        try {
            $complement->historiser();
            $this->getEntityManager()->flush($complement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu au niveau de l'ORM", 0, $e);
        }
        return $complement;
    }

    public function restore(Complement $complement) : Complement
    {
        try {
            $complement->dehistoriser();
            $this->getEntityManager()->flush($complement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu au niveau de l'ORM", 0, $e);
        }
        return $complement;
    }

    public function delete(Complement $complement) : Complement
    {
        try {
            $this->getEntityManager()->remove($complement);
            $this->getEntityManager()->flush($complement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu au niveau de l'ORM", 0, $e);
        }
        return $complement;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Complement::class)->createQueryBuilder('complement');
        return $qb;
    }

    /**
     * @var int $id
     * @return Complement|null
     */
    public function getComplement(?int $id) : ?Complement
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('complement.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Complement partagent le même id [".$id."]");
        }
        return $result;
    }

    public function getRequestedComplement(AbstractActionController $controller, string $param = 'complement') : ?Complement
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getComplement($id);
        return $result;
    }

    /**
     * @param string $attachmentType
     * @param string $attachmentId
     * @param string|null $type
     * @return Complement[]
     */
    public function getCompelementsByAttachement(string $attachmentType, string $attachmentId, ?string $type = null) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('complement.attachmentType = :attachmentType')
            ->andWhere('complement.attachmentId = :attachmentId')
            ->setParameter('attachmentType', $attachmentType)
            ->setParameter('attachmentId', $attachmentId)
        ;

        if ($type !== null) {
            $qb = $qb->andWhere('complement.type = :type')
                ->setParameter('type', $type);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function isAutorite(?Agent $autorite, Agent $agent) : bool
    {
        if ($autorite === null) return false;
       $qb =  $this->createQueryBuilder()
           ->andWhere('complement.type = :type')
           ->setParameter('type', Complement::COMPLEMENT_TYPE_AUTORITE)
           ->andWhere('complement.attachmentType = :agentClass')
           ->andWhere('complement.complementType = :agentClass')
           ->setParameter('agentClass', Agent::class)
           ->andWhere('complement.attachmentId = :agentId')
           ->setParameter('agentId', $agent->getId())
           ->andWhere('complement.complementId = :autoriteId')
           ->setParameter('autoriteId', $autorite->getId())
       ;
       $result = $qb->getQuery()->getResult();
       return (!empty($result));
    }

    public function isSuperieur(?Agent $superieur, Agent $agent) : bool
    {
        if ($superieur === null) return false;
        $qb =  $this->createQueryBuilder()
            ->andWhere('complement.type = :type')
            ->setParameter('type', Complement::COMPLEMENT_TYPE_RESPONSABLE)
            ->andWhere('complement.attachmentType = :agentClass')
            ->andWhere('complement.complementType = :agentClass')
            ->setParameter('agentClass', Agent::class)
            ->andWhere('complement.attachmentId = :agentId')
            ->setParameter('agentId', $agent->getId())
            ->andWhere('complement.complementId = :superieurId')
            ->setParameter('superieurId', $superieur->getId())
        ;
        $result = $qb->getQuery()->getResult();
        return (!empty($result));
    }

    /** FACADE ********************************************************************************************************/


}