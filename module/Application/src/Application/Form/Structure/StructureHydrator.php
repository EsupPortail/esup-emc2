<?php

namespace Application\Form\Structure;

use Application\Entity\Db\Structure;
use Application\Service\Structure\StructureServiceAwareTrait;
use DateTime;
use Zend\Stdlib\Hydrator\HydratorInterface;

class StructureHydrator implements HydratorInterface {
    use StructureServiceAwareTrait;

    const date_format = 'd/m/Y';
    /**
     * @param Structure $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle_court'     => $object->getLibelleCourt(),
            'libelle_long'      => $object->getLibelleLong(),
            'sigle'             => $object->getSigle(),
            'type'              => ($object->getType())?$object->getType()->getCode():null,
            'date_ouverture'    => ($object->getDateOuverture())?$object->getDateOuverture()->format(StructureHydrator::date_format):null,
            'date_fermeture'    => ($object->getDateFermeture())?$object->getDateFermeture()->format(StructureHydrator::date_format):null,
            'description'       => $object->getDescription(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Structure $object
     * @return Structure
     */
    public function hydrate(array $data, $object)
    {
        $type = $this->getStructureService()->getStructureTypeByCode($data['type']);
        $dateOuverture = ($data['date_ouverture'] !== "")?DateTime::createFromFormat(StructureHydrator::date_format, $data['date_ouverture']):null;
        $dateFermeture = ($data['date_fermeture'] !== "")?DateTime::createFromFormat(StructureHydrator::date_format, $data['date_fermeture']):null;

        $object->setLibelleCourt($data['libelle_court']);
        $object->setLibelleLong($data['libelle_long']);
        $object->setSigle($data['sigle']);
        $object->setType($type);
        $object->setDateOuverture($dateOuverture);
        $object->setDateFermeture($dateFermeture);
        $object->setDescription($data['description']);
        return $object;
    }

}