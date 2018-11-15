<?php

namespace Application\Form\Application;

use Application\Entity\Db\Application;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ApplicationHydrator implements HydratorInterface {

    /**
     * @param Application $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
            'url' => $object->getUrl(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Application $object
     * @return Application
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        if ($data['description'] === null || $data['description'] === '') {
            $object->setDescription(null);
        } else {
            $object->setDescription($data['description']);
        }
        if ($data['url'] === null || $data['url'] === '') {
            $object->setUrl(null);
        } else {
            $object->setUrl($data['url']);
        }
        return $object;
    }

}