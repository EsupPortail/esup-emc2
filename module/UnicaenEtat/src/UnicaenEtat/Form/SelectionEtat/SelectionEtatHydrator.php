<?php

namespace UnicaenEtat\Form\SelectionEtat;

use UnicaenEtat\Entity\Db\HasEtatInterface;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class SelectionEtatHydrator implements HydratorInterface {
    use EtatServiceAwareTrait;

    /**
     * @param HasEtatInterface $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'etat' => ($object->getEtat())?$object->getEtat()->getId():null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param HasEtatInterface $object
     * @return HasEtatInterface
     */
    public function hydrate(array $data, $object)
    {
        $etat = (isset($data['etat']))?$this->getEtatService()->getEtat($data['etat']):null;
        $object->setEtat($etat);
        return $object;
    }

}