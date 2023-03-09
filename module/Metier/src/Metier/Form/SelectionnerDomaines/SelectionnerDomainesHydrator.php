<?php

namespace Metier\Form\SelectionnerDomaines;

use Laminas\Hydrator\HydratorInterface;
use Metier\Entity\HasDomainesInterface;
use Metier\Service\Domaine\DomaineServiceAwareTrait;

class SelectionnerDomainesHydrator implements HydratorInterface {
    use DomaineServiceAwareTrait;

    /**
     * @param HasDomainesInterface $object
     * @return array
     */
    public function extract(object $object): array
    {
        $domaineIds = [];
        foreach ($object->getDomaines() as $domaine) $domaineIds[] = $domaine->getId();
        $data = [
            'domaines' => $domaineIds,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param HasDomainesInterface $object
     * @return HasDomainesInterface
     */
    public function hydrate(array $data, object $object) : object
    {
        $domaineIds = $data['domaines'] ?? null;

        foreach ($object->getDomaines() as $domaine) {
            if (!array_search($domaine->getId(), $domaineIds)) $object->removeDomaine($domaine);
        }
        foreach ($domaineIds as $domaineId) {
            $domaine = $this->getDomaineService()->getDomaine($domaineId);
            if (!$object->hasDomaine($domaine)) $object->addDomaine($domaine);
        }

        return $object;
    }


}