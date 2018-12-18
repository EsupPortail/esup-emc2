<?php

namespace Application\Form\FicheMetier;

use Application\Entity\Db\FicheMetier;
use Application\Service\Poste\PosteServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

;

class AssocierPosteHydrator implements HydratorInterface {
    use PosteServiceAwareTrait;

    /**
     * @param FicheMetier $object
     * @return array
     */
    public function extract($object)
    {
        return [
            'poste' => ($object->getPoste())?$object->getPoste()->getId():0,
        ];
    }

    /**
     * @param FicheMetier $object
     * @param array $data
     * @return FicheMetier
     */
    public function hydrate(array $data, $object)
    {
        $metierType = $this->getPosteService()->getPoste($data['poste']);

        if ($metierType) {
            $object->setPoste($metierType);
        } else {
            $object->setPoste(null);
        }
        return $object;
    }
}