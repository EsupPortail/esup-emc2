<?php

namespace Formation\Form\SessionParametre;

use Formation\Entity\Db\SessionParametre;
use Laminas\Hydrator\HydratorInterface;

class SessionParametreHydrator implements HydratorInterface
{

    public function extract(object $object): array
    {
        /** @var SessionParametre $object */
        $data = [
            'mail' => $object->isMailActive(),
            'evenement' => $object->isEvenementActive(),
            'enquete' => $object->isEnqueteActive(),
            'emargement' => $object->isEmargementActive(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $isMail = $data['mail'] ?? false;
        $isEvenement = $data['evenement'] ?? false;
        $isEnquete = $data['enquete'] ?? false;
        $isEmargement = $data['emargement'] ?? false;

        /** @var SessionParametre $object */
        $object->setMail($isMail);
        $object->setEvenement($isEvenement);
        $object->setEnquete($isEnquete);
        $object->setEmargement($isEmargement);

        return $object;
    }

}