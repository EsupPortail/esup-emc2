<?php

namespace Application\Form\Rifseep;

use Application\Entity\Db\FichePoste;
use Laminas\Hydrator\HydratorInterface;

class RifseepHydrator implements HydratorInterface {

    /**
     * @param FichePoste $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            "rifseep" => $object->getRifseep(),
            "nbi" => $object->getNbi(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FichePoste $object
     * @return FichePoste
     */
    public function hydrate(array $data, $object)
    {
        $rifseep = ($data['rifseep'] AND trim($data['rifseep']) !== '')?trim($data['rifseep']):null;
        $nbi = ($data['nbi'] AND trim($data['nbi']) !== '')?trim($data['nbi']):null;

        $object->setRifseep($rifseep);
        $object->setNbi($nbi);

        return $object;
    }

}