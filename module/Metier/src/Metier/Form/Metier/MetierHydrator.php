<?php

namespace Metier\Form\Metier;

use Carriere\Service\Categorie\CategorieServiceAwareTrait;
use Metier\Entity\Db\Metier;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class MetierHydrator implements HydratorInterface {
    use CategorieServiceAwareTrait;
    use DomaineServiceAwareTrait;

    /**
     * @param Metier $object
     * @return array
     */
    public function extract($object): array
    {
        $domaineIds = [];
        $domaines = $object->getDomaines();
        if ($domaines) {
            foreach ($domaines as $domaine) $domaineIds[] = $domaine->getId();
        }

        $data = [
            'categorie' => ($object->getCategorie())?$object->getCategorie()->getId():null,
            'domaines' => $domaineIds,
            'libelle' => $object->getLibelle(false),
            'libelle_feminin' => $object->getLibelleFeminin(),
            'libelle_masculin' => $object->getLibelleMasculin(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Metier $object
     * @return Metier
     */
    public function hydrate(array $data, $object) : object
    {
        $categorie = (isset($data['categorie']) AND trim($data['categorie']) !== '')?$this->getCategorieService()->getCategorie($data['categorie']):null;
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== '')?trim($data['libelle']):null;
        $libelleFeminin = (isset($data['libelle_feminin']) AND trim($data['libelle_feminin']) !== '')?trim($data['libelle_feminin']):null;
        $libelleMasculin = (isset($data['libelle_masculin']) AND trim($data['libelle_masculin']) !== '')?trim($data['libelle_masculin']):null;

        $object->clearDomaines();
        foreach ($data['domaines'] as $id) {
            $domaine = $this->getDomaineService()->getDomaine($id);
            if ($domaine) $object->addDomaine($domaine);
        }

        $object->setLibelle($libelle);
        $object->setLibelleFeminin($libelleFeminin);
        $object->setLibelleMasculin($libelleMasculin);
        $object->setCategorie($categorie);

        return $object;
    }

}