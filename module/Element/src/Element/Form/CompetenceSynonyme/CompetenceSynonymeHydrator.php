<?php

namespace Element\Form\CompetenceSynonyme;

use Element\Entity\Db\CompetenceSynonyme;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class CompetenceSynonymeHydrator implements HydratorInterface
{
    use CompetenceServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var CompetenceSynonyme $object */
        $data = [
            'competence' => $object->getCompetence()?->getId(),
            'libelle' => $object->getLibelle(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $competence = (isset($data['competence'])) ? $this->getCompetenceService()->getCompetence($data['competence']) : null;
        $libelle = (isset($data['libelle']) AND trim($data['libelle'])) ? trim($data['libelle']) : null;

        /** @var CompetenceSynonyme $object */
        $object->setCompetence($competence);
        $object->setLibelle($libelle);
        return $object;
    }

}
