<?php

namespace Application\Form\RessourceRh;

use Application\Entity\Db\Metier;
use Application\Service\Fonction\FonctionServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

class MetierHydrator implements HydratorInterface {
    use FonctionServiceAwareTrait;

    /**
     * @param Metier $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'fonction' => ($object->getFonction())?$object->getFonction()->getId():null,
            'libelle' => $object->getLibelle(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Metier $object
     * @return Metier
     */
    public function hydrate(array $data, $object)
    {
        $fonction = $this->getFonctionService()->getFonction($data['fonction']);

        $object->setLibelle($data['libelle']);
        $object->setFonction($fonction);
        return $object;
    }

}