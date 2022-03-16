<?php

namespace Application\Form\SelectionApplication;

use Application\Entity\Db\Activite;
use Element\Entity\Db\Application;
use Element\Entity\Db\ApplicationElement;
use Application\Entity\Db\FicheMetier;
use Zend\Hydrator\HydratorInterface;

class SelectionApplicationHydrator implements HydratorInterface {

    /**
     * @param Activite|FicheMetier $object
     * @return array|void
     */
    public function extract($object)
    {
        $applications = array_map(function (ApplicationElement $a) { return $a->getApplication(); }, $object->getApplicationListe());
        $applicationIds = array_map(function (Application $f) { return $f->getId();}, $applications);
        $data = [
            'applications' => $applicationIds,
        ];
        return $data;
    }

    public function hydrate(array $data, $object)
    {
        //never used
    }
}