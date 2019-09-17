<?php

namespace Application\Form\FormationTheme;

use Application\Entity\Db\FormationTheme;
use Zend\Hydrator\HydratorInterface;

class FormationThemeHydrator implements HydratorInterface {

    /**
     * @param FormationTheme $object
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
     * @param FormationTheme $object
     * @return FormationTheme
     */
    public function hydrate(array $data, $object)
    {
        if ($data['libelle'] == '') {
            $object->setLibelle(null);
        } else {
            $object->setLibelle($data['libelle']);
        }

        return $object;
    }

}