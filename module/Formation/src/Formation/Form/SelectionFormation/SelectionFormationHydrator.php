<?php

namespace Formation\Form\SelectionFormation;

use Application\Entity\Db\Activite;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationElement;
use Laminas\Hydrator\HydratorInterface;

class SelectionFormationHydrator implements HydratorInterface
{

    /**
     * @param Activite $object
     * @return array|void
     */
    public function extract($object): array
    {
        $formations = array_map(function (FormationElement $a) { return $a->getFormation(); }, $object->getFormationListe());
        $formationIds = array_map(function (Formation $f) { return $f->getId();}, $formations);
        $data = [
            'formations' => $formationIds,
        ];
        return $data;
    }

    public function hydrate(array $data, $object)
    {
        //never used
    }
}