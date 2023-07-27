<?php

namespace Formation\Service\SessionParametre;

use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\SessionParametre;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class SessionParametreService {
    use EntityManagerAwareTrait;

    /** Gestion de l'entite ******************************************************************/

    public function create(SessionParametre $parametre) : SessionParametre
    {
        try {
            $this->getEntityManager()->persist($parametre);
            $this->getEntityManager()->flush($parametre);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $parametre;
    }

    public function update(SessionParametre $parametre) : SessionParametre
    {
        try {
            $this->getEntityManager()->flush($parametre);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $parametre;
    }

    public function historise(SessionParametre $parametre) : SessionParametre
    {
        try {
            $parametre->historiser();
            $this->getEntityManager()->flush($parametre);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $parametre;
    }

    public function restore(SessionParametre $parametre) : SessionParametre
    {
        try {
            $parametre->dehistoriser();
            $this->getEntityManager()->flush($parametre);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $parametre;
    }

    public function delete(SessionParametre $parametre) : SessionParametre
    {
        try {
            $this->getEntityManager()->remove($parametre);
            $this->getEntityManager()->flush($parametre);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $parametre;
    }

    /** REQUETAGE ***************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        try {
            $qb = $this->getEntityManager()->getRepository(SessionParametre::class)->createQueryBuilder('parametre');
        } catch (NotSupported $e) {
            throw new RuntimeException("Un problème est survenu lors de la création du QueryBuilder de [".SessionParametre::class."]",0,$e);
        }
        return $qb;
    }

    public function getSessionParametre(?int $id) : ?SessionParametre
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('parametre.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs SessionParametre partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getRequestedSessionParametre(AbstractActionController $controller, string $param = 'session-parametre') : ?SessionParametre
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getSessionParametre($id);
        return $result;
    }

    /** FACADE ********************************************************************************************/


}