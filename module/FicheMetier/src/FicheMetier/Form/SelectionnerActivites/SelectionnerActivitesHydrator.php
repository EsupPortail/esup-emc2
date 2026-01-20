<?php

namespace FicheMetier\Form\SelectionnerActivites;

use FicheMetier\Entity\Db\Activite;
use FicheMetier\Entity\Db\ActiviteElement;
use FicheMetier\Entity\Db\Interface\HasActivitesInterface;
use FicheMetier\Service\Activite\ActiviteServiceAwareTrait;
use FicheMetier\Service\ActiviteElement\ActiviteElementServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class SelectionnerActivitesHydrator implements HydratorInterface
{
    use ActiviteServiceAwareTrait;
    use ActiviteElementServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var HasActivitesInterface $object */
        $activites = array_map(function (ActiviteElement $a) {
            return $a->getActivite();
        }, $object->getActivites());
        $activitesIds = array_map(function (Activite $f) {
            return $f->getId();
        }, $activites);
        $data = [
            'activites' => $activitesIds,
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $activitesIds = $data["activites"];

        $activites = [];
        foreach ($activitesIds as $activiteId) {
            $activite = $this->getActiviteService()->getActivite($activiteId);
            if ($activite) $activites[$activite->getId()] = $activite;
        }

        /** @var HasActivitesInterface $object */
        foreach ($object->getActivites() as $activiteElement) {
            if (!isset($activites[$activiteElement->getActivite()->getId()])) {
                $this->getActiviteElementService()->delete($activiteElement);
            }
        }

        foreach ($activites as $activite) {
            if (!$object->hasActivite($activite)) {
                $this->getActiviteElementService()->addActiviteElement($object, $activite, null, 9999, false);
            }
        }

        return $object;
    }


}
