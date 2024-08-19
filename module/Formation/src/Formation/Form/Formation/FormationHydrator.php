<?php

namespace Formation\Form\Formation;

use Formation\Entity\Db\Formation;
use Formation\Service\ActionType\ActionTypeServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class FormationHydrator implements HydratorInterface
{
    use ActionTypeServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;

    public function extract($object): array
    {
        /** @var Formation $object */
        $data = [
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
            'lien' => $object->getLien(),
            'groupe' => ($object->getGroupe()) ? $object->getGroupe()->getId() : null,
            'affichage' => $object->getAffichage(),
            'rattachement' => $object->getRattachement(),
            'type' => $object->getType(),
            'actiontype' => ($object->getActionType()) ? $object->getActionType()->getId() : null,
            'objectifs' => $object->getObjectifs(),
            'programme' => $object->getProgramme(),
            'prerequis' => $object->getPrerequis(),
            'public' => $object->getPublic(),
            'complement' => $object->getComplement(),
        ];

        return $data;
    }

    public function hydrate(array $data,object $object) : object
    {
        $groupe = (isset($data['groupe']) && $data['groupe'] !== "") ? $this->getFormationGroupeService()->getFormationGroupe($data['groupe']) : null;
        $description = (isset($data['description']) && trim($data['description']) != '')?trim($data['description']):null;
        $affichage = !((isset($data['affichage']) and $data['affichage'] === '0'));
        $rattachement = $data['rattachement'] ?? null;
        $type = $data['type'] ?? null;
        $actiontype = (isset($data['actiontype']) AND $data['actiontype'] !== '')? $this->getActionTypeService()->getActionType($data['actiontype']) : null;
        $objectifs = $data['objectifs'] ?? null;
        $programme = $data['programme'] ?? null;
        $prerequis = $data['prerequis'] ?? null;
        $public = $data['public'] ?? null;
        $complement = $data['complement'] ?? null;

        /** @var Formation $object */
        $object->setLibelle($data['libelle']);
        $object->setDescription($description);
        $object->setLien($data['lien']);
        $object->setGroupe($groupe);
        $object->setAffichage($affichage);
        $object->setRattachement($rattachement);
        $object->setActionType($actiontype);
        $object->setType($type);
        $object->setObjectifs($objectifs);
        $object->setProgramme($programme);
        $object->setPrerequis($prerequis);
        $object->setPublic($public);
        $object->setComplement($complement);
        return $object;
    }


}