<?php

namespace Application\Form\SelectionCompetence;

use Application\Entity\Db\Activite;
use Application\Entity\Db\Competence;
use Zend\Hydrator\HydratorInterface;

class SelectionCompetenceHydrator implements HydratorInterface {

    /**
     * @param Activite $object
     * @return array|void
     */
    public function extract($object)
    {
        $competences = $object->getCompetences();
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