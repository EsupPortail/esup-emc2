<?php

namespace Formation\Form\FormationTheme;

use Formation\Entity\Db\FormationTheme;
use Zend\Hydrator\HydratorInterface;

class FormationThemeHydrator implements HydratorInterface
{

    /**
     * @param FormationTheme $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle' => ($object->getLibelle()) ?: null,
//            'ordre'     => ($object->getOrdre())?:null,
//            'couleur'   => ($object->getCouleur())?:null,
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
        $object->setLibelle((isset($data['libelle']) and trim($data['libelle']) !== '') ? trim($data['libelle']) : null);
//        $object->setOrdre((isset($data['ordre']) AND trim($data['ordre']) !== '')?trim($data['ordre']):null);
//        $object->setCouleur((isset($data['couleur']) AND trim($data['couleur']) !== '')?trim($data['couleur']):null);
        return $object;
    }


}