<?php

namespace Application\Form\FicheMetier;

use Application\Entity\Db\FicheTypeExterne;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

class AjouterFicheTypeHydrator implements HydratorInterface {
    use FicheMetierServiceAwareTrait;

    /**
     * @param FicheTypeExterne $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'fiche_type'        => ($object->getFicheType())?$object->getFicheType()->getId():null,
            'quotite'           => ($object->getQuotite())?:null,
            'est_principale'    => ($object->getPrincipale())?:null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FicheTypeExterne $object
     * @return FicheTypeExterne
     */
    public function hydrate(array $data, $object)
    {
        $ficheType = $this->getFicheMetierService()->getFicheMetierType($data['fiche_type']);
        $object->setFicheType($ficheType);
        $object->setQuotite($data['quotite']);
        $object->setPrincipale($data['est_principale']);
        return $object;
    }
}