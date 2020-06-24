<?php

namespace Mailing\Form\MailContent;

use Mailing\Model\Db\MailType;
use Zend\Hydrator\HydratorInterface;

class MailContentHydrator implements HydratorInterface {

    /**
     * @param MailType $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'sujet' => $object->getSujet(),
            'corps' => $object->getCorps(),
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
        $sujet = (isset($data['sujet']) AND trim($data['sujet']) !== '') ? trim($data['sujet']) : null;
        $corps = (isset($data['corps']) AND trim($data['corps']) !== '') ? trim($data['corps']) : null;

        $object->setSujet($sujet);
        $object->setCorps($corps);
        return $object;
    }


}