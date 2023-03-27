<?php

namespace Element\Form\ApplicationElement;

use Element\Entity\Db\ApplicationElement;
use Element\Service\Application\ApplicationServiceAwareTrait;
use Element\Service\Niveau\NiveauServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class ApplicationElementHydrator implements HydratorInterface {
    use ApplicationServiceAwareTrait;
    use NiveauServiceAwareTrait;

    /**
     * @param ApplicationElement $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'application'   => ($object->getApplication())?$object->getApplication()->getId():null,
            'niveau'       => ($object->getNiveauMaitrise())?$object->getNiveauMaitrise()->getId():null,
            'clef'       => ($object->isClef()),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param ApplicationElement $object
     * @return ApplicationElement
     */
    public function hydrate(array $data, $object) : object
    {
        $application = isset($data['application'])?$this->getApplicationService()->getApplication($data['application']):null;
        $niveau = (isset($data['niveau']) AND $data['niveau'] !== '')?$this->getNiveauService()->getMaitriseNiveau($data['niveau']):null;

        $object->setApplication($application);
        $object->setNiveauMaitrise($niveau);

        if (isset($data['clef'])) {
            $object->setClef($data['clef']);
        }

        return $object;
    }
}