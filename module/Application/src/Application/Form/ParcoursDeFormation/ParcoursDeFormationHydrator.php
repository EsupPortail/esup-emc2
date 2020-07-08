<?php

namespace Application\Form\ParcoursDeFormation;

use Application\Entity\Db\ParcoursDeFormation;
use Application\Service\Formation\FormationServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class ParcoursDeFormationHydrator implements HydratorInterface {
    use FormationServiceAwareTrait;

    /**
     * @param ParcoursDeFormation $object
     * @return array
     */
    public function extract($object)
    {
        $formationId = [];
        if ($object->getFormations() !== null) {
            foreach ($object->getFormations() as $formation) {
                $formationId[] = $formation->getId();
            }
        }

        $data = [
            'type'        => $object->getType(),
            'libelle'     => $object->getLibelle(),
            'description' => $object->getDescription(),
            'formations'  => $formationId,
        ];

        if ($object->getType() === ParcoursDeFormation::TYPE_CATEGORIE) $data['categorie'] = $object->getReference();
        if ($object->getType() === ParcoursDeFormation::TYPE_METIER) $data['metier'] = $object->getReference();

        return $data;
    }

    /**
     * @param array $data
     * @param ParcoursDeFormation $object
     * @return ParcoursDeFormation
     */
    public function hydrate(array $data, $object)
    {
        $libelle = (isset($data['libelle']) AND trim($data['libelle'])!=='')?$data['libelle']:null;
        $description = (isset($data['description']) AND trim($data['description'])!=='')?$data['description']:null;
        $type = (isset($data['type']))?$data['type']:null;

        $formations = [];
        if (isset($data['formations'])) {
            foreach ($data['formations'] as $formationId) {
                $formation = $this->getFormationService()->getFormation($formationId);
                if ($formation !== null) $formations[] = $formation;
            }
        }

        $reference = null;
        if ($type ===  ParcoursDeFormation::TYPE_CATEGORIE) $reference = $data['categorie'];
        if ($type ===  ParcoursDeFormation::TYPE_METIER) $reference = $data['metier'];

        $object->setLibelle($libelle);
        $object->setType($type);
        $object->setReference($reference);
        $object->setDescription($description);
        $object->setFormations($formations);

        return $object;
    }
}