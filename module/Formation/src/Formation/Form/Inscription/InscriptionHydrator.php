<?php

namespace Formation\Form\Inscription;

use Formation\Entity\Db\FormationInstanceInscrit;
use Formation\Provider\Etat\InscriptionEtats;
use Laminas\Hydrator\HydratorInterface;

class InscriptionHydrator implements HydratorInterface
{
    /**
     * @param FormationInstanceInscrit $object
     * @return array
     */
    public function extract(object $object): array
    {
        $description = null;
        switch ($object->getEtat()->getCode()) {
            case InscriptionEtats::ETAT_DEMANDE :
                $description = $object->getJustificationAgent();
                break;
            case InscriptionEtats::ETAT_VALIDER_RESPONSABLE :
                $description = $object->getJustificationResponsable();
                break;
            case InscriptionEtats::ETAT_REFUSER :
                $description = $object->getJustificationRefus();
                break;
        }

        $data = [
            'description' => $description,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FormationInstanceInscrit $object
     * @return FormationInstanceInscrit
     */
    public function hydrate(array $data, object $object)
    {
        $description = (isset($data['HasDescription']) AND isset($data['HasDescription']['description']) AND trim($data['HasDescription']['description']) !== '')?trim($data['HasDescription']['description']):null;

        switch ($object->getEtat()->getCode()) {
            case InscriptionEtats::ETAT_DEMANDE :
                $object->setJustificationAgent($description);
                break;
            case InscriptionEtats::ETAT_VALIDER_RESPONSABLE :
                $object->setJustificationResponsable($description);
                break;
            case InscriptionEtats::ETAT_REFUSER :
                $object->setJustificationRefus($description);
                break;
        }

        return $object;
    }

}