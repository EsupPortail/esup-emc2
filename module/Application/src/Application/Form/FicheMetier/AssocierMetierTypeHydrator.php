<?php

namespace Application\Form\FicheMetier;

use Application\Entity\Db\FicheMetier;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

;

class AssocierMetierTypeHydrator implements HydratorInterface {
    use FicheMetierServiceAwareTrait;

    /**
     * @param FicheMetier $object
     * @return array
     */
    public function extract($object)
    {
        return [
            'metier_type' => ($object->getMetierType())?$object->getMetierType()->getId():0,
        ];
    }

    /**
     * @param FicheMetier $object
     * @param array $data
     * @return FicheMetier
     */
    public function hydrate(array $data, $object)
    {
        $metierType = $this->getFicheMetierService()->getFicheMetierType($data['metier_type']);

        if ($metierType) {
            $object->setMetierType($metierType);
        } else {
            $object->setMetierType(null);
        }
        return $object;
    }
}