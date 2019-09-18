<?php

namespace Application\Form\RessourceRh;

use Application\Entity\Db\MissionSpecifiqueTheme;
use Zend\Hydrator\HydratorInterface;

class MissionSpecifiqueThemeHydrator implements HydratorInterface
{
    /**
     * @param MissionSpecifiqueTheme $object
     * @return array
     */
    public function extract($object)
    {
        $data = [];
        $data['libelle'] = $object->getLibelle();
        return $data;
    }

    /**
     * @param array $data
     * @param MissionSpecifiqueTheme $object
     * @return MissionSpecifiqueTheme
     */
    public function hydrate(array $data, $object)
    {
        if (isset($data['libelle'])) {
            $object->setLibelle($data['libelle']);
        } else {
            $object->setLibelle(null);
        }
        return $object;
    }

}