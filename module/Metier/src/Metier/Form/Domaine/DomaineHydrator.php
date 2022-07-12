<?php

namespace Metier\Form\Domaine;

use Metier\Entity\Db\Domaine;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class DomaineHydrator implements HydratorInterface {
    use FamilleProfessionnelleServiceAwareTrait;

    /**
     * @param Domaine $object
     * @return array
     */
    public function extract($object)  : array
    {
        $data = [
            'libelle' => $object->getLibelle(),
            'famille' => ($object->getFamille())?$object->getFamille()->getId():null,
            'fonction' => $object->getTypeFonction(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Domaine $object
     * @return Domaine
     */
    public function hydrate(array $data, $object) : Domaine
    {
        $famille = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelle($data['famille']);

        $object->setLibelle($data['libelle']);
        $object->setTypeFonction($data['fonction']);
        $object->setFamille($famille);


        return $object;
    }

}