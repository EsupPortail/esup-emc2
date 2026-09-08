<?php

namespace Carriere\Form\Specialite;

use Carriere\Entity\Db\Correspondance;
use Carriere\Entity\Db\CorrespondanceType;
use Carriere\Service\CorrespondanceType\CorrespondanceTypeServiceAwareTrait;
use DateTime;
use Laminas\Hydrator\HydratorInterface;

class SpecialiteHydrator implements HydratorInterface {

    use CorrespondanceTypeServiceAwareTrait;

    public function extract(object $object) : array
    {
        /** @var Correspondance $object */
        $data = [
            'type' => $object->getType()?->getId(),
            'code' => $object->getCategorie(),
            'libelle_court' => $object->getLibelleCourt(),
            'libelle_long' => $object->getLibelleLong(),
            'date_ouverture' => $object->getDateDebut(),
            'date_fermeture' => $object->getDateFin(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $code = (isset($data['code']) AND trim($data['code']) !== '') ? trim($data['code']) : null;
        $libelleCourt = (isset($data['libelle_court']) AND trim($data['libelle_court']) !== '') ? trim($data['libelle_court']) : null;
        $libelleLong = (isset($data['libelle_long']) AND trim($data['libelle_long']) !== '') ? trim($data['libelle_long']) : null;
        $type = (isset($data['type']) AND trim($data['type']) !== '') ? $this->getCorrespondanceTypeService()->getCorrespondanceType(trim($data['type'])) : null;

        $dateOuverture = (isset($data['date_ouverture']) AND trim($data['date_ouverture']) !== "")?trim($data['date_ouverture']):null;
        $dateOuverture = DateTime::createFromFormat('Y-m-d H:m:i', $dateOuverture. " 0:00:01");
        if ($dateOuverture === false) $dateOuverture = null;
        $dateFermeture = (isset($data['date_fermeture']) AND trim($data['date_fermeture']) !== "")?trim($data['date_fermeture']):null;
        $dateFermeture = DateTime::createFromFormat('Y-m-d H:m:i', $dateFermeture. " 23:59:59");
        if ($dateFermeture === false) $dateFermeture = null;

        /** @var Correspondance $object */
        $object->setType($type);
        $object->setCategorie($code);
        $object->setLibelleCourt($libelleCourt);
        $object->setLibelleLong($libelleLong);
        $object->setDateDebut($dateOuverture);
        $object->setDateFin($dateFermeture);

        return $object;
    }

}
