<?php

namespace Application\Form\FicheMetierType;

use Application\Entity\Db\FicheMetierType;
use Application\Service\Application\ApplicationServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ApplicationsHydrator implements HydratorInterface {
    use ApplicationServiceAwareTrait;
    /**
     * @param FicheMetierType $object
     * @return array
     */
    public function extract($object)
    {
        $applicationsId = [];
        foreach ($object->getApplications() as $application) {
            $applicationsId[] = $application->getId();
        }

        $data = [
            'formation' => $object->getApplicationsFormation(),
            'applications' => $applicationsId,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FicheMetierType $object
     * @return FicheMetierType
     */
    public function hydrate(array $data, $object)
    {

        foreach ($object->getApplications() as $application) $object->removeApplication($application);

        $object->setApplicationsFormation($data['formation']);

        foreach ($data['applications'] as $applicationId) {
            $application = $this->getApplicationService()->getApplication($applicationId);
            $object->addApplication($application);
        }

        return $object;
    }

}