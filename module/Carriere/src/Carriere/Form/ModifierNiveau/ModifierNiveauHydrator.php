<?php

namespace Carriere\Form\ModifierNiveau;

use Carriere\Entity\Db\Corps;
use Carriere\Service\Niveau\NiveauServiceAwareTrait;
use Metier\Entity\Db\Metier;
use Laminas\Hydrator\HydratorInterface;

class ModifierNiveauHydrator implements HydratorInterface {
    use NiveauServiceAwareTrait;

    /**
     * @param Corps|Metier $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'niveau' => ($object->getNiveau())?$object->getNiveau()->getId():null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Corps|Metier $object
     * @return Corps|Metier
     */
    public function hydrate(array $data, $object)
    {
        $niveau = (isset($data['niveau']) AND trim($data['niveau']) !== "")?$this->getNiveauService()->getNiveau($data['niveau']):null;
        $object->setNiveau($niveau);
        return $object;
    }


}