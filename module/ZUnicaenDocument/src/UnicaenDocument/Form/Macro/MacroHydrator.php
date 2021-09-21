<?php

namespace UnicaenDocument\Form\Macro;

use UnicaenDocument\Entity\Db\Macro;
use Zend\Hydrator\HydratorInterface;

class MacroHydrator implements HydratorInterface {

    /**
     * @param Macro $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'code' => $object->getCode(),
            'variable' => $object->getVariable(),
            'methode' => $object->getMethode(),
            'description' => $object->getDescription(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Macro $object
     * @return Macro
     */
    public function hydrate(array $data, $object)
    {
        $code = (isset($data['code']) AND trim($data['code']) !== null)?trim($data['code']):null;
        $variable = (isset($data['variable']) AND trim($data['variable']) !== null)?trim($data['variable']):null;
        $methode = (isset($data['methode']) AND trim($data['methode']) !== null)?trim($data['methode']):null;
        $description = (isset($data['description']) AND trim($data['description']) !== null)?trim($data['description']):null;

        $object->setCode($code);
        $object->setVariable($variable);
        $object->setMethode($methode);
        $object->setDescription($description);

        return $object;
    }

}