<?php

namespace Element\Form\CompetenceTheme;

use Element\Entity\Db\CompetenceTheme;
use Zend\Hydrator\HydratorInterface;

class CompetenceThemeHydrator implements HydratorInterface {

    /**
     * @var CompetenceTheme $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle' => $object->getLibelle(),
        ];

        return $data;
    }

    /**
     * @param array $data
     * @param CompetenceTheme $object
     * @return CompetenceTheme
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        return $object;
    }


}