<?php

namespace Metier\Form\Domaine;

use Laminas\Hydrator\HydratorInterface;
use Metier\Entity\Db\Domaine;

class DomaineHydrator implements HydratorInterface
{

    /**
     * @param Domaine $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'libelle' => $object->getLibelle(),
            'fonction' => $object->getTypeFonction(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Domaine $object
     * @return Domaine
     */
    public function hydrate(array $data, $object): object
    {
        $libelle = (isset($data['libelle']) and trim($data['libelle'])) ? trim($data['libelle']) : null;
        $fonction = (isset($data['fonction']) and trim($data['fonction'])) ? trim($data['fonction']) : null;
        $object->setLibelle($libelle);
        $object->setTypeFonction($fonction);

        return $object;
    }

}