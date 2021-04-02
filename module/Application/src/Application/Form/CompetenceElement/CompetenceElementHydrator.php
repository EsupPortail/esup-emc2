<?php

namespace Application\Form\CompetenceElement;

use Application\Entity\Db\ApplicationElement;
use Application\Entity\Db\CompetenceElement;
use Application\Service\Competence\CompetenceServiceAwareTrait;
use Application\Service\MaitriseNiveau\MaitriseNiveauServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class CompetenceElementHydrator implements HydratorInterface {
    use CompetenceServiceAwareTrait;
    use MaitriseNiveauServiceAwareTrait;

    /**
     * @param CompetenceElement $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'competence'   => ($object->getCompetence())?$object->getCompetence()->getId():null,
            'niveau'       => ($object->getNiveauMaitrise())?$object->getNiveauMaitrise()->getId():null,
            'clef'         => $object->isClef(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param CompetenceElement $object
     * @return CompetenceElement
     */
    public function hydrate(array $data, $object)
    {
        $competence = isset($data['competence'])?$this->getCompetenceService()->getCompetence($data['competence']):null;
        $niveau = (isset($data['niveau']) AND $data['niveau'] !== '')?$this->getMaitriseNiveauService()->getMaitriseNiveau($data['niveau']):null;

        $object->setCompetence($competence);
        $object->setNiveauMaitrise($niveau);

        if (isset($data['clef'])) {
            $object->setClef($data['clef']);
        }

        return $object;
    }
}