<?php

namespace UnicaenValidation\Form\ValidationInstance;

use UnicaenValidation\Entity\Db\ValidationInstance;
use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class ValidationInstanceHydrator implements HydratorInterface {
    use ValidationTypeServiceAwareTrait;

    /**
     * @param ValidationInstance $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'type'          => ($object AND $object->getType())?$object->getType()->getId():null,
            'valeur'        => $object?$object->getValeur():null,
            'entityclass'   => $object?$object->getEntityClass():null,
            'entityid'      => $object?$object->getEntityId():null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param ValidationInstance $object
     * @return ValidationInstance
     */
    public function hydrate(array $data, $object)
    {
        $type = $this->getValidationTypeService()->getValidationType($data['type']);
        $object->setType($type);
        $object->setValeur(isset($data['valeur'])?$data['valeur']:null);
        $object->setEntityClass(isset($data['entityclass'])?$data['entityclass']:null);
        $object->setEntityId(isset($data['entityid'])?$data['entityid']:null);
        return $object;
    }
}