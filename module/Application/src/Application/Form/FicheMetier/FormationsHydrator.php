<?php

namespace Application\Form\FicheMetier;

use Application\Entity\Db\FicheMetier;
use Application\Service\Formation\FormationServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class FormationsHydrator implements HydratorInterface {
    use FormationServiceAwareTrait;
    /**
     * @param FicheMetier $object
     * @return array
     */
    public function extract($object)
    {
        $array = [];
        foreach ($object->getFormations() as $formation) {
            $array[] = $formation->getId();
        }
        $data = [
                'formations' => $array,
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
        if (isset($data['formations'])) {
            $object->clearFormations();
            foreach ($data['formations'] as $formationId) {
                $formation = $this->getFormationService()->getFormation($formationId);
                if ($formation) $object->addFormation($formation);
            }
        }
        return $object;
    }
}