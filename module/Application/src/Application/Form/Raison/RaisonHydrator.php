<?php

namespace Application\Form\Raison;

use FicheMetier\Entity\Db\FicheMetier;
use Laminas\Hydrator\HydratorInterface;

class RaisonHydrator implements HydratorInterface {

    /**
     * @param FicheMetier $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            "raison" => $object->getRaison(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FicheMetier $object
     * @return FicheMetier
     */
    public function hydrate(array $data, $object) : object
    {
        $description = (isset($data['raison']) AND trim($data['raison']) != '')?trim($data['raison']):null;
        $object->setRaison($description);

        return $object;
    }

}