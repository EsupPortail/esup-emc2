<?php

namespace Application\Form\ApplicationGroupe;

use Application\Entity\Db\ApplicationGroupe;
use Zend\Hydrator\HydratorInterface;

class ApplicationGroupeHydrator implements HydratorInterface {

    /**
     * @param ApplicationGroupe $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle'   => ($object->getLibelle())?:null,
            'ordre'     => ($object->getOrdre())?:null,
//            'couleur'   => ($object->getCouleur())?:null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param ApplicationGroupe $object
     * @return ApplicationGroupe
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle((isset($data['libelle']) AND trim($data['libelle']) !== '')?trim($data['libelle']):null);
        $object->setOrdre((isset($data['ordre']) AND trim($data['ordre']) !== '')?trim($data['ordre']):null);
//        $object->setCouleur((isset($data['couleur']) AND trim($data['couleur']) !== '')?trim($data['couleur']):null);
        return $object;
    }


}