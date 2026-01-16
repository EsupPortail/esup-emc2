<?php

namespace Carriere\Form\FamilleProfessionnelle;

use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;
use Carriere\Entity\Db\FamilleProfessionnelle;

class FamilleProfessionnelleHydrator implements HydratorInterface
{
    use CorrespondanceServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var FamilleProfessionnelle $object */
        $data = [
            'libelle' => $object->getLibelle(),
            'specialite' => $object->getCorrespondance()?->getId(),
            'position' => $object->getPosition(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== null) ? trim($data['libelle']) : null;
        $specialite = (isset($data['specialite']) AND $data['specialite'] !== '')?$this->getCorrespondanceService()->getCorrespondance($data['specialite']):null;
        $position = (isset($data['position']) AND $data['position'] !== '')?$data['position']:null;

        /** @var FamilleProfessionnelle $object */
        $object->setLibelle($libelle);
        $object->setCorrespondance($specialite);
        $object->setPosition($position);
        return $object;
    }


}
