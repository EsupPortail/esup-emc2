<?php

namespace EntretienProfessionnel\Form\ConfigurationRecopie;

use Application\Entity\Db\ConfigurationEntretienProfessionnel;
use Laminas\Hydrator\HydratorInterface;

class ConfigurationRecopieHydrator implements HydratorInterface {

    /**
     * @param ConfigurationEntretienProfessionnel $object
     * @return array
     */
    public function extract($object): array
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
