<?php

namespace Formation\Form\SelectionFormateur;

use Formation\Entity\Db\Interfaces\HasFormationCollectionInterface;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class SelectionFormateurHydrator implements HydratorInterface
{
    use FormationServiceAwareTrait;

    /**
     * @param HasFormationCollectionInterface $object
     * @return array
     */
    public function extract($object): array
    {
//        $formations = $object->getFormations();
//        $formationIds = array_map(function (Formation $f) { return $f->getId();}, $formations->toArray());
        $data = [
//            'formations' => $formationIds,
        ];
        return $data;
    }

    public function hydrate(array $data, $object): object
    {
//        $formationsInForm = [];
//        if (isset($data['formations'])) {
//            foreach ($data['formations'] as $formationId) {
//                $formationsInForm[$formationId] = $this->getFormationService()->getFormation($formationId);
//            }
//        }
//
//        /** @var HasFormationCollectionInterface $object */
//        foreach ($object->getFormations() as $formation) {
//            if (!in_array($formation, $formationsInForm)) $object->removeFormation($formation);
//        }
//        foreach ($formationsInForm as $formation) {
//            if (!$object->hasFormation($formation)) $object->addFormation($formation);
//        }

        return $object;
    }
}