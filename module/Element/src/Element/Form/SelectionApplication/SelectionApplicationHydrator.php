<?php

namespace Element\Form\SelectionApplication;

use Element\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Element\Entity\Db\Application;
use Element\Entity\Db\ApplicationElement;
use Element\Service\Application\ApplicationServiceAwareTrait;
use Element\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class SelectionApplicationHydrator implements HydratorInterface {
    use ApplicationServiceAwareTrait;
    use ApplicationElementServiceAwareTrait;

    /**
     * @param HasApplicationCollectionInterface $object
     * @return array|void
     */
    public function extract($object): array
    {
        $applications = array_map(function (ApplicationElement $a) { return $a->getApplication(); }, $object->getApplicationListe());
        $applicationIds = array_map(function (Application $f) { return $f->getId();}, $applications);
        $data = [
            'applications' => $applicationIds,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param HasApplicationCollectionInterface $object
     * @return HasApplicationCollectionInterface
     */
    public function hydrate(array $data, $object) : object
    {
        $applicationIds = $data["applications"];

        $applications = [];
        foreach ($applicationIds as $applicationId) {
            $application = $this->getApplicationService()->getApplication($applicationId);
            if ($application) $applications[$application->getId()] = $application;
        }

        foreach ($object->getApplicationCollection() as $applicationElement) {
            if (! isset($applications[$applicationElement->getApplication()->getId()])) {
                $this->getApplicationElementService()->delete($applicationElement);
            }
        }

        foreach ($applications as $application) {
            if (!$object->hasApplication($application)) {
                $element = new ApplicationElement();
                $element->setApplication($application);
                $this->getApplicationElementService()->create($element);
                $object->addApplicationElement($element);
            }
        }

        return $object;
    }
}