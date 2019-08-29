<?php

namespace Application\Form\Activite;

use Application\Entity\Db\Activite;
use Application\Service\Application\ApplicationServiceAwareTrait;
use Application\Service\Formation\FormationServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ActiviteHydrator implements HydratorInterface {
    use ApplicationServiceAwareTrait;
    use FormationServiceAwareTrait;

    /**
     * @param Activite $object
     * @return array
     */
    public function extract($object)
    {
        $applicationIds = [];
        foreach ($object->getApplications() as $application) {
            $applicationIds[] = $application->getId();
        }

        $formationIds = [];
        foreach ($object->getFormations() as $formation) {
            $formationIds[] = $formation->getId();
        }

        $data = [
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
            'applications' => $applicationIds,
            'formations' => $formationIds,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Activite $object
     * @return Activite
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        $object->setDescription($data['description']);

        foreach ($object->getApplications() as $application) {
            $object->removeApplication($application);
        }
        foreach ($data['applications'] as $id) {
            $application = $this->getApplicationService()->getApplication($id);
            if ($application) $object->addApplication($application);
        }

        foreach ($object->getFormations() as $formation) {
            $object->removeFormation($formation);
        }
        foreach ($data['formations'] as $id) {
            $formation = $this->getFormationService()->getFormation($id);
            if ($formation) $object->addFormation($formation);
        }
        return $object;
    }

}