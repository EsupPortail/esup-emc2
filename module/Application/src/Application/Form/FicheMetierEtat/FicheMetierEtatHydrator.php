<?php

namespace Application\Form\FicheMetierEtat;

use Application\Entity\Db\FicheMetier;
use Application\Entity\Db\FicheMetierEtat;
use Zend\Hydrator\HydratorInterface;

class FicheMetierEtatHydrator implements HydratorInterface {

    /**
     * @param FicheMetierEtat $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'code'          => ($object->getCode())?$object->getCode():null,
            'libelle'       => ($object->getLibelle())?$object->getLibelle():null,
            'description'   => ($object->getDescription())?$object->getDescription():null,
            'couleur'       => ($object->getCouleur())?$object->getCouleur():null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FicheMetierEtat $object
     * @return FicheMetierEtat
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode(isset($data['code'])?$data['code']:null);
        $object->setLibelle(isset($data['libelle'])?$data['libelle']:null);
        $object->setDescription(isset($data['description'])?$data['description']:null);
        $object->setCouleur(isset($data['couleur'])?$data['couleur']:null);
        return $object;
    }

}