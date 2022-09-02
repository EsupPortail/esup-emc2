<?php

namespace Formation\Form\Formateur;

use Formation\Entity\Db\Formateur;
use Laminas\Hydrator\HydratorInterface;

class FormateurHydrator implements HydratorInterface
{

    /**
     * @param Formateur $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'prenom' => ($object) ? $object->getPrenom() : null,
            'nom' => ($object) ? $object->getNom() : null,
            'email' => ($object) ? $object->getEmail() : null,
            'attachement' => ($object) ? $object->getAttachement() : null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Formateur $object
     * @return Formateur
     */
    public function hydrate(array $data, $object)
    {
        $prenom = (isset($data['prenom']) and trim($data['prenom']) !== "") ? trim($data['prenom']) : null;
        $nom = (isset($data['nom']) and trim($data['nom']) !== "") ? trim($data['nom']) : null;
        $email = (isset($data['email']) and trim($data['email']) !== "") ? trim($data['email']) : null;
        $attachement = (isset($data['attachement']) and trim($data['attachement']) !== "") ? trim($data['attachement']) : null;

        $object->setPrenom($prenom);
        $object->setNom($nom);
        $object->setEmail($email);
        $object->setAttachement($attachement);
        return $object;
    }

}