<?php

namespace Application\Form\Complement;

use Application\Entity\Db\Complement;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class ComplementHydrator implements HydratorInterface {
    use EntityManagerAwareTrait;

    /**
     * @param Complement $object
     * @return array
     */
    public function extract($object): array
    {
        $complement = null;
        if ($object->getComplementType() AND $object->getComplementId() ) $complement = $this->getEntityManager()->getRepository($object->getComplementType())->find($object->getComplementId());

        $data = [
            'sas' => [
                'id' => ($complement)?$complement->getId():null,
                'label' => ($complement)?$complement->toString():null,
            ],
            'text' => $object->getComplementText(),
        ];

        return $data;
    }

    /**
     * @param array $data
     * @param Complement $object
     * @return Complement
     */
    public function hydrate(array $data, $object)
    {
        $complement =  (isset($data['sas']) AND isset($data['sas']['id']))?$this->getEntityManager()->getRepository($object->getComplementType())->find($data['sas']['id']):null;
        $texte = (isset($data['text']) AND trim($data['text']) !== '')?trim($data['text']):null;

        $object->setComplementType(($complement)?get_class($complement):null);
        $object->setComplementId(($complement)?$complement->getId():null);
        $object->setComplementText($texte);

        return $object;
    }


}