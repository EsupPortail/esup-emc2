<?php

namespace FicheMetier\Form\MissionPrincipale;

use Carriere\Entity\Db\NiveauEnveloppe;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Carriere\Service\Niveau\NiveauServiceAwareTrait;
use FicheMetier\Entity\Db\Mission;
use Laminas\Hydrator\HydratorInterface;
use Referentiel\Service\Referentiel\ReferentielServiceAwareTrait;

class MissionPrincipaleHydrator implements HydratorInterface
{

    use FamilleProfessionnelleServiceAwareTrait;
    use NiveauServiceAwareTrait;
    use ReferentielServiceAwareTrait;


    public function extract(object $object): array
    {
        /** @var Mission $object */
        $data = [
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
            'familleprofessionnelle' => $object->getFamillesProfessionnellesIds(),
            'borne_inferieure' => $object->getNiveau()?->getBorneInferieure()?->getId(),
            'borne_superieure' => $object->getNiveau()?->getBorneSuperieure()?->getId(),
            'referentiel' => $object->getReferentiel()?->getId(),
            'identifiant' => $object->getReference(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $libelle = (isset($data['libelle']) and trim($data['libelle']) != '') ? trim($data['libelle']) : null;
        $description = (isset($data['description']) and trim($data['description']) != '') ? trim($data['description']) : null;
        $familleProfessionnelleIds = (isset($data['familleprofessionnelle'])) ? $data['familleprofessionnelle'] : [];
        $borneInferieure = (isset($data['borne_inferieure']) and $data['borne_inferieure'] !== '') ? $this->getNiveauService()->getNiveau($data['borne_inferieure']) : null;
        $borneSuperieure = (isset($data['borne_superieure']) and $data['borne_superieure'] !== '') ? $this->getNiveauService()->getNiveau($data['borne_superieure']) : null;
        $referentiel = (isset($data['referentiel'])) ? $this->getReferentielService()->getReferentiel($data['referentiel']):null;
        $identifiant = (isset($data['identifiant']) AND trim($data['identifiant']) != '') ? trim($data['identifiant']) : null;


        /** @var Mission $object * */
        //traitement des familles professionnelles
        $object->clearFamillesProfessionnelles();
        foreach ($familleProfessionnelleIds as $familleProfessionnelleId) {
            $famille = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelle($familleProfessionnelleId);
            if ($famille) $object->addFamilleProfessionnelle($famille);
        }
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

        $object->setReferentiel($referentiel);
        $object->setReference($identifiant);
        $object->setLibelle($libelle);
        $object->setDescription($description);
        return $object;
    }
}
