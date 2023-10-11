<?php

namespace Formation\Form\SelectionFormation;

use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationElement;
use Formation\Entity\Db\Interfaces\HasFormationCollectionInterface;
use Laminas\Hydrator\HydratorInterface;

class SelectionFormationHydrator implements HydratorInterface
{

    /**
     * @param HasFormationCollectionInterface $object
     * @return array
     */
    public function extract($object): array
    {
        $formations = array_map(function ($a) { return ($a instanceof  Formation)?$a: $a->getFormation(); }, $object->getFormationListe());
        $formationIds = array_map(function (Formation $f) { return $f->getId();}, $formations);
        $data = [
            'formations' => $formationIds,
        ];
        return $data;
    }

    public function hydrate(array $data, $object): object
    {
        return $object;
    }
}