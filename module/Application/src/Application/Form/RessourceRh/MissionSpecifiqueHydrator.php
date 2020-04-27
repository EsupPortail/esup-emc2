<?php

namespace Application\Form\RessourceRh;

use Application\Entity\Db\MissionSpecifique;
use Application\Service\MissionSpecifique\MissionSpecifiqueServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class MissionSpecifiqueHydrator implements HydratorInterface
{
    use MissionSpecifiqueServiceAwareTrait;

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
            $type = $this->getMissionSpecifiqueService()->getMissionSpecifiqueType($data['type']);
            $object->setType($type);
        } else {
            $object->setType(null);
        }

        if (isset($data['theme']) AND $data['theme'] != "") {
            $theme = $this->getMissionSpecifiqueService()->getMissionSpecifiqueTheme($data['theme']);
            $object->setTheme($theme);
        } else {
            $object->setTheme(null);
        }

        return $object;
    }



}