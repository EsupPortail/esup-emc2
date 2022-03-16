<?php

namespace Application\Form\CompetenceElement;

use Element\Entity\Db\ApplicationElement;
use Element\Entity\Db\CompetenceElement;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\Niveau\NiveauServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class CompetenceElementHydrator implements HydratorInterface {
    use CompetenceServiceAwareTrait;
    use NiveauServiceAwareTrait;

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
        $niveau = (isset($data['niveau']) AND $data['niveau'] !== '')?$this->getNiveauService()->getMaitriseNiveau($data['niveau']):null;

        $object->setCompetence($competence);
        $object->setNiveauMaitrise($niveau);

        if (isset($data['clef'])) {
            $object->setClef($data['clef']);
        }

        return $object;
    }
}