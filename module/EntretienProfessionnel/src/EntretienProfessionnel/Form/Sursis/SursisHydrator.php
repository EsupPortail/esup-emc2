<?php

namespace EntretienProfessionnel\Form\Sursis;

use DateTime;
use EntretienProfessionnel\Entity\Db\Sursis;
use Zend\Hydrator\HydratorInterface;

class SursisHydrator implements HydratorInterface {

    /**
     * @param Sursis $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'date' => $object->getSursis()->format('Y-m-d'),
            'description' => $object->getDescription(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Sursis $object
     * @return Sursis
     */
    public function hydrate(array $data, $object)
    {
        $date = (isset($data['date']))?DateTime::createFromFormat('Y-m-d', $data['date']):null;
        $description = (isset($data['description']) AND trim($data['description']) !== "")?trim($data['description']):null;

        $object->setSursis($date);
        $object->setDescription($description);
        return $object;
    }


}