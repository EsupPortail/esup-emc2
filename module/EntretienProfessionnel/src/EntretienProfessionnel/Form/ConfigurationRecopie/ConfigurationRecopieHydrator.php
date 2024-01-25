<?php

namespace EntretienProfessionnel\Form\ConfigurationRecopie;

use Application\Entity\Db\ConfigurationEntretienProfessionnel;
use Laminas\Hydrator\HydratorInterface;

class ConfigurationRecopieHydrator implements HydratorInterface {

    /**
     * @param ConfigurationEntretienProfessionnel $object
     * @return array
     */
    public function extract(object $object): array
    {
        [$form, $ids] = explode("|",$object->getValeur());
        [$from, $to] = explode(";", $ids);
        $data = [
            'operation' => 'recopie',
            'from' => $from,
            'to'   => $to,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param ConfigurationEntretienProfessionnel $object
     * @return ConfigurationEntretienProfessionnel
     */
    public function hydrate(array $data, object $object): object
    {
        $valeur = $data['from'] . ";" . $data['to'];
        $object->setOperation($data['operation']);
        $object->setValeur($valeur);
        return $object;
    }
}
