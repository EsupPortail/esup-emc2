<?php

namespace Application\Form\Formation;

use Application\Entity\Db\Formation;
use Application\Service\Formation\FormationServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class FormationHydrator implements HydratorInterface {
    use FormationServiceAwareTrait;

    /**
     * @var Formation $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
            'lien' => $object->getLien(),
            'theme' => ($object->getTheme())?$object->getTheme()->getId():null,
        ];

        return $data;
    }

    /**
     * @param array $data
     * @param Formation $object
     * @return Formation
     */
    public function hydrate(array $data, $object)
    {
        $theme = null;
        if (isset($data['theme'])) $theme = $this->getFormationService()->getFormationTheme($data['theme']);
        $object->setLibelle($data['libelle']);
        $object->setDescription($data['description']);
        $object->setLien($data['lien']);
        $object->setTheme($theme);
        return $object;
    }


}