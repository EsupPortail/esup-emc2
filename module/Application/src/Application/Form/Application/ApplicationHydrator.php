<?php

namespace Application\Form\Application;

use Application\Entity\Db\Application;
use Application\Service\Formation\FormationServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class ApplicationHydrator implements HydratorInterface {
    use FormationServiceAwareTrait;

    /**
     * @param Application $object
     * @return array
     */
    public function extract($object)
    {
        $formationIds = [];
        foreach ($object->getFormations() as $formation) $formationIds[] = $formation->getId();

        $data = [
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
            'url' => $object->getUrl(),
            'formations' => $formationIds,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Application $object
     * @return Application
     */
    public function hydrate(array $data, $object)
    {
        $formations = [];
        foreach($data['formations'] as $id) {
            $formations[] = $this->getFormationService()->getFormation($id);
        }

        $object->setLibelle($data['libelle']);
        if ($data['description'] === null || $data['description'] === '') {
            $object->setDescription(null);
        } else {
            $object->setDescription($data['description']);
        }
        if ($data['url'] === null || $data['url'] === '') {
            $object->setUrl(null);
        } else {
            $object->setUrl($data['url']);
        }

        foreach ($object->getFormations() as $formation) $object->removeFormation($formation);
        foreach ($formations as $formation) $object->addFormation($formation);

        return $object;
    }

}