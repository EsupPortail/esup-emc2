<?php

namespace UnicaenEtat\Form\Etat;

use UnicaenEtat\Entity\Db\Etat;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class EtatHydrator implements HydratorInterface {
    use EtatTypeServiceAwareTrait;

    /**
     * @param Etat $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'code' => ($object)?$object->getCode():null,
            'libelle' => ($object)?$object->getLibelle():null,
            'icone' => ($object)?$object->getIcone():null,
            'couleur' => ($object)?$object->getCouleur():null,
            'type' => ($object AND $object->getType())?$object->getType()->getId():null,
            'ordre' => ($object)?$object->getOrdre():9999,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Etat $object
     * @return Etat
     */
    public function hydrate(array $data, $object)
    {
        $code = (isset($data['code']) AND trim($data['code']) !== "")?trim($data['code']):null;
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== "")?trim($data['libelle']):null;
        $icone = (isset($data['icone']) AND trim($data['icone']) !== "")?trim($data['icone']):null;
        $couleur = (isset($data['couleur']) AND trim($data['couleur']) !== "")?trim($data['couleur']):null;
        $type = isset($data['couleur'])?$this->getEtatTypeService()->getEtatType($data['type']):null;
        $ordre = (isset($data['ordre']) AND trim($data['ordre']) !== "")?trim($data['ordre']):9999;

        $object->setCode($code);
        $object->setLibelle($libelle);
        $object->setIcone($icone);
        $object->setCouleur($couleur);
        $object->setType($type);
        $object->setOrdre($ordre);

        return $object;
    }

}