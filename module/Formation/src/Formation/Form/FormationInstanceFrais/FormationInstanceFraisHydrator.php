<?php

namespace Formation\Form\FormationInstanceFrais;

use Formation\Entity\Db\InscriptionFrais;
use Laminas\Hydrator\HydratorInterface;

class FormationInstanceFraisHydrator implements HydratorInterface
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
        $repas = $data['repas'] ?? null;
        $hebergement = $data['hebergement'] ?? null;
        $transport = $data['transport'] ?? null;

        $object->setFraisRepas($repas);
        $object->setFraisHebergement($hebergement);
        $object->setFraisTransport($transport);
        return $object;
    }

}