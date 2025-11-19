<?php

namespace FicheMetier\Form\Activite;

use FicheMetier\Entity\Db\Activite;
use Laminas\Hydrator\HydratorInterface;
use Metier\Service\Referentiel\ReferentielServiceAwareTrait;

class ActiviteHydrator implements HydratorInterface
{
    use ReferentielServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var Activite $object */
        $data = [
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
            'referentiel' => $object->getReferentiel()?->getId(),
            'identifiant' => $object->getIdOrig(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) != '') ? trim($data['libelle']) : null;
        $description = (isset($data['description']) AND trim($data['description']) != '') ? trim($data['description']) : null;
        $referentiel = (isset($data['referentiel'])) ? $this->getReferentielService()->getReferentiel($data['referentiel']):null;
        $identifiant = (isset($data['identifiant']) AND trim($data['identifiant']) != '') ? trim($data['identifiant']) : null;

        /** @var Activite $object */
        $object->setLibelle($libelle);
        $object->setDescription($description);
        $object->setReferentiel($referentiel);
        $object->setIdOrig($identifiant);
        return $object;
    }

}
