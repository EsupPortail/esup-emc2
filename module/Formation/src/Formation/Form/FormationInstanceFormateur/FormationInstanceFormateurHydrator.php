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
            'attachement' => ($object) ? $object->getAttachement() : null,
            'volume' => ($object) ? $object->getVolume() : null,
            'montant' => ($object) ? $object->getMontant() : null,
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
        $attachement = (isset($data['attachement']) and trim($data['attachement']) !== "") ? trim($data['attachement']) : null;
        $volume = (isset($data['volume']) and trim($data['volume']) !== "") ? trim($data['volume']) : null;
        $montant = (isset($data['montant']) and trim($data['montant']) !== "") ? trim($data['montant']) : null;

        $object->setPrenom($prenom);
        $object->setNom($nom);
        $object->setAttachement($attachement);
        $object->setVolume($volume);
        $object->setMontant($montant);
        return $object;
    }

}