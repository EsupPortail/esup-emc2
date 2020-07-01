<?php

namespace Application\Form\FicheMetierEtat;

use Application\Entity\Db\FicheMetier;
use Application\Service\FicheMetierEtat\FicheMetierEtatServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class FicheMetierEtatHydrator implements HydratorInterface {
    use FicheMetierEtatServiceAwareTrait;

    /**
     * @param FicheMetier $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'etat' => ($object->getEtat())?$object->getEtat()->getId():0,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FicheMetier $object
     * @return FicheMetier
     */
    public function hydrate(array $data, $object)
    {
        $etat = (isset($data['etat']))?$this->getFicheMetierEtatService()->getEtat($data['etat']):null;
        $object->setEtat($etat);
        return $object;
    }

}