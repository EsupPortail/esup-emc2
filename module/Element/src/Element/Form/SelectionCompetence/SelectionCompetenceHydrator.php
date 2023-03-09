<?php

namespace Element\Form\SelectionCompetence;

use Element\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Laminas\Form\Element;
use Laminas\Hydrator\HydratorInterface;

class SelectionCompetenceHydrator implements HydratorInterface {
    use CompetenceServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;

    /**
     * @param HasCompetenceCollectionInterface $object
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

    /**
     * @param array $data
     * @param HasCompetenceCollectionInterface $object
     * @return HasCompetenceCollectionInterface
     */
    public function hydrate(array $data, $object) : object
    {
        $competenceIds = $data["competences"];

        $competences = [];
        foreach ($competenceIds as $competenceId) {
            $competence = $this->getCompetenceService()->getCompetence($competenceId);
            if ($competence) $competences[$competence->getId()] = $competence;
        }

        foreach ($object->getCompetenceCollection() as $competenceElement) {
            if (! isset($competences[$competenceElement->getCompetence()->getId()])) {
                $this->getCompetenceElementService()->delete($competenceElement);
            }
        }

        foreach ($competences as $competence) {
            if (!$object->hasCompetence($competence)) {
                $element = new CompetenceElement();
                $element->setCompetence($competence);
                $this->getCompetenceElementService()->create($element);
                $object->addCompetenceElement($element);
            }
        }

        return $object;
    }
}