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
            'ordre' => ($object->getOrdre() !== null) ? $object->getOrdre(): null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FormationGroupe $object
     * @return FormationGroupe
     */
    public function hydrate(array $data, $object) : object
    {
        $libelle = (isset($data['libelle']) and trim($data['libelle']) !== '') ? trim($data['libelle']) : null;
        $description = (isset($data['HasDescription']) AND isset($data['HasDescription']['description']) && trim($data['HasDescription']['description']) != '')?trim($data['HasDescription']['description']):null;
        $ordre = (isset($data['ordre'])) ? $data['ordre'] : null;

        $object->setLibelle($libelle);
        $object->setDescription($description);
        $object->setOrdre($ordre?((int) $ordre):null);
        return $object;
    }


}