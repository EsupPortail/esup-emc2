<?php

namespace Formation\Form\InscriptionFrais;

use Formation\Entity\Db\InscriptionFrais;
use Laminas\Hydrator\HydratorInterface;

class InscriptionFraisHydrator implements HydratorInterface
{

    /**
     * @param InscriptionFrais $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'repas' => ($object) ? $object->getFraisRepas() : null,
            'hebergement' => ($object) ? $object->getFraisHebergement() : null,
            'transport' => ($object) ? $object->getFraisTransport() : null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param InscriptionFrais $object
     * @return InscriptionFrais
     */
    public function hydrate(array $data, $object): object
    {
        $repas = (isset($data['repas']) && trim ($data['repas'] !== '')) ? trim($data['repas']):null;
        $hebergement = (isset($data['hebergement']) && trim ($data['hebergement'] !== '')) ? trim($data['hebergement']):null;
        $transport = (isset($data['transport']) && trim ($data['transport'] !== '')) ? trim($data['transport']):null;

        $object->setFraisRepas($repas);
        $object->setFraisHebergement($hebergement);
        $object->setFraisTransport($transport);
        return $object;
    }

}