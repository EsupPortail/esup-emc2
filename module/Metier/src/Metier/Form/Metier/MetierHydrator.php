<?php

namespace Metier\Form\Metier;

use Carriere\Service\Categorie\CategorieServiceAwareTrait;
use Metier\Entity\Db\Metier;
use Laminas\Hydrator\HydratorInterface;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;

class MetierHydrator implements HydratorInterface {
    use CategorieServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var  Metier $object */
        $familleIds = [];
        $familles = $object->getFamillesProfessionnelles();
        if ($familles) {
            foreach ($familles as $famille) $familleIds[] = $famille->getId();
        }

        $data = [
            'categorie' => ($object->getCategorie())?$object->getCategorie()->getId():null,
            'familles' => $familleIds,
            'libelle' => $object->getLibelle(false),
            'libelle_feminin' => $object->getLibelleFeminin(),
            'libelle_masculin' => $object->getLibelleMasculin(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object) : object
    {
        $categorie = (isset($data['categorie']) AND trim($data['categorie']) !== '')?$this->getCategorieService()->getCategorie($data['categorie']):null;
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== '')?trim($data['libelle']):null;
        $libelleFeminin = (isset($data['libelle_feminin']) AND trim($data['libelle_feminin']) !== '')?trim($data['libelle_feminin']):null;
        $libelleMasculin = (isset($data['libelle_masculin']) AND trim($data['libelle_masculin']) !== '')?trim($data['libelle_masculin']):null;

        /** @var  Metier $object */

        $object->clearFamillesProfessionnelles();
        if (isset($data['familles'])) {
            foreach ($data['familles'] as $id) {
                $famille = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelle($id);
                if ($famille) $object->addFamillesProfessionnelles($famille);
            }
        }

        $object->setLibelle($libelle);
        $object->setLibelleFeminin($libelleFeminin);
        $object->setLibelleMasculin($libelleMasculin);
        $object->setCategorie($categorie);

        return $object;
    }

}