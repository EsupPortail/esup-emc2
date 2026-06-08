<?php

namespace Carriere\Form\SelectionnerCategories;

use Carriere\Entity\Db\Interface\HasCategorieInterface;
use Carriere\Service\Categorie\CategorieServiceAwareTrait;
use DateTime;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\FicheMetierCategorie;
use Laminas\Hydrator\HydratorInterface;

class SelectionnerCategoriesHydrator implements HydratorInterface
{
    use CategorieServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var FicheMetier|object $object */
        $categoriesId = [];
        foreach ($object->getCategories(false) as $categorie) { $categoriesId[] = $categorie->getCategorie()->getId(); };
        $data = [
            'categories' => $categoriesId,
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $now = new DateTime();
        $categoriesId = (isset($data['categories']))?($data['categories']):[];
        $categoriesFromSelect = [];
        foreach ($categoriesId as $categorieId) {
            $categoriesFromSelect[] = $this->getCategorieService()->getCategorie($categorieId);
        }

        /** @var FicheMetier|object $object */
        $categoriesFromObject = $object->getCategories(false);
        foreach ($categoriesFromObject as $categorie) {
            if (! in_array($categorie->getCategorie(), $categoriesFromSelect)) $categorie->setHistoDestruction($now);
        }
        foreach ($categoriesFromSelect as $categorieFromSelect) {
            if (!$object->hasCategorie($categorieFromSelect)) {
                $object->addCategorie($categorieFromSelect);
            }
        }
        return $object;
    }


}
