<?php

namespace Application\Form\RessourceRh;

use Application\Entity\Db\Metier;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

class MetierHydrator implements HydratorInterface {
    use RessourceRhServiceAwareTrait;

    /**
     * @param Metier $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'famille' => $object->getFamille(),
            'domaine' => $object->getDomaine(),
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
        $famille = $this->getRessourceRhService()->getMetierFamille($data['famille']);
        $domaine = $this->getRessourceRhService()->getDomaine($data['domaine']);

        $object->setLibelle($data['libelle']);
        $object->setFamille($famille);
        $object->setDomaine($domaine);
        return $object;
    }

}