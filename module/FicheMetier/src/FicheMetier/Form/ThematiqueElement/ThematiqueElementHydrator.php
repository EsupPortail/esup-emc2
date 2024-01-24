<?php

namespace FicheMetier\Form\ThematiqueElement;

use Element\Service\Niveau\NiveauServiceAwareTrait;
use FicheMetier\Entity\Db\ThematiqueElement;
use Laminas\Hydrator\HydratorInterface;

class ThematiqueElementHydrator implements HydratorInterface
{
    use NiveauServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var ThematiqueElement $object */
        $data = [
            'niveau' => $object->getNiveauMaitrise()?->getId(),
            'complement' => $object->getComplement(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $niveau = (isset($data['niveau']))?$this->getNiveauService()->getMaitriseNiveau($data['niveau']):null;
        $complement = (isset($data['complement']) && trim($data['complement']) !== "")?trim($data['complement']):null;

        /** @var ThematiqueElement $object */
        $object->setNiveauMaitrise($niveau);
        $object->setComplement($complement);
        return $object;

    }

}