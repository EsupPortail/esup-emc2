<?php

namespace Application\Form\RessourceRh;

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
        $object->setFamille($famille);

        return $object;
    }

}