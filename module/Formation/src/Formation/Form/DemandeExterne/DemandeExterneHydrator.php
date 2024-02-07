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
            'pourquoi' => $object->getPourquoi(),
            'montant' => $object->getMontant(),
            'lieu' => $object->getLieu(),
            'debut' => $object->getDebut(),
            'fin' => $object->getFin(),
            'modalite' => $object->getJustificationAgent(),
            'motivation' => $object->getJustificationAgent(),
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
        $pourquoi = (isset($data['pourquoi']) AND trim($data['pourquoi']) !== '')?trim($data['pourquoi']):null;
        $montant = (isset($data['montant']) AND trim($data['montant']) !== '')?trim($data['montant']):null;
        $lieu = (isset($data['lieu']) AND trim($data['lieu']) !== '')?trim($data['lieu']):null;
        $debut = (isset($data['debut']) AND trim($data['debut']) !== '')?DateTime::createFromFormat('d/m/Y', $data['debut']):null;
        $fin = (isset($data['fin']) AND trim($data['fin']) !== '')?DateTime::createFromFormat('d/m/Y', $data['fin']):null;
        $modalite = $data['prise-en-charge']??null;
        $motivation = (isset($data['motivation']) AND trim($data['motivation']) !== '')?trim($data['motivation']):null;
        $priseEnCharge = isset($data['prise-en-charge'])??true;
        $cofinanceur = (isset($data['cofinanceur']) AND trim($data['cofinanceur']) !== '')?trim($data['cofinanceur']):null;

        /** @var DemandeExterne $object */
        $object->setLibelle($libelle);
        $object->setOrganisme($organisme);
        $object->setContact($contact);
        $object->setPourquoi($pourquoi);
        $object->setMontant($montant);
        $object->setLieu($lieu);
        $object->setDebut($debut);
        $object->setFin($fin);
        $object->setModalite($modalite);
        $object->setJustificationAgent($motivation);
        $object->setPriseEnCharge($priseEnCharge);
        $object->setCofinanceur($cofinanceur);

        return $object;
    }

}