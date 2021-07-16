<?php

namespace Application\Form\Niveau;

use Application\Entity\Db\Niveau;
use Zend\Hydrator\HydratorInterface;

class NiveauHydrator implements HydratorInterface {

    /**
     * @param Niveau $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'niveau' => $object->getNiveau(),
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
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
        $niveau = isset($data['niveau'])?$data['niveau']:null;
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== '')?trim($data['niveau']):null;
        $description = (isset($data['description']) AND trim($data['description']) !== '')?trim($data['description']):null;

        $object->setNiveau($niveau);
        $object->setLibelle($libelle);
        $object->setDescription($description);

        return $object;
    }


}