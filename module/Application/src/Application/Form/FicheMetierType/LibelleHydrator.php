<?php

namespace Application\Form\FicheMetierType;

use Application\Entity\Db\FicheMetier;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

class LibelleHydrator implements HydratorInterface {
    use RessourceRhServiceAwareTrait;
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
        $metier = $this->getRessourceRhService()->getMetier($data['metier']);
        $object->setMetier($metier);
        return $object;
    }

}