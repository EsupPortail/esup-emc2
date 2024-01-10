<?php

namespace MissionSpecifique\Form\MissionSpecifique;

use MissionSpecifique\Entity\Db\MissionSpecifique;
use MissionSpecifique\Service\MissionSpecifique\MissionSpecifiqueServiceAwareTrait;
use MissionSpecifique\Service\MissionSpecifiqueTheme\MissionSpecifiqueThemeServiceAwareTrait;
use MissionSpecifique\Service\MissionSpecifiqueType\MissionSpecifiqueTypeServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class MissionSpecifiqueHydrator implements HydratorInterface
{
    use MissionSpecifiqueServiceAwareTrait;
    use MissionSpecifiqueThemeServiceAwareTrait;
    use MissionSpecifiqueTypeServiceAwareTrait;

    public function extract($object): array
    {
        /** @var MissionSpecifique $object */

        $data = [];
        $data['libelle'] = $object->getLibelle();
        if ($object->getType()) $data['type'] = $object->getType()->getId();
        if ($object->getTheme()) $data['theme'] = $object->getTheme()->getId();
        if ($object->getDescription()) $data['description'] = $object->getDescription();
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        /** @var MissionSpecifique $object */
        if (isset($data['libelle'])) {
            $object->setLibelle($data['libelle']);
        } else {
            $object->setLibelle(null);
        }

        if (isset($data['type']) AND $data['type'] != "") {
            $type = $this->getMissionSpecifiqueTypeService()->getMissionSpecifiqueType($data['type']);
            $object->setType($type);
        } else {
            $object->setType(null);
        }

        if (isset($data['theme']) AND $data['theme'] != "") {
            $theme = $this->getMissionSpecifiqueThemeService()->getMissionSpecifiqueTheme($data['theme']);
            $object->setTheme($theme);
        } else {
            $object->setTheme(null);
        }

        $description = (isset($data['description']) AND trim($data['description']) !== '')?trim($data['description']):null;
        $object->setDescription($description);
        return $object;
    }



}