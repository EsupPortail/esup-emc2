<?php

namespace Application\Form\FicheMetier;

use Application\Entity\Db\FicheMetier;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

class FormationBaseHydrator implements HydratorInterface {
    use RessourceRhServiceAwareTrait;
    /**
     * @param FicheMetier $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'description' => $object->getConnaissances(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FicheMetier $object
     * @return FicheMetier
     */
    public function hydrate(array $data, $object)
    {
        $object->setConnaissances($data['description']);
        return $object;
    }

}