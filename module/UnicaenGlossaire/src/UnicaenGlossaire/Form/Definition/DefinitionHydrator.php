<?php

namespace UnicaenGlossaire\Form\Definition;

use UnicaenGlossaire\Entity\Db\Definition;
use Zend\Hydrator\HydratorInterface;

class DefinitionHydrator implements HydratorInterface {

    /**
     * @param Definition $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'terme' => $object->getTerme(),
            'description' => $object->getDefinition(),
            'alternatives' => $object->getAlternatives(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Definition $object
     * @return Definition
     */
    public function hydrate(array $data, $object)
    {
        $terme = (isset($data['terme']) AND trim($data['terme']) !== '')?trim($data['terme']):null;
        $definition = (isset($data['description']) AND trim($data['description']) !== '')?trim($data['description']):null;
        $alternatives = (isset($data['alternatives']) AND trim($data['alternatives']) !== '')?trim($data['alternatives']):null;

        $object->setTerme($terme);
        $object->setDefinition($definition);
        $object->setAlternatives($alternatives);
        return $object;
    }


}