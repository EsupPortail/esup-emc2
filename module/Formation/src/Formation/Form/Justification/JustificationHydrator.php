<?php

namespace Formation\Form\Justification;

use Formation\Entity\Db\DemandeExterne;
use Formation\Entity\Db\Inscription;
use Formation\Provider\Etat\DemandeExterneEtats;
use Formation\Provider\Etat\InscriptionEtats;
use Laminas\Hydrator\HydratorInterface;

class JustificationHydrator implements HydratorInterface
{
    /**
     * @param $object
     * @return array
     */
    public function extract(object $object): array
    {
        $description = null;
        $etattype = ($object->getEtatActif())?$object->getEtatActif()->getType()->getCode():null;
        switch ($etattype) {
            case null :
            case InscriptionEtats::ETAT_DEMANDE :
                $description = $object->getJustificationAgent();
                break;
            case InscriptionEtats::ETAT_VALIDER_RESPONSABLE :
            case DemandeExterneEtats::ETAT_VALIDATION_RESP :
                $description = $object->getJustificationResponsable();
                break;
            case InscriptionEtats::ETAT_REFUSER :
            case DemandeExterneEtats::ETAT_REJETEE :
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
     * @param $object
     * @return Inscription
     */
    public function hydrate(array $data, object $object): object
    {
        $description = (isset($data['HasDescription']) AND isset($data['HasDescription']['description']) AND trim($data['HasDescription']['description']) !== '')?trim($data['HasDescription']['description']):null;

        $etattype = ($object->getEtatActif())?$object->getEtatActif()->getType()->getCode():null;
        switch ($etattype) {
            case null :
            case InscriptionEtats::ETAT_DEMANDE :
                //note deplacer dans le controller : malin ?
//                $object->setJustificationAgent($description);
                break;
            case InscriptionEtats::ETAT_VALIDER_RESPONSABLE :
            case DemandeExterneEtats::ETAT_VALIDATION_RESP :
                //note deplacer dans le controller : malin ?
//                $object->setJustificationResponsable($description);
                break;
            case InscriptionEtats::ETAT_REFUSER :
            case DemandeExterneEtats::ETAT_REJETEE :
                $object->setJustificationRefus($description);
                break;
        }

        return $object;
    }

}