<?php

namespace Application\Form\FicheMetierType;

use Application\Entity\Db\FicheMetier;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

class FormationOperationnelleHydrator implements HydratorInterface {
    use RessourceRhServiceAwareTrait;
    /**
     * @param FicheMetier $object
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
     * @param FicheMetier $object
     * @return FicheMetier
     */
    public function hydrate(array $data, $object)
    {
        $object->setCompetencesOperationnelles($data['description']);
        $object->setCompetencesOperationnellesFormation($data['formation']);

        return $object;
    }

}