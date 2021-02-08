<?php

namespace Application\Form\ModifierNiveau;

use Application\Entity\Db\Corps;
use Metier\Entity\Db\Metier;
use Zend\Hydrator\HydratorInterface;

class ModifierNiveauHydrator implements HydratorInterface {

    /**
     * @param Corps|Metier $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'niveau' => $object->getNiveau(),
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
        $niveau = (isset($data['niveau']) AND trim($data['niveau']) !== "")?$data['niveau']:null;
        $object->setNiveau($niveau);
        return $object;
    }


}