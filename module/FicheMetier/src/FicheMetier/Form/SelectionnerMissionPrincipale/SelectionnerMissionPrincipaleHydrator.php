<?php

namespace FicheMetier\Form\SelectionnerMissionPrincipale;

use FicheMetier\Entity\Db\Mission;
use FicheMetier\Entity\Db\MissionElement;
use FicheMetier\Service\MissionElement\MissionElementServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;
use FicheMetier\Entity\Db\Interface\HasMissionsPrincipalesInterface;

class SelectionnerMissionPrincipaleHydrator implements HydratorInterface
{
    use MissionPrincipaleServiceAwareTrait;
    use MissionElementServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var HasMissionsPrincipalesInterface $object */
        $missions = array_map(function (MissionElement $a) { return $a->getMission(); }, $object->getMissions());
        $missionsIds = array_map(function (Mission $f) { return $f->getId();}, $missions);
        $data = [
            'missions' => $missionsIds,
        ];
        return $data;

    }

    public function hydrate(array $data, object $object): object
    {
        $missionsIds = $data["missions"];

        $missions = [];
        foreach ($missionsIds as $missionId) {
            $mission = $this->getMissionPrincipaleService()->getMissionPrincipale($missionId);
            if ($mission) $missions[$mission->getId()] = $mission;
        }

        /** @var HasMissionsPrincipalesInterface $object */
        foreach ($object->getMissions() as $missionElement) {
            if (!isset($missions[$missionElement->getMission()->getId()])) {
                $this->getMissionElementService()->delete($missionElement);
            }
        }

        foreach ($missions as $mission) {
            if (!$object->hasMission($mission)) {
                $this->getMissionElementService()->addMissionElement($object, $mission, null, 9999, false);
            }
        }

        return $object;
    }


}
