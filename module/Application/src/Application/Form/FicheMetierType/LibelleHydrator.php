<?php

namespace Application\Form\FicheMetierType;

use Application\Entity\Db\FicheMetierType;
use Application\Service\Metier\MetierServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

class LibelleHydrator implements HydratorInterface {
    use MetierServiceAwareTrait;
    /**
     * @param FicheMetierType $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'metier' => $object->getMetier()->getLibelle(),
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
        $metier = $this->getMetierService()->getMetier($data['metier']);
        $object->setMetier($metier);
        return $object;
    }

}