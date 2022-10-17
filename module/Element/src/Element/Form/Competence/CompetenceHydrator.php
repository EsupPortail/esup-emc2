<?php

namespace Element\Form\Competence;

use Element\Entity\Db\Competence;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceTheme\CompetenceThemeServiceAwareTrait;
use Element\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class CompetenceHydrator implements HydratorInterface {
    use CompetenceServiceAwareTrait;
    use CompetenceThemeServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;

    /**
     * @param Competence $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [];
        $data['libelle'] = $object->getLibelle();
        $data['description'] = $object->getDescription();
        $data['type'] = ($object->getType())?$object->getType()->getId():null;
        $data['theme'] = ($object->getTheme())?$object->getTheme()->getId():null;
        return $data;
    }

    /**
     * @param array $data
     * @param Competence $object
     * @return Competence
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        $object->setDescription($data['description']);

        $type  = null; $theme = null;
        if (isset($data['type']) AND $data['type'] != '') $type = $this->getCompetenceTypeService()->getCompetenceType($data['type']);
        if (isset($data['theme']) AND $data['theme'] != '') $theme = $this->getCompetenceThemeService()->getCompetenceTheme($data['theme']);

        $object->setTheme($theme);
        $object->setType($type);

        return $object;
    }

}