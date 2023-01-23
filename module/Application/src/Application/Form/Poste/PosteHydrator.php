<?php

namespace Application\Form\Poste;

use Application\Entity\Db\Poste;
use Laminas\Hydrator\HydratorInterface;

class PosteHydrator implements HydratorInterface {

    /**
     * @param Poste $object
     * @return array
     */
    public function extract($object): array
    {

        $data = [
            'referentiel' => $object?$object->getReferentiel():null,
            'intitule' => $object?$object->getIntitule():null,
            'poste_id' => $object?$object->getPosteId():null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Poste $object
     * @return Poste
     */
    public function hydrate(array $data, $object) : object
    {
        $refentiel = (isset($data['referentiel']) AND trim($data['referentiel']) !== "")?trim($data['referentiel']):null;
        $intitule = (isset($data['intitule']) AND trim($data['intitule']) !== "")?trim($data['intitule']):null;
        $posteId = (isset($data['poste_id']) AND trim($data['poste_id']) !== "")?trim($data['poste_id']):null;

        $object->setReferentiel($refentiel);
        $object->setIntitule($intitule);
        $object->setPosteId($posteId);

        return $object;
    }

}
