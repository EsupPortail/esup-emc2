<?php

namespace Application\Form\FicheMetier;

use Application\Entity\Db\FicheMetier;
use Application\Service\Competence\CompetenceServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class GererCompetenceHydrator implements HydratorInterface {
    use CompetenceServiceAwareTrait;

    /**
     * @param FicheMetier $object
     * @return array
     */
    public function extract($object)
    {
        $competences = $object->getCompetences();
        $competenceIds = [];
        foreach ($competences as $competence) {
            $competenceIds[] = $competence->getId();
        }

        $data = [
            'competences' => $competenceIds,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FicheMetier $object
     * @return FicheMetier
     */
    public function hydrate(array $data, $object)
    {
        $object->clearCompetences();
        foreach ($data['competences'] as $competenceId) {
            $competence = $this->getCompetenceService()->getCompetence($competenceId);
            if ($competence) $object->addCompetence($competence);
        }
        return $object;
    }
}