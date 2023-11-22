<?php

namespace Carriere\Service\Mobilite;

use Carriere\Entity\Db\Mobilite;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class MobiliteService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(Mobilite $mobilite): Mobilite
    {
        $this->getObjectManager()->persist($mobilite);
        $this->getObjectManager()->flush($mobilite);
        return $mobilite;
    }

    public function update(Mobilite $mobilite): Mobilite
    {
        $this->getObjectManager()->flush($mobilite);
        return $mobilite;
    }

    public function historise(Mobilite $mobilite): Mobilite
    {
        $mobilite->historiser();
        $this->getObjectManager()->flush($mobilite);
        return $mobilite;
    }

    public function restore(Mobilite $mobilite): Mobilite
    {
        $mobilite->dehistoriser();
        $this->getObjectManager()->flush($mobilite);
        return $mobilite;
    }

    public function delete(Mobilite $mobilite): Mobilite
    {
        $this->getObjectManager()->remove($mobilite);
        $this->getObjectManager()->flush($mobilite);
        return $mobilite;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuider(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Mobilite::class)->createQueryBuilder('mobilite');
        return $qb;
    }

    /** @return Mobilite[] */
    public function getMobilites(string $champ = 'libelle', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuider()
            ->orderBy('mobilite.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getMobiliteAsOptions(string $champ = 'libelle', string $ordre = 'ASC'): array
    {
        $mobilites = $this->getMobilites($champ, $ordre);
        $array = [];
        foreach ($mobilites as $mobilite) {
            $array[$mobilite->getId()] = $mobilite->getLibelle();
        }
        return $array;
    }

    public function getMobilite(?int $id): ?Mobilite
    {
        $qb = $this->createQueryBuider()
            ->andWhere('mobilite.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Mobilite::class."] partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedMobilite(AbstractActionController $controller, string $param = 'mobilite'): ?Mobilite
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getMobilite($id);
        return $result;
    }
}