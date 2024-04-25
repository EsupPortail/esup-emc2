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
        $userId=(isset($data['user']) AND isset($data['user']['id']) AND $data['user']['id'] !== '')?$data['user']['id']:null;
        $user  = null;
        if ($userId) {
            $splits = explode("||", $userId);
            if ($splits[0] === 'app') {
                $user = $this->getUserService()->getRepo()->find($splits[1]);
            } else {
                $user = $this->getUserService()->getRepo()->find($splits[0]);
            }
        }
        $description = (isset($data['description']) AND trim($data['description']) !== '')?trim($data['description']):null;

        /** @var Observateur $object */
        $object->setUser($user);
        $object->setDescription($description);
        return $object;
    }

}