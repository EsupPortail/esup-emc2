<?php

namespace Formation\Form\StagiaireExterne;

use DateTime;
use Formation\Entity\Db\StagiaireExterne;
use Laminas\Hydrator\HydratorInterface;

class StagiaireExterneHydrator implements HydratorInterface {

    public function extract(object $object): array
    {
        /** @var StagiaireExterne $object */
        $data = [
            'prenom' => $object->getPrenom(),
            'nom' => $object->getNom(),
            'date_naissance' => ($object->getDateNaissance())?$object->getDateNaissance()->format('Y-m-d'):null,
            'sexe' => $object->getSexe(),
            'structure' => $object->getStructure(),
            'email' => $object->getEmail(),
            'login' => $object->getLogin(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $prenom = (isset($data['prenom']) && trim($data['prenom']) !== '')?trim($data['prenom']):null;
        $nom    = (isset($data['nom']) && trim($data['nom']) !== '')?trim($data['nom']):null;
        $date   = (isset($data['date_naissance']) && $data['date_naissance'] !== '')?DateTime::createFromFormat('Y-m-d', $data['date_naissance']):null;
        $sexe   = (isset($data['sexe']))?$data['sexe']:null;
        $structure   = (isset($data['structure']) && trim($data['structure']) !== '')?trim($data['structure']):null;
        $email   = (isset($data['email']) && trim($data['email']) !== '')?trim($data['email']):null;
        $login   = (isset($data['login']) && trim($data['login']) !== '')?trim($data['login']):null;

        /** @var StagiaireExterne $object */
        $object->setPrenom($prenom);
        $object->setNom($nom);
        $object->setDateNaissance($date);
        $object->setSexe($sexe);
        $object->setStructure($structure);
        $object->setEmail($email);
        $object->setLogin($login);

        return $object;
    }


}