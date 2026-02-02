<?php

namespace Element\Form\ApplicationElement;

use Element\Entity\Db\ApplicationElement;
use Element\Service\Application\ApplicationServiceAwareTrait;
use Element\Service\NiveauMaitrise\NiveauMaitriseServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class ApplicationElementHydrator implements HydratorInterface {
    use ApplicationServiceAwareTrait;
    use NiveauMaitriseServiceAwareTrait;

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
            'precision'    => $object->getCommentaire(),
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
        $niveau = (isset($data['niveau']) AND $data['niveau'] !== '')?$this->getNiveauMaitriseService()->getMaitriseNiveau($data['niveau']):null;
        $precision =  (isset($data['precision']) AND trim($data['precision']) !== '')?trim($data['precision']):null;

        $object->setApplication($application);
        $object->setNiveauMaitrise($niveau);
        $object->setCommentaire($precision);

        if (isset($data['clef'])) {
            $object->setClef($data['clef']);
        }

        return $object;
    }
}