<?php

namespace Application\Form\FicheMetierType;

use Application\Entity\Db\FicheMetierType;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

class FormationBaseHydrator implements HydratorInterface {
    use RessourceRhServiceAwareTrait;
    /**
     * @param FicheMetierType $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'description' => $object->getConnaissances(),
            'formation' => $object->getConnaissancesFormation(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FicheMetierType $object
     * @return FicheMetierType
     */
    public function hydrate(array $data, $object)
    {
        $object->setConnaissances($data['description']);
        $object->setConnaissancesFormation($data['formation']);

        return $object;
    }

}