<?php

namespace Structure\Service\Type;

use Doctrine\ORM\Exception\ORMException;
use RuntimeException;
use Structure\Entity\Db\StructureType;
use UnicaenApp\Service\EntityManagerAwareTrait;

class TypeService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES ************************************************/

    public function create(StructureType $type) : StructureType
    {
        try {
            $this->getEntityManager()->persist($type);
            $this->getEntityManager()->flush($type);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue en base de donnée.",0,$e);
        }
        return $type;
    }

    public function update(StructureType $type) : StructureType
    {
        try {
            $this->getEntityManager()->flush($type);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue en base de donnée.",0,$e);
        }
        return $type;
    }

    public function historise(StructureType $type) : StructureType
    {
        try {
            $type->historise();
            $this->getEntityManager()->flush($type);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue en base de donnée.",0,$e);
        }
        return $type;
    }

    public function restore(StructureType $type) : StructureType
    {
        try {
            $type->dehistorise();
            $this->getEntityManager()->flush($type);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue en base de donnée.",0,$e);
        }
        return $type;
    }

    public function delete(StructureType $type) : StructureType
    {
        try {
            $this->getEntityManager()->remove($type);
            $this->getEntityManager()->flush($type);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue en base de donnée.",0,$e);
        }
        return $type;
    }

    /** QUERYING ******************************************************************/

    /** @return StructureType[] */
    public function getTypes() : array
    {
        $result = $this->getEntityManager()->getRepository(StructureType::class)->findAll();
        $types = []; foreach ($result as $item) $types[$item->getId()] = $item;
        return $types;
    }

}