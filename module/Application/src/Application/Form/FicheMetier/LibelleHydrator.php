<?php

namespace Application\Form\FicheMetier;

use Application\Entity\Db\FicheMetier;
use Application\Service\Metier\MetierServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class LibelleHydrator implements HydratorInterface {
    use MetierServiceAwareTrait;

    /**
     * @param FicheMetier $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'metier' => ($object->getMetier())?$object->getMetier()->getLibelle():null,
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
        $metier = $this->getMetierService()->getMetier($data['metier']);
        $object->setMetier($metier);
        return $object;
    }

}