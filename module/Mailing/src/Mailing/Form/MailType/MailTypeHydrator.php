<?php

namespace Mailing\Form\MailType;

use Mailing\Model\Db\MailType;
use Zend\Hydrator\HydratorInterface;

class MailTypeHydrator implements HydratorInterface {

    /**
     * @param MailType $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'code' => $object->getCode(),
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
            'actif'  => $object->getActif(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param MailType $object
     * @return MailType
     */
    public function hydrate(array $data, $object)
    {
        $code        = (isset($data['code']) AND trim($data['code']) !== '')?trim($data['code']):null;
        $libelle     = (isset($data['libelle']) AND trim($data['libelle']) !== '')?trim($data['libelle']):null;
        $description = (isset($data['description']) AND trim($data['description']) !== '')?trim($data['description']):null;
        $actif       = (isset($data['actif']) AND trim($data['actif']) !== '')?trim($data['actif']):null;

        $object->setCode($code);
        $object->setLibelle($libelle);
        $object->setDescription($description);
        $object->setActif($actif);

        return $object;
    }


}