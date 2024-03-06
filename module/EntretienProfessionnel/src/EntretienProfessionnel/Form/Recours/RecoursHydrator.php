<?php

namespace EntretienProfessionnel\Form\Recours;

use EntretienProfessionnel\Entity\Db\Recours;
use Laminas\Hydrator\HydratorInterface;

class RecoursHydrator implements HydratorInterface {

    public function extract(object $object): array
    {
        /** @var Recours $object */
        $data = [
            'date_procedure' => ($object->getDateProcedure())?$object->getDateProcedure()->format('Y-m-d'):null,
            'commentaire' => $object->getCommentaire(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $dateProcedure = (isset($data['date_procedure']) AND trim($data['date_procedure']) !== "")?trim($data['date_procedure']):null;
        $commentaire = (isset($data['commentaire']) AND trim($data($data['commentaire'])) !== "")?trim($data['commentaire']):null;

        /** @var Recours $object */
        $object->setDateProcedure($dateProcedure);
        $object->setCommentaire($commentaire);
        return $object;
    }
}