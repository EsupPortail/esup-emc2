<?php

namespace Formation\Form\FormationInstance;

use Formation\Entity\Db\FormationInstance;
use Zend\Hydrator\HydratorInterface;

class FormationInstanceHydrator implements HydratorInterface {

    /**
     * @param FormationInstance $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'description'     => ($object->getComplement())?:null,
            'principale'      => ($object->getNbPlacePrincipale())?:0,
            'complementaire'  => ($object->getNbPlaceComplementaire())?:0,
            'lieu'            => ($object->getLieu())?:null,
            'type'            => ($object->getType())?:null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FormationInstance $object
     * @return FormationInstance
     */
    public function hydrate(array $data, $object)
    {
        $description     = (isset($data['description']) AND trim($data['description']) !== "")?trim($data['description']):null;
        $principale      = (isset($data['principale']))?((int) $data['principale']):0;
        $complementaire  = (isset($data['complementaire']))?((int) $data['complementaire']):0;
        $lieu            = (isset($data['lieu']) AND trim($data['lieu']) !== "")?trim($data['lieu']):null;
        $type            = (isset($data['type']) AND trim($data['type']) !== "")?trim($data['type']):null;

        $object->setComplement($description);
        $object->setNbPlacePrincipale($principale);
        $object->setNbPlaceComplementaire($complementaire);
        $object->setLieu($lieu);
        $object->setType($type);

        return $object;
    }


}