<?php

namespace FicheMetier\Form\FicheMetierIdentification;

use FicheMetier\Entity\Db\FicheMetier;
use Laminas\Hydrator\HydratorInterface;
use Referentiel\Service\Referentiel\ReferentielServiceAwareTrait;

class FicheMetierIdentificationHydrator implements HydratorInterface
{
    use ReferentielServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var FicheMetier $object */
        $data = [
            'libelle' => $object->getLibelle(),
            'referentiel' => $object->getReferentiel()?->getId(),
            'identifiant' => $object->getReference(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $libelle = (isset($data['libelle']) and trim($data['libelle']) != '') ? trim($data['libelle']) : null;
        $referentiel = (isset($data['referentiel'])) ? $this->getReferentielService()->getReferentiel($data['referentiel']) : null;
        $identifiant = (isset($data['identifiant']) and trim($data['identifiant']) != '') ? trim($data['identifiant']) : null;

        /** @var FicheMetier $object */
        $object->setLibelle($libelle);
        $object->setReferentiel($referentiel);
        $object->setReference($identifiant);
        return $object;
    }

}
