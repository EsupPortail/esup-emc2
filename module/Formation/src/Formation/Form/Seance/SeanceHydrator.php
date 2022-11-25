<?php

namespace Formation\Form\Seance;

use DateTime;
use Formation\Entity\Db\Seance;
use Laminas\Hydrator\HydratorInterface;

class SeanceHydrator implements HydratorInterface
{

    /**
     * @param Seance $object
     * @return array
     */
    public function extract($object): array
    {
        $jour = ($object->getJour())?$object->getJour()->format('d/m/Y'):null;
        $data = [
            'type' => $object->getType(),
            'jour' => $jour,
            'debut' => $object->getDebut(),
            'fin' => $object->getFin(),
            'lieu' => $object->getLieu(),
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
    public function hydrate(array $data, $object)
    {
        $type = (isset($data['type'])) ? $data['type'] : null;
        $jour = (isset($data['jour'])) ? DateTime::createFromFormat('d/m/Y',$data['jour']) : null;
        $jour = ($jour === false)?null:$jour;
        $debut = (isset($data['debut'])) ? $data['debut'] : null;
        $fin = (isset($data['fin'])) ? $data['fin'] : null;
        $lieu = (isset($data['lieu'])) ? $data['lieu'] : null;
        $volume = (isset($data['volume'])) ? ((float) $data['volume']) : null;
        $volumeDebut = (isset($data['volume_debut']) AND trim($data['volume_debut']) !== '') ? DateTime::createFromFormat('d/m/Y', $data['volume_debut']) : null;
        $volumeFin = (isset($data['volume_fin'])  AND trim($data['volume_debut']) !== '') ? DateTime::createFromFormat('d/m/Y',$data['volume_fin']) : null;

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