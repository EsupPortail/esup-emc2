<?php

namespace Fichier\Form\Upload;

use Fichier\Entity\Db\Fichier;
use Zend\Hydrator\HydratorInterface;

class UploadHydrator implements HydratorInterface {

    /**
     * @var Fichier $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'nature' => ($object)?$object->getNature()->getId():null,
        ];
        return $data;
    }

    public function hydrate(array $data, $object)
    {
        return $object;
    }

}