<?php

namespace Formation\Form\Formation;

use Formation\Entity\Db\Formation;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class FormationHydrator implements HydratorInterface
{
    use FormationGroupeServiceAwareTrait;

    /**
     * @return array
     * @var Formation $object
     */
    public function extract($object)
    {
        $data = [
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
            'lien' => $object->getLien(),
            'groupe' => ($object->getGroupe()) ? $object->getGroupe()->getId() : null,
        ];

        return $data;
    }

    /**
     * @param array $data
     * @param Formation $object
     * @return Formation
     */
    public function hydrate(array $data, $object)
    {
        $groupe = (isset($data['groupe']) && $data['groupe'] !== "") ? $this->getFormationGroupeService()->getFormationGroupe($data['groupe']) : null;
        $object->setLibelle($data['libelle']);
        $object->setDescription($data['description']);
        $object->setLien($data['lien']);
        $object->setGroupe($groupe);
        return $object;
    }


}