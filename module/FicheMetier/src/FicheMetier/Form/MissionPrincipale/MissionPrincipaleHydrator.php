<?php

namespace FicheMetier\Form\MissionPrincipale;

use Carriere\Entity\Db\NiveauEnveloppe;
use Carriere\Service\Niveau\NiveauServiceAwareTrait;
use FicheMetier\Entity\Db\Mission;
use FicheMetier\Service\MissionActivite\MissionActiviteServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;

class MissionPrincipaleHydrator implements HydratorInterface {

    use FamilleProfessionnelleServiceAwareTrait;
    use MissionActiviteServiceAwareTrait;
    use NiveauServiceAwareTrait;


    public function extract(object $object): array
    {
        /** @var Mission $object */
        $data = [
            'libelle' => $object->getLibelle(),
            'activites' => $object->getActivitesAsList(),
            'familleprofessionnelle' => $object->getFamillesProfessionnellesIds(),
            'borne_inferieure' => $object->getNiveau()?->getBorneInferieure()?->getId(),
            'borne_superieure' => $object->getNiveau()?->getBorneSuperieure()?->getId(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) != '') ? trim($data['libelle']) : null;
        $activitesAsList = (isset($data['activites'])) ? $data['activites'] : [];
        $familleProfessionnelleIds = (isset($data['familleprofessionnelle'])) ? $data['familleprofessionnelle'] : [];
        $borneInferieure = (isset($data['borne_inferieure']) AND $data['borne_inferieure'] !== '') ? $this->getNiveauService()->getNiveau($data['borne_inferieure']) : null;
        $borneSuperieure = (isset($data['borne_superieure']) AND $data['borne_superieure'] !== '') ? $this->getNiveauService()->getNiveau($data['borne_superieure']) : null;


        /** @var Mission $object **/
        //traitement des activites
        $missions = $this->getMissionActiviteService()->transforms($object, $activitesAsList);
        $object->clearActivites();
        foreach ($missions as $missionActivite) {
            $object->addMissionActivite($missionActivite);
        }
        //traitement des familles professionnelles
        $object->clearFamillesProfessionnelles();
        foreach ($familleProfessionnelleIds as $familleProfessionnelleId) {
            $famille = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelle($familleProfessionnelleId);
            if ($famille) $object->addFamilleProfessionnelle($famille);
        }
        //traitement de l'enveloppe de niveau
        if ($borneInferieure AND $borneSuperieure) {
            if ($object->getNiveau()) {
                $object->getNiveau()->setBorneInferieure($borneInferieure);
                $object->getNiveau()->setBorneSuperieure($borneSuperieure);
            } else {
                $niveau = new NiveauEnveloppe();
                $niveau->setBorneInferieure($borneInferieure);
                $niveau->setBorneSuperieure($borneSuperieure);
                $object->setNiveau($niveau);
            }
        }

        $object->setLibelle($libelle);
        return $object;
    }
}
