<?php

namespace Formation\Form\SessionParametre;

use Formation\Entity\Db\SessionParametre;
use Laminas\Hydrator\HydratorInterface;

class SessionParametreHydrator implements HydratorInterface {

    /**
     * @param SessionParametre $object
     * @return array
     */
    public function extract(object $object): array
    {
        $data = [
            'mail' => $object->isMailActive(),
            'evenement' => $object->isEvenementActive(),
            'enquete' => $object->isEnqueteActive(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param SessionParametre $object
     * @return SessionParametre
     */
    public function hydrate(array $data, object $object): object
    {
        $isMail = $data['mail'] ?? false;
        $isEvenement = $data['evenement'] ?? false;
        $isEnquete = $data['enquete'] ?? false;

        $object->setMail($isMail);
        $object->setEvenement($isEvenement);
        $object->setEnquete($isEnquete);

        return $object;
    }

}