<?php

namespace Element\Service\CompetenceType;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\CompetenceType;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class CompetenceTypeService
{
    use ProvidesObjectManager;

    /** ENTITY MANAGMENT **********************************************************************************************/

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function create(CompetenceType $type): CompetenceType
    {
        $this->getObjectManager()->persist($type);
        $this->getObjectManager()->flush($type);
        return $type;
    }

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function update(CompetenceType $type): CompetenceType
    {
        $this->getObjectManager()->flush($type);
        return $type;
    }

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function historise(CompetenceType $type): CompetenceType
    {
        $type->historiser();
        $this->getObjectManager()->flush($type);
        return $type;
    }

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function restore(CompetenceType $type): CompetenceType
    {
        $type->dehistoriser();
        $this->getObjectManager()->flush($type);
        return $type;
    }

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function delete(CompetenceType $type): CompetenceType
    {
        $this->getObjectManager()->remove($type);
        $this->getObjectManager()->flush($type);
        return $type;
    }

    /** REQUETE *******************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(CompetenceType::class)->createQueryBuilder('type')
            ->addSelect('competence')->leftJoin('type.competences', 'competence');
        return $qb;
    }

    /** @return CompetenceType[] */
    public function getCompetencesTypes(bool $withHisto = false, string $champ = 'libelle', string $order = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('type.' . $champ, $order);
        if ($withHisto === false) $qb = $qb->andWhere('type.histoDestruction IS NULL');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $order
     * @return array
     */
    public function getCompetencesTypesAsOptions(string $champ = 'libelle', string $order = 'ASC'): array
    {
        $types = $this->getCompetencesTypes(false, $champ, $order);
        $options = [];
        foreach ($types as $type) {
            $options[$type->getId()] = $type->getLibelle();
        }
        return $options;
    }

    public function getCompetenceType(?int $id): ?CompetenceType
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('type.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs CompetenceType partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedCompetenceType(AbstractActionController $controller, string $paramName = 'competence-type'): ?CompetenceType
    {
        $id = $controller->params()->fromRoute($paramName);
        $type = $this->getCompetenceType($id);
        return $type;
    }

    public function getCompetenceTypeByLibelle(string $libelle): ?CompetenceType
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('type.libelle = :libelle')->setParameter('libelle', $libelle);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs CompetenceType partagent le même id [" . $libelle . "]", 0, $e);
        }
        return $result;
    }

    public function getCompetenceTypeByCode(?string $code): ?CompetenceType
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('type.code = :code')->setParameter('code', $code);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs CompetenceType partagent le même code [" . $code . "]", 0, $e);
        }
        return $result;
    }

    /** FACADE ***********************************************************************/

    public function createWith(?string $libelle): CompetenceType
    {
        $type = new CompetenceType();
        $type->setLibelle($libelle);
        $this->create($type);
        return $type;
    }
}
