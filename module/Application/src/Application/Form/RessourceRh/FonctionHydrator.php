<?php

namespace Application\Form\RessourceRh;

use Application\Entity\Db\Fonction;
use Application\Service\Domaine\DomaineServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class FonctionHydrator implements HydratorInterface {
    use DomaineServiceAwareTrait;

    /**
     * @param Fonction $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'domaine' => ($object->getDomaine())?$object->getDomaine()->getId():null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Fonction $object
     * @return Fonction
     */
    public function hydrate(array $data, $object)
    {
        $domaine = $this->getDomaineService()->getDomaine($data['domaine']);

        $object->setDomaine($domaine);

        return $object;
    }

}