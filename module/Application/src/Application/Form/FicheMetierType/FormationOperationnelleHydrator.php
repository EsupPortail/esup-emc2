<?php

namespace Application\Form\FicheMetierType;

use Application\Entity\Db\FicheMetierType;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

class FormationOperationnelleHydrator implements HydratorInterface {
    use RessourceRhServiceAwareTrait;
    /**
     * @param FicheMetierType $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'description' => $object->getCompetencesOperationnelles(),
            'formation' => $object->getCompetencesOperationnellesFormation(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FicheMetierType $object
     * @return FicheMetierType
     */
    public function hydrate(array $data, $object)
    {
        $object->setCompetencesOperationnelles($data['description']);
        $object->setCompetencesOperationnellesFormation($data['formation']);

        return $object;
    }

}