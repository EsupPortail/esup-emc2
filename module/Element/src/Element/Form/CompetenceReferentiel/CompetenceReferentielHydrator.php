<?php

namespace Element\Form\CompetenceReferentiel;

use Element\Entity\Db\CompetenceReferentiel;
use Laminas\Hydrator\HydratorInterface;

class CompetenceReferentielHydrator implements HydratorInterface
{

    public function extract(object $object): array
    {
        /** @var CompetenceReferentiel $object */
        $data = [
            'libelle_court' => $object->getLibelleCourt(),
            'libelle_long' => $object->getLibelleLong(),
            'couleur' => $object->getCouleur(),
        ];

        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        /** @var CompetenceReferentiel $object */
        $libelleCourt = (isset($data['libelle_court']) && trim($data['libelle_court']) !== '') ? trim($data['libelle_court']) : null;
        $libelleLong = (isset($data['libelle_long']) && trim($data['libelle_long']) !== '') ? trim($data['libelle_long']) : null;
        $couleur = (isset($data['couleur']) && trim($data['couleur']) !== '') ? trim($data['couleur']) : null;

        $object->setLibelleCourt($libelleCourt);
        $object->setLibelleLong($libelleLong);
        $object->setCouleur($couleur);
        return $object;
    }


}