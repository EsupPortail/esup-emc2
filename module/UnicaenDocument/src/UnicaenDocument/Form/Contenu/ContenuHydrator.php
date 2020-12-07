<?php

namespace UnicaenDocument\Form\Contenu;

use UnicaenDocument\Entity\Db\Content;
use Zend\Hydrator\HydratorInterface;

class ContenuHydrator implements HydratorInterface {

    /**
     * @param Content $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'code' => ($object)?$object->getCode():null,
            'type' => ($object)?$object->getType():null,
            'description' => ($object)?$object->getDescription():null,
            'complement' => ($object)?$object->getComplement():null,
            'contenu' => ($object)?$object->getContent():null,
        ];

        return $data;
    }

    /**
     * @param Content $object
     * @param array $data
     * @return Content
     */
    public function hydrate(array $data, $object)
    {
        $code = (isset($data['code']) AND trim($data['code']) !== "")?trim($data['code']):null;
        $type = (isset($data['type']) AND trim($data['type']) !== "")?trim($data['type']):null;
        $description = (isset($data['description']) AND trim($data['description']) !== "")?trim($data['description']):null;
        $complement = (isset($data['complement']) AND trim($data['complement']) !== "")?strip_tags(trim($data['complement'])):null;
        $contenu = (isset($data['contenu']) AND trim($data['contenu']) !== "")?trim($data['contenu']):null;

        $object->setCode($code);
        $object->setType($type);
        $object->setDescription($description);
        $object->setComplement($complement);
        $object->setContent($contenu);

        return $object;
    }

}