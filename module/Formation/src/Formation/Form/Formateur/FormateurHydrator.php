<?php

namespace Formation\Form\Formateur;

use Formation\Entity\Db\Formateur;
use Laminas\Hydrator\HydratorInterface;

class FormateurHydrator implements HydratorInterface
{

    public function extract($object): array
    {
        /** @var Formateur $object */
        $data = [
            'formateur-type' => ($object) ? $object->getType() : null,

            'organisme' => ($object) ? $object->getOrganisme() : null,

            'prenom' => ($object) ? $object->getPrenom() : null,
            'nom' => ($object) ? $object->getNom() : null,
            'attachement' => ($object) ? $object->getAttachement() : null,

            'email' => ($object) ? $object->getEmail() : null,
            'telephone' => ($object) ? $object->getTelephone() : null,

        ];
        return $data;
    }

    public function hydrate(array $data, $object) : object
    {
        $type = $data['formateur-type'] ?? null;
        $organisme = (isset($data['organisme']) and trim($data['organisme']) !== "") ? trim($data['organisme']) : null;
        $prenom = (isset($data['prenom']) and trim($data['prenom']) !== "") ? trim($data['prenom']) : null;
        $nom = (isset($data['nom']) and trim($data['nom']) !== "") ? trim($data['nom']) : null;
        $attachement = (isset($data['attachement']) and trim($data['attachement']) !== "") ? trim($data['attachement']) : null;
        $email = (isset($data['email']) and trim($data['email']) !== "") ? trim($data['email']) : null;
        $telephone = (isset($data['telephone']) and trim($data['telephone']) !== "") ? trim($data['telephone']) : null;

        /** @var Formateur $object */
        $object->setType($type);
        $object->setOrganisme($organisme);
        $object->setPrenom($prenom);
        $object->setNom($nom);
        $object->setAttachement($attachement);
        $object->setEmail($email);
        $object->setTelephone($telephone);

        return $object;
    }

}