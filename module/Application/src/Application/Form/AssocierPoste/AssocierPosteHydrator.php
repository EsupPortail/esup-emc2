<?php

namespace Application\Form\AssocierPoste;

use Application\Entity\Db\FichePoste;
use Application\Service\Poste\PosteServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

;

class AssocierPosteHydrator implements HydratorInterface {
    use PosteServiceAwareTrait;

    /**
     * @param FichePoste $object
     * @return array
     */
    public function extract($object)
    {
        return [
            'poste' => ($object->getPoste())?$object->getPoste()->getId():0,
        ];
    }

    /**
     * @param FichePoste $object
     * @param array $data
     * @return FichePoste
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