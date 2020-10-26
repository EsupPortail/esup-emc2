<?php

namespace Formation\Form\FormationInstanceFrais;

use Formation\Entity\Db\FormationInstanceFrais;
use Zend\Hydrator\HydratorInterface;

class FormationInstanceFraisHydrator implements HydratorInterface {

    /**
     * @param FormationInstanceFrais $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'repas' => ($object)?$object->getFraisRepas():null,
            'hebergement' => ($object)?$object->getFraisHebergement():null,
            'transport' => ($object)?$object->getFraisTransport():null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FormationInstanceFrais $object
     * @return FormationInstanceFrais
     */
    public function hydrate(array $data, $object)
    {
        $repas = isset($data['repas'])?$data['repas']:null;
        $hebergement = isset($data['hebergement'])?$data['hebergement']:null;
        $transport = isset($data['transport'])?$data['transport']:null;

        $object->setFraisRepas($repas);
        $object->setFraisHebergement($hebergement);
        $object->setFraisTransport($transport);
        return $object;
    }

}