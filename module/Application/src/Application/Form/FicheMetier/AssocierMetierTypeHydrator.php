<?php

namespace Application\Form\FicheMetier;

use Application\Entity\Db\FichePoste;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

;

class AssocierMetierTypeHydrator implements HydratorInterface {
    use FicheMetierServiceAwareTrait;

    /**
     * @param FichePoste $object
     * @return array
     */
    public function extract($object)
    {
        return [
            'metier_type' => ($object->getMetierType())?$object->getMetierType()->getId():0,
        ];
    }

    /**
     * @param FichePoste $object
     * @param array $data
     * @return FichePoste
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