<?php

namespace Application\Form\ConfigurationEntretienProfessionnel;

use Application\Entity\Db\ConfigurationEntretienProfessionnel;
use Zend\Hydrator\HydratorInterface;

class ConfigurationEntretienProfessionnelHydrator implements HydratorInterface {

    /**
     * @param ConfigurationEntretienProfessionnel $object
     * @return array
     */
    public function extract($object)
    {
        $splits = explode(";",$object->getValeur());
        $data = [
            'operation' => 'recopie',
            'from' => $splits[0],
            'to'   => $splits[1],
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param ConfigurationEntretienProfessionnel $object
     * @return ConfigurationEntretienProfessionnel
     */
    public function hydrate(array $data, $object)
    {
        $valeur = $data['from'] . ";" . $data['to'];
        $object->setOperation($data['operation']);
        $object->setValeur($valeur);
        return $object;
    }
}
