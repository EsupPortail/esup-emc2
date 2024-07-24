<?php

namespace Formation\Form\FormationGroupe;

use Formation\Entity\Db\FormationGroupe;
use Formation\Service\Axe\AxeServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class FormationGroupeHydrator implements HydratorInterface
{
    use AxeServiceAwareTrait;

    public function extract($object): array
    {
        /** @var FormationGroupe $object */
        $data = [
            'libelle' => ($object->getLibelle()) ?: null,
            'axe' => ($object->getAxe()) ?$object->getAxe()->getId(): null,
            'description' => $object->getDescription(),
            'ordre' => ($object->getOrdre() !== null) ? $object->getOrdre(): null,
        ];
        return $data;
    }

    public function hydrate(array $data, $object) : object
    {
        $libelle = (isset($data['libelle']) and trim($data['libelle']) !== '') ? trim($data['libelle']) : null;
        $description = (isset($data['description']) && trim($data['description']) != '')?trim($data['description']):null;
        $ordre = (isset($data['ordre'])) ? $data['ordre'] : null;
        $axe = (isset($data['axe']) and trim($data['axe']) !== '') ? $this->getAxeService()->getAxe($data['axe']) : null;

        /** @var FormationGroupe $object */
        $object->setLibelle($libelle);
        $object->setAxe($axe);
        $object->setDescription($description);
        $object->setOrdre($ordre?((int) $ordre):null);
        return $object;
    }


}