<?php

namespace Element\Form\SelectionCompetence;

use Application\Entity\Db\Activite;
use Application\Entity\Db\FicheMetier;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use Laminas\Hydrator\HydratorInterface;

class SelectionCompetenceHydrator implements HydratorInterface {

    /**
     * @param Activite|FicheMetier $object
     * @return array|void
     */
    public function extract($object): array
    {
        $competences = array_map(function (CompetenceElement $a) { return $a->getCompetence(); }, $object->getCompetenceListe());
        $competenceIds = array_map(function (Competence $f) { return $f->getId();}, $competences);
        $data = [
            'competences' => $competenceIds,
        ];
        return $data;
    }

    public function hydrate(array $data, $object)
    {
        //never used
    }
}