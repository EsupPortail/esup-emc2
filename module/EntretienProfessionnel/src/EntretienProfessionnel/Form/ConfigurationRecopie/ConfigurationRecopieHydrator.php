<?php

namespace EntretienProfessionnel\Form\ConfigurationRecopie;

use Application\Entity\Db\ConfigurationEntretienProfessionnel;
use Laminas\Hydrator\HydratorInterface;

class ConfigurationRecopieHydrator implements HydratorInterface
{
    public function extract(object $object): array
    {
        /** @var ConfigurationEntretienProfessionnel $object */
        [$form, $ids] = ($object->getValeur() !== null)?explode("|",$object->getValeur()):[null,null];
        [$from, $to] = (isset($ids))?explode(";", $ids):[null,null];
        $data = [
            'operation' => 'recopie',
            'from' => $from,
            'to'   => $to,
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $valeur = $data['from'] . ";" . $data['to'];

        /** @var ConfigurationEntretienProfessionnel $object  */
        $object->setOperation($data['operation']);
        $object->setValeur($valeur);
        return $object;
    }
}
