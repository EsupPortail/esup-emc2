<?php

namespace EntretienProfessionnel\Form\Sursis;

use DateTime;
use EntretienProfessionnel\Entity\Db\Sursis;
use Laminas\Hydrator\HydratorInterface;

class SursisHydrator implements HydratorInterface {

    /**
     * @param Sursis $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'date' => ($object->getSursis())?$object->getSursis()->format('Y-m-d'):null,
            'HasDescription' => ['description' => $object->getDescription()],
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
        $date = (isset($data['date']))?DateTime::createFromFormat('Y-m-d H:i:s', $data['date']." 23:59:59"):null;
        $description = (isset($data['HasDescription']) AND isset($data['HasDescription']['description']) && trim($data['HasDescription']['description']) != '')?trim($data['HasDescription']['description']):null;

        $object->setSursis($date);
        $object->setDescription($description);
        return $object;
    }


}