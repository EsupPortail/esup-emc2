<?php

namespace Application\Form\RessourceRh;

use Application\Entity\Db\Grade;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

class GradeHydrator implements HydratorInterface {
    use RessourceRhServiceAwareTrait;

    /**
     * @param Grade $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle'   => $object->getLibelle(),
            'rang'      => $object->getRang(),
            'corps'     => ($object && $object->getCorps())?$object->getCorps()->getId():0,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Grade $object
     * @return Grade
     */
    public function hydrate(array $data, $object)
    {
        $corps = $this->getRessourceRhService()->getCorps($data['corps']);

        $object->setLibelle($data['libelle']);
        $object->setRang($data['rang']);
        $object->setCorps($corps);
        return $object;
    }

}