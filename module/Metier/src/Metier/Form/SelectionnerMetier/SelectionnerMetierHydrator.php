<?php

namespace Metier\Form\SelectionnerMetier;

use Metier\Entity\HasMetierInterface;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class SelectionnerMetierHydrator implements HydratorInterface {
    use MetierServiceAwareTrait;

    /**
     * @param HasMetierInterface $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'metier' => ($object->getMetier())?$object->getMetier()->getLibelle():null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param HasMetierInterface $object
     * @return HasMetierInterface
     */
    public function hydrate(array $data, $object) : object
    {
        $metier = $this->getMetierService()->getMetier($data['metier']);
        $object->setMetier($metier);
        return $object;
    }

}