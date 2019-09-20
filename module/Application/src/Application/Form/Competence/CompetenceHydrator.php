<?php

namespace Application\Form\Competence;

use Application\Entity\Db\Competence;
use Application\Service\Competence\CompetenceServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class CompetenceHydrator implements HydratorInterface {
    use CompetenceServiceAwareTrait;

    /**
     * @param Competence $object
     * @return array
     */
    public function extract($object)
    {
        $data = [];
        $data['libelle'] = $object->getLibelle();
        $data['precision'] = $object->getPrecision();
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
        $object->setPrecision($data['precision']);
        $object->setDescription($data['description']);

        $type  = $this->getCompetenceService()->getCompetenceType($data['type']);
        $theme = $this->getCompetenceService()->getCompetenceTheme($data['theme']);

        $object->setTheme($theme);
        $object->setType($type);

        return $object;
    }

}