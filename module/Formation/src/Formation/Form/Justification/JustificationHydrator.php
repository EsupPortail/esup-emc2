<?php

namespace Formation\Form\Justification;

use Formation\Entity\Db\DemandeExterne;
use Formation\Entity\Db\Inscription;
use Formation\Provider\Etat\DemandeExterneEtats;
use Formation\Provider\Etat\InscriptionEtats;
use Laminas\Hydrator\HydratorInterface;

class JustificationHydrator implements HydratorInterface
{
    public function extract(object $object): array
    {
        /** @var Inscription|DemandeExterne $object */
        $description = null;
        $etattype = ($object->getEtatActif())?$object->getEtatActif()->getType()->getCode():null;
        switch ($etattype) {
            case null :
                $description = null;
                break;
            case InscriptionEtats::ETAT_DEMANDE :
                $description = $object->getJustificationResponsable();
                break;
            case InscriptionEtats::ETAT_VALIDER_RESPONSABLE :
            case DemandeExterneEtats::ETAT_VALIDATION_RESP :
                $description = $object->getJustificationDrh();
                break;
            case InscriptionEtats::ETAT_VALIDER_DRH :
//            case DemandeExterneEtats::ETAT_VALIDATION_DRH :
                $description = $object->getJustificationDrh();
                break;
            case InscriptionEtats::ETAT_REFUSER :
            case DemandeExterneEtats::ETAT_REJETEE :
                $description = $object->getJustificationRefus();
                break;
        }

        $data = [
            'missions' => $object->getMissions()??null,
            'justification' => $description,
            'rqth' => $object->isRqth(),
            'precision_rqth' => $object->getPrecisionRqth(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $etape = $data['etape'];
        $missions = (isset($data['missions']) AND trim($data['missions']) !== '')?trim($data['missions']):null;
        $justification = (isset($data['justification']) AND trim($data['justification']) !== '')?trim($data['justification']):null;
        $rqth = $data['rqth'];
        $precisionRqth = (isset($data['precision_rqth']) AND trim($data['precision_rqth']) !== '')?trim($data['precision_rqth']):null;

        switch ($etape) {
            case 'AGENT' : $object->setJustificationAgent($justification); break;
            case 'RESPONSABLE' : $object->setJustificationResponsable($justification); break;
            case 'GESTIONNAIRE' : $object->setJustificationGestionnaire($justification); break;
            case 'DRH' : $object->setJustificationDrh($justification); break;
            case 'REFUS' : $object->setJustificationRefus($justification); break;
        }

//        /** @var DemandeExterne|Inscription $object */
//        $etattype = ($object->getEtatActif())?$object->getEtatActif()->getType()->getCode():null;
//        switch ($etattype) {
//            case null :
//            case InscriptionEtats::ETAT_DEMANDE :
//                $object->setJustificationAgent($justification);
//                break;
//            case InscriptionEtats::ETAT_VALIDER_RESPONSABLE :
//            case DemandeExterneEtats::ETAT_VALIDATION_RESP :
//                $object->setJustificationResponsable($justification);
//                break;
//            case InscriptionEtats::ETAT_VALIDER_DRH :
////            case DemandeExterneEtats::ETAT_VALIDATION_DRH :
//                $object->setJustificationDrh($justification);
//                break;
//            case InscriptionEtats::ETAT_REFUSER :
//            case DemandeExterneEtats::ETAT_REJETEE :
//                $object->setJustificationRefus($justification);
//                break;
//        }
        $object->setMissions($missions);
        $object->setRqth($rqth);
        $object->setPrecisionRqth($precisionRqth);

        return $object;
    }

}