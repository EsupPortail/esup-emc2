<?php

namespace Carriere\Form\SelectionnerCategorie;

use Carriere\Entity\Db\Interface\HasCategorieInterface;
use Carriere\Service\Categorie\CategorieServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class SelectionnerCategorieHydrator implements HydratorInterface
{
    use CategorieServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var HasCategorieInterface $object */
        $data = [
            'categorie' => $object->getCategorie()?->getId(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $categorie = (isset($data['categorie']))?$this->getCategorieService()->getCategorie($data['categorie']):null;

        /** @var HasCategorieInterface $object */
        $object->setCategorie($categorie);
        return $object;
    }


}
