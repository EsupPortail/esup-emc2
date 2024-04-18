<?php

namespace Formation\Form\DemandeExterne;

use DateTime;
use Formation\Entity\Db\DemandeExterne;
use Laminas\Hydrator\HydratorInterface;

class DemandeExterneHydrator implements HydratorInterface {

    public function extract(object $object): array
    {
        /** @var DemandeExterne $object */
        $data = [
            'libelle' => $object->getLibelle(),
            'organisme' => $object->getOrganisme(),
            'contact' => $object->getContact(),
            'missions' => $object->getMissions(),
            'pourquoi' => $object->getPourquoi(),
            'montant' => $object->getMontant(),
            'lieu' => $object->getLieu(),
            'debut' => $object->getDebut(),
            'fin' => $object->getFin(),
            'modalite' => $object->getModalite(),
            'motivation' => $object->getJustificationAgent(),
            'conge-formation-syndicale' => $object->isCongeFormationSyndicale(),
            'prise-en-charge' => $object->isPriseEnCharge(),
            'cofinanceur' => $object->getCofinanceur(),
        ];


        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== '')?trim($data['libelle']):null;
        $organisme = (isset($data['organisme']) AND trim($data['organisme']) !== '')?trim($data['organisme']):null;
        $contact = (isset($data['contact']) AND trim($data['contact']) !== '')?trim($data['contact']):null;
        $missions = (isset($data['missions']) AND trim($data['missions']) !== '')?trim($data['missions']):null;
        $pourquoi = (isset($data['pourquoi']) AND trim($data['pourquoi']) !== '')?trim($data['pourquoi']):null;
        $montant = (isset($data['montant']) AND trim($data['montant']) !== '')? ((float) trim($data['montant'])): null;
        $lieu = (isset($data['lieu']) AND trim($data['lieu']) !== '')?trim($data['lieu']):null;
        $debut = (isset($data['debut']) AND trim($data['debut']) !== '')?DateTime::createFromFormat('d/m/Y', $data['debut']):null;
        $fin = (isset($data['fin']) AND trim($data['fin']) !== '')?DateTime::createFromFormat('d/m/Y', $data['fin']):null;
        $modalite = $data['modalite']??null;
        $motivation = (isset($data['motivation']) AND trim($data['motivation']) !== '')?trim($data['motivation']):null;
        $congeFormationSyndicale = isset($data['conge-formation-syndicale'])??true;
        $priseEnCharge = isset($data['prise-en-charge'])??true;
        $cofinanceur = (isset($data['cofinanceur']) AND trim($data['cofinanceur']) !== '')?trim($data['cofinanceur']):null;

        /** @var DemandeExterne $object */
        $object->setLibelle($libelle);
        $object->setOrganisme($organisme);
        $object->setContact($contact);
        $object->setMissions($missions);
        $object->setPourquoi($pourquoi);
        $object->setMontant($montant);
        $object->setLieu($lieu);
        $object->setDebut($debut);
        $object->setFin($fin);
        $object->setModalite($modalite);
        $object->setJustificationAgent($motivation);
        $object->setCongeFormationSyndicale($congeFormationSyndicale);
        $object->setPriseEnCharge($priseEnCharge);
        $object->setCofinanceur($cofinanceur);

        return $object;
    }

}