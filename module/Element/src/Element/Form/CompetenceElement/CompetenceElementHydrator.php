<?php

namespace Element\Form\CompetenceElement;

use Element\Entity\Db\CompetenceElement;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\NiveauMaitrise\NiveauMaitriseServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class CompetenceElementHydrator implements HydratorInterface
{
    use CompetenceServiceAwareTrait;
    use NiveauMaitriseServiceAwareTrait;

    /**
     * @param CompetenceElement $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'competence' => ($object and $object->getCompetence()) ? $object->getCompetence()->getId() : null,
            'niveau' => ($object and $object->getNiveauMaitrise()) ? $object->getNiveauMaitrise()->getId() : null,
            'clef' => $object->isClef(),
            'precision' => $object->getCommentaire(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param CompetenceElement $object
     * @return CompetenceElement
     */
    public function hydrate(array $data, $object): object
    {
        $competence = isset($data['competence']) ? $this->getCompetenceService()->getCompetence($data['competence']) : null;
        $niveau = (isset($data['niveau']) and $data['niveau'] !== '') ? $this->getNiveauMaitriseService()->getMaitriseNiveau($data['niveau']) : null;
        $precision = (isset($data['precision']) and trim($data['precision']) !== '') ? trim($data['precision']) : null;

        $object->setCompetence($competence);
        $object->setNiveauMaitrise($niveau);
        $object->setCommentaire($precision);

        if (isset($data['clef'])) {
            $object->setClef($data['clef']);
        }

        return $object;
    }
}