<?php

namespace Carriere\Form\SpecialiteType;

use Carriere\Entity\Db\CorrespondanceType;
use DateTime;
use Laminas\Hydrator\HydratorInterface;

class SpecialiteTypeHydrator implements HydratorInterface {

    public function extract(object $object) : array
    {
        /** @var CorrespondanceType $object */
        $data = [
            'code' => $object->getCode(),
            'libelle_court' => $object->getLibelleCourt(),
            'libelle_long' => $object->getLibelleLong(),
            'description' => $object->getDescription(),
            'date_ouverture' => $object->getDateOuverture(),
            'date_fermeture' => $object->getDateFermeture(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $code = (isset($data['code']) AND trim($data['code']) !== '') ? trim($data['code']) : null;
        $libelleCourt = (isset($data['libelle_court']) AND trim($data['libelle_court']) !== '') ? trim($data['libelle_court']) : null;
        $libelleLong = (isset($data['libelle_long']) AND trim($data['libelle_long']) !== '') ? trim($data['libelle_long']) : null;
        $description = (isset($data['description']) AND trim($data['description']) !== '') ? trim($data['description']) : null;

        $dateOuverture = (isset($data['date_ouverture']) AND trim($data['date_ouverture']) !== "")?trim($data['date_ouverture']):null;
        $dateOuverture = DateTime::createFromFormat('Y-m-d H:m:i', $dateOuverture. " 0:00:01");
        if ($dateOuverture === false) $dateOuverture = null;
        $dateFermeture = (isset($data['date_fermeture']) AND trim($data['date_fermeture']) !== "")?trim($data['date_fermeture']):null;
        $dateFermeture = DateTime::createFromFormat('Y-m-d H:m:i', $dateFermeture. " 23:59:59");
        if ($dateFermeture === false) $dateFermeture = null;

        /** @var CorrespondanceType $object */
        $object->setCode($code);
        $object->setLibelleCourt($libelleCourt);
        $object->setLibelleLong($libelleLong);
        $object->setDescription($description);
        $object->setDateOuverture($dateOuverture);
        $object->setDateFermeture($dateFermeture);

        return $object;
    }

}
