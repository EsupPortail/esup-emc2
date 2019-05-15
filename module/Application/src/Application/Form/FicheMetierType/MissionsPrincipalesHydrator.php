<?php

namespace Application\Form\FicheMetierType;

use Application\Entity\Db\FicheMetier;
use Zend\Stdlib\Hydrator\HydratorInterface;

class MissionsPrincipalesHydrator implements HydratorInterface {

    /**
     * @param FicheMetier $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'description' => $object->getMissionsPrincipales(),
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
        $object->setMissionsPrincipales($data['description']);
        return $object;
    }

}