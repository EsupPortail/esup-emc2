<?php

namespace UnicaenEtat\Form\ActionType;

use UnicaenEtat\Entity\Db\ActionType;
use Zend\Hydrator\HydratorInterface;

class ActionTypeHydrator implements HydratorInterface
{
    /**
     * @param ActionType $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'code' => $object->getCode(),
            'libelle' => $object->getLibelle(),
            'couleur' => $object->getCouleur(),
            'icone' => $object->getIcone(),
        ];

        return $data;
    }

    /**
     * @param array $data
     * @param ActionType $object
     * @return ActionType
     */
    public function hydrate(array $data, $object)
    {
        $code = (isset($data['code']) AND trim($data['code']) !== "")?trim($data['code']):null;
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== "")?trim($data['libelle']):null;
        $couleur = (isset($data['couleur']) AND trim($data['couleur']) !== "")?trim($data['couleur']):null;
        $icone = (isset($data['icone']) AND trim($data['icone']) !== "")?trim($data['icone']):null;

        $object->setCode($code);
        $object->setLibelle($libelle);
        $object->setCouleur($couleur);
        $object->setIcone($icone);
        return $object;
    }

}