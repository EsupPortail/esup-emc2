<?php

namespace Structure\Form\Contact;

use Laminas\Hydrator\HydratorInterface;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenContact\Entity\Db\Contact;
use UnicaenContact\Service\Type\TypeServiceAwareTrait;

class ContactHydrator implements HydratorInterface
{
    use StructureServiceAwareTrait;
    use TypeServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var Contact $object */
        $structuresId = $this->getStructureService()->getStructureIdsHavingContact($object);
        $data = [
            'type' => $object->getType()?->getId(),
            'service' => $object->getService(),
            'denomination' => $object->getDenomination(),
            'telephone' => $object->getTelephone(),
            'email' => $object->getEmail(),
            'url' => $object->getUrl(),
            'structures' => $structuresId,
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        /** @var Contact $object */
        $type = (isset($data['type'])) ? $this->getTypeService()->getType($data['type']) : null;
        $service = (isset($data['service']) AND trim($data['service']) !== "")?trim($data['service']):null;
        $denomination = (isset($data['denomination']) AND trim($data['denomination']) !== "")?trim($data['denomination']):null;
        $telephone = (isset($data['telephone']) AND trim($data['telephone']) !== "")?trim($data['telephone']):null;
        $email = (isset($data['email']) AND trim($data['email']) !== "")?trim($data['email']):null;

        $structuresIdOld = $this->getStructureService()->getStructureIdsHavingContact($object);
        $structuresIdNew = (isset($data['structures']))?$data['structures']:[];

        $structures = [];
        foreach ($structuresIdOld as $structureId) {
            if (!in_array($structureId, $structuresIdNew)) {
                $structure = $this->getStructureService()->getStructure($structureId);
                $structure->removeContact($object);
                $structures[] = $structure;
            }
        }
        foreach ($structuresIdNew as $structureId) {
            if (!in_array($structureId, $structuresIdOld)) {
                $structure = $this->getStructureService()->getStructure($structureId);
                $structure->addContact($object);
                $structures[] = $structure;
            }
        }


        $object->setType($type);
        $object->setService($service);
        $object->setDenomination($denomination);
        $object->setTelephone($telephone);
        $object->setEmail($email);
        $object->setTmp($structures);

        return $object;
    }
}