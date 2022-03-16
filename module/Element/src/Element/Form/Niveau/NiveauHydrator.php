<?php

namespace Element\Form\Niveau;

use Element\Entity\Db\Niveau;
use Zend\Hydrator\HydratorInterface;

class NiveauHydrator implements HydratorInterface {

    /**
     * @param Niveau $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle' => $object->getLibelle(),
            'niveau' => $object->getNiveau(),
            'HasDescription' => ['description' => $object->getDescription()],
            'type' => $object->getType(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Niveau $object
     * @return Niveau
     */
    public function hydrate(array $data, $object)
    {
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== '')?trim($data['libelle']):null;
        $niveau = (isset($data['niveau']))?$data['niveau']:null;
        $type = (isset($data['type']))?$data['type']:null;
        $description = (isset($data['HasDescription']) AND isset($data['HasDescription']['description']) && trim($data['HasDescription']['description']) != '')?trim($data['HasDescription']['description']):null;

        $object->setLibelle($libelle);
        $object->setType($type);
        $object->setNiveau($niveau);
        $object->setDescription($description);
        return $object;
    }


}