<?php

namespace Formation\Form\Axe;

use Formation\Entity\Db\Axe;
use Laminas\Hydrator\HydratorInterface;

class AxeHydrator implements HydratorInterface
{

    public function extract($object): array
    {
        /** @var Axe $object */
        $data = [
            'libelle' => ($object->getLibelle()) ?: null,
            'HasDescription' => ['description' => $object->getDescription()],
            'ordre' => ($object->getOrdre() !== null) ? $object->getOrdre(): null,
        ];
        return $data;
    }

    public function hydrate(array $data, $object) : object
    {
        $libelle = (isset($data['libelle']) and trim($data['libelle']) !== '') ? trim($data['libelle']) : null;
        $description = (isset($data['HasDescription']) AND isset($data['HasDescription']['description']) && trim($data['HasDescription']['description']) != '')?trim($data['HasDescription']['description']):null;
        $ordre = (isset($data['ordre'])) ? $data['ordre'] : null;

        /** @var Axe $object */
        $object->setLibelle($libelle);
        $object->setDescription($description);
        $object->setOrdre($ordre?((int) $ordre):null);
        return $object;
    }


}