<?php

namespace Formation\Form\FormationInstance;

use Formation\Entity\Db\FormationInstance;
use Zend\Hydrator\HydratorInterface;

class FormationInstanceHydrator implements HydratorInterface
{

    /**
     * @param FormationInstance $object
     * @return array
     */
    public function extract($object) : array
    {
        $data = [
            'description' => ($object AND $object->getComplement()) ?: null,
            'principale' => ($object AND $object->getNbPlacePrincipale()) ?: 0,
            'complementaire' => ($object AND $object->getNbPlaceComplementaire()) ?: 0,
            'lieu' => ($object AND $object->getLieu()) ?: null,
            'type' => ($object AND $object->getType()) ?: null,
            'inscription' => ($object) ? $object->isAutoInscription() : null,
            'cout_ht' => ($object) ? $object->getCoutHt() : null,
            'cout_ttc' => ($object) ? $object->getCoutTtc() : null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FormationInstance $object
     * @return FormationInstance
     */
    public function hydrate(array $data, $object)
    {
        $description = (isset($data['description']) and trim($data['description']) !== "") ? trim($data['description']) : null;
        $principale = (isset($data['principale'])) ? ((int)$data['principale']) : 0;
        $complementaire = (isset($data['complementaire'])) ? ((int)$data['complementaire']) : 0;
        $lieu = (isset($data['lieu']) and trim($data['lieu']) !== "") ? trim($data['lieu']) : null;
        $type = (isset($data['type']) and trim($data['type']) !== "") ? trim($data['type']) : null;
        $inscription = (isset($data['inscription']))?$data['inscription'] : false;
        $coutHt = (isset($data['cout_ht']) and trim($data['cout_ht']) !== "") ? trim($data['cout_ht']) : null;
        $coutTtc = (isset($data['cout_ttc']) and trim($data['cout_ttc']) !== "") ? trim($data['cout_ttc']) : null;

        $object->setComplement($description);
        $object->setNbPlacePrincipale($principale);
        $object->setNbPlaceComplementaire($complementaire);
        $object->setLieu($lieu);
        $object->setType($type);
        $object->setAutoInscription($inscription);
        $object->setCoutHt($coutHt);
        $object->setCoutTtc($coutTtc);

        return $object;
    }


}