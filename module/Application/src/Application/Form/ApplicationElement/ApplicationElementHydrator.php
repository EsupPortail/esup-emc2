<?php

namespace Application\Form\ApplicationElement;

use Application\Entity\Db\ApplicationElement;
use Application\Service\Application\ApplicationServiceAwareTrait;
use Application\Service\CompetenceMaitrise\CompetenceMaitriseServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class ApplicationElementHydrator implements HydratorInterface {
    use ApplicationServiceAwareTrait;
    use CompetenceMaitriseServiceAwareTrait;

    /**
     * @param ApplicationElement $object
     * @return array
     */
    public function extract($object)
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
    public function hydrate(array $data, $object)
    {
        $application = isset($data['application'])?$this->getApplicationService()->getApplication($data['application']):null;
        $niveau = (isset($data['niveau']) AND $data['niveau'] !== '')?$this->getCompetenceMaitriseService()->getCompetenceMaitrise($data['niveau']):null;

        $object->setApplication($application);
        $object->setNiveauMaitrise($niveau);

        if (isset($data['clef'])) {
            $object->setClef($data['clef']);
        }

        return $object;
    }
}