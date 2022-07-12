<?php

namespace Application\Form\HasPeriode;

use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use DateTime;
use Laminas\Hydrator\HydratorInterface;

class HasPeriodeHydrator implements HydratorInterface {

    /**
     * @param HasPeriodeInterface $object
     * @return array
     */
    public function extract($object) : array
    {
        $data = [
            'HasPeriode' => [
                "date_debut" => ($object->getDateDebut())?$object->getDateDebut()->format(HasPeriodeFieldset::format):null,
                "date_fin" => ($object->getDateFin())?$object->getDateFin()->format(HasPeriodeFieldset::format):null,
            ],
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param HasPeriodeInterface $object
     * @return HasPeriodeInterface
     */
    public function hydrate(array $data, $object) : object
    {
        $dateDebut = (isset($data['HasPeriode']) AND isset($data['HasPeriode']['date_debut']))?DateTime::createFromFormat(HasPeriodeFieldset::format, $data['HasPeriode']['date_debut']):null;
        $dateFin = (isset($data['HasPeriode']) AND isset($data['HasPeriode']['date_fin']))?DateTime::createFromFormat(HasPeriodeFieldset::format, $data['HasPeriode']['date_fin']):null;
        $object->setDateDebut($dateDebut);
        $object->setDateFin($dateFin);
        return $object;
    }

}