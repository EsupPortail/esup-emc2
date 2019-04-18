<?php

namespace Fichier\Form\Upload;

use Zend\Stdlib\Hydrator\HydratorInterface;

class UploadHydrator implements HydratorInterface {

    public function extract($object)
    {
        $data = [];
        return $data;
    }

    public function hydrate(array $data, $object)
    {
        return $object;
    }

}