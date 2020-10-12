<?php

namespace Application\Form\FormationInstanceFormateur;

use Application\Entity\Db\FormationInstanceFormateur;
use Zend\Hydrator\HydratorInterface;

class FormationInstanceFormateurHydrator implements HydratorInterface {

    /**
     * @param FormationInstanceFormateur $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'prenom'    => ($object)?$object->getPrenom():null,
            'nom'       => ($object)?$object->getNom():null,
            'volume'    => ($object)?$object->getVolume():null,
            'montant'   => ($object)?$object->getMontant():null,
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
        $prenom     = (isset($data['prenom']) AND trim($data['prenom']) !== "")?trim($data['prenom']):null;
        $nom        = (isset($data['nom']) AND trim($data['nom']) !== "")?trim($data['nom']):null;
        $volume     = (isset($data['volume']) AND trim($data['volume']) !== "")?trim($data['volume']):null;
        $montant    = (isset($data['montant']) AND trim($data['montant']) !== "")?trim($data['montant']):null;

        $object->setPrenom($prenom);
        $object->setNom($nom);
        $object->setVolume($volume);
        $object->setMontant($montant);
        return $object;
    }

}