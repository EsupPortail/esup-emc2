<?php

namespace Formation\Form\Formation;

use Formation\Entity\Db\Formation;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class FormationHydrator implements HydratorInterface
{
    use FormationGroupeServiceAwareTrait;

    /**
     * @return array
     * @var Formation $object
     */
    public function extract($object): array
    {
        $data = [
            'libelle' => $object->getLibelle(),
            'HasDescription' => ['description' => $object->getDescription()],
            'lien' => $object->getLien(),
            'groupe' => ($object->getGroupe()) ? $object->getGroupe()->getId() : null,
            'affichage' => $object->getAffichage(),
            'rattachement' => $object->getRattachement(),
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
        $description = (isset($data['HasDescription']) AND isset($data['HasDescription']['description']) && trim($data['HasDescription']['description']) != '')?trim($data['HasDescription']['description']):null;
        $affichage = !((isset($data['affichage']) and $data['affichage'] === '0'));
        $rattachement = $data['rattachement'] ?? null;

        $object->setLibelle($data['libelle']);
        $object->setDescription($description);
        $object->setLien($data['lien']);
        $object->setGroupe($groupe);
        $object->setAffichage($affichage);
        $object->setRattachement($rattachement);
        return $object;
    }


}