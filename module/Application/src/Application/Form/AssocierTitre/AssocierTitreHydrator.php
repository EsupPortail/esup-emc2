<?php

namespace Application\Form\AssocierTitre;

use Application\Entity\Db\FichePoste;
use Zend\Hydrator\HydratorInterface;

class AssocierTitreHydrator implements HydratorInterface {

    /**
     * @param FichePoste $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'titre' => $object->getLibelle(),
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
        if ($data['titre'] == '') $object->setLibelle(null);
        else $object->setLibelle($data['titre']);

        return $object;
    }


}