<?php

namespace Application\Form\RessourceRh;

use Application\Entity\Db\MissionSpecifique;
use Application\Entity\Db\MissionSpecifiqueTheme;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class MissionSpecifiqueHydrator implements HydratorInterface
{
    use RessourceRhServiceAwareTrait;

    /**
     * @param MissionSpecifique $object
     * @return array
     */
    public function extract($object)
    {
        $data = [];
        $data['libelle'] = $object->getLibelle();
        if ($object->getType()) $data['type'] = $object->getType()->getId();
        if ($object->getTheme()) $data['theme'] = $object->getTheme()->getId();
        return $data;
    }

    /**
     * @param array $data
     * @param MissionSpecifique $object
     * @return MissionSpecifique
     */
    public function hydrate(array $data, $object)
    {
        if (isset($data['libelle'])) {
            $object->setLibelle($data['libelle']);
        } else {
            $object->setLibelle(null);
        }

        if (isset($data['type']) AND $data['type'] != "") {
            $type = $this->getRessourceRhService()->getMissionSpecifiqueType($data['type']);
            $object->setType($type);
        } else {
            $object->setType(null);
        }

        if (isset($data['theme']) AND $data['theme'] != "") {
            $theme = $this->getRessourceRhService()->getMissionSpecifiqueTheme($data['theme']);
            $object->setTheme($theme);
        } else {
            $object->setTheme(null);
        }

        return $object;
    }



}