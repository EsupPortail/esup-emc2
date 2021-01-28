<?php

namespace Application\Form\ModifierRattachement;

use Application\Entity\Db\ParcoursDeFormation;
use Zend\Hydrator\HydratorInterface;

class ModifierRattachementHydrator implements HydratorInterface {

    /**
     * @param ParcoursDeFormation $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'type'        => $object->getType(),
        ];

        if ($object->getType() === ParcoursDeFormation::TYPE_CATEGORIE) $data['categorie'] = $object->getReference();
        if ($object->getType() === ParcoursDeFormation::TYPE_DOMAINE) $data['domaine'] = $object->getReference();
        if ($object->getType() === ParcoursDeFormation::TYPE_METIER) $data['metier'] = $object->getReference();

        return $data;
    }

    /**
     * @param array $data
     * @param ParcoursDeFormation $object
     * @return ParcoursDeFormation
     */
    public function hydrate(array $data, $object)
    {
        $type = (isset($data['type']))?$data['type']:null;

        $reference = null;
        if ($type ===  ParcoursDeFormation::TYPE_CATEGORIE) $reference = $data['categorie'];
        if ($type ===  ParcoursDeFormation::TYPE_DOMAINE) $reference = $data['domaine'];
        if ($type ===  ParcoursDeFormation::TYPE_METIER) $reference = $data['metier'];

        $object->setType($type);
        $object->setReference($reference);

        return $object;
    }
}