<?php

namespace Application\Form\FicheMetier;

use Application\Entity\Db\FicheMetier;
use Application\Service\Application\ApplicationServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class ApplicationsHydrator implements HydratorInterface {
    use ApplicationServiceAwareTrait;
    /**
     * @param FicheMetier $object
     * @return array
     */
    public function extract($object)
    {
        $applicationsId = [];
        foreach ($object->getApplications() as $application) {
            $applicationsId[] = $application->getId();
        }

        $data = [
            'applications' => $applicationsId,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FicheMetier $object
     * @return FicheMetier
     */
    public function hydrate(array $data, $object)
    {
        foreach ($object->getApplications() as $application) $object->removeApplication($application);

        foreach ($data['applications'] as $applicationId) {
            $application = $this->getApplicationService()->getApplication($applicationId);
            $object->addApplication($application);
        }

        return $object;
    }

}