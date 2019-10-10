<?php

namespace Application\Form\CompetenceTheme;

use Application\Entity\Db\CompetenceTheme;
use Application\Service\Formation\FormationServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class CompetenceThemeHydrator implements HydratorInterface {
    use FormationServiceAwareTrait;

    /**
     * @var CompetenceTheme $object
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
     * @param CompetenceTheme $object
     * @return CompetenceTheme
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        return $object;
    }


}