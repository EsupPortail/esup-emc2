<?php

namespace Element\Form\ApplicationTheme;

use Element\Entity\Db\ApplicationTheme;
use Zend\Hydrator\HydratorInterface;

class ApplicationThemeHydrator implements HydratorInterface {

    /**
     * @param ApplicationTheme $object
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
     * @param ApplicationTheme $object
     * @return ApplicationTheme
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle((isset($data['libelle']) AND trim($data['libelle']) !== '')?trim($data['libelle']):null);
        $object->setOrdre((isset($data['ordre']) AND trim($data['ordre']) !== '')?trim($data['ordre']):null);
//        $object->setCouleur((isset($data['couleur']) AND trim($data['couleur']) !== '')?trim($data['couleur']):null);
        return $object;
    }


}