<?php

namespace  EntretienProfessionnel\Form\Observateur;

use EntretienProfessionnel\Entity\Db\Observateur;
use Laminas\Hydrator\HydratorInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class ObservateurHydrator implements HydratorInterface
{
    use UserServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var Observateur $object */
        $data = [
            'user' => [
                'id' => $object->getUser()?->getId(),
                'label' => $object->getUser()?->getDisplayName(),
            ],
            'description' => $object->getDescription(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $user =(isset($data['user']) AND isset($data['user']['id']) AND $data['description']['id'] !== '')?$this->getUserService()->getRepo()->find($data['description']['id']):null;
        $description = (isset($data['description']) AND trim($data['description']) !== '')?trim($data['description']):null;

        /** @var Observateur $object */
        $object->setUser($user);
        $object->setDescription($description);
        return $object;
    }

}