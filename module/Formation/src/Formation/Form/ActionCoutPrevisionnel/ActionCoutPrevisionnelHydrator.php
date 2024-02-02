<?php

namespace Formation\Form\ActionCoutPrevisionnel;

use Formation\Entity\Db\ActionCoutPrevisionnel;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\PlanDeFormation\PlanDeFormationServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class ActionCoutPrevisionnelHydrator implements HydratorInterface
{
    use FormationServiceAwareTrait;
    use PlanDeFormationServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var ActionCoutPrevisionnel $object */
        $data = [
            'action' => ($object->getAction())?->getId(),
            'plan' => ($object->getPlan())?->getId(),
            'cout' => $object->getCoutParSession(),
            'nombre' => $object->getNombreDeSession(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $action = (isset($data['action']) && trim($data['action']) !== '')? $this->getFormationService()->getFormation($data['action']) : null;
        $plan = (isset($data['plan']) && trim($data['plan'])) ? $this->getPlanDeFormationService()->getPlanDeFormation($data['plan']) : null;
        $cout = $data['cout'] ?? null;
        $nombre = $data['nombre'] ?? null;

        /** @var ActionCoutPrevisionnel $object */
        $object->setAction($action);
        $object->setPlan($plan);
        $object->setCoutParSession($cout);
        $object->setNombreDeSession($nombre);
        return $object;
    }


}