<?php

namespace Application\Form\AgentPPP;

use Application\Entity\Db\AgentPPP;
use Application\Form\HasPeriode\HasPeriodeFieldset;
use DateTime;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class AgentPPPHydrator implements HydratorInterface {
    use EtatServiceAwareTrait;

    /**
     * @param AgentPPP $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'type'              => $object->getType(),
            'libelle'           => $object->getLibelle(),
            'HasPeriode'        => [
                'date_debut' => ($object->getDateDebut())?$object->getDateDebut()->format(HasPeriodeFieldset::format):null,
                'date_fin'   => ($object->getDateFin())?$object->getDateFin()->format(HasPeriodeFieldset::format):null,
            ],
            'etat'              => [
                'etat' => ($object->getEtat())?$object->getEtat()->getId():null,
            ],
            'cpf'               => $object->getFormationCPF(),
            'cout'              => $object->getFormationCout(),
            'priseencharge'     => $object->getFormationPriseEnCharge(),
            'organisme'         => $object->getFormationOrganisme(),
            'complement'        => $object->getComplement(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param AgentPPP $object
     * @return AgentPPP
     */
    public function hydrate(array $data, $object)
    {
        $type = (isset($data['type']) AND trim($data['type']) !== '')?trim($data['type']):null;
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== '')?trim($data['libelle']):null;
        $dataDebut = (isset($data['HasPeriode']) AND isset($data['HasPeriode']['date_debut']) AND trim($data['HasPeriode']['date_debut']) !== '')?DateTime::createFromFormat(HasPeriodeFieldset::format, $data['HasPeriode']['date_debut']):null;
        $dateFin = (isset($data['HasPeriode']) AND isset($data['HasPeriode']['date_fin']) AND trim($data['HasPeriode']['date_fin']) !== '')?DateTime::createFromFormat(HasPeriodeFieldset::format, $data['HasPeriode']['date_fin']):null;
        $etat = (isset($data['etat']) AND isset($data['etat']['etat']))?$this->getEtatService()->getEtat($data['etat']['etat']):null;
        $cpf = (isset($data['cpf']) AND trim($data['cpf']) !== '')?$data['cpf']:null;
        $cout = (isset($data['cout']) AND trim($data['cout']) !== '')?$data['cout']:null;
        $priseencharge = (isset($data['priseencharge']) AND trim($data['priseencharge']) !== '')?$data['priseencharge']:null;
        $organisme = (isset($data['organisme']) AND trim($data['organisme']) !== '')?trim($data['organisme']):null;
        $complement = (isset($data['complement']) AND trim($data['complement']) !== '')?trim($data['complement']):null;

        $object->setType($type);
        $object->setLibelle($libelle);
        $object->setDateDebut($dataDebut);
        $object->setDateFin($dateFin);
        $object->setEtat($etat);
        $object->setFormationCPF($cpf);
        $object->setFormationCout($cout);
        $object->setFormationPriseEnCharge($priseencharge);
        $object->setFormationOrganisme($organisme);
        $object->setComplement($complement);

        return $object;
    }


}