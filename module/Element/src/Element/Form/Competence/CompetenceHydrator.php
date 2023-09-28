<?php

namespace Element\Form\Competence;

use Element\Entity\Db\Competence;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceReferentiel\CompetenceReferentielServiceAwareTrait;
use Element\Service\CompetenceTheme\CompetenceThemeServiceAwareTrait;
use Element\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class CompetenceHydrator implements HydratorInterface {
    use CompetenceServiceAwareTrait;
    use CompetenceReferentielServiceAwareTrait;
    use CompetenceThemeServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var Competence $object */
        $data = [];
        $data['libelle'] = $object->getLibelle();
        $data['description'] = $object->getDescription();
        $data['type'] = ($object->getType())?$object->getType()->getId():null;
        $data['theme'] = ($object->getTheme())?$object->getTheme()->getId():null;
        $data['referentiel'] = ($object->getReferentiel())?$object->getReferentiel()->getId():null;
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

        $type  = null; $theme = null;
        if (isset($data['type']) AND $data['type'] != '') $type = $this->getCompetenceTypeService()->getCompetenceType($data['type']);
        if (isset($data['theme']) AND $data['theme'] != '') $theme = $this->getCompetenceThemeService()->getCompetenceTheme($data['theme']);
        if (isset($data['referentiel']) AND $data['referentiel'] != '') $referentiel = $this->getCompetenceReferentielService()->getCompetenceReferentiel($data['referentiel']);

        $object->setReferentiel($referentiel);
        $object->setTheme($theme);
        $object->setType($type);

        return $object;
    }

}