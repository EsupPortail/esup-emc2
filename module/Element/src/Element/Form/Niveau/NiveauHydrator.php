<?php

namespace Element\Form\Niveau;

use Element\Entity\Db\NiveauMaitrise;
use Laminas\Hydrator\HydratorInterface;

class NiveauHydrator implements HydratorInterface {

    public function extract($object): array
    {
        /** @var NiveauMaitrise $object */
        $data = [
            'libelle' => $object->getLibelle(),
            'niveau' => $object->getNiveau(),
            'HasDescription' => ['description' => $object->getDescription()],
            'type' => $object->getType(),
        ];
        return $data;
    }

    public function hydrate(array $data, $object) : object
    {
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== '')?trim($data['libelle']):null;
        $niveau = (isset($data['niveau']))?$data['niveau']:null;
        $type = (isset($data['type']))?$data['type']:null;
        $description = (isset($data['HasDescription']) AND isset($data['HasDescription']['description']) && trim($data['HasDescription']['description']) != '')?trim($data['HasDescription']['description']):null;

        /** @var NiveauMaitrise $object */
        $object->setLibelle($libelle);
        $object->setType($type);
        $object->setNiveau($niveau);
        $object->setDescription($description);
        return $object;
    }


}