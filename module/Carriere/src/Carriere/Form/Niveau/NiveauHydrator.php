<?php

namespace Carriere\Form\Niveau;

use Carriere\Entity\Db\Niveau;
use Carriere\Service\Categorie\CategorieServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class NiveauHydrator implements HydratorInterface {

    use CategorieServiceAwareTrait;

    /**
     * @param Niveau $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'niveau' => $object->getNiveau(),
            'etiquette' => $object->getEtiquette(),
            'libelle' => $object->getLibelle(),
            'categorie' => $object->getCategorie()?->getId(),
            'HasDescription' => [
                'description' => $object->getDescription()
            ],
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Niveau $object
     * @return Niveau
     */
    public function hydrate(array $data, $object) : object
    {
        $niveau = $data['niveau'] ?? null;
        $etiquette = (isset($data['etiquette']) AND trim($data['etiquette']) !== '')?trim($data['etiquette']):null;
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== '')?trim($data['libelle']):null;
        $categorie = (isset($data['categorie']))?$this->getCategorieService()->getCategorie($data['categorie']):null;
        $description = (isset($data['HasDescription']) AND isset($data['HasDescription']['description']) && trim($data['HasDescription']['description']) != '')?trim($data['HasDescription']['description']):null;

        $object->setNiveau((int) $niveau);
        $object->setEtiquette($etiquette);
        $object->setLibelle($libelle);
        $object->setCategorie($categorie);
        $object->setDescription($description);

        return $object;
    }


}