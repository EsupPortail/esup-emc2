<?php

namespace Structure\Service\Type;

use DoctrineModule\Persistence\ProvidesObjectManager;
use Structure\Entity\Db\StructureType;

class TypeService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES ************************************************/

    public function create(StructureType $type): StructureType
    {
        $this->getObjectManager()->persist($type);
        $this->getObjectManager()->flush($type);
        return $type;
    }

    public function update(StructureType $type): StructureType
    {
        $this->getObjectManager()->flush($type);
        return $type;
    }

    public function delete(StructureType $type): StructureType
    {
        $this->getObjectManager()->remove($type);
        $this->getObjectManager()->flush($type);
        return $type;
    }

    /** QUERYING ******************************************************************/

    /** @return StructureType[] */
    public function getTypes(): array
    {
        $result = $this->getObjectManager()->getRepository(StructureType::class)->findAll();
        $types = [];
        foreach ($result as $item) $types[$item->getId()] = $item;
        return $types;
    }

}