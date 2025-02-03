<?php

namespace UnicaenContact\Service\Type;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use UnicaenContact\Entity\Db\Type;

class TypeService {
    use ProvidesObjectManager;

    /** GESTION DES ENTITÉS *******************************************************************************************/

    public function create(Type $type): Type
    {
        $this->getObjectManager()->persist($type);
        $this->getObjectManager()->flush();
        return $type;
    }

    public function update(Type $type): Type
    {
        $this->getObjectManager()->flush();
        return $type;
    }

    public function historise(Type $type): Type
    {
        $type->historiser();
        $this->getObjectManager()->flush();
        return $type;
    }

    public function restore(Type $type): Type
    {
        $type->dehistoriser();
        $this->getObjectManager()->flush();
        return $type;
    }

    public function delete(Type $type): Type
    {
        $this->getObjectManager()->remove($type);
        $this->getObjectManager()->flush();
        return $type;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Type::class)->createQueryBuilder('contacttype')
            ->leftjoin('contacttype.contacts', 'contact')->addSelect('contact')
        ;
        return $qb;
    }

    /** @return Type[] */
    public function getTypes(bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('contacttype.libelle', 'ASC');

        if (!$withHisto) $qb = $qb->andWhere('contacttype.histoDestruction IS NULL');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return Type[] */
    public function getTypesAsOptions(bool $withHisto = false): array
    {
        $types = $this->getTypes($withHisto);

        $options = [];
        foreach ($types as $type) {
            $options[$type->getId()] = $type->getLibelle();
        }
        return $options;
    }

    public function getType(?int $id): ?Type
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('contacttype.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Type::class."] partagent le même id [".$id."]", 0 , $e);
        }
        return $result;
    }

    public function getRequestedType(AbstractActionController $controller, string $param = 'contact-type'): ?Type
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getType($id);
        return $result;
    }

    public function getTypeByCode(?string $code): ?Type
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('contacttype.code = :code')->setParameter('code', $code);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Type::class."] partagent le même code [".$code."]", 0 , $e);
        }
        return $result;
    }

    /** FACADE ********************************************************************************************************/
}