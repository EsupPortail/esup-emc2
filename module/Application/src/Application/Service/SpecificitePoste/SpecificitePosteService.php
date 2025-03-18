<?php

namespace Application\Service\SpecificitePoste;

use Application\Entity\Db\SpecificitePoste;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;

class SpecificitePosteService
{
    use ProvidesObjectManager;

    /** GESTION ENTITE ************************************************************************************************/

    public function create(SpecificitePoste $specificite): SpecificitePoste
    {
        $this->getObjectManager()->persist($specificite);
        $this->getObjectManager()->flush($specificite);
        return $specificite;
    }

    public function update(SpecificitePoste $specificite): SpecificitePoste
    {
        $this->getObjectManager()->flush($specificite);
        return $specificite;
    }

    public function delete(SpecificitePoste $specificite): SpecificitePoste
    {
        $this->getObjectManager()->remove($specificite);
        $this->getObjectManager()->flush();
        return $specificite;
    }

    /** REQUETAGE ***************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(SpecificitePoste::class)->createQueryBuilder('specificite')
        ;
        return $qb;
    }

    /**
     * @param int|null $id
     * @return SpecificitePoste|null
     */
    public function getSpecificitePoste(?int $id): ?SpecificitePoste
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('specificite.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs SpecificitePoste partagent le même id [" . $id . "]",0,$e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return SpecificitePoste|null
     */
    public function getRequestedSpecificitePoste(AbstractActionController $controller, string $param = "specificite-poste"): ?SpecificitePoste
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getSpecificitePoste($id);
        return $result;
    }
}