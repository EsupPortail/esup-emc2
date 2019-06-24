<?php

namespace Application\Form\Activite;

use Application\Entity\Db\Activite;
use Application\Service\Application\ApplicationServiceAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ActiviteHydrator implements HydratorInterface {
    use ApplicationServiceAwareTrait;

    /**
     * @param Activite $object
     * @return array
     */
    public function extract($object)
    {
        $formationIds = [];
        foreach ($object->getApplications() as $application) {
            $formationIds[] = $application->getId();
        }

        $data = [
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
            'applications' => $formationIds,
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

        $object->getApplications()->clear();
        foreach ($data['applications'] as $id) {
            $application = $this->getApplicationService()->getApplication($id);
            if ($application) $object->addApplication($application);
        }
        return $object;
    }

}