<?php

namespace Element\Form\CompetenceElement;

use Element\Entity\Db\CompetenceElement;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\Niveau\NiveauServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class CompetenceElementHydrator implements HydratorInterface {
    use CompetenceServiceAwareTrait;
    use NiveauServiceAwareTrait;

    /**
     * @param CompetenceElement $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'competence'   => ($object AND $object->getCompetence())?$object->getCompetence()->getId():null,
            'niveau'       => ($object AND $object->getNiveauMaitrise())?$object->getNiveauMaitrise()->getId():null,
            'clef'         => $object->isClef(),
            'precision'    => $object->getCommentaire(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param CompetenceElement $object
     * @return CompetenceElement
     */
    public function hydrate(array $data, $object) : object
    {
        $competence = isset($data['competence'])?$this->getCompetenceService()->getCompetence($data['competence']):null;
        $niveau = (isset($data['niveau']) AND $data['niveau'] !== '')?$this->getNiveauService()->getMaitriseNiveau($data['niveau']):null;
        $precision =  (isset($data['precision']) AND trim($data['precision']) !== '')?trim($data['precision']):null;

        $object->setCompetence($competence);
        $object->setNiveauMaitrise($niveau);
        $object->setCommentaire($precision);

        if (isset($data['clef'])) {
            $object->setClef($data['clef']);
        }

        return $object;
    }
}