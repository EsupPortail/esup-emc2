<?php

namespace Application\Form\SpecificitePoste;



use Application\Entity\Db\SpecificitePoste;
use Laminas\Hydrator\HydratorInterface;

class SpecificitePosteHydrator implements HydratorInterface {

    /**
     * @param SpecificitePoste $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            "specificite" => $object->getSpecificite(),
            "encadrement" => $object->getEncadrement(),
            "relations_internes" => $object->getRelationsInternes(),
            "relations_externes" => $object->getRelationsExternes(),
            "contraintes" => $object->getContraintes(),
            "moyens" => $object->getMoyens(),
            "formations" => $object->getFormations(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param SpecificitePoste $object
     * @return SpecificitePoste
     */
    public function hydrate(array $data, $object)
    {
        $object->setSpecificite(null);
        if ($data['specificite'] !== '' ) $object->setSpecificite($data['specificite']);
        $object->setEncadrement(null);
        if ($data['encadrement'] !== '' ) $object->setEncadrement($data['encadrement']);
        $object->setRelationsInternes(null);
        if ($data['relations_internes'] !== '' ) $object->setRelationsInternes($data['relations_internes']);
        $object->setRelationsExternes(null);
        if ($data['relations_externes'] !== '' ) $object->setRelationsExternes($data['relations_externes']);
        $object->setContraintes(null);
        if ($data['contraintes'] !== '' ) $object->setContraintes($data['contraintes']);
        $object->setMoyens(null);
        if ($data['moyens'] !== '' ) $object->setMoyens($data['moyens']);
        $object->setFormations(null);
        if ($data['formations'] !== '' ) $object->setFormations($data['formations']);

        return $object;
    }

}