<?php

namespace Indicateur\Form\Indicateur;

use Indicateur\Entity\Db\Indicateur;
use Zend\Stdlib\Hydrator\HydratorInterface;

class IndicateurHydrator implements HydratorInterface {

    /**
     * @param Indicateur $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle' => $object->getTitre(),
            'description' => $object->getDescription(),
            'view_id' => $object->getViewId(),
            'entity' => $object->getEntity(),
            'requete' => $object->getRequete(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Indicateur $object
     * @return Indicateur
     */
    public function hydrate(array $data, $object)
    {
        $object->setTitre($data['libelle']);
        $object->setDescription($data['description']);
        $object->setViewId($data['view_id']);
        $object->setEntity($data['entity']);
        $object->setRequete($data['requete']);
        return $object;
    }

}