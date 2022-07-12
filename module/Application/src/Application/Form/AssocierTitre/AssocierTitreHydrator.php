<?php

namespace Application\Form\AssocierTitre;

use Application\Entity\Db\FichePoste;
use Laminas\Hydrator\HydratorInterface;

class AssocierTitreHydrator implements HydratorInterface {

    /**
     * @param FichePoste $object
     * @return array
     */
    public function extract($object): array
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