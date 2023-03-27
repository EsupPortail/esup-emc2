<?php

namespace Element\Form\ApplicationTheme;

use Element\Entity\Db\ApplicationTheme;
use Laminas\Hydrator\HydratorInterface;

class ApplicationThemeHydrator implements HydratorInterface {

    /**
     * @param ApplicationTheme $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'libelle'   => ($object->getLibelle())?:null,
            'ordre'     => ($object->getOrdre())?:null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param ApplicationTheme $object
     * @return ApplicationTheme
     */
    public function hydrate(array $data, $object) : object
    {
        $object->setLibelle((isset($data['libelle']) AND trim($data['libelle']) !== '')?trim($data['libelle']):null);
        $object->setOrdre((isset($data['ordre']) AND trim($data['ordre']) !== '')?trim($data['ordre']):null);
        return $object;
    }


}