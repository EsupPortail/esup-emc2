<?php

namespace Formation\Form\SelectionGestionnaire;

use Formation\Entity\Db\FormationInstance;
use Laminas\Hydrator\HydratorInterface;
use UnicaenUtilisateur\Entity\Db\AbstractUser;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class SelectionGestionnaireHydrator implements HydratorInterface
{
    use UserServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var FormationInstance $object */
        $gestionnaires = $object->getGestionnaires();
        $gestionnaireIds = array_map(function (AbstractUser $f) {
            return $f->getId();
        }, $gestionnaires);
        $data = [
            'gestionnaires' => $gestionnaireIds,
        ];
        return $data;
    }

    public function hydrate(array $data, $object): object
    {
        /** @var FormationInstance $object */
        $gestionnaires = [];
        if (isset($data['gestionnaires'])) {
            foreach ($data['gestionnaires'] as $gestionnaireId) {
                $gestionnaire = $this->getUserService()->getRepo()->find($gestionnaireId);
                $gestionnaires[$gestionnaireId] = $gestionnaire;
            }
        }

        foreach ($object->getGestionnaires() as $gestionnaire) {
            if (!in_array($gestionnaire, $gestionnaires)) {
                $object->removeGestionnaire($gestionnaire);
            }
        }
        foreach ($gestionnaires as $gestionnaire) {
            if (!$object->hasGestionnaire($gestionnaire)) {
                $object->addGestionnaire($gestionnaire);
            }
        }

        return $object;
    }
}