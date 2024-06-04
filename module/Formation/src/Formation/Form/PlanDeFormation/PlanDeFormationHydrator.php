<?php

namespace Formation\Form\PlanDeFormation;

use DateTime;
use Formation\Entity\Db\PlanDeFormation;
use Laminas\Hydrator\HydratorInterface;

class PlanDeFormationHydrator implements HydratorInterface {

    public function extract(object $object): array
    {
        /** @var PlanDeFormation $object */
        $data = [
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
            'date_debut' => $object->getDateDebut()?->format("Y-m-d"),
            'date_fin' => $object->getDateFin()?->format("Y-m-d"),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object) : object
    {
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== "")?trim($data['libelle']):null;
        $description = (isset($data['description']) AND trim($data['description']) !== "")?trim($data['description']):null;
        $dateDebut = (isset($data['date_debut']) AND $data['date_debut'] !== '')?DateTime::createFromFormat('Y-m-d H:i', $data['date_debut']." 08:00"):null;
        $dateFin = (isset($data['date_fin']) AND $data['date_fin'] !== '')?DateTime::createFromFormat('Y-m-d H:i', $data['date_fin']." 20:00"):null;

        /** @var PlanDeFormation $object */
        $object->setLibelle($libelle);
        $object->setDescription($description);
        $object->setDateDebut($dateDebut);
        $object->setDateFin($dateFin);
        return $object;
    }


}