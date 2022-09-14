<?php

namespace Fichier\Form\Upload;

use Fichier\Entity\Db\Fichier;
use Laminas\Hydrator\HydratorInterface;

class UploadHydrator implements HydratorInterface {

    /**
     * @var Fichier $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'nature' => ($object AND $object->getNature())?$object->getNature()->getId():null,
        ];
        return $data;
    }

    public function hydrate(array $data, $object)
    {
        return $object;
    }

}