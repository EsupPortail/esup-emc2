<?php

namespace Application\Form\FonctionActivite;

use Application\Entity\Db\FonctionActivite;
use Application\Service\Fonction\FonctionServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class FonctionActiviteHydrator implements HydratorInterface {
    use FonctionServiceAwareTrait;

    /**
     * @param FonctionActivite $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'code' => $object->getCode(),
            'libelle' => $object->getLibelle(),
            'destination' => ($object->getDestination())?$object->getDestination()->getId():null,
        ];

        return $data;
    }

    /**
     * @param array $data
     * @param FonctionActivite $object
     * @return FonctionActivite
     */
    public function hydrate(array $data, $object)
    {
        $code = (isset($data['code']) AND trim($data['code']) !== "")?trim($data['code']):null;
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== "")?trim($data['libelle']):null;
        $destination = (isset($data['destination']))?$this->getFonctionService()->getDestination($data['destination']):null;

        $object->setCode($code);
        $object->setLibelle($libelle);
        $object->setDestination($destination);
        return $object;
    }

}