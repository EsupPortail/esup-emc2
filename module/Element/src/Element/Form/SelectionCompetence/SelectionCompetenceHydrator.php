<?php

namespace Element\Form\SelectionCompetence;

use Doctrine\Common\Collections\Collection;
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

    private ?Collection $collection = null;

    public function getCollection(): ?Collection
    {
        return $this->collection;
    }

    public function setCollection(?Collection $collection): void
    {
        $this->collection = $collection;
    }


    /**
     * @param HasCompetenceCollectionInterface $object
     * @return array
     */
    public function extract($object): array
    {
        $collection = ($this->getCollection() !== null)?$this->getCollection():$object->getCompetenceCollection();
        $array = $collection->toArray();
        $competences = array_map(function (CompetenceElement $a) { return $a->getCompetence(); }, $collection->toArray());
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
        $collection = ($this->getCollection() !== null)?$this->getCollection():$object->getCompetenceCollection();
        $competenceIds = $data["competences"];

        $competences = [];
        foreach ($competenceIds as $competenceId) {
            $competence = $this->getCompetenceService()->getCompetence($competenceId);
            if ($competence) $competences[$competence->getId()] = $competence;
        }

        foreach ($collection as $competenceElement) {
            if (! isset($competences[$competenceElement->getCompetence()->getId()])) {
                $collection->removeElement($competenceElement);
            }
        }

        foreach ($competences as $competence) {
            if (!$object->hasCompetence($competence)) {
                $element = new CompetenceElement();
                $element->setCompetence($competence);
                $this->getCompetenceElementService()->create($element);
                $collection->add($element);
            }
        }

        return $object;
    }
}