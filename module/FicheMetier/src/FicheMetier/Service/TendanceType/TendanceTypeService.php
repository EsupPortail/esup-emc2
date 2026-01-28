<?php

namespace FicheMetier\Service\TendanceType;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\TendanceType;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class TendanceTypeService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(TendanceType $tendanceType): TendanceType
    {
        $this->getObjectManager()->persist($tendanceType);
        $this->getObjectManager()->flush($tendanceType);
        return $tendanceType;
    }

    public function update(TendanceType $tendanceType): TendanceType
    {
        $this->getObjectManager()->flush($tendanceType);
        return $tendanceType;
    }

    public function historise(TendanceType $tendanceType): TendanceType
    {
        $tendanceType->historiser();
        $this->getObjectManager()->flush($tendanceType);
        return $tendanceType;
    }

    public function restore(TendanceType $tendanceType): TendanceType
    {
        $tendanceType->dehistoriser();
        $this->getObjectManager()->flush($tendanceType);
        return $tendanceType;
    }

    public function delete(TendanceType $tendanceType): TendanceType
    {
        $this->getObjectManager()->remove($tendanceType);
        $this->getObjectManager()->flush($tendanceType);
        return $tendanceType;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(TendanceType::class)->createQueryBuilder('tendancetype');
        return $qb;
    }

    public function getTendanceType(?int $id): ?TendanceType
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('tendancetype.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".TendanceType::class."] partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getTendanceTypeByCode(?string $code): ?TendanceType
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('tendancetype.code = :code')->setParameter('code', $code);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".TendanceType::class."] partagent le même code [".$code."]",0,$e);
        }
        return $result;
    }

    public function getRequestedTendanceType(AbstractActionController $controller, string $param='tendance-type'): ?TendanceType
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getTendanceType($id);
    }

    /** @return TendanceType[] */
    public function getTendancesTypes(string $champ='libelle', string $ordre='ASC', bool $histo=false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('tendancetype.' . $champ, $ordre);

        if (!$histo) $qb = $qb->andWhere('tendancetype.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function generateTendanceDictionnaire(): array
    {
        $dictionnaire = [];
        $types = $this->getTendancesTypes();

        foreach ($types as $type) {
            $dictionnaire[$type->getLibelle()] = $type;
        }
        return $dictionnaire;
    }

    /** FACADE ********************************************************************************************************/
}