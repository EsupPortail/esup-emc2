<?php

namespace Carriere\Form\SelectionnerNiveauCarriere;

use Carriere\Entity\Db\Interface\HasNiveauCarriereInterface;
use Carriere\Service\Niveau\NiveauServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class SelectionnerNiveauCarriereHydrator implements HydratorInterface
{
    use NiveauServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var HasNiveauCarriereInterface $object */
        $data = [
            'niveau_carriere' => $object->getNiveauCarriere()?->getId(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $niveau = (isset($data['niveau_carriere']))?$this->getNiveauService()->getNiveau($data['niveau_carriere']):null;

        /** @var HasNiveauCarriereInterface $object */
        $object->setNiveauCarriere($niveau);
        return $object;
    }


}
