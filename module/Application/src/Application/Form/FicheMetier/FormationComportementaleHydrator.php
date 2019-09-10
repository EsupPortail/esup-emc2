<?php

namespace Application\Form\FicheMetier;

use Application\Entity\Db\FicheMetier;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class FormationComportementaleHydrator implements HydratorInterface {
    use RessourceRhServiceAwareTrait;
    /**
     * @param FicheMetier $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'description' => $object->getCompetencesComportementales(),
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
        $object->setCompetencesComportementales($data['description']);

        return $object;
    }

}