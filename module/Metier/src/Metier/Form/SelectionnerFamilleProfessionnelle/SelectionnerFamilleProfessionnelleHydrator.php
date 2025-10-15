<?php

namespace Metier\Form\SelectionnerFamilleProfessionnelle;

use Laminas\Hydrator\HydratorInterface;
use Metier\Entity\Db\FamilleProfessionnelle;
use Metier\Entity\Db\Interface\HasFamillesProfessionnellesInterface;
use Metier\Entity\HasMetierInterface;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;

class SelectionnerFamilleProfessionnelleHydrator implements HydratorInterface
{
    use FamilleProfessionnelleServiceAwareTrait;

    /**
     * @param HasMetierInterface $object
     * @return array
     */
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

    /**
     * @param array $data
     * @param HasMetierInterface $object
     * @return HasMetierInterface
     */
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