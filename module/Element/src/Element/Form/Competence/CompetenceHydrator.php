<?php

namespace Element\Form\Competence;

use Element\Entity\Db\Competence;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceDiscipline\CompetenceDisciplineServiceAwareTrait;
use Element\Service\CompetenceReferentiel\CompetenceReferentielServiceAwareTrait;
use Element\Service\CompetenceTheme\CompetenceThemeServiceAwareTrait;
use Element\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class CompetenceHydrator implements HydratorInterface {
    use CompetenceServiceAwareTrait;
    use CompetenceDisciplineServiceAwareTrait;
    use CompetenceReferentielServiceAwareTrait;
    use CompetenceThemeServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var Competence $object */
        $data = [];
        $data['libelle'] = $object->getLibelle();
        $data['description'] = $object->getDescription();
        $data['discipline'] = ($object->getDiscipline())?$object->getDiscipline()->getId():null;
        $data['type'] = ($object->getType())?$object->getType()->getId():null;
        $data['theme'] = ($object->getTheme())?$object->getTheme()->getId():null;
        $data['referentiel'] = ($object->getReferentiel())?$object->getReferentiel()->getId():null;
        $data['identifiant'] = ($object->getIdSource())??null;
        return $data;
    }

    /**
     * @param array $data
     * @param Competence $object
     * @return Competence
     */
    public function hydrate(array $data, $object) : object
    {
        $object->setLibelle($data['libelle']);
        $object->setDescription($data['description']);
        $object->setIdSource($data['identifiant']??null);

        $referentiel = null; $theme = null; $type  = null; $discipline = null;
        if (isset($data['referentiel']) AND $data['referentiel'] != '') $referentiel = $this->getCompetenceReferentielService()->getCompetenceReferentiel($data['referentiel']);
        if (isset($data['theme']) AND $data['theme'] != '') $theme = $this->getCompetenceThemeService()->getCompetenceTheme($data['theme']);
        if (isset($data['type']) AND $data['type'] != '') $type = $this->getCompetenceTypeService()->getCompetenceType($data['type']);
        if (isset($data['discipline']) AND $data['discipline'] != '') $discipline = $this->getCompetenceDisciplineService()->getCompetenceDiscipline($data['discipline']);

        $object->setReferentiel($referentiel);
        $object->setTheme($theme);
        $object->setType($type);
        $object->setDiscipline($discipline);

        return $object;
    }

}