<?php

namespace UnicaenParametre\Form\Parametre;

use UnicaenParametre\Entity\Db\Parametre;
use Zend\Hydrator\HydratorInterface;

class ParametreHydrator implements HydratorInterface {

    /**
     * @param Parametre $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'code' => $object->getCode(),
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
            'valeurs_possibles' => $object->getValeursPossibles(),
            'ordre' => $object->getOrdre(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Parametre $object
     * @return Parametre
     */
    public function hydrate(array $data, $object)
    {
        $code = (isset($data['code']) and trim($data['code']) !== '')?trim($data['code']):null;
        $libelle = (isset($data['libelle']) and trim($data['libelle']) !== '')?trim($data['libelle']):null;
        $description = (isset($data['description']) and trim($data['description']) !== '')?trim($data['description']):null;
        $valeurs = (isset($data['valeurs_possibles']) and trim($data['valeurs_possibles']) !== '')?trim($data['valeurs_possibles']):null;
        $ordre = (isset($data['ordre']) and trim($data['ordre']) !== '')?trim($data['ordre']):null;

        $object->setCode($code);
        $object->setLibelle($libelle);
        $object->setDescription($description);
        $object->setValeursPossibles($valeurs);
        $object->setOrdre($ordre);
        return $object;
    }


}