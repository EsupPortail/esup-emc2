<?php

namespace Application\Form\HasDescription;

use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Zend\Hydrator\HydratorInterface;

class HasDescriptionHydrator implements HydratorInterface {

    /**
     * @param HasDescriptionInterface $object
     * @return array
     */
    public function extract($object) : array
    {
        $data = [
            'HasDescription' => [ "description" => $object->getDescription()],
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param HasDescriptionInterface $object
     * @return HasDescriptionInterface
     */
    public function hydrate(array $data, $object) : object
    {
        if (isset($data['HasDescription']) AND isset($data['HasDescription']['description']) && $data['HasDescription']['description'] != '') $object->setDescription($data['HasDescription']['description']);
        return $object;
    }

}