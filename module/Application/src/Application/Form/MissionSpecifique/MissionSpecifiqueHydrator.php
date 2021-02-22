<?php

namespace Application\Form\MissionSpecifique;

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
        if ($object->getDescription()) $data['description'] = $object->getDescription();
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

        $description = (isset($data['description']) AND trim($data['description']) !== '')?trim($data['description']):null;
        $object->setDescription($description);
        return $object;
    }



}