<?php

namespace Application\Service\SpecificiteActivite;

use Application\Entity\Db\SpecificiteActivite;
use Application\Entity\Db\SpecificitePoste;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class SpecificiteActiviteService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(SpecificiteActivite $specificiteActivite): SpecificiteActivite
    {
        $this->getObjectManager()->persist($specificiteActivite);
        $this->getObjectManager()->flush($specificiteActivite);
        return $specificiteActivite;
    }

    public function update(SpecificiteActivite $specificiteActivite): SpecificiteActivite
    {
        $this->getObjectManager()->flush($specificiteActivite);
        return $specificiteActivite;
    }

    public function historise(SpecificiteActivite $specificiteActivite): SpecificiteActivite
    {
        $specificiteActivite->historiser();
        $this->getObjectManager()->flush($specificiteActivite);
        return $specificiteActivite;
    }

    public function restore(SpecificiteActivite $specificiteActivite): SpecificiteActivite
    {
        $specificiteActivite->dehistoriser();
        $this->getObjectManager()->flush($specificiteActivite);
        return $specificiteActivite;
    }

    public function delete(SpecificiteActivite $specificiteActivite): SpecificiteActivite
    {
        $this->getObjectManager()->remove($specificiteActivite);
        $this->getObjectManager()->flush($specificiteActivite);
        return $specificiteActivite;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(SpecificiteActivite::class)->createQueryBuilder('sa')
            ->join('sa.specificite', 'specificite')->addSelect('specificite')
            ->join('sa.activite', 'activite')->addSelect('activite')
            ->join('activite.libelles', 'libelle')->addSelect('libelle')
            ->andWhere('libelle.histoDestruction IS NULL')
            ->join('activite.descriptions', 'description')->addSelect('description')
            ->andWhere('description.histoDestruction IS NULL');
        return $qb;
    }

    /**
     * @param int|null $id
     * @return SpecificiteActivite|null
     */
    public function getSpecificiteActivite(?int $id): ?SpecificiteActivite
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('sa.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs SpecificiteActivite partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestSpecificiteActivite(AbstractActionController $controller, string $param = "specificite-activite"): ?SpecificiteActivite
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getSpecificiteActivite($id);
        return $result;
    }

    /**
     * @return SpecificiteActivite[]
     */
    public function getSpecificitesActivitesBySpecificite(SpecificitePoste $specificite): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('sa.specificite = :specificite')
            ->setParameter('specificite', $specificite);
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}