<?php

namespace Carriere\Form\SelectionnerFamilleProfessionnelle;

use Carriere\Entity\Db\FamilleProfessionnelle;
use Carriere\Entity\Db\Interface\HasFamilleProfessionnelleInterface;
use Carriere\Entity\Db\Interface\HasFamillesProfessionnellesInterface;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class SelectionnerFamilleProfessionnelleHydrator implements HydratorInterface
{
    use FamilleProfessionnelleServiceAwareTrait;

    public function extract($object): array
    {
        /** @var HasFamilleProfessionnelleInterface $object */
        $data = [
            'familleprofessionnelle' => $object->getFamilleProfessionnelle(),
        ];
        return $data;
    }

    public function hydrate(array $data, $object): object
    {
        $famille = isset($data['familleprofessionnelle']) ? $this->getFamilleProfessionnelleService()->getFamilleProfessionnelle($data['familleprofessionnelle']) : null;

        /** @var HasFamilleProfessionnelleInterface $object */
        $object->setFamilleProfessionnelle($famille);
        return $object;
    }

}