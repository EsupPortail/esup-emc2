<?php

namespace Formation\Form\EnqueteCategorie;

use Formation\Entity\Db\EnqueteCategorie;
use Laminas\Hydrator\HydratorInterface;

class EnqueteCategorieHydrator implements HydratorInterface
{


    public function extract(object $object): array
    {
        /** @var EnqueteCategorie $object */
        $data = [
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
            'ordre' => $object->getOrdre(),
        ];
        return $data;
    }

    public function hydrate(array $data, $object): object
    {
        $libelle = (isset($data['libelle']) and trim($data['libelle']) !== '') ? trim($data['libelle']) : null;
        $description = (isset($data['description']) and trim($data['description']) !== '') ? trim($data['description']) : null;
        $ordre = (isset($data['ordre'])) ? trim($data['ordre']) : null;

        /** @var EnqueteCategorie $object */
        $object->setLibelle($libelle);
        $object->setDescription($description);
        $object->setOrdre($ordre);

        return $object;
    }

}