<?php

namespace EntretienProfessionnel\Form\Campagne;

use DateTime;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class  CampagneHydrator implements HydratorInterface {
    use CampagneServiceAwareTrait;

    /**
     * @param Campagne $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'annee' => $object->getAnnee(),
            'date_debut' => $object->getDateDebut()?->format('Y-m-d'),
            'date_fin' => $object->getDateFin()?->format('Y-m-d'),
            'date_circulaire' => $object->getDateCirculaire()?$object->getDateCirculaire()->format('Y-m-d'):null,
            'date_en_poste' => $object->getDateEnPoste()?$object->getDateEnPoste()->format('Y-m-d'):null,
            'precede' => $object->getPrecede()?$object->getPrecede()->getId():null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Campagne $object
     * @return Campagne
     */
    public function hydrate(array $data, $object) : object
    {
        $annee = (isset($data['annee']))?$data['annee']:null;
        $date_debut = (isset($data['date_debut']))? DateTime::createFromFormat('Y-m-d H:i:s',$data['date_debut'].' 08:00:00'):null;
        if ($date_debut === false) $date_debut = null;
        $date_fin   = (isset($data['date_fin']))?   DateTime::createFromFormat('Y-m-d H:i:s',$data['date_fin']. ' 20:00:00'):null;
        if ($date_fin === false) $date_debut = null;
        $date_circulaire   = (isset($data['date_circulaire']))?   DateTime::createFromFormat('Y-m-d H:i:s',$data['date_circulaire']. ' 08:00:00'):null;
        if ($date_circulaire === false) $date_circulaire = null;
        $date_en_poste   = (isset($data['date_en_poste']))?   DateTime::createFromFormat('Y-m-d H:i:s',$data['date_en_poste']. ' 08:00:00'):null;
        if ($date_en_poste === false) $date_en_poste = null;
        $precede = (isset($data['precede']) AND $data['precede'] !== '')?$this->getCampagneService()->getCampagne($data['precede']):null;

        $object->setAnnee($annee);
        $object->setDateDebut($date_debut);
        $object->setDateFin($date_fin);
        $object->setDateCirculaire($date_circulaire);
        $object->setDateEnPoste($date_en_poste);
        $object->setPrecede($precede);

        return $object;
    }
}