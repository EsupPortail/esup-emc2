<?php

namespace Formation\Form\Formation;

use Formation\Entity\Db\Formation;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Formation\Service\FormationTheme\FormationThemeServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class FormationHydrator implements HydratorInterface {
    use FormationThemeServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;

    /**
     * @var Formation $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
            'lien' => $object->getLien(),
            'groupe' => ($object->getGroupe())?$object->getGroupe()->getId():null,
            'theme' => ($object->getTheme())?$object->getTheme()->getId():null,
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
        $groupe = (isset($data['groupe']) && $data['groupe'] !== "" ) ? $this->getFormationGroupeService()->getFormationGroupe($data['groupe']) : null;
        $theme = (isset($data['theme']) && $data['theme'] !== "" ) ? $this->getFormationThemeService()->getFormationTheme($data['theme']) : null;
        $object->setLibelle($data['libelle']);
        $object->setDescription($data['description']);
        $object->setLien($data['lien']);
        $object->setTheme($theme);
        $object->setGroupe($groupe);
        return $object;
    }


}