<?php

namespace Carriere\Service\CorrespondanceType;

use Carriere\Entity\Db\CorrespondanceType;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class CorrespondanceTypeService
{

    use ProvidesObjectManager;

    /** GESTION DES ENITIES *******************************************************************************************/

    // les grades sont importés et ne sont pas gérés dans l'application.

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

    /** FACADE ********************************************************************************************************/


}