<?php

namespace Structure\Form\Observateur;

use Laminas\Hydrator\HydratorInterface;
use Structure\Entity\Db\Observateur;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class ObservateurHydrator implements HydratorInterface
{
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var Observateur $object */
        $data = [
            'structure-sas' => $object->getStructure()?['id' => $object->getStructure()->getId(), 'label' => $object->getStructure()->getLibelleLong()]:null,
            'utilisateur-sas' => $object->getUtilisateur()?['id' => $object->getUtilisateur()->getId(), 'label' => $object->getUtilisateur()->getDisplayName()]:null,
            'description' => $object->getDescription(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $structure = isset($data['structure-sas']['id']) ? $this->getStructureService()->getStructure($data['structure-sas']['id']) : null;
        $utilisateur = isset($data['utilisateur-sas']['id']) ? $this->getUserService()->getRepo()->find($data['utilisateur-sas']['id']) : null;
        $description = (isset($data['description']) AND trim($data["description"]) !== "")?trim($data["description"]):null;

        /** @var Observateur $object */
        $object->setStructure($structure);
        $object->setUtilisateur($utilisateur);
        $object->setDescription($description);
        return $object;
    }

}