<?php

namespace Application\Form\FicheMetier;

use Application\Entity\Db\FicheMetier;
use Application\Service\Affectation\AffectationAwareServiceTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

class FicheMetierCreationHydrator implements HydratorInterface {
    use AffectationAwareServiceTrait;

    /**
     * @param FicheMetier $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle' => $object->getLibelle(),
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
        $affectation = $this->getAffectationService()->getAffectation($data['affectation']);

        $object->setLibelle($data['libelle']);
        return $object;
    }

}