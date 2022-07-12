<?php

namespace Formation\Form\FormationJournee;

use DateTime;
use Formation\Entity\Db\FormationInstanceJournee;
use Laminas\Hydrator\HydratorInterface;

class FormationJourneeHydrator implements HydratorInterface
{

    /**
     * @param FormationInstanceJournee $object
     * @return array
     */
    public function extract($object): array
    {
        $jour = ($object->getJour())?$object->getJour()->format('d/m/Y'):null;
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
        $jour = (isset($data['jour'])) ? DateTime::createFromFormat('d/m/Y',$data['jour']) : null;
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