<?php

namespace EntretienProfessionnel\Form\CampagneConfigurationIndicateur;

use EntretienProfessionnel\Entity\Db\CampagneConfigurationIndicateur;
use Laminas\Hydrator\HydratorInterface;

class CampagneConfigurationIndicateurHydrator implements HydratorInterface
{
    public function extract(object $object): array
    {
        /** @var CampagneConfigurationIndicateur $object */
        $data = [
            'code' => $object->getCode(),
            'libelle' => $object->getLibelle(),
            'requete' => $object->getRequete(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $code = (isset($data['code']) AND trim($data['code']) !== "") ? trim($data['code']) : null;
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== "") ? trim($data['libelle']) : null;
        $requete = (isset($data['requete']) AND trim($data['requete']) !== "") ? trim($data['requete']) : null;

        /** @var CampagneConfigurationIndicateur $object */
        $object->setCode($code);
        $object->setLibelle($libelle);
        $object->setRequete(strip_tags($requete));
        return $object;
    }
}
