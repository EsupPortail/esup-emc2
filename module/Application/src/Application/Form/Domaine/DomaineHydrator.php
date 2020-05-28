<?php

namespace Application\Form\Domaine;

use Application\Entity\Db\Domaine;
use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class DomaineHydrator implements HydratorInterface {
    use FamilleProfessionnelleServiceAwareTrait;

    /**
     * @param Domaine $object
     * @return array
     */
    public function extract($object)
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
    public function hydrate(array $data, $object)
    {
        $famille = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelle($data['famille']);

        $object->setLibelle($data['libelle']);
        $object->setTypeFonction($data['fonction']);
        $object->setFamille($famille);


        return $object;
    }

}