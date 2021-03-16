<?php

namespace Application\Form\CompetenceMaitrise;

use Application\Entity\Db\CompetenceMaitrise;
use Zend\Hydrator\HydratorInterface;

class CompetenceMaitriseHydrator implements HydratorInterface {

    /**
     * @param CompetenceMaitrise $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle' => $object->getLibelle(),
            'niveau' => $object->getNiveau(),
            'description' => $object->getDescription(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param CompetenceMaitrise $object
     * @return CompetenceMaitrise
     */
    public function hydrate(array $data, $object)
    {
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== '')?trim($data['libelle']):null;
        $niveau = (isset($data['niveau']))?$data['niveau']:null;
        $description = (isset($data['description']) AND trim($data['description']) !== '')?trim($data['description']):null;

        $object->setLibelle($libelle);
        $object->setNiveau($niveau);
        $object->setDescription($description);
        return $object;
    }


}