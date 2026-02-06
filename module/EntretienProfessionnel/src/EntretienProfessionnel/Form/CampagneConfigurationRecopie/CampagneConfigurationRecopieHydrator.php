<?php

namespace EntretienProfessionnel\Form\CampagneConfigurationRecopie;

use EntretienProfessionnel\Entity\Db\CampagneConfigurationRecopie;
use Laminas\Hydrator\HydratorInterface;
use UnicaenAutoform\Service\Champ\ChampServiceAwareTrait;

class CampagneConfigurationRecopieHydrator implements HydratorInterface {

    use ChampServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var CampagneConfigurationRecopie $object */
        $data = [
            'type' => $object->getFormulaire(),
            'champ-from' => $object->getFrom()?->getId(),
            'champ-to' => $object->getTo()?->getId(),
            'description' => $object->getDescription(),
        ];
        return $data;
    }

    public function hydrate(array $data,object $object): object
    {
        $formulaire = (isset($data['type']) and $data['type'] !== '') ? $data['type'] : null;
        $champFrom = (isset($data['champ-from']) and $data['champ-from'] !== '') ? $this->getChampService()->getChamp($data['champ-from']) : null;
        $champTo = (isset($data['champ-to']) and $data['champ-to'] !== '') ? $this->getChampService()->getChamp($data['champ-to']) : null;
        $description = (isset($data['description']) and $data['description'] !== '') ? $data['description'] : null;

        /** @var CampagneConfigurationRecopie $object */
        $object->setFormulaire($formulaire);
        $object->setFrom($champFrom);
        $object->setTo($champTo);
        $object->setDescription($description);
        return $object;
    }
}
