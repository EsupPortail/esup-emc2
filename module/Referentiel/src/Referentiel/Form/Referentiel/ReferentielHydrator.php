<?php

namespace Referentiel\Form\Referentiel;

use Laminas\Hydrator\HydratorInterface;
use Referentiel\Entity\Db\Referentiel;

class ReferentielHydrator implements HydratorInterface
{
    public function extract(object $object): array
    {
        /** @var Referentiel $object */
        $data = [
            'libelle_court' => $object->getLibelleCourt(),
            'libelle_long' => $object->getLibelleLong(),
            'couleur' => $object->getCouleur(),
            'description' => $object->getDescription(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $libelleCourt = (isset($data['libelle_court']) AND trim($data['libelle_court']) !== '') ? trim($data['libelle_court']) : null;
        $libelleLong = (isset($data['libelle_long']) AND trim($data['libelle_long']) !== '') ? trim($data['libelle_long']) : null;
        $couleur = (isset($data['couleur']) AND trim($data['couleur']) !== '') ? trim($data['couleur']) : null;
        $description = (isset($data['description']) AND trim($data['description']) !== '') ? trim($data['description']) : null;

        /** @var Referentiel $object */
        $object->setLibelleCourt($libelleCourt);
        $object->setLibelleLong($libelleLong);
        $object->setCouleur($couleur);
        $object->setDescription($description);
        return $object;
    }


}
