<?php

namespace Application\Form\Competence;

use Application\Entity\Db\Competence;
use Application\Service\Competence\CompetenceServiceAwareTrait;
use Application\Service\CompetenceTheme\CompetenceThemeServiceAwareTrait;
use Application\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class CompetenceHydrator implements HydratorInterface {
    use CompetenceServiceAwareTrait;
    use CompetenceThemeServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;

    /**
     * @param Competence $object
     * @return array
     */
    public function extract($object)
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