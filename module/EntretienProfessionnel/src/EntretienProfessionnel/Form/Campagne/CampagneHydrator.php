<?php

namespace EntretienProfessionnel\Form\Campagne;

use DateTime;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class  CampagneHydrator implements HydratorInterface {
    use CampagneServiceAwareTrait;

    /**
     * @param Campagne $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'annee' => $object->getAnnee(),
            'date_debut' => $object->getDateDebut()?$object->getDateDebut()->format('Y-m-d'):null,
            'date_fin' => $object->getDateFin()?$object->getDateFin()->format('Y-m-d'):null,
            'precede' => $object->getPrecede()?$object->getPrecede()->getId():null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Campagne $object
     * @return Campagne
     */
    public function hydrate(array $data, $object)
    {
        $annee = (isset($data['annee']))?$data['annee']:null;
        $date_debut = (isset($data['date_debut']))? DateTime::createFromFormat('Y-m-d H:i:s',$data['date_debut'].' 08:00:00'):null;
        $date_fin   = (isset($data['date_fin']))?   DateTime::createFromFormat('Y-m-d H:i:s',$data['date_fin']. ' 20:00:00'):null;
        $precede = (isset($data['precede']) AND $data['precede'] !== '')?$this->getCampagneService()->getCampagne($data['precede']):null;

        $object->setAnnee($annee);
        $object->setDateDebut($date_debut);
        $object->setDateFin($date_fin);
        $object->setPrecede($precede);

        return $object;
    }
}