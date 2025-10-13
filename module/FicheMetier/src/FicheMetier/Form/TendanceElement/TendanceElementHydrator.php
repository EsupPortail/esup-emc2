<?php

namespace FicheMetier\Form\TendanceElement;

use FicheMetier\Entity\Db\TendanceElement;
use Laminas\Hydrator\HydratorInterface;

class TendanceElementHydrator implements HydratorInterface
{

    public function extract(object $object): array
    {
        /** @var TendanceElement $object */
        $data = [
            'texte' => $object->getTexte(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $texte = (isset($data['texte']) && trim($data['texte']) !== "")?trim($data['texte']):null;

        /** @var TendanceElement $object */
        $object->setTexte($texte);
        return $object;

    }

}