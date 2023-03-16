<?php

namespace Metier\Form\Reference;

use Metier\Entity\Db\Reference;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Metier\Service\Referentiel\ReferentielServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class ReferenceHydrator implements HydratorInterface {
    use MetierServiceAwareTrait;
    use ReferentielServiceAwareTrait;

    /**
     * @param Reference $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'metier' => ($object->getMetier())?$object->getMetier()->getId():null,
            'referentiel' => ($object->getReferentiel())?$object->getReferentiel()->getId():null,
            'code' => $object->getCode(),
            'lien' => $object->getLien(),
            'page' => $object->getPage(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Reference $object
     * @return Reference
     */
    public function hydrate(array $data, $object) : object
    {
        $metier = (isset($data['metier']))?$this->getMetierService()->getMetier($data['metier']):null;
        $referentiel = (isset($data['referentiel']))?$this->getReferentielService()->getReferentiel($data['referentiel']):null;
        $code = (isset($data['code']) AND trim($data['code']) !== "")?$data['code']:null;
        $lien = (isset($data['lien']) AND trim($data['lien']) !== "")?$data['lien']:null;
        $page = (isset($data['page']) AND trim($data['page']) !== "")?$data['page']:null;

        $object->setMetier($metier);
        $object->setReferentiel($referentiel);
        $object->setCode($code);
        $object->setLien($lien);
        $object->setPage($page);

        return $object;
    }


}