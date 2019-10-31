<?php

namespace Application\Form\Validation;

use Application\Entity\Db\Validation;
use Application\Service\Validation\ValidationValeurServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class ValidationHydrator implements HydratorInterface {
    use ValidationValeurServiceAwareTrait;
    /**
     * @param Validation $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'type' => ($object->getType())?$object->getType()->getLibelle():"Aucun type de spécifié",
            'valeur' => ($object->getValeur())?$object->getValeur()->getId():null,
            'commentaire' => $object->getCommentaire(),
            'object_id' => $object->getObjectId(),
        ];

        return $data;
    }

    /**
     * @param array $data
     * @param Validation $object
     * @return Validation
     */
    public function hydrate(array $data, $object)
    {
        $valeur = $this->getValidationValeurService()->getValidationValeur($data['valeur']);

        $object->setValeur($valeur);
        $object->setCommentaire($data['commentaire']);

        return $object;
    }


}