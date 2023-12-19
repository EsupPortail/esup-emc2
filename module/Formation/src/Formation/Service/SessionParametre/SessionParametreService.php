<?php

namespace Formation\Service\SessionParametre;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\SessionParametre;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;

class SessionParametreService
{
    use ProvidesObjectManager;

    /** Gestion de l'entite ******************************************************************/

    public function create(SessionParametre $parametre): SessionParametre
    {
        $this->getObjectManager()->persist($parametre);
        $this->getObjectManager()->flush($parametre);
        return $parametre;
    }

    public function update(SessionParametre $parametre): SessionParametre
    {
        $this->getObjectManager()->flush($parametre);
        return $parametre;
    }

    public function historise(SessionParametre $parametre): SessionParametre
    {
        $parametre->historiser();
        $this->getObjectManager()->flush($parametre);
        return $parametre;
    }

    public function restore(SessionParametre $parametre): SessionParametre
    {
        $parametre->dehistoriser();
        $this->getObjectManager()->flush($parametre);
        return $parametre;
    }

    public function delete(SessionParametre $parametre): SessionParametre
    {
        $this->getObjectManager()->remove($parametre);
        $this->getObjectManager()->flush($parametre);
        return $parametre;
    }

    /** REQUETAGE ***************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(SessionParametre::class)->createQueryBuilder('parametre');
        return $qb;
    }

    public function getSessionParametre(?int $id): ?SessionParametre
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('parametre.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs SessionParametre partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedSessionParametre(AbstractActionController $controller, string $param = 'session-parametre'): ?SessionParametre
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getSessionParametre($id);
        return $result;
    }

    /** FACADE ********************************************************************************************/


}