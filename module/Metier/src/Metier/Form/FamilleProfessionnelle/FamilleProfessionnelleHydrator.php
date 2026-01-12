<?php

namespace Metier\Form\FamilleProfessionnelle;

use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;
use Metier\Entity\Db\FamilleProfessionnelle;

class FamilleProfessionnelleHydrator implements HydratorInterface
{
    use CorrespondanceServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var FamilleProfessionnelle $object */
        $data = [
            'libelle' => $object->getLibelle(),
            'correspondance' => $object->getCorrespondance()?->getId(),
            'position' => $object->getPosition(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== null) ? trim($data['libelle']) : null;
        $correspondance = (isset($data['correspondance']) AND $data['correspondance'] !== '')?$this->getCorrespondanceService()->getCorrespondance($data['correspondance']):null;
        $position = (isset($data['position']) AND $data['position'] !== '')?$data['position']:null;

        /** @var FamilleProfessionnelle $object */
        $object->setLibelle($libelle);
        $object->setCorrespondance($correspondance);
        $object->setPosition($position);
        return $object;
    }


}
