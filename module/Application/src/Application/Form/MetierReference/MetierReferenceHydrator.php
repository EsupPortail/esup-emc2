<?php

namespace Application\Form\MetierReference;

use Application\Entity\Db\MetierReference;
use Application\Service\Metier\MetierServiceAwareTrait;
use Application\Service\MetierReferentiel\MetierReferentielServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class MetierReferenceHydrator implements HydratorInterface {
    use MetierServiceAwareTrait;
    use MetierReferentielServiceAwareTrait;

    /**
     * @param MetierReference $object
     * @return array
     */
    public function extract($object)
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
     * @param MetierReference $object
     * @return MetierReference
     */
    public function hydrate(array $data, $object)
    {
        $metier = (isset($data['metier']))?$this->getMetierService()->getMetier($data['metier']):null;
        $referentiel = (isset($data['referentiel']))?$this->getMetierReferentielService()->getMetierReferentiel($data['referentiel']):null;
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