<?php

namespace Element\Form\Application;

use Element\Entity\Db\Application;
use Element\Service\ApplicationGroupe\ApplicationGroupeServiceAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class ApplicationHydrator implements HydratorInterface {
    use ApplicationGroupeServiceAwareTrait;
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
            'groupe' => ($object->getGroupe())?$object->getGroupe()->getId():null,
            'HasDescription' => [
                'description' => $object->getDescription()
            ],
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
        if (isset($data['formations'])) {
            foreach ($data['formations'] as $id) {
                $formations[] = $this->getFormationService()->getFormation($id);
            }
        }

        $groupe = (isset($data['groupe']) AND trim($data['groupe']) !== "")?$this->getApplicationGroupeService()->getApplicationGroupe($data['groupe']):null;
        $object->setGroupe($groupe);

        $object->setLibelle($data['libelle']);
        if ($data['url'] === null || $data['url'] === '') {
            $object->setUrl(null);
        } else {
            $object->setUrl($data['url']);
        }

        foreach ($object->getFormations() as $formation) $object->removeFormation($formation);
        foreach ($formations as $formation) $object->addFormation($formation);

        $description = (isset($data['HasDescription']) AND isset($data['HasDescription']['description']) && trim($data['HasDescription']['description']) != '')?trim($data['HasDescription']['description']):null;
        $object->setDescription($description);

        return $object;
    }

}