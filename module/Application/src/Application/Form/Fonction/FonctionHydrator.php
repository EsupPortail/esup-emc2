<?php

namespace Application\Form\Fonction;

use Application\Entity\Db\Fonction;
use Zend\Stdlib\Hydrator\HydratorInterface;

class FonctionHydrator implements HydratorInterface {

    /**
     * @param Fonction $object
     * @return array
     */
    public function extract($object)
    {
        $data = [];
        $data['libelle_masculin'] = null;
        $data['libelle_feminin'] = null;


        foreach ($object->getLibelles() as $libelle) {
            if ($libelle->getDefault()) {
                if ($libelle->getGenre() === 'M') $data['libelle_masculin'] = $libelle->getLibelle();
                if ($libelle->getGenre() === 'F') $data['libelle_feminin'] = $libelle->getLibelle();
            }
        }

        return $data;
    }

    /**
     * @param array $data
     * @param Fonction $object
     * @return Fonction
     */
    public function hydrate(array $data, $object)
    {
        //delegué au fonction du controller
    }

}