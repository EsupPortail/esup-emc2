<?php

namespace  EntretienProfessionnel\Form\Observateur;

use EntretienProfessionnel\Entity\Db\Observateur;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class ObservateurHydrator implements HydratorInterface
{
    use EntretienProfessionnelServiceAwareTrait;
    use UserServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var Observateur $object */
        $data = [
            'entretien' => [
                'id' => $object->getEntretienProfessionnel()?->getId(),
                'label' => $object->getEntretienProfessionnel()?->prettyPrint(),
            ],
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
        $entretienId=(isset($data['entretien']) AND isset($data['entretien']['id']) AND $data['entretien']['id'] !== '')?$data['entretien']['id']:null;
        $entretien  = $this->getEntretienProfessionnelService()->getEntretienProfessionnel($entretienId);
        $description = (isset($data['description']) AND trim($data['description']) !== '')?trim($data['description']):null;

        /** @var Observateur $object */
        $object->setEntretienProfessionnel($entretien);
        $object->setUser($user);
        $object->setDescription($description);
        return $object;
    }

}