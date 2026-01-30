<?php

namespace Carriere\Form\SelectionnerFamillesProfessionnelles;

use Carriere\Entity\Db\FamilleProfessionnelle;
use Carriere\Entity\Db\Interface\HasFamillesProfessionnellesInterface;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class SelectionnerFamillesProfessionnellesHydrator implements HydratorInterface
{
    use FamilleProfessionnelleServiceAwareTrait;

    public function extract($object): array
    {
        /** @var  $data */

        /** @var HasFamillesProfessionnellesInterface $object */
        $familles = $object->getFamillesProfessionnelles();
        $ids = array_map(function (FamilleProfessionnelle $a) {
            return $a->getId();
        }, $familles);

        $data = [
            'familleprofessionnelle' => $ids,
        ];
        return $data;
    }

    public function hydrate(array $data, $object): object
    {
        $familles = [];
        if (isset($data['familleprofessionnelle'])) {
            foreach ($data['familleprofessionnelle'] as $id) {
                $famille = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelle($id);
                $familles[] = $famille;
            }
        }

        /** @var HasFamillesProfessionnellesInterface $object */
        $object->clearFamillesProfessionnelles();
        foreach ($familles as $famille) $object->addFamilleProfessionnelle($famille);
        return $object;
    }

}