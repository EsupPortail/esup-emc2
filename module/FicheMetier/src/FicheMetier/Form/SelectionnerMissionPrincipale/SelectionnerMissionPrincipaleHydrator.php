<?php

namespace FicheMetier\Form\SelectionnerMissionPrincipale;

use FicheMetier\Entity\Db\FicheMetierMission;
use FicheMetier\Entity\Db\Mission;
use FicheMetier\Service\FicheMetierMission\FicheMetierMissionServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;
use FicheMetier\Entity\Db\Interface\HasMissionsPrincipalesInterface;

class SelectionnerMissionPrincipaleHydrator implements HydratorInterface
{
    use MissionPrincipaleServiceAwareTrait;
    use FicheMetierMissionServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var HasMissionsPrincipalesInterface $object */
        $missions = array_map(function (FicheMetierMission $a) { return $a->getMission(); }, $object->getMissions());
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
        foreach ($object->getMissions() as $ficheMetierMission) {
            if (! isset($missions[$ficheMetierMission->getMission()->getId()])) {
                $this->getFicheMetierMissionService()->delete($ficheMetierMission);
            }
        }

        foreach ($missions as $mission) {
            if (!$object->hasMission($mission)) {
                $element = new FicheMetierMission();
                $element->setMission($mission);
                $element->setFicheMetier($object);
                $element->setOrdre(9999);
                $this->getFicheMetierMissionService()->create($element);
//                $object->addMissionElement($element);
            }
        }

        return $object;
    }


}
