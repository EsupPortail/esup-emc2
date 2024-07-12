<?php

namespace Formation\Form\Seance;

use DateTime;
use Formation\Entity\Db\Seance;
use Formation\Service\Lieu\LieuServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class SeanceHydrator implements HydratorInterface
{
    use LieuServiceAwareTrait;

    /**
     * @param Seance $object
     * @return array
     */
    public function extract($object): array
    {
        $jour = ($object->getJour()) ? $object->getJour()->format('d/m/Y') : null;
        $data = [
            'type' => $object->getType(),
            'jour' => $jour,
            'debut' => $object->getDebut(),
            'fin' => $object->getFin(),
            'lieu-sas' => [
                'id' => $object->getLieu()?->getId(),
                'label' => $object->getLieu()?$object->getLieu()->getBatiment()." ".$object->getLieu()->getLibelle():null,
                'extra' => $object->getLieu()?$object->getLieu()->getCampus()." ".$object->getLieu()->getVille():null,
            ],
            'volume' => $object->getVolume(),
            'volume_debut' => $object->getVolumeDebut(),
            'volume_fin' => $object->getVolumeFin(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Seance $object
     * @return Seance
     */
    public function hydrate(array $data, $object): object
    {
        $type = (isset($data['type'])) ? $data['type'] : null;
        $jour = (isset($data['jour'])) ? DateTime::createFromFormat('d/m/Y', $data['jour']) : null;
        $jour = ($jour === false) ? null : $jour;
        $debut = (isset($data['debut'])) ? $data['debut'] : null;
        $fin = (isset($data['fin'])) ? $data['fin'] : null;
        $lieu = (isset($data['lieu-sas']['id']))?$this->getLieuService()->getLieu($data['lieu-sas']['id']):null;
        $volume = (isset($data['volume'])) ? ((float)$data['volume']) : null;
        $volumeDebut = (isset($data['volume_debut']) and trim($data['volume_debut']) !== '') ? DateTime::createFromFormat('d/m/Y', $data['volume_debut']) : null;
        $volumeFin = (isset($data['volume_fin']) and trim($data['volume_debut']) !== '') ? DateTime::createFromFormat('d/m/Y', $data['volume_fin']) : null;

        $object->setType($type);
        $object->setJour($jour);
        $object->setDebut($debut);
        $object->setFin($fin);
        $object->setLieu($lieu);
        $object->setVolume($volume);
        $object->setVolumeDebut($volumeDebut);
        $object->setVolumeFin($volumeFin);

        return $object;
    }

}