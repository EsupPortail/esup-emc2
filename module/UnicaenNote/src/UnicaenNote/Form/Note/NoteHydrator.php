<?php

namespace UnicaenNote\Form\Note;

use UnicaenNote\Entity\Db\Note;
use UnicaenNote\Service\PorteNote\PorteNoteServiceAwareTrait;
use UnicaenNote\Service\Type\TypeServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class NoteHydrator implements HydratorInterface {
    use PorteNoteServiceAwareTrait;
    use TypeServiceAwareTrait;

    /**
     * @param Note $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
//            'porte-note' => ($object->getPortenote())?$object->getPortenote()->getId():null,
            'type' => ($object->getType())?$object->getType()->getId():null,
            'description' => ($object->getTexte())?:null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Note $object
     * @return Note
     */
    public function hydrate(array $data, $object)
    {
//        $portenote = (isset($data['porte-note']))?$this->getPorteNoteService()->getPorteNote($data['porte-note']):null;
        $type = (isset($data['type']))?$this->getTypeService()->getType($data['type']):null;
        $description = (isset($data['description']) AND trim($data['description']) !== "")?trim($data['description']):null;

//        $object->setPortenote($portenote);
        $object->setType($type);
        $object->setTexte($description);
        return $object;
    }


}