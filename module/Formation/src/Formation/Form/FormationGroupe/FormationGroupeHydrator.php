<?php

namespace Formation\Form\FormationGroupe;

use Formation\Entity\Db\FormationGroupe;
use Laminas\Hydrator\HydratorInterface;

class FormationGroupeHydrator implements HydratorInterface
{

    /**
     * @param FormationGroupe $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'libelle' => ($object->getLibelle()) ?: null,
            'HasDescription' => ['description' => $object->getDescription()],
            'ordre' => ($object->getOrdre()) ?: null,
//            'couleur'   => ($object->getCouleur())?:null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FormationGroupe $object
     * @return FormationGroupe
     */
    public function hydrate(array $data, $object)
    {
        $description = (isset($data['HasDescription']) AND isset($data['HasDescription']['description']) && trim($data['HasDescription']['description']) != '')?trim($data['HasDescription']['description']):null;

        $object->setLibelle((isset($data['libelle']) and trim($data['libelle']) !== '') ? trim($data['libelle']) : null);
        $object->setDescription($description);
        $object->setOrdre((isset($data['ordre']) and trim($data['ordre']) !== '') ? trim($data['ordre']) : null);
//        $object->setCouleur((isset($data['couleur']) AND trim($data['couleur']) !== '')?trim($data['couleur']):null);
        return $object;
    }


}