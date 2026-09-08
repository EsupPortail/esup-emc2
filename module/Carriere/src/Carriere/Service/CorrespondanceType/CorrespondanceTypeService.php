<?php

namespace Carriere\Service\CorrespondanceType;

use Carriere\Entity\Db\CorrespondanceType;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class CorrespondanceTypeService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITÉS *******************************************************************************************/

    public function create(CorrespondanceType $correspondanceType): void
    {
        $correspondanceType->setSourceId("EMC2"); //todo faire une constante !!!
        $correspondanceType->setInsertedOn(new DateTime());

        $this->getObjectManager()->persist($correspondanceType);
        $this->getObjectManager()->flush($correspondanceType);
    }

    public function update(CorrespondanceType $correspondanceType): void
    {
        $correspondanceType->setUpdatedOn(new DateTime());
        $this->getObjectManager()->flush($correspondanceType);
    }

    public function delete(CorrespondanceType $correspondanceType): void
    {
        //$this->getObjectManager()->remove($correspondanceType);
        $correspondanceType->setDeletedOn(new DateTime());
        $this->getObjectManager()->flush($correspondanceType);
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(CorrespondanceType::class)->createQueryBuilder('ctype');
        return $qb;
    }

    public function getCorrespondanceType(?int $id): ?CorrespondanceType
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('ctype.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs CorrespondanceType partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedCorrespondanceType(AbstractActionController $controller, string $param = 'type'): ?CorrespondanceType
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getCorrespondanceType($id);
        return $result;
    }

    /**
     * @return CorrespondanceType[]
     */
    public function getCorrespondancesTypes(string $champ = 'code', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('ctype.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getCorrespondanceTypeByCode(string $code): ?CorrespondanceType
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('ctype.code = :code')->setParameter('code', $code)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".CorrespondanceType::class."] partagent le même id [" . $code . "]", 0, $e);
        }
        return $result;
    }

    public function generateDictionnaire(string $discrimant): array
    {
        $types = $this->getCorrespondancesTypes();

        $dictionnaire = [];
        foreach ($types as $type) {
            $tabId = match ($discrimant) {
                'code' => $type->getCode(),
                default => $type->getId(),
            };
            $dictionnaire[$tabId] = $type;
        }
        return $dictionnaire;
    }

    public function getCorrespondancesTypesAsOptions(): array
    {
        $types = $this->getCorrespondancesTypes();

        $options = [];
        foreach ($types as $type) {
            $options[$type->getId()] = $this->optionify($type);
        }
        return $options;
    }

    /** FACADE ********************************************************************************************************/

    public function optionify(CorrespondanceType $type): array
    {
        $this_option = [
            'value' => $type->getId(),
            'attributes' => [
                'data-content' =>
                    "<code>".$type->getCode() . "</code> " . $type->getLibelleLong(),
            ],
            'label' => $type->getLibelleLong(),
        ];
        return $this_option;
    }

}