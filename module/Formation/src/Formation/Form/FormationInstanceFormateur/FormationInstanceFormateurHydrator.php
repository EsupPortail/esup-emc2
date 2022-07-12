<?php

namespace Formation\Form\FormationInstanceFormateur;

use Formation\Entity\Db\FormationInstanceFormateur;
use Zend\Hydrator\HydratorInterface;

class FormationInstanceFormateurHydrator implements HydratorInterface
{

    /**
     * @param FormationInstanceFormateur $object
     * @return array
     */
    public function extract($object)
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
     * @param FormationInstanceFormateur $object
     * @return FormationInstanceFormateur
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