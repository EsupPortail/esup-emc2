<?php

namespace Formation\Form\FormationJournee;

use DateTime;
use Formation\Entity\Db\FormationInstanceJournee;
use Zend\Hydrator\HydratorInterface;

class FormationJourneeHydrator implements HydratorInterface
{

    /**
     * @param FormationInstanceJournee $object
     * @return array
     */
    public function extract($object)
    {
        $jour = $object->getJour()->format('Y-m-d');
        $data = [
            'jour' => $jour,
            'debut' => $object->getDebut(),
            'fin' => $object->getFin(),
            'lieu' => $object->getLieu(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FormationInstanceJournee $object
     * @return FormationInstanceJournee
     */
    public function hydrate(array $data, $object)
    {
        $jour = (isset($data['jour'])) ? DateTime::createFromFormat('Y-m-d',$data['jour']) : null;
        $debut = (isset($data['debut'])) ? $data['debut'] : null;
        $fin = (isset($data['fin'])) ? $data['fin'] : null;
        $lieu = (isset($data['lieu'])) ? $data['lieu'] : null;

        $object->setJour($jour);
        $object->setDebut($debut);
        $object->setFin($fin);
        $object->setLieu($lieu);

        return $object;
    }

}