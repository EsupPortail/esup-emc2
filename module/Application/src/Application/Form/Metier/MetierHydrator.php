<?php

namespace Application\Form\Metier;

use Application\Entity\Db\Metier;
use Application\Service\Domaine\DomaineServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class MetierHydrator implements HydratorInterface {
    use DomaineServiceAwareTrait;

    /**
     * @param Metier $object
     * @return array
     */
    public function extract($object)
    {
        $domaineIds = [];
        $domaines = $object->getDomaines();
        if ($domaines) {
            foreach ($domaines as $domaine) $domaineIds[] = $domaine->getId();
        }

        $data = [
            'domaines' => $domaineIds,
            'fonction' => $object->getFonction(),
            'libelle' => $object->getLibelle(),
            'expertise' => ($object->hasExpertise()),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Metier $object
     * @return Metier
     */
    public function hydrate(array $data, $object)
    {
        $domaine = $this->getDomaineService()->getDomaine($data['domaine']);

        $object->clearDomaines();
        foreach ($data['domaines'] as $id) {
            $domaine = $this->getDomaineService()->getDomaine($id);
            if ($domaine) $object->addDomaine($domaine);
        }

        $object->setLibelle($data['libelle']);
        $object->setFonction($data['fonction']);
        $object->setExpertise($data['expertise']);

        return $object;
    }

}